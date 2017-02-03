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
	<form method="post" action="<?php echo esc_attr( $this->url() ); ?>">
        <?php foreach($hidden as $name => $value): ?>
        <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
        <?php endforeach ?>

        <h2><?php echo esc_html( __( 'Ad email and access key', 'another-wordpress-classifieds-plugin' ) ); ?></h2>

        <p class="awpcp-form-spacer">
            <label for="ad-email"><?php echo esc_html( __( 'Enter your email address', 'another-wordpress-classifieds-plugin' ) ); ?></label>
            <input class="awpcp-textfield inputbox" id="ad-email" type="text" size="50" name="ad_email" value="<?php echo awpcp_esc_attr($form['ad_email']) ?>" />
            <?php echo awpcp_form_error('ad_email', $errors) ?>
        </p>

        <p class="awpcp-form-spacer">
            <label for="ad-key"><?php echo esc_html( __( 'Enter your Ad access key', 'another-wordpress-classifieds-plugin' ) ); ?></label>
            <input class="awpcp-textfield inputbox" id="ad-key" type="text" size="50" name="ad_key" value="<?php echo awpcp_esc_attr($form['ad_key']) ?>" />
            <?php echo awpcp_form_error('ad_key', $errors) ?>
            <br><a href="<?php echo esc_attr( $send_access_key_url ); ?>"><?php echo esc_html( __( 'Click here to have your Ad access keys sent to you.', 'another-wordpress-classifieds-plugin' ) ); ?></a>
        </p>

        <input type="submit" class="button" value="<?php echo esc_attr( _x( "Continue", 'enter email and access key form', 'another-wordpress-classifieds-plugin' ) ); ?>" />
	</form>
</div>
