<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$module = Newsletter::instance();
$controls = new NewsletterControls();

if (!$controls->is_action()) {
    $controls->data = get_option('newsletter_main');
} else {


    if ($controls->is_action('save')) {
        $errors = null;

        // SMTP Validation (?)

        if (empty($controls->errors)) {
            $module->merge_options($controls->data);
            $controls->messages .= __('Saved.', 'newsletter');
        }
    }

    if ($controls->is_action('smtp_test')) {

        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $mail = new PHPMailer();
        ob_start();
        $mail->IsSMTP();
        $mail->SMTPDebug = true;
        $mail->CharSet = 'UTF-8';
        $message = 'This Email is sent by PHPMailer of WordPress';
        $mail->IsHTML(false);
        $mail->Body = $message;
        $mail->From = $module->options['sender_email'];
        $mail->FromName = $module->options['sender_name'];
        if (!empty($module->options['return_path'])) {
            $mail->Sender = $module->options['return_path'];
        }
        if (!empty($module->options['reply_to'])) {
            $mail->AddReplyTo($module->options['reply_to']);
        }

        $mail->Subject = '[' . get_option('blogname') . '] SMTP test';

        $mail->Host = $controls->data['smtp_host'];
        if (!empty($controls->data['smtp_port'])) {
            $mail->Port = (int) $controls->data['smtp_port'];
        }

        $mail->SMTPSecure = $controls->data['smtp_secure'];
        $mail->SMTPAutoTLS = false;

        if (!empty($controls->data['smtp_user'])) {
            $mail->SMTPAuth = true;
            $mail->Username = $controls->data['smtp_user'];
            $mail->Password = $controls->data['smtp_pass'];
        }

        $mail->SMTPKeepAlive = true;
        $mail->ClearAddresses();
        $mail->AddAddress($controls->data['smtp_test_email']);

        $mail->Send();
        $mail->SmtpClose();
        $debug = htmlspecialchars(ob_get_clean());

        if ($mail->IsError()) {
            $controls->errors = '<strong>Connection/email delivery failed.</strong><br>You should contact your provider reporting the SMTP parameter and asking about connection to that SMTP.<br><br>';
            $controls->errors = $mail->ErrorInfo;
        } else
            $controls->messages = 'Success.';

        $controls->messages .= '<textarea style="width:100%;height:250px;font-size:10px">';
        $controls->messages .= $debug;
        $controls->messages .= '</textarea>';
    }
}
?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

	<div id="tnp-heading">

        <h2><?php _e('SMTP Settings', 'newsletter') ?></h2>
    
    <p>
        <i class="fa fa-info-circle"></i> <a href="http://www.thenewsletterplugin.com/extensions" target="_blank">Discover how SMTP services can boost your newsletters!</a>
        <!--
    <p>SMTP (Simple Mail Transfer Protocol) refers to external delivery services you can use to send emails.</p>
    <p>SMTP services are usually more reliable, secure and spam-aware than the standard delivery method available to your blog.</p>
    <p>Even better, using the <a href="http://www.thenewsletterplugin.com/extensions">integration extensions</a>, you can benefit of more efficient service connections, bounce detection and other nice features.</p>
        -->
    </p>
    
    <p>
            <strong>These options can be overridden by extensions which integrates with external
                SMTPs (like MailJet, SendGrid, ...) if installed and activated.</strong>
        </p>
        <p>

            What you need to know to use and external SMTP can be found
            <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-configuration#smtp" target="_blank">here</a>.
            <br>
            On GoDaddy you should follow this <a href="http://www.thenewsletterplugin.com/godaddy-using-smtp-external-server-shared-hosting" target="_blank">special setup</a>.
        </p>
        <p>
            Consider <a href="http://www.thenewsletterplugin.com/affiliate/sendgrid" target="_blank">SendGrid</a> for a serious and reliable SMTP service.
        </p>
    
    </div>

	<div id="tnp-body">

    <form method="post" action="">
        <?php $controls->init(); ?>

        <table class="form-table">
            <tr>
                <th>Enable the SMTP?</th>
                <td><?php $controls->yesno('smtp_enabled'); ?></td>
            </tr>
            <tr>
                <th>SMTP host/port</th>
                <td>
                    host: <?php $controls->text('smtp_host', 30); ?>
                    port: <?php $controls->text('smtp_port', 6); ?>
                    <?php $controls->select('smtp_secure', array('' => 'No secure protocol', 'tls' => 'TLS protocol', 'ssl' => 'SSL protocol')); ?>
                    <p class="description">
                        Leave port empty for default value (25). To use Gmail try host "smtp.gmail.com" and port "465" and SSL protocol (without quotes).
                        For GoDaddy use "relay-hosting.secureserver.net".
                    </p>
                </td>
            </tr>
            <tr>
                <th>Authentication</th>
                <td>
                    user: <?php $controls->text('smtp_user', 30); ?>
                    password: <?php $controls->text('smtp_pass', 30); ?>
                    <p class="description">
                        If authentication is not required, leave "user" field blank.
                    </p>
                </td>
            </tr>
            <tr>
                <th>Test email address</th>
                <td>
                    <?php $controls->text_email('smtp_test_email', 30); ?>
                    <?php $controls->button('smtp_test', 'Send a test email to this address'); ?>
                    <p class="description">
                        If the test reports a "connection failed", review your settings and, if correct, contact
                        your provider to unlock the connection (if possible).
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
