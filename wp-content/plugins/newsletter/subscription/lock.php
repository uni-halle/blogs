<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterSubscription::instance();

if (!$controls->is_action()) {
    $controls->data = $module->get_options('lock');
} else {
    if ($controls->is_action('save')) {
        $module->save_options($controls->data, 'lock');
    }
}
?>

<div class="wrap" id="tnp-wrap">
    
    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">
    
    <h2><?php _e('Locked Content', 'newsletter') ?></h2>
    <p>
        Please, <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-locked-content" target="_blank">read more here how to use and configure</a>,
        since it can incredibly increase your subscription rate.
    </p>
    
    </div>

	<div id="tnp-body">
    
    <form method="post" action="">
        <?php $controls->init(); ?>
        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Tags or categories to block', 'newsletter') ?></th>
                <td>
                    <?php $controls->text('ids', 70); ?> (<?php _e('comma separated', 'newsletter') ?>)
                    <p class="description">
                        <?php _e('Use tag or category slugs or id for which lock the posts content', 'newsletter') ?>
                    </p>
                </td>
            </tr>

            <tr valign="top">
                <th><?php _e('Unlock destination URL', 'newsletter') ?></th>
                <td>
                    <?php $controls->text('url', 70); ?>
                    <p class="description">
                        <?php _e('URL where redirect subscribers when they click on unlocking URL ({unlock_url}) inserted in newsletters and welcome message.', 'newsletter') ?>
                        <br>
                        Newsletters with tracking active can link directly the pages with locked content since the URLs will 
                        unlock the content as well.
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Denied content message', 'newsletter') ?></th>
                <td>
                    <?php $controls->wp_editor('message'); ?>

                    <p class="description">
                        This message is shown in place of protected post or page content which is surrounded with
                        [newsletter_lock] and [/newsletter_lock] short codes or in place of the full content if they are
                        in categories or have tags as specified above.<br />
                        You can use the {subscription_form} tag to display the subscription form.<br>
                        <strong>Remeber to add the {unlock_url} on the welcome email so the user can unlock the content.</strong>
                    </p>
                </td>
            </tr>
        </table>
        <p>
            <?php $controls->button_save(); ?>
        </p>
    </form>
</div>
    
    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>
    
</div>