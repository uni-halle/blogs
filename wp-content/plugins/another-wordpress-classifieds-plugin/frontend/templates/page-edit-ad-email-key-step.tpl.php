<?php
    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }

    foreach ($errors as $index => $error) {
        if (is_numeric($index)) {
            echo awpcp_print_error($error);
        }
    }
?>

<div>
	<form method="post" action="<?php echo $this->url() ?>">
        <?php foreach($hidden as $name => $value): ?>
        <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
        <?php endforeach ?>

        <h2><?php _e('Ad email and access key', 'AWPCP') ?></h2>

        <p class="awpcp-form-spacer">
            <label for="ad-email"><?php _e('Enter your email address', 'AWPCP') ?></label>
            <input class="inputbox" id="ad-email" type="text" size="50" name="ad_email" value="<?php echo awpcp_esc_attr($form['ad_email']) ?>" />
            <?php echo awpcp_form_error('ad_email', $errors) ?>
        </p>

        <p class="awpcp-form-spacer">
            <label for="ad-key"><?php _e('Enter your Ad access key', 'AWPCP') ?></label>
            <input class="inputbox" id="ad-key" type="text" size="50" name="ad_key" value="<?php echo awpcp_esc_attr($form['ad_key']) ?>" />
            <?php echo awpcp_form_error('ad_key', $errors) ?>
            <br><a href="<?php echo $send_access_key_url; ?>"><?php _e( 'Click here to have your Ad access keys sent to you.', 'AWPCP' ); ?></a>
        </p>

        <input type="submit" class="button" value="<?php _ex("Continue", 'ad details form', "AWPCP") ?>" />
	</form>
</div>
