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
	<form method="post" action="<?php echo $send_access_key_url; ?>">
        <?php foreach( $hidden as $name => $value ): ?>
        <input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
        <?php endforeach; ?>

        <h2><?php _e( 'Resend Ad access key', 'AWPCP' ); ?></h2>

        <p class="awpcp-form-spacer">
            <label for="ad-email"><?php _e( 'Enter your email address', 'AWPCP' ); ?></label>
            <input class="inputbox" id="ad-email" type="text" size="50" name="ad_email" value="<?php echo awpcp_esc_attr( $form['ad_email'] ); ?>" />
            <?php echo awpcp_form_error( 'ad_email', $errors ); ?>
        </p>

        <input type="submit" class="button" value="<?php _ex( "Continue", 'send ad access key form', "AWPCP" ); ?>" />
	</form>
</div>
