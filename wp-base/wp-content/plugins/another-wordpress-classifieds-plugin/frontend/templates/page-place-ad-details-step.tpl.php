<h2><?php _e('Enter Ad Details', 'AWPCP') ?></h2>

<?php awpcp_print_messages(); ?>

<?php
    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }

    foreach ($errors as $index => $error) {
        if (is_numeric($index)) {
            echo awpcp_print_message($error, array('error'));
        } else {
            echo awpcp_print_message($error, array('error', 'ghost'));
        }
    }
?>

<?php if ($ui['delete-button']): ?>
<form class="awpcp-delete-ad-form" action="<?php echo $this->url() ?>" method="post">
    <?php foreach($hidden as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach ?>
    <input type="hidden" name="step" value="delete-ad" />

    <span><?php _e( 'You can use this button to delete your Ad.', 'AWPCP' ); ?></span>
    <span class="confirm">&nbsp;<?php _ex( 'Are you sure?', 'delete ad form in frontend edit ad screen', 'AWPCP' ); ?></span>
    <input class="button confirm" type="button" value="<?php _e("Cancel", "AWPCP"); ?>" />
    <input class="button button-primary" type="submit" value="<?php _e("Delete Ad", "AWPCP"); ?>" />
</form>
<?php endif ?>

<!-- TODO: check where is used $formdisplayvalue -->
<div>
	<form class="awpcp-details-form" id="adpostform" name="adpostform" action="<?php echo $this->url() ?>" method="post">
        <?php foreach($hidden as $name => $value): ?>
        <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
        <?php endforeach ?>

        <?php if ($ui['user-dropdown']): ?>

        <h3><?php _e('Ad Owner', 'AWPCP') ?></h3>
        <?php echo $page->users_dropdown($form['user_id'], $errors) ?>

        <?php endif; ?>

        <?php if ($ui['start-end-date']): ?>

        <h3><?php _e('Start & End Date', 'AWPCP'); ?></h3>

        <p class="awpcp-form-spacer">
            <label for="start-date"><?php _ex('Start Date', 'ad details form', 'AWPCP') ?></label>
            <?php $date = awpcp_time($form['start_date'], 'awpcp-date'); ?>
            <input class="inputbox" id="start-date" type="text" size="50" datepicker-placeholder value="<?php echo awpcp_esc_attr($date); ?>" />
            <input type="hidden" name="start_date" value="<?php echo awpcp_time( $form['start_date'], 'Y/m/d' ); ?>" />
            <?php echo awpcp_form_error('start_date', $errors) ?>
        </p>

        <p class="awpcp-form-spacer">
            <label for="end-date"><?php _ex('End Date', 'ad details form', 'AWPCP') ?></label>
            <?php $date = awpcp_time($form['end_date'], 'awpcp-date'); ?>
            <input class="inputbox" id="end-date" type="text" size="50" datepicker-placeholder value="<?php echo awpcp_esc_attr($date); ?>" />
            <input type="hidden" name="end_date" value="<?php echo awpcp_time( $form['end_date'], 'Y/m/d' ); ?>" />
            <?php echo awpcp_form_error('end_date', $errors) ?>
        </p>

        <?php endif; ?>

        <h3><?php _e('Add Details and Contact Information', 'AWPCP') ?></h3>

        <p class="awpcp-form-spacer awpcp-form-spacer-title">
            <label for="ad-title"><?php _e('Ad Title', 'AWPCP') ?></label>

            <?php
            if ($form['characters_allowed_in_title'] == 0) {
                $text = _x('No characters limit.', 'ad details form', 'AWPCP');
                $characters = '';
            } else {
                $text = _x('characters left.', 'ad details form', 'AWPCP');
                $characters = $form['remaining_characters_in_title'];
            }
            ?>

            <input class="inputbox required" id="ad-title" type="text" size="50" name="ad_title" value="<?php echo awpcp_esc_attr($form['ad_title']) ?>" data-max-characters="<?php echo $form['characters_allowed_in_title']; ?>" data-remaining-characters="<?php echo $form['remaining_characters_in_title'] ?>"/>
            <br/><label for="ad-title" class="characters-left"><span class="characters-left-placeholder"><?php echo $characters ?></span>&nbsp;<?php echo $text ?></label>
            <?php echo awpcp_form_error('ad_title', $errors) ?>
        </p>

        <?php if ($ui['category-field']): ?>
        <p class="awpcp-form-spacer">
            <label for="add_new_ad_cat"><?php _e('Ad Category', 'AWPCP') ?></label>
            <select class="required" id="add_new_ad_cat" name="ad_category">
                <option value=""><?php _e('Select your Ad category', 'AWPCP') ?></option>
                <?php echo get_categorynameidall($form['ad_category']); ?>
            </select>
            <?php echo awpcp_form_error('ad_category', $errors) ?>
        </p>
        <?php endif ?>

        <?php if ($ui['website-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $validator = $ui['website-field-required'] ? 'required url' : 'url' ?>
            <label for="website-url"><?php _ex('Website URL', 'ad details form', 'AWPCP') ?></label>
            <input class="inputbox <?php echo $validator ?>" id="website-url" type="text" size="50" name="websiteurl" value="<?php echo awpcp_esc_attr($form['websiteurl']) ?>" />
            <?php echo awpcp_form_error('websiteurl', $errors) ?>
        </p>
        <?php endif ?>

        <p class="awpcp-form-spacer">
            <?php $readonly = $ui['contact-name-field-readonly'] ? 'readonly="readonly"' : ''; ?>
            <label for="ad-contact-name"><?php _ex('Name of Person to Contact', 'ad details form', 'AWPCP') ?></label>
            <input class="inputbox required" id="ad-contact-name" <?php echo $readonly; ?> type="text"  size="50" name="ad_contact_name" value="<?php echo awpcp_esc_attr($form['ad_contact_name']) ?>" />
            <?php echo awpcp_form_error('ad_contact_name', $errors) ?>
        </p>

        <p class="awpcp-form-spacer">
            <?php $readonly = $ui['contact-email-field-readonly'] ? 'readonly="readonly"' : ''; ?>
            <label for="ad-contact-email"><?php _ex("Contact Person's Email", 'ad details form', 'AWPCP') ?>&nbsp;<span class="helptext"><?php _ex('(Please enter a valid email. The codes needed to edit your Ad will be sent to your email address)', 'ad details form', 'AWPCP') ?></span></label>
            <input class="inputbox required email" id="ad-contact-email" <?php echo $readonly; ?> type="text" size="50" name="ad_contact_email" value="<?php echo awpcp_esc_attr($form['ad_contact_email']) ?>" />
            <?php echo awpcp_form_error('ad_contact_email', $errors) ?>
        </p>

        <?php if ($ui['contact-phone-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $validator = $ui['contact-phone-field-required'] ? 'required' : '' ?>
            <label for="ad-contact-phone"><?php _ex("Contact Person's Phone Number", 'ad details form', 'AWPCP') ?></label>
            <input class="inputbox <?php echo $validator ?>" id="ad-contact-phone" type="text" size="50" name="ad_contact_phone" value="<?php echo awpcp_esc_attr($form['ad_contact_phone']) ?>" />
            <?php echo awpcp_form_error('ad_contact_phone', $errors); ?>
        </p>
        <?php endif ?>

        <?php
        $query = array(
            'country' => $form['ad_country'],
            'state' => $form['ad_state'],
            'city' => $form['ad_city'],
            'county' => $form['ad_county_village']
        );
        $translations = array(
            'country' => 'ad_country',
            'state' => 'ad_state',
            'city' => 'ad_city',
            'county' => 'ad_county_village'
        );

        if ($ui['module-region-fields'])
            echo awpcp_region_control_form_fields($query, $translations, $errors, false);
        else
            echo awpcp_region_form_fields($query, $translations, 'details', $errors);
        ?>

        <?php if ($ui['price-field']): ?>
        <p class="awpcp-form-spacer">
            <?php $validator = $ui['price-field-required'] ? 'required money' : 'money' ?>
            <?php $price = $form['ad_item_price'] ? awpcp_format_money( $form['ad_item_price'], false ) : ''; ?>
            <label for="ad-item-price"><?php _ex('Item Price', 'ad details form', 'AWPCP') ?></label>
            <input class="<?php echo $validator ?>" id="ad-item-price" type="text" size="50" name="ad_item_price" value="<?php echo esc_attr( $price ); ?>" />
            <?php echo awpcp_form_error('ad_item_price', $errors) ?>
        </p>
        <?php endif ?>

        <p class="awpcp-form-spacer">
            <label for="ad-details"><?php _ex('Ad Details', 'ad details form', 'AWPCP') ?></label>

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
            <label for="ad-details" class="characters-left"><span class="characters-left-placeholder"><?php echo $characters ?></span>&nbsp;<?php echo $text ?></label>

            <textarea class="textareainput required" id="ad-details" name="ad_details" rows="10" cols="50" data-max-characters="<?php echo $form['characters_allowed']; ?>" data-remaining-characters="<?php echo $form['remaining_characters'] ?>"><?php echo awpcp_esc_textarea($form['ad_details']) ?></textarea>
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
            <a href="<?php esc_attr($text) ?>" target="_blank"><?php _ex("Read our Terms of Service", 'ad details form', "AWPCP"); ?></a>
        <?php else: ?>
            <label><?php _ex("Terms of service:", 'ad details form', "AWPCP") ?></label>
            <textarea readonly="readonly" rows="5" cols="50"><?php echo $text ?></textarea>
        <?php endif ?>
            <br>
            <input class="required" id="terms-of-service" type="checkbox" name="terms-of-service" value="1" />
            <label class="inline" for="terms-of-service"><?php _ex('I agree to the terms of service', 'ad details form', 'AWPCP'); ?></label>
            <?php echo awpcp_form_error('terms-of-service', $errors) ?>
        </p>
        <?php endif ?>



        <?php if ($ui['captcha']): ?>
        <div class='awpcp-form-spacer'>
            <?php $captcha = awpcp_create_captcha( get_awpcp_option( 'captcha-provider' ) ); ?>
            <?php echo $captcha->render(); ?>
            <?php echo awpcp_form_error('captcha', $errors) ?>
        </div>
        <?php endif ?>

        <?php /*if (is_admin() && isset($form['ad_id']) && absint($form['ad_id']) > 0): ?>
        <input type="submit" class="button" value="<?php _ex("Edit Ad", 'ad details form', "AWPCP") ?>" />
        <?php elseif (is_admin()): ?>
        <input type="submit" class="button" value="<?php _ex("Place Ad", 'ad details form', "AWPCP") ?>" />
        <?php else:*/ ?>
        <input type="submit" class="button" value="<?php _ex('Continue', 'ad details form', "AWPCP"); ?>" />
        <?php /*endif*/ ?>
	</form>
</div>
