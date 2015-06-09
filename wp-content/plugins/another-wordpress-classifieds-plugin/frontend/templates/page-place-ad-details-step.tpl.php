<h2><?php echo esc_html( __( 'Enter Ad Details', 'AWPCP' ) ); ?></h2>

<?php
    if ( isset( $transaction ) && get_awpcp_option( 'show-create-listing-form-steps' ) ) {
        echo awpcp_render_listing_form_steps( 'listing-details', $transaction );
    }
?>

<?php
    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }

    awpcp_print_form_errors( $errors );
?>

<?php if ($ui['listing-actions']): ?>
<?php echo awpcp_listing_actions_component()->render( $listing, array( 'hidden-params' => $hidden, 'current_url' => $this->url() ) ); ?>
<?php endif ?>

<!-- TODO: check where is used $formdisplayvalue -->
<div>
	<form class="awpcp-details-form" id="adpostform" name="adpostform" action="<?php echo esc_attr( $this->url() ) ?>" method="post">
        <?php foreach($hidden as $name => $value): ?>
        <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
        <?php endforeach ?>

        <?php if ($ui['user-dropdown']): ?>

        <h3><?php echo esc_html( __( 'Ad Owner', 'AWPCP' ) ); ?></h3>
        <p class="awpcp-form-spacer">
            <?php
                echo awpcp_users_field()->render( array(
                    'required' => awpcp_get_option( 'requireuserregistration' ),
                    'selected' => awpcp_array_data( 'user_id', $edit ? null : '', $form ),
                    'label' => __( 'User', 'AWPCP' ),
                    'default' => __( 'Select an User owner for this Ad', 'AWPCP' ),
                    'id' => 'ad-user-id',
                    'name' => 'user',
                    'class' => array( 'awpcp-users-dropdown', 'awpcp-dropdown' ),
                ) );
            ?>
            <?php echo awpcp_form_error( 'user', $errors ); ?>
        </p>

        <?php endif; ?>

        <?php if ($ui['start-end-date']): ?>

        <h3><?php echo esc_html( __( 'Start & End Date', 'AWPCP' ) ); ?></h3>

        <p class="awpcp-form-spacer">
            <label for="start-date"><?php echo esc_html( _x( 'Start Date', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['start-date'] ? '*' : ''; ?></label>
            <?php $date = awpcp_datetime( 'awpcp-date', $form['start_date'] ); ?>
            <input class="inputbox" id="start-date" type="text" size="50" datepicker-placeholder value="<?php echo awpcp_esc_attr($date); ?>" />
            <input type="hidden" name="start_date" value="<?php echo esc_attr( awpcp_datetime( 'Y/m/d', $form['start_date'] ) ); ?>" />
            <?php echo awpcp_form_error('start_date', $errors); ?>
        </p>

        <p class="awpcp-form-spacer">
            <label for="end-date"><?php echo esc_html( _x( 'End Date', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['end-date'] ? '*' : ''; ?></label>
            <?php $date = awpcp_datetime( 'awpcp-date', $form['end_date'] ); ?>
            <input class="inputbox" id="end-date" type="text" size="50" datepicker-placeholder value="<?php echo awpcp_esc_attr($date); ?>" />
            <input type="hidden" name="end_date" value="<?php echo esc_attr( awpcp_datetime( 'Y/m/d', $form['end_date'] ) ); ?>" />
            <?php echo awpcp_form_error('end_date', $errors); ?>
        </p>

        <?php endif; ?>

        <h3><?php echo esc_html( __( 'Add Details and Contact Information', 'AWPCP' ) ); ?></h3>

        <?php if ($ui['category-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $dropdown = new AWPCP_CategoriesDropdown(); ?>
            <?php echo $dropdown->render( array( 'selected' => awpcp_array_data( 'ad_category', '', $form ), 'name' => 'ad_category' ) ); ?>
            <?php echo awpcp_form_error( 'ad_category', $errors ); ?>
        </p>
        <?php endif ?>

        <?php
            echo awpcp_form_fields()->render_fields(
                $form,
                $errors,
                isset( $listing ) ? $listing : null,
                array( 'category' => $form['ad_category'], 'action' => 'normal' )
            );
        ?>

        <?php if ($ui['terms-of-service']): ?>
        <p class="awpcp-form-spacer">
        <?php $text = get_awpcp_option('tos') ?>

        <?php if (string_starts_with($text, 'http://', false) || string_starts_with($text, 'https://', false)): ?>
            <a href="<?php echo esc_attr( $text ); ?>" target="_blank"><?php echo esc_html( _x( "Read our Terms of Service", 'ad details form', "AWPCP" ) ); ?></a>
        <?php else: ?>
            <label><?php echo esc_html( _x( 'Terms of service:', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['terms-of-service'] ? '*' : ''; ?></label>
            <textarea readonly="readonly" rows="5" cols="50"><?php echo esc_textarea( $text ); ?></textarea>
        <?php endif ?>
            <br>
            <input class="required" id="terms-of-service" type="checkbox" name="terms-of-service" value="1" />
            <label class="inline" for="terms-of-service"><?php echo esc_html( _x( 'I agree to the terms of service', 'ad details form', 'AWPCP' ) ); ?></label>
            <?php echo awpcp_form_error('terms-of-service', $errors) ?>
        </p>
        <?php endif ?>

        <?php if ($ui['captcha']): ?>
        <div class='awpcp-form-spacer'>
            <?php $captcha = awpcp_create_captcha( get_awpcp_option( 'captcha-provider' ) ); ?>
            <?php echo $captcha->render(); ?>
            <?php echo awpcp_form_error('captcha', $errors) ?>
        </div>
        <?php endif; ?>

        <?php if ( $preview ): ?>
        <input type="submit" class="button" value="<?php echo esc_attr( __( 'Preview Ad', 'AWPCP' ) ); ?>" />
        <?php else: ?>
        <input type="submit" class="button" value="<?php echo esc_attr( __( 'Continue', 'AWPCP' ) ); ?>" />
        <?php endif; ?>
	</form>
</div>
