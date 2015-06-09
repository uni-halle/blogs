<form method="post" action="<?php echo esc_attr($this->url(array('action' => false))) ?>">
    <p><?php echo esc_html( __( 'The table below shows all the form fields that users may need to fill to create a listing. Use the six-dots icons at the end of each row to drag the form fields around and modify the order in which those fields appear in the Ad Details form.', 'AWPCP' ) ); ?></p>
    <p><?php
        $settings_url = awpcp_get_admin_settings_url( 'form-field-settings' );
        $settings_link = sprintf( '<a href="%s">%s</a>', $settings_url, __( 'Form', 'AWPCP' ) );

        $message = __( 'Go to the <form-fields-settings-link> settings section to control which of the standard fields appear and if the user is required to enter a value. If you have the Extra Fields module, the rest of the fields can be configured from the Extra Fields admin section.', 'AWPCP' );
        $message = str_replace( '<form-fields-settings-link>', $settings_link, $message );

        echo $message; ?></p>

    <?php /*foreach ($this->params as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach*/ ?>

    <?php echo $table->views(); ?>
    <?php echo $table->display(); ?>
</form>
