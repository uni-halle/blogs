<?php

@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = Newsletter::instance();

if (!$controls->is_action()) {
    $controls->data = get_option('newsletter_main');
    
} else {
    if ($controls->is_action('remove')) {

        $wpdb->query("delete from " . $wpdb->prefix . "options where option_name like 'newsletter%'");

        $wpdb->query("drop table " . $wpdb->prefix . "newsletter, " . $wpdb->prefix . "newsletter_stats, " .
                $wpdb->prefix . "newsletter_emails, " .
                $wpdb->prefix . "newsletter_work");

        echo 'Newsletter plugin destroyed. Please, deactivate it now.';
        return;
    }

    if ($controls->is_action('save')) {
        $errors = null;

        // Validation
        $controls->data['sender_email'] = $newsletter->normalize_email($controls->data['sender_email']);
        if (!$newsletter->is_email($controls->data['sender_email'])) {
            $controls->errors .= __('The sender email address is not correct.', 'newsletter') . '<br>';
        }

        $controls->data['return_path'] = $newsletter->normalize_email($controls->data['return_path']);
        if (!$newsletter->is_email($controls->data['return_path'], true)) {
            $controls->errors .= __('Return path email is not correct.', 'newsletter') . '<br>';
        }

        $controls->data['php_time_limit'] = (int) $controls->data['php_time_limit'];
        if ($controls->data['php_time_limit'] == 0)
            unset($controls->data['php_time_limit']);

        //$controls->data['test_email'] = $newsletter->normalize_email($controls->data['test_email']);
        //if (!$newsletter->is_email($controls->data['test_email'], true)) {
        //    $controls->errors .= 'Test email is not correct.<br />';
        //}

        $controls->data['reply_to'] = $newsletter->normalize_email($controls->data['reply_to']);
        if (!$newsletter->is_email($controls->data['reply_to'], true)) {
            $controls->errors .= __('Reply to email is not correct.', 'newsletter') . '<br>';
        }
        if (empty($controls->errors)) {
            $module->merge_options($controls->data);
            $controls->messages .= __('Saved.', 'newsletter');
        }
    }
}
?>

<div class="wrap" id="tnp-wrap">

    <?php $help_url = 'http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration'; ?>
    
        <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">
    
    <h2><?php _e('General Settings', 'newsletter') ?></h2>

    </div>
    <div id="tnp-body">

    <form method="post" action="">
        <?php $controls->init(); ?>

        <div id="tabs">

            <ul>
                <li><a href="#tabs-basic"><?php _e('Basic Settings', 'newsletter') ?></a></li>
                <li><a href="#tabs-speed"><?php _e('Delivery Speed', 'newsletter') ?></a></li>
                <li><a href="#tabs-advanced"><?php _e('Advanced Settings', 'newsletter') ?></a></li>
            </ul>

            <div id="tabs-basic">

                <p>
                    <strong>Important!</strong>
                    <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration" target="_blank">Read the configuration page</a>
                    to know every details about these settings.
                </p>


                <table class="form-table">

                    <tr valign="top">
                        <th><?php _e('Sender email address', 'newsletter') ?></th>
                        <td>
                            <?php $controls->text_email('sender_email', 40); ?> (valid email address)

                            <p class="description">
                                <?php _e('Email address from which subscribers will see your email coming.', 'newsletter') ?> 
                                Since this setting can
                                affect the reliability of delivery,
                                <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#sender" target="_blank">read my notes here</a> (important).
                                Generally use an address within your domain name.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Sender name', 'newsletter') ?></th>
                        <td>
                            <?php $controls->text('sender_name', 40); ?> (optional)

                            <p class="description">
                                <?php _e('Name from which subscribers will see your email coming (for example your blog title).', 'newsletter') ?> 
                                Since this setting can affect the reliability of delivery (usually under Windows)
                                <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#sender" target="_blank">read my notes here</a>.
                            </p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th><?php _e('Return path', 'newsletter') ?></th>
                        <td>
                            <?php $controls->text_email('return_path', 40); ?> (valid email address, default empty)
                            <p class="description">
                                Email address where delivery error messages are sent by mailing systems (eg. mailbox full, invalid address, ...).<br>
                                Some providers do not accept this field: they can block emails or force it to a different value affecting the delivery reliability.
                                <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#return-path" target="_blank">Read my notes here</a> (important).
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th><?php _e('Reply to', 'newsletter') ?></th>
                        <td>
                            <?php $controls->text_email('reply_to', 40); ?>
                            <p class="description">
                                This is the email address where subscribers will reply (eg. if they want to reply to a newsletter). Leave it blank if
                                you don't want to specify a different address from the sender email above. Since this setting can
                                affect the reliability of delivery,
                                <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#reply-to" target="_blank">read my notes here</a> (important).
                            </p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th>License key</th>
                        <td>
                            <?php $controls->text('contract_key', 40); ?>
                            <p class="description">
                                This key is used by <a href="http://www.thenewsletterplugin.com/plugins/newsletter" target="_blank">extensions</a> to
                                self update. It does not unlock hidden features or like!
                                <?php if (defined('NEWSLETTER_LICENSE_KEY')) { ?>
                                    <br>A global license key is actually defined, this value will be ignored.
                                <?php } ?>
                            </p>
                        </td>
                    </tr>

                </table>
            </div>

            <div id="tabs-speed">

                <p>
                    You can set the speed of the email delivery as <strong>emails per hour</strong>. The delivery engine
                    runs every <strong>5 minutes</strong> and sends a limited number of email to keep the sending rate
                    below the specified limit. For example if you set 120 emails per hour the delivery engine will
                    send at most 10 emails per run.
                </p>
                <p>
                    <strong>Important!</strong> Read the
                    <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-delivery-engine" target="_blank">delivery engine page</a>
                    to solve speed problems and find blog setup examples to make it work at the best.
                </p>

                <table class="form-table">
                    <tr>
                        <th><?php _e('Max emails per hour', 'newsletter') ?></th>
                        <td>
                            <?php $controls->text('scheduler_max', 5); ?>
                            <p class="description">
                                The Newsletter delivery engine respects this limit and it should be set to a value less than the maximum allowed by your provider
                                (Hostgator: 500 per hour, Dreamhost: 100 per hour, Go Daddy: 1000 per <strong>day</strong> using their SMTP, Gmail: 500 per day).
                                Read <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-delivery-engine" target="_blank">more on delivery engine</a> (important).
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            
            <div id="tabs-advanced">

                <p>
                    Every setting is explained <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#advanced" target="_blank">here</a>.
                </p>

                <table class="form-table">

                    <tr valign="top">
                        <th>Enable access to blog editors?</th>
                        <td>
                            <?php $controls->yesno('editor'); ?>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th>API key</th>
                        <td>
                            <?php $controls->text('api_key', 40); ?>
                            <p class="description">
                                When non-empty can be used to directly call the API for external integration. See API documentation on
                                documentation panel.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th>Custom CSS</th>
                        <td>
                            <?php $controls->textarea('css'); ?>
                            <p class="description">
                                Add here your own css to style the forms. The whole form is enclosed in a div with class
                                "newsletter" and it's made with a table (guys, I know about your table less design
                                mission, don't blame me too much!)
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th>Email body content encoding</th>
                        <td>
                            <?php $controls->select('content_transfer_encoding', array('' => 'Default', '8bit' => '8 bit', 'base64' => 'Base 64')); ?>
                            <p class="description">
                                Sometimes setting it to Base 64 solves problem with old mail servers (for example truncated or unformatted emails.
                                <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#enconding" target="_blank">Read more here</a>.
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th>PHP max execution time</th>
                        <td>
                            <?php $controls->text('php_time_limit', 10); ?>
                            (before write in something, <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#advanced" target="_blank">read here</a>)
                            <p class="description">
                                Sets the PHP max execution time in seconds, overriding the default of your server.
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th>Totally remove this plugin</th>
                        <td>
                            <?php $controls->button_confirm('remove', 'Totally remove this plugin', 'Really sure to totally remove this plugin. All data will be lost!'); ?>
                        </td>
                    </tr>
                </table>

            </div>




        </div> <!-- tabs -->

        <p>
            <?php $controls->button_save(); ?>
        </p>

    </form>
    <p></p>
</div>
    
<?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>
    
</div>
    
