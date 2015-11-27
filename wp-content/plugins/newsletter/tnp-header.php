<?php
global $current_user, $wpdb, $newsletter;

$dismissed = get_option('newsletter_dismissed', array());

if (isset($_REQUEST['dismiss'])) {
    $dismissed[$_REQUEST['dismiss']] = 1;
    update_option('newsletter_dismissed', $dismissed);
}

$user_count = $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where status='C'");

function newsletter_print_entries($group) {
    $entries = apply_filters('newsletter_menu_' . $group, array());
    if ($entries) {
        foreach ($entries as &$entry) {
            echo '<li><a href="';
            echo $entry['url'];
            echo '">';
            echo $entry['label'];
            if (isset($entry['description'])) {
                echo '<small>';
                echo $entry['description'];
                echo '</small>';
            }
            echo '</a></li>';
        }
    }
}
?>

<div class="tnp-drowpdown" id="tnp-header">
    <a href="?page=newsletter_main_index"><img src="<?php echo plugins_url('newsletter'); ?>/images/header/tnp-logo-red-header.png" class="tnp-header-logo"style="vertical-align: bottom;"></a>
    <ul>
        <li><a href="#"><i class="fa fa-users"></i> <?php _e('Subscribers', 'newsletter') ?></a>
            <ul>
                <li><a href="?page=newsletter_users_index"><i class="fa fa-search"></i> <?php _e('Search And Edit', 'newsletter') ?>
                        <small><?php _e('Add, edit, search', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_users_import"><i class="fa fa-upload"></i> <?php _e('Import', 'newsletter') ?>
                        <small><?php _e('Import from external sources', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_users_export"><i class="fa fa-download"></i> <?php _e('Export', 'newsletter') ?>
                        <small><?php _e('Export your subscribers list', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_users_massive"><i class="fa fa-wrench"></i> <?php _e('Mainteinance', 'newsletter') ?>
                        <small><?php _e('Massive actions: change list, clean up, ...', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_users_stats"><i class="fa fa-bar-chart"></i> <?php _e('Statistics', 'newsletter') ?>
                        <small><?php _e('All about your subscribers', 'newsletter') ?></small></a></li>
                <?php
                newsletter_print_entries('subscribers');
                ?>
            </ul>
        </li>
        <li><a href="#"><i class="fa fa-list"></i> <?php _e('List Building', 'newsletter') ?></a>
            <ul>
                <li><a href="?page=newsletter_subscription_options"><i class="fa fa-sign-in"></i> <?php _e('Subscription', 'newsletter') ?>
                        <small><?php _e('The subscription process in detail', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_wpusers"><i class="fa fa-wordpress"></i> <?php _e('WP Registration', 'newsletter') ?>
                        <small><?php _e('Subscribe on WP registration', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_profile"><i class="fa fa-check-square-o"></i> <?php _e('Subscription Form Fields', 'newsletter') ?>
                        <small><?php _e('When and what data to collect', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_lists"><i class="fa fa-th-list"></i> <?php _e('Lists', 'newsletter') ?>
                        <small><?php _e('Profile the subscribers for a better targeting', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_unsubscription"><i class="fa fa-sign-out"></i> <?php _e('Unsubscription', 'newsletter') ?>
                        <small><?php _e('How to give the last goodbye (or avoid it!)', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_lock"><i class="fa fa-lock"></i> <?php _e('Locked Content', 'newsletter') ?>
                        <small><?php _e('Make your best content available only upon subscription', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_forms"><i class="fa fa-pencil"></i> <?php _e('Custom Forms', 'newsletter') ?>
                        <small><?php _e('Hand coded form storage', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_subscription_template"><i class="fa fa-file-text-o"></i> <?php _e('Messages Template', 'newsletter') ?>
                        <small><?php _e('Change the look of your service emails', 'newsletter') ?></small></a></li>
                <?php
                newsletter_print_entries('subscription');
                ?>            
            </ul>
        </li>
        <li><a href="#"><i class="fa fa-newspaper-o"></i> <?php _e('Newsletters', 'newsletter') ?></a>
            <ul>
                <li><a href="?page=newsletter_emails_index"><i class="fa fa-newspaper-o"></i> <?php _e('Single Newsletter', 'newsletter') ?>
                        <small><?php _e('The classic "write & send" newsletters', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_statistics_index"><i class="fa fa-bar-chart"></i> <?php _e('Statistics', 'newsletter') ?>
                        <small><?php _e('Tracking configuration and basic data', 'newsletter') ?></small></a></li>
                <?php
                newsletter_print_entries('newsletters');
                ?>
            </ul>
        </li>
        <li><a href="#"><i class="fa fa-cog"></i> <?php _e('Settings', 'newsletter') ?></a>
            <ul>
                <li><a href="?page=newsletter_main_startup"><i class="fa fa-fighter-jet"></i> <?php _e('Quick Startup', 'newsletter') ?>
                        <small><?php _e('The minimum you need to start', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_main_main"><i class="fa fa-cogs"></i> <?php _e('General Settings', 'newsletter') ?>
                        <small><?php _e('Delivery speed, sender details, ...', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_main_info"><i class="fa fa-info"></i> <?php _e('Company Info', 'newsletter') ?>
                        <small><?php _e('Social, address, logo and general info', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_main_smtp"><i class="fa fa-envelope-o"></i> <?php _e('SMTP', 'newsletter') ?>
                        <small><?php _e('External mail servers', 'newsletter') ?></small></a></li>
                <li><a href="?page=newsletter_main_diagnostic"><i class="fa fa-tasks"></i> <?php _e('Diagnostics', 'newsletter') ?>
                        <small><?php _e('Something not working? Start here!', 'newsletter') ?></small></a></li>
                <?php
                newsletter_print_entries('settings');
                ?>
            </ul>
        </li>
        <li class="tnp-professional-extensions-button"><a href="http://www.thenewsletterplugin.com/extensions" target="_blank">
                <i class="fa fa-trophy"></i> <?php _e('Professional Extensions', 'newsletter') ?></a></li>
    </ul>
</div>


<?php if (false && NEWSLETTER_HEADER) { ?>
    <div id="tnp-header">
        <!--
            <a href="http://www.thenewsletterplugin.com" target="_blank"><img src="<?php echo plugins_url('newsletter'); ?>/images/header/logo.png" style="height: 30px; margin-bottom: 10px; display: block;"></a>

            <div style="border-top: 1px solid white; width: 100%; margin-bottom: 10px;"></div>
        -->
        <?php if (NEWSLETTER_DEBUG) { ?>
            <img src="<?php echo plugins_url('newsletter'); ?>/images/header/debug.png" style="vertical-align: middle;" title="Debug mode active!">&nbsp;&nbsp;&nbsp;
        <?php } ?>
        <img src="<?php echo plugins_url('newsletter'); ?>/images/header/logo.png" style="vertical-align: middle;">

        <a href="http://www.thenewsletterplugin.com/?utm_source=plugin&utm_medium=link&utm_campaign=newsletter-extensions&utm_content=<?php echo NEWSLETTER_VERSION ?>" target="_blank" style="font-weight: bold; font-size: 13px; text-transform: uppercase">
            Get the Professional Extensions!
        </a>
        &nbsp;&nbsp;&nbsp;
        <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-documentation" target="_blank">
            <i class="fa fa-file-text"></i> Documentation
        </a>
        &nbsp;&nbsp;
        <a href="http://www.thenewsletterplugin.com/forums" target="_blank">
            <i class="fa fa-life-ring"></i> Forum
        </a>
        &nbsp;&nbsp;
        <a href="https://www.facebook.com/thenewsletterplugin
           " target="_blank">
            <i class="fa fa-facebook-square"></i> Facebook
        </a>
        &nbsp;&nbsp;
        Stay updated: 
        <form target="_blank" style="display: inline" action="http://www.thenewsletterplugin.com/wp-content/plugins/newsletter/subscribe.php" method="post">
            <input type="email" name="ne" placeholder="Your email" required size="30" value="<?php echo esc_attr($current_user->user_email) ?>">
            <input type="hidden" name="nr" value="plugin">
            <input type="submit" value="Go">
        </form>

    </div>
<?php } ?>

<?php if (NEWSLETTER_DEBUG || !isset($dismissed['rate']) && $user_count > 200) { ?>
    <div class="notice">
        <a href="<?php echo $_SERVER['REQUEST_URI'] . '&dismiss=rate' ?>" class="dismiss">&times;</a>
        <p>
            We never asked before and we're curious: <a href="http://wordpress.org/extend/plugins/newsletter/" target="_blank">would you rate this plugin</a>?
            (few seconds required - account on WordPress.org required, every blog owner should have one...). <strong>Really appreciated, The Newsletter Team</strong>.
        </p>
    </div>
<?php } ?>

<?php if (NEWSLETTER_DEBUG || !isset($dismissed['newsletter-page']) && empty(NewsletterSubscription::instance()->options['url'])) { ?>
    <div class="notice">
        <a href="<?php echo $_SERVER['REQUEST_URI'] . '&dismiss=newsletter-page' ?>" class="dismiss">&times;</a>
        <p>
            You should create a blog page to show the subscription form and the subscription messages. Go to the
            <a href="?page=newsletter_subscription_options">subscription panel</a> to
            configure it.
        </p>
    </div>
<?php } ?>

<div id="tnp-notification">
    <?php Newsletter::instance()->warnings(); ?>
    <?php 
    if (isset($controls)) {
        $controls->show(); 
        $controls->messages = '';
        $controls->errors = '';
    }
    ?>
</div>