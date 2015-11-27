<?php
$module = NewsletterStatistics::instance();
$email = $module->get_email((int)$_GET['id']);
?>

<div class="wrap" id="tnp-wrap">
    
    <?php $help_url = 'http://www.thenewsletterplugin.com/plugins/newsletter/statistics-module'; ?>
    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">
    
        <h2><?php _e('Statistics for', 'newsletter') ?> "<?php echo esc_html($email->subject); ?>"</h2>

        <p>
            Complete statistics for this email are available with
            <a href="http://www.thenewsletterplugin.com/plugins/newsletter/reports-module?utm_source=plugin&utm_medium=link&utm_campaign=newsletter-report&utm_content=<?php echo NEWSLETTER_VERSION?>" target="_blank">Reports for Newsletter</a>.
            Even for already sent email, the Reports for Newsletter will display collected data.
        </p>
    </div>

    <div id="tnp-body">
    
    <?php if (!$email->track) { ?>
        <div class="error"><p>Warning! This email has the tracking option disabled, no data will be collected.</p></div>
    <?php } ?>

    <table class="widefat" style="width: auto">
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Email Id</td>
                <td><?php echo $email->id; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <?php
                    if ($email->status == 'sending') {
                        if ($email->send_on > time()) {
                            echo 'planned';
                        } else {
                            echo 'sending';
                        }
                    } else {
                        echo $email->status;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Progress</td>
                <td>
                    <?php if ($email->status == 'sent' || $email->status == 'sending') echo $email->sent . ' of ' . $email->total; ?>
                </td>
            </tr>
            <tr>
                <td>Emails Opened</td>
                <td><?php echo $module->get_read_count($email->id); ?></td>
            </tr>
            <tr>
                <td>Emails Clicked</td>
                <td>
                    <?php echo $module->get_clicked_count($email->id); ?> (details on clicks available with
                    <a href="http://www.thenewsletterplugin.com/plugins/newsletter/reports-module?utm_source=plugin&utm_medium=link&utm_campaign=newsletter-report" target="_blank">Reports for Newsletter</a>)
                </td>
            </tr>
        </tbody>
    </table>

</div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>
    
</div>