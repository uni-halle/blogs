<div id="widget-modification-notice" class="update-nag clearfix awpcp-sticky-notice">
    <p><?php _e('Thank you for using Another WordPress Classifieds Plugin.', 'AWPCP'); ?>
    <p><?php _e('AWPCP 3.0 includes several modifications to the <strong>Search Ads</strong>, <strong>Featured Ads</strong> and <strong>Latest Ads</strong> widgets. For example, the Latest Ads widget can now be used in multiple sidebars. Also, there is a new Widget to show Random Ads.', 'AWPCP'); ?></p>
    <p><?php _e('Unfortunately, those changes could cause WordPress to recognize the new widgets as totally different ones, in which case, the old widgets would be removed from your sidebars. If you are currently using any of the AWPCP widgets, please review your sidebars configuration and make sure to restore any widget that is missing.', 'AWPCP') ?></p>

    <div class="actions">
        <?php $text = _x('Close this, I\'ll check my Widget configuration.', 'widget modification notice', 'AWPCP') ?>
        <p class="align-centered">
            <a id="link-dismiss" class="button-primary" title="<?php echo esc_attr($text) ?>" data-action="disable-widget-modification-notice"><?php echo $text ?></a>
        </p>
    </div>
</div>