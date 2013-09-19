<?php
    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }
?>

<?php $msg = __('You are responding to Ad: %s.', 'AWPCP'); ?>
<p><?php echo sprintf($msg, $ad_link); ?></p>

<form class="awpcp-reply-to-ad-form" method="post" name="myform">
    <?php foreach($hidden as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach ?>

    <p class="awpcp-form-spacer">
        <label for="awpcp-contact-sender-name"><?php _e("Your name", "AWPCP"); ?></label>
        <input id="awpcp-contact-sender-name" class="inputbox required" type="text" name="sender_name" value="<?php echo $form['sender_name']; ?>" />
        <?php echo awpcp_form_error('sender_name', $errors) ?>
    </p>

    <p class="awpcp-form-spacer">
        <label for="awpcp-contact-sender-email"><?php _e("Your email address", "AWPCP"); ?></label>
        <input id="awpcp-contact-sender-email" class="inputbox required email" type="text" name="sender_email" value="<?php echo $form['sender_email']; ?>" />
        <?php echo awpcp_form_error('sender_email', $errors) ?>
    </p>

    <p class="awpcp-form-spacer">
        <label for="awpcp-contact-message"><?php _e("Your message", "AWPCP"); ?></label>
        <textarea id="awpcp-contact-message" class="textareainput required" name="message" rows="5" cols="90%"><?php echo $form['message']; ?></textarea>
        <?php echo awpcp_form_error('message', $errors) ?>
    </p>

    <?php if ($ui['captcha']): ?>
    <p class='awpcp-form-spacer'>
        <?php $captcha = awpcp_create_captcha( get_awpcp_option( 'captcha-provider' ) ); ?>
        <?php echo $captcha->render(); ?>
        <?php echo awpcp_form_error('captcha', $errors) ?>
    </p>
    <?php endif ?>

    <input type="submit" class="button" value="<?php _e("Continue","AWPCP"); ?>" />
</form>
