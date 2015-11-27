<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';

$controls = new NewsletterControls();

if ($controls->is_action('feed_enable')) {
    delete_option('newsletter_feed_demo_disable');
    $controls->messages = 'Feed by Mail demo panels enabled. On next page reload it will show up.';
}

if ($controls->is_action('feed_disable')) {
    update_option('newsletter_feed_demo_disable', 1);
    $controls->messages = 'Feed by Mail demo panel disabled. On next page reload it will disappear.';
}

$emails_module = NewsletterEmails::instance();
$emails = $wpdb->get_results("select * from " . NEWSLETTER_EMAILS_TABLE . " where type='message' order by id desc limit 5");

$users_module = NewsletterUsers::instance();
$query = "select * from " . NEWSLETTER_USERS_TABLE . " order by id desc";
$query .= " limit 10";
$subscribers = $wpdb->get_results($query);

$last_email = $wpdb->get_row(
         $wpdb->prepare("select * from " . NEWSLETTER_EMAILS_TABLE . " where type='message' and status in ('sent', 'sending') and send_on<%d order by id desc limit 1", time()));

if ($last_email) {
    $last_email_sent = $last_email->sent; 
    $last_email_opened = $wpdb->get_var("select count(distinct user_id) from " . NEWSLETTER_STATS_TABLE . " where email_id=" . $last_email->id);
    $last_email_notopened = $last_email_sent-$last_email_opened;
    $last_email_clicked = $wpdb->get_var("select count(distinct user_id) from " . NEWSLETTER_STATS_TABLE . " where url<>'' and email_id=" . $last_email->id);
    $last_email_opened -= $last_email_clicked;
    
    $overall_sent = $wpdb->get_var("select sum(sent) from " . NEWSLETTER_EMAILS_TABLE . " where type='message' and status in ('sent', 'sending')");

    $overall_opened = $wpdb->get_var("select count(distinct user_id,email_id) from " . NEWSLETTER_STATS_TABLE);
    $overall_notopened = $overall_sent-$overall_opened;
    $overall_clicked = $wpdb->get_var("select count(distinct user_id,email_id) from " . NEWSLETTER_STATS_TABLE . " where url<>''");
    $overall_opened -= $overall_clicked;    
} else {
    $last_email_opened = 500;
    $last_email_notopened = 400;
    $last_email_clicked = 200;
    
    $overall_opened = 500;
    $overall_notopened = 400;
    $overall_clicked = 200;
}
         
$months = $wpdb->get_results("select count(*) as c, concat(year(created), '-', date_format(created, '%m')) as d "
        . "from " . NEWSLETTER_USERS_TABLE . " where status='C' "
        . "group by concat(year(created), '-', date_format(created, '%m')) order by d desc limit 12");
$values = array();
$labels = array();
foreach ($months as $month) {
    $values[] = (int)$month->c;
    $labels[] = (string)$month->d;
}
$values = array_reverse($values);
$labels = array_reverse($labels);

?>
<script type="text/javascript" src="<?php echo plugins_url('newsletter') ?>/js/Chart.min.js"></script>

<div class="wrap" id="tnp-wrap">

    <?php $help_url = 'http://www.thenewsletterplugin.com/plugins/newsletter'; ?>
    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2>TNP <?php _e('Dashboard', 'newsletter') ?></h2>
        <p><?php _e('Your powerful control panel', 'newsletter') ?></p>

    </div>

    <div id="tnp-body">
        <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">
                <div id="postbox-container-1" class="postbox-container">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <!-- START Statistics -->
                        <div id="tnp-dash-statistics" class="postbox">
                            <h3><?php _e('Statistics','newsletter') ?>
                                <a href="<?php echo NewsletterStatistics::$instance->get_admin_page_url('index'); ?>">
                                    <i class="fa fa-bar-chart"></i> <?php _e('Statistics', 'newsletter') ?>
                                </a>
                            </h3>
                            <div class="inside">

                                <?php if (!$last_email) { ?>
                                    <p style="text-align: center">
                                        <?php _e('These charts are only for example:<br>create and send your first newsletter to have real statistics!', 'newsletter') ?>
                                    </p>
                                <?php } ?>

                                <table style="width: 100%">
                                    <tr>
                                        <td>
                                            <canvas id="chart-last-email" width="180" height="180"/>
                                        </td>
                                        <td>
                                            <canvas id="chart-overall" width="180" height="180"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center">
                                            <?php _e('Last Newsletter', 'newsletter') ?>
                                        </td>
                                        <td style="text-align: center">
                                            <?php _e('Overall', 'newsletter') ?>
                                        </td>
                                    </tr>
                                </table>

                                <div id="canvas-holder">
                                    <canvas id="chart-area3" width="360" height="180"/>
                                </div>
                                <p style="text-align: center"><?php _e('Subscriptions over time', 'newsletter') ?></p>

                                <script>
                                    var last_email = [
                                        {
                                            value: <?php echo $last_email_notopened; ?>,
                                            color: "#ECF0F1",
                                            highlight: "#ECF0F1",
                                            label: "Not opened"
                                        },
                                        {
                                            value: <?php echo $last_email_opened; ?>,
                                            color: "#E67E22",
                                            highlight: "#E67E22",
                                            label: "Opened"
                                        },
                                        {
                                            value: <?php echo $last_email_clicked; ?>,
                                            color: "#27AE60",
                                            highlight: "#27AE60",
                                            label: "Clicked"
                                        }
                                    ];
                                    
                                    var overall = [
                                        {
                                            value: <?php echo $overall_notopened; ?>,
                                            color: "#ECF0F1",
                                            highlight: "#ECF0F1",
                                            label: "Not opened"
                                        },
                                        {
                                            value: <?php echo $overall_opened; ?>,
                                            color: "#E67E22",
                                            highlight: "#E67E22",
                                            label: "Opened"
                                        },
                                        {
                                            value: <?php echo $overall_clicked; ?>,
                                            color: "#27AE60",
                                            highlight: "#27AE60",
                                            label: "Clicked"
                                        }
                                    ];                                    

                                    var data2 = {
                                        labels: <?php echo json_encode($labels) ?>,
                                        datasets: [
                                            {
                                                label: "Subscriptions",
                                                fillColor: "#ECF0F1",
                                                strokeColor: "#27AE60",
                                                pointColor: "#ECF0F1",
                                                pointStrokeColor: "#27AE60",
                                                pointHighlightFill: "#27AE60",
                                                pointHighlightStroke: "#27AE60",
                                                data: <?php echo json_encode($values) ?>
                                            }
                                        ]
                                    };


                                    jQuery(document).ready(function ($) {
                                        ctx1 = $('#chart-last-email').get(0).getContext("2d");
                                        ctx2 = $('#chart-area3').get(0).getContext("2d");
                                        ctx3 = $('#chart-overall').get(0).getContext("2d");
                                        myDoughnutChart = new Chart(ctx1).Doughnut(last_email);
                                        myLineChart = new Chart(ctx2).Line(data2, {
                                            datasetStroke : true,
                                            datasetStrokeWidth : 4
                                        });
                                        myPieChart = new Chart(ctx3).Pie(overall);
                                    });
                                </script>
                            </div>
                        </div>
                        <!-- END Statistics -->
                        <!-- START Documentation -->
                        <div id="tnp-dash-documentation" class="postbox">
                            <h3><?php _e('Documentation','newsletter') ?>
                                <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-documentation" target="_blank">
                                    <i class="fa fa-life-ring"></i> <?php _e('Read all', 'newsletter') ?>
                                </a>
                            </h3>
                            <div class="inside">
                                <div class="tnp-video-container">
                                    <iframe width="480" height="360" src="https://www.youtube.com/embed/JaxK7XwqvVI?rel=0" frameborder="0" allowfullscreen></iframe>
                                </div>
                                <div>
                                    <a class="orange" href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-documentation/email-sending-issues" target="_blank">
                                        <i class="fa fa-exclamation-triangle"></i> <?php _e('Problem sending messages? Start here!', 'newsletter') ?>
                                    </a>
                                </div>
                                
                                <div>
                                    <a class="blue" href="http://www.thenewsletterplugin.com/support/video-tutorials" target="_blank">
                                        <i class="fa fa-youtube-play"></i> <?php _e('All Video Tutorials', 'newsletter') ?>
                                    </a>
                                </div>
                                <div>
                                    <a class="purple" href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-preferences" target="_blank">
                                        <i class="fa fa-question-circle"></i> <?php _e('Learn how to segment your suscribers', 'newsletter') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- END Documentation -->
                    </div>
                </div>
                
                <div id="postbox-container-2" class="postbox-container">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <!-- START Newsletters -->
                        <div id="tnp-dash-newsletters" class="postbox">
                            <h3><?php _e('Newsletters','newsletter') ?>
                                <a href="<?php echo $emails_module->get_admin_page_url('index'); ?>">
                                    <i class="fa fa-list"></i> <?php _e('List', 'newsletter') ?>
                                </a>
                                <a href="<?php echo $emails_module->get_admin_page_url('theme'); ?>">
                                    <i class="fa fa-plus-square"></i> <?php _e('New', 'newsletter') ?>
                                </a>
                            </h3>
                            <div class="inside">
                                <table width="100%">
                                <?php foreach ($emails as &$email) { ?>
                                    <tr>
                                        <td><?php if($email->subject) echo htmlspecialchars($email->subject); else echo "Newsletter #".$email->id; ?></td>
                                        <td><?php
                                            if ($email->status == 'sending') {
                                                if ($email->send_on > time()) {
                                                    _e('Scheduled', 'newsletter');
                                                } else {
                                                    _e('Sending', 'newsletter');
                                                }
                                            } elseif($email->status == 'new') {
                                                _e('Draft', 'newsletter');
                                            } else {
                                                echo ucfirst($email->status);
                                            } ?>
                                            <br>
                                            <?php if (true || $email->status == 'sending') {
                                                if ($email->send_on > time()) {
                                                    echo "<small>".$emails_module->format_date($email->send_on)."</small>";
                                                } else { ?>
                                            <div id="canvas-nl-<?php echo $email->id ?>" style="width:100px; height:5px; background-color: lightcoral;">
                                                <div class="canvas-inner" style="background-color: green; width: <?php echo intval($email->sent / $email->total)*100 ?>%; height: 100%;">&nbsp;</div>
                                            </div>
                                             <?php }} ?>
                                        </td>
                                        <td style="white-space:nowrap">
                                            <a class="button" title="<?php _e('Edit', 'newsletter') ?>" href="<?php echo $emails_module->get_admin_page_url('edit'); ?>&amp;id=<?php echo $email->id; ?>"><i class="fa fa-pencil"></i></a>
                                            <a class="button" title="<?php _e('Statistics', 'newsletter') ?>" href="<?php echo NewsletterStatistics::instance()->get_admin_page_url('view'); ?>&amp;id=<?php echo $email->id; ?>"><i class="fa fa-bar-chart"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </table>
                            </div>
                        </div>
                        <!-- END Newsletters -->
                        <!-- START Premium -->
                        <div id="tnp-dash-premium" class="postbox">
                            <h3><?php _e('Premium', 'newsletter') ?>
                                <a href="http://www.thenewsletterplugin.com/extensions" target="_blank">
                                    <i class="fa fa-trophy"></i> <?php _e('Buy', 'newsletter') ?>
                                </a>
                            </h3>
                            <div class="inside">
                                <div>
                                    <a href="http://www.thenewsletterplugin.com/extensions" target="_blank">
                                        <img style="width: 100%;"src="http://cdn.thenewsletterplugin.com/dashboard01.gif">
                                    </a>
                                </div>
                                <div>
                                    <a href="http://www.thenewsletterplugin.com/extensions" target="_blank">
                                        <img style="width: 100%;"src="http://cdn.thenewsletterplugin.com/dashboard02.png">
                                    </a>
                                </div>
                                <!--                                <div>
                                                                    <img src="<?php echo plugins_url('newsletter') ?>/images/extensions/tnp-reports-extension-icon-150x150.png"> 
                                                                    <span>Reports Extension</span>
                                                                </div>
                                                                <div>
                                                                    <img src="<?php echo plugins_url('newsletter') ?>/images/extensions/tnp-feed-by-mail-extension-icon-150x150.png"> 
                                                                    <span>Feed By Mail Extension</span>
                                                                </div>
                                                                <div>
                                                                    <img src="<?php echo plugins_url('newsletter') ?>/images/extensions/tnp-woocommerce-extension-150x150px.png"> 
                                                                    <span>WooCommerce Extension</span>
                                                                </div>-->
                            </div>
                        </div>
                        <!-- END Premium -->
                    </div>
                </div>
                <div id="postbox-container-3" class="postbox-container">
                    <div id="column3-sortables" class="meta-box-sortables ui-sortable">
                        <!-- START Subscribers -->
                        <div id="tnp-dash-subscribers" class="postbox">
                            <h3><?php _e('Last Subscribers','newsletter') ?>
                                <a href="<?php echo $users_module->get_admin_page_url('index'); ?>">
                                    <i class="fa fa-users"></i> <?php _e('List', 'newsletter') ?>
                                </a>
                                <a href="<?php echo $users_module->get_admin_page_url('new'); ?>">
                                    <i class="fa fa-user-plus"></i> <?php _e('New', 'newsletter') ?>
                                </a>
                            </h3>
                            <div class="inside">
                                <table width="100%">
                                    <?php foreach ($subscribers as $s) { ?>
                                        <tr>
                                            <td><?php echo $s->email ?><br>
                                                <?php echo $s->name ?> <?php echo $s->surname ?></td>
                                            <td><?php
                                                switch ($s->status) {
                                                    case 'S': _e('NOT CONFIRMED', 'newsletter');
                                                        break;
                                                    case 'C': _e('CONFIRMED', 'newsletter');
                                                        break;
                                                    case 'U': _e('UNSUBSCRIBED', 'newsletter');
                                                        break;
                                                    case 'B': _e('BOUNCED', 'newsletter');
                                                        break;
                                                }
                                                ?></td>
                                            <td style="white-space:nowrap">
                                                <a class="button" title="<?php _e('Edit', 'newsletter') ?>" href="<?php echo $users_module->get_admin_page_url('edit'); ?>&amp;id=<?php echo $s->id; ?>"><i class="fa fa-pencil"></i></a>
                                                <a title="<?php _e('Profile', 'newsletter') ?>" href="<?php echo plugins_url('newsletter/do/profile.php'); ?>?nk=<?php echo $s->id . '-' . $s->token; ?>" class="button" target="_blank"><i class="fa fa-user"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <!-- END Subscribers -->
                </div>
            </div>
        </div>
    </div>
    
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>
