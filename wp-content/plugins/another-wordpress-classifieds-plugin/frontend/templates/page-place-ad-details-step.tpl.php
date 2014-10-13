<h2><?php echo esc_html( __( 'Enter Ad Details', 'AWPCP' ) ); ?></h2>

<?php
    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }

    awpcp_print_form_errors( $errors );
?>

<?php if ($ui['delete-button']): ?>
<form class="awpcp-delete-ad-form" action="<?php echo esc_attr( $this->url() ); ?>" method="post">
    <?php foreach($hidden as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach ?>
    <input type="hidden" name="step" value="delete-ad" />

    <span><?php echo esc_html( __( 'You can use this button to delete your Ad.', 'AWPCP' ) ); ?></span>
    <span class="confirm">&nbsp;<?php echo esc_html( _x( 'Are you sure?', 'delete ad form in frontend edit ad screen', 'AWPCP' ) ); ?></span>
    <input class="button confirm" type="button" value="<?php echo esc_attr( __( "Cancel", "AWPCP" ) ); ?>" />
    <input class="button button-primary" type="submit" value="<?php echo esc_attr( __( "Delete Ad", "AWPCP" ) ); ?>" />
</form>
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
                    'required' => true,
                    'selected' => awpcp_array_data( 'user_id', '', $form ),
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

        <p class="awpcp-form-spacer awpcp-form-spacer-title">
            <label for="ad-title"><?php echo esc_html( __( 'Ad Title', 'AWPCP' ) ); ?><?php echo $required['ad-title'] ? '*' : ''; ?></label>

            <?php
            if ($form['characters_allowed_in_title'] == 0) {
                $text = _x('No characters limit.', 'ad details form', 'AWPCP');
                $characters = '';
            } else {
                $text = _x('characters left.', 'ad details form', 'AWPCP');
                $characters = $form['remaining_characters_in_title'];
            }
            ?>

            <input class="inputbox required" id="ad-title" type="text" size="50" name="ad_title" value="<?php echo awpcp_esc_attr($form['ad_title']) ?>" data-max-characters="<?php echo esc_attr( $form['characters_allowed_in_title'] ); ?>" data-remaining-characters="<?php echo esc_attr( $form['remaining_characters_in_title'] ); ?>"/>
            <br/><label for="ad-title" class="characters-left"><span class="characters-left-placeholder"><?php echo esc_html( $characters ); ?></span>&nbsp;<?php echo esc_html( $text ); ?></label>
            <?php echo awpcp_form_error('ad_title', $errors) ?>
        </p>

        <?php if ($ui['category-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $dropdown = new AWPCP_CategoriesDropdown(); ?>
            <?php echo $dropdown->render( array( 'selected' => awpcp_array_data( 'ad_category', '', $form ), 'name' => 'ad_category' ) ); ?>
            <?php echo awpcp_form_error( 'ad_category', $errors ); ?>
        </p>
        <?php endif ?>

        <?php if ($ui['website-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $validator = $ui['website-field-required'] ? 'required url' : 'url' ?>
            <label for="website-url"><?php echo esc_html( _x( 'Website URL', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['website-url'] ? '*' : ''; ?></label>
            <input class="inputbox <?php echo $validator ?>" id="website-url" type="text" size="50" name="websiteurl" value="<?php echo awpcp_esc_attr($form['websiteurl']) ?>" />
            <?php echo awpcp_form_error('websiteurl', $errors) ?>
        </p>
        <?php endif ?>

        <p class="awpcp-form-spacer">
            <?php $readonly = $ui['contact-name-field-readonly'] ? 'readonly="readonly"' : ''; ?>
            <label for="ad-contact-name"><?php echo esc_html( _x( 'Name of Person to Contact', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['ad-contact-name'] ? '*' : ''; ?></label>
            <input class="inputbox required" id="ad-contact-name" <?php echo $readonly; ?> type="text"  size="50" name="ad_contact_name" value="<?php echo awpcp_esc_attr($form['ad_contact_name']) ?>" />
            <?php echo awpcp_form_error('ad_contact_name', $errors) ?>
        </p>

        <p class="awpcp-form-spacer">
            <?php $readonly = $ui['contact-email-field-readonly'] ? 'readonly="readonly"' : ''; ?>
            <label for="ad-contact-email"><?php echo esc_html( _x( "Contact Person's Email", 'ad details form', 'AWPCP' ) ); ?><?php echo $required['ad-contact-email'] ? '*' : ''; ?>&nbsp;<span class="helptext"><?php echo esc_html( _x( '(Please enter a valid email. The codes needed to edit your Ad will be sent to your email address)', 'ad details form', 'AWPCP' ) ); ?></span></label>
            <input class="inputbox required email" id="ad-contact-email" <?php echo $readonly; ?> type="text" size="50" name="ad_contact_email" value="<?php echo awpcp_esc_attr($form['ad_contact_email']) ?>" />
            <?php echo awpcp_form_error('ad_contact_email', $errors) ?>
        </p>

        <?php if ($ui['contact-phone-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $validator = $ui['contact-phone-field-required'] ? 'required' : '' ?>
            <label for="ad-contact-phone"><?php echo esc_html( _x( "Contact Person's Phone Number", 'ad details form', 'AWPCP' ) ); ?><?php echo $required['ad-contact-phone'] ? '*' : ''; ?></label>
            <input class="inputbox <?php echo $validator ?>" id="ad-contact-phone" type="text" size="50" name="ad_contact_phone" value="<?php echo awpcp_esc_attr($form['ad_contact_phone']) ?>" />
            <?php echo awpcp_form_error('ad_contact_phone', $errors); ?>
        </p>
        <?php endif ?>

        <?php
        $options = array(
            'showTextField' => true,
            'maxRegions' => $form['regions-allowed'],
            'disabled' => !$ui['allow-regions-modification'],
        );

        $selector = new AWPCP_MultipleRegionSelector( $form['regions'], $options );
        echo $selector->render('details', array(), $errors);
        ?>

        <?php if ($ui['price-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $validator = $ui['price-field-required'] ? 'required money' : 'money' ?>
            <?php $price = $form['ad_item_price'] ? awpcp_format_money( $form['ad_item_price'], false ) : ''; ?>
            <label for="ad-item-price"><?php echo esc_html( _x( 'Item Price', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['ad-item-price'] ? '*' : ''; ?></label>
            <input class="<?php echo $validator ?>" id="ad-item-price" type="text" size="50" name="ad_item_price" value="<?php echo esc_attr( $price ); ?>" />
            <?php echo awpcp_form_error('ad_item_price', $errors) ?>
        </p>
        <?php endif ?>

        <p class="awpcp-form-spacer">
            <label for="ad-details"><?php echo esc_html( _x( 'Ad Details', 'ad details form', 'AWPCP' ) ); ?><?php echo $required['ad-details'] ? '*' : ''; ?></label>

            <?php
            if ($form['characters_allowed'] == 0) {
                $text = _x('No characters limit.', 'ad details form', 'AWPCP');
                $characters = '';
            } else {
                $text = _x('characters left.', 'ad details form', 'AWPCP');
                $characters = $form['remaining_characters'];
            }
            ?>

            <?php echo awpcp_form_error('ad_details', $errors) ?>
            <label for="ad-details" class="helptext"><?php echo nl2br(get_awpcp_option('htmlstatustext')) ?></label>
            <label for="ad-details" class="characters-left"><span class="characters-left-placeholder"><?php echo $characters ?></span>&nbsp;<?php echo esc_html( $text ); ?></label>

            <textarea class="awpcp-textarea textareainput required" id="ad-details" name="ad_details" rows="10" cols="50" data-max-characters="<?php echo esc_attr( $form['characters_allowed'] ); ?>" data-remaining-characters="<?php echo esc_attr( $form['remaining_characters'] ); ?>"><?php /* Content alerady escaped if necessary. Do not escape again here! */ echo $form['ad_details']; ?></textarea>
        </p>

        <?php
        if ($ui['extra-fields']) {
            echo awpcp_extra_fields_render_form(array('category' => $form['ad_category'], 'ad' => $form['ad_id']),
                                                $form,
                                                'normal',
                                                $errors);
        }
        ?>

        <?php if ($ui['terms-of-service']): ?>
        <p class="awpcp-form-spacer">
        <?php $text = get_awpcp_option('tos') ?>

        <?php if (string_starts_with($text, 'http://', false) || string_starts_with($text, 'https://', false)): ?>
            <a href="<?php echo esc_attr( $text ); ?>" target="_blank"><?php echo esc_html( __( "Read our Terms of Service", 'ad details form', "AWPCP" ) ); ?></a>
        <?php else: ?>
            <label><?php echo esc_html( __( "Terms of service:", 'ad details form', "AWPCP" ) ); ?><?php echo $required['terms-of-service'] ? '*' : ''; ?></label>
            <textarea readonly="readonly" rows="5" cols="50"><?php echo esc_textarea( $text ); ?></textarea>
        <?php endif ?>
            <br>
            <input class="required" id="terms-of-service" type="checkbox" name="terms-of-service" value="1" />
            <label class="inline" for="terms-of-service"><?php echo esc_html( __( 'I agree to the terms of service', 'ad details form', 'AWPCP' ) ); ?></label>
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
