<?php

require_once(AWPCP_DIR . '/frontend/page-place-ad.php');


/**
 * @since  2.1.4
 */
class AWPCP_EditAdPage extends AWPCP_Place_Ad_Page {

    public $active = false;
    public $messages = array();

    public function __construct($page='awpcp-edit-ad', $title=null) {
        parent::__construct($page, $title);
    }

    protected function get_ad() {
        if (!isset($this->ad)) $this->ad = null;

        if (is_null($this->ad)) {
            if ($id = awpcp_request_param('ad_id', awpcp_request_param('id', false))) {
                $this->ad = AWPCP_Ad::find_by_id($id);
            }
        }

        return $this->ad;
    }

    protected function get_edit_hash($ad) {
        return wp_create_nonce("edit-ad-{$ad->ad_id}");
    }

    protected function verify_edit_hash($ad) {
        return wp_verify_nonce(awpcp_request_param('hash'), "edit-ad-{$ad->ad_id}");
    }

    protected function is_user_allowed_to_edit($ad) {
        if (awpcp_current_user_is_admin())
            return true;
        if ($ad->user_id == wp_get_current_user()->ID)
            return true;
        if ($this->verify_edit_hash($ad))
            return true;
        return false;
    }

    protected function send_email_notifications($ad) {
        $messages = $this->get_ad_notices($ad);

        // send user notification
        if (AWPCP_Ad::belongs_to_user($ad->ad_id, wp_get_current_user()->ID)) {
            awpcp_ad_updated_email($ad, join("\n\n", $messages));
        }

        $ad_approve = get_awpcp_option('adapprove') == 1;
        $images_approve = get_awpcp_option('imagesapprove') == 1;

        // send admin notification
        if (($ad_approve || $images_approve) && $ad->disabled) {
            awpcp_ad_awaiting_approval_email($ad, $ad_approve, $images_approve);
        }
    }

    protected function _dispatch($default=null) {
        $is_admin_user = awpcp_current_user_is_admin();
        $user = wp_get_current_user();

        if ($user->ID && !is_admin() && get_awpcp_option('enable-user-panel') == 1) {
            $url = admin_url('admin.php?page=awpcp-panel');
            $message = __('Please go to the Ad Management panel to edit your Ads.', 'AWPCP');
            $message = sprintf('%s <a href="%s">%s</a>', $message, $url, __('Click here', 'AWPCP'));
            return $this->render('content', awpcp_print_message($message));
        }

        $ad = $this->get_ad();
        if (!is_null($ad) && !$this->is_user_allowed_to_edit($ad)) {
            $message = __('You are not allowed to edit the especified Ad.', 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        $action = $this->get_current_action($default);

        switch ($action) {
            case 'details':
            case 'save-details':
                return $this->details_step();
                break;
            case 'upload-images':
                return $this->upload_images_step();
                break;
            case 'delete-ad':
                return $this->delete_ad_step();
                break;
            case 'send-access-key':
                return $this->send_access_key_step();
                break;
            default:
                return $this->edit_ad_step();
                break;
        }
    }

    public function edit_ad_step($show_errors=true) {
        global $wpdb;

        $errors = array();
        $messages = $this->messages;

        $form = array(
            'ad_email' => awpcp_post_param('ad_email'),
            'ad_key' => awpcp_post_param('ad_key'),
            'attempts' => (int) awpcp_post_param('attempts', 0));

        if ($form['attempts'] == 0 && get_awpcp_option('enable-user-panel') == 1) {
            $url = admin_url('admin.php?page=awpcp-panel');
            $message = __('You are currently not logged in, if you have an account in this website you can log in and go to the Ad Management panel to edit your Ads.', 'AWPCP');
            $message = sprintf('%s <a href="%s">%s</a>', $message, $url, __('Click here', 'AWPCP'));
            $this->messages[] = $message;
        }

        $send_access_key_url = add_query_arg( array( 'step' => 'send-access-key' ), $this->url() );

        if (empty($form['ad_email'])) {
            $errors['ad_email'] = __('Please enter the email address you used when you created your Ad in addition to the Ad access key that was emailed to you after your Ad was submitted.', 'AWPCP');
        } else if (!is_email($form['ad_email'])) {
            $errors['ad_email'] = __('Please enter a valid email address.', 'AWPCP');
        }

        if (empty($form['ad_key'])) {
            $errors['ad_key'] = __('Please enter your Ad access key.', 'AWPCP');
        }

        if (empty($errors)) {
            $this->ad = AWPCP_Ad::find_by_email_and_key($form['ad_email'], $form['ad_key']);
            if (is_null($this->ad)) {
                $errors[] = __('The email address and access key you entered does not match any of the Ads in our system.', 'AWPCP');
            } else {
                return $this->details_step();
            }
        } else if ($form['attempts'] == 0 || $show_errors === false) {
            $errors = array();
        }

        $hidden = array('attempts' => $form['attempts'] + 1);
        $params = compact( 'form', 'hidden', 'messages', 'errors', 'send_access_key_url' );
        $template = AWPCP_DIR . '/frontend/templates/page-edit-ad-email-key-step.tpl.php';

        return $this->render($template, $params);
    }

    public function details_step() {
        $ad = $this->get_ad();

        if (is_null($ad)) return $this->edit_ad_step();

        if (strcmp($this->get_current_action(), 'save-details') === 0) {
            return $this->save_details_step();
        } else {
            return $this->details_step_form($ad, array());
        }
    }

    public function details_step_form($ad, $form, $errors=array()) {
        $form = $this->get_posted_details( $form );
        $form = array_merge( $form, $this->get_characters_allowed( $ad->ad_id ) );

        // if there are errors then the user already sent edited information,
        // and we don't need to provide defaults from Ad object
        if (empty($errors)) {
            foreach ($this->get_ad_info($ad->ad_id) as $field => $value) {
                $form[$field] = empty($form[$field]) ? $value : $form[$field];
            }
        }

        $hidden = array('hash' => $this->get_edit_hash($ad));

        return $this->details_form($form, true, $hidden, $errors);
    }

    public function save_details_step($errors=array()) {
        global $wpdb, $hasextrafieldsmodule;

        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __('The especified Ad doesn\'t exists.', 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        $data = $this->get_posted_details( $_POST );
        $characters = $this->get_characters_allowed( $ad->ad_id );
        $errors = array();

        if (!$this->validate_details($data, true, $errors)) {
            return $this->details_step_form($ad, $data, $errors);
        }

        do_action('awpcp_before_edit_ad', $ad);

        // only admins can change the owner of an Ad
        if (!awpcp_current_user_is_admin() || empty($data['user_id'])) {
            $data['user_id'] = $ad->user_id;
        }

        $ad->user_id = $data['user_id'];
        $ad->ad_title = $this->prepare_ad_title( $data['ad_title'], $characters['characters_allowed_in_title']);
        $ad->ad_details = $this->prepare_ad_details($data['ad_details'], $characters['characters_allowed']);
        $ad->ad_contact_name = $data['ad_contact_name'];
        $ad->ad_contact_phone = $data['ad_contact_phone'];
        $ad->ad_contact_email = $data['ad_contact_email'];
        $ad->websiteurl = $data['websiteurl'];
        $ad->ad_country = $data['ad_country'];
        $ad->ad_state = $data['ad_state'];
        $ad->ad_city = $data['ad_city'];
        $ad->ad_county_village = $data['ad_county_village'];
        $ad->ad_item_price = awpcp_parse_money($data['ad_item_price']) * 100;
        $ad->ad_last_updated = current_time('mysql');

        if (awpcp_current_user_is_admin()) {
            $ad->ad_startdate = awpcp_time($data['start_date']);
            $ad->ad_enddate = awpcp_time($data['end_date']);
        }

        if (awpcp_current_user_is_admin() && !empty($data['ad_category'])) {
            $ad->ad_category_id = $data['ad_category'];
        }

        if (!$ad->save()) {
            $errors[] = __('There was an unexpected error trying to save your Ad details. Please try again or contact an administrator.', 'AWPCP');
            return $this->details_step_form($ad, $data, $errors);
        }

        do_action('awpcp_edit_ad', $ad);

        if (is_admin() || !get_awpcp_option('imagesallowdisallow')) {
            return $this->finish_step();
        } else {
            return $this->upload_images_step();
        }
    }

    public function upload_images_step() {
        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __('The specified Ad doesn\'t exists. No images can be added at this time.', 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        $errors = array();
        $this->upload_images($ad, $errors);

        $payment_term = awpcp_payments_api()->get_ad_payment_term($ad);

        $images_allowed = $payment_term->images;
        $images_uploaded = $ad->get_total_images_uploaded();
        $images_left = max($images_allowed - $images_uploaded, 0);
        $max_image_size = get_awpcp_option('maximagesize');

        // see if we can move to the next step
        if (!get_awpcp_option('imagesallowdisallow')) {
            return $this->finish_step();
        } else if (empty($errors) && awpcp_post_param('submit-no-images', false)) {
            return $this->finish_step();
        } else if (($images_uploaded == 0 && $images_allowed == 0)) {
            return $this->finish_step();
        }


        // we are still here... let's show the upload images form
        $params = array(
            'images_left' => $images_left,
            'images_uploaded' => $images_uploaded,
            'max_image_size' => $max_image_size,
            'images' => awpcp_get_ad_images($ad->ad_id),
            'hidden' => array(
                'ad_id' => $ad->ad_id,
                'hash' => $this->get_edit_hash($ad)),
            'messages' => $this->messages,
            'errors' => $errors
        );

        return $this->upload_images_form($ad, $params);
    }

    public function finish_step() {
        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __('The especified Ad doesn\'t exists.', 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        // TODO: move awpcp_calculate_ad_disabled_state() function to Ad model
        if ($ad->disabled && !awpcp_calculate_ad_disabled_state($ad->ad_id)) {
            $ad->enable();
        }

        $this->send_email_notifications($ad);

        if (is_admin()) {
            $message = __('The Ad has been edited successfully. <a href="%s">Go back to view listings</a>.', 'AWPCP');
            $page = awpcp_current_user_is_admin() ? 'awpcp-listings' : 'awpcp-panel';
            $url = add_query_arg('page', $page, admin_url('admin.php'));

            $this->messages[] = sprintf($message, $url);
        }

        $template = AWPCP_DIR . '/frontend/templates/page-place-ad-finish-step.tpl.php';
        $params = array(
            'messages' => array_merge($this->messages, $this->get_ad_notices($ad)),
            'edit' => true,
            'ad' => $ad
        );

        return $this->render($template, $params);
    }

    public function delete_ad_step() {
        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __('The especified Ad doesn\'t exists.', 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        if ( awpcp_post_param( 'confirm', false ) && $ad->delete() ) {
            $this->messages[] = __('Your Ad has been successfully deleted.', 'AWPCP');
            return $this->edit_ad_step();
        } else {
            $this->messages[] = __('There was a problem trying to delete your Ad. The Ad was not deleted.', 'AWPCP');
            return $this->details_step();
        }
    }

    public function send_access_key_step() {
        global $wpdb;

        $errors = array();
        $form = array(
            'ad_email' => awpcp_post_param('ad_email'),
            'attempts' => (int) awpcp_post_param('attempts', 0)
        );

        if ($form['attempts'] == 0 && get_awpcp_option('enable-user-panel') == 1) {
            $url = admin_url('admin.php?page=awpcp-panel');
            $message = __('You are currently not logged in, if you have an account in this website you can log in and go to the Ad Management panel to edit your Ads.', 'AWPCP');
            $message = sprintf('%s <a href="%s">%s</a>', $message, $url, __('Click here', 'AWPCP'));
            $this->messages[] = $message;
        }

        if (empty($form['ad_email'])) {
            $errors['ad_email'] = __('Please enter the email address you used when you created your Ad.', 'AWPCP');
        } else if (!is_email($form['ad_email'])) {
            $errors['ad_email'] = __('Please enter a valid email address.', 'AWPCP');
        }

        $ads = array();
        if ( empty( $errors ) ) {
            $ads = AWPCP_Ad::find_by_email( $form['ad_email'] );
            if ( empty( $ads ) ) {
                $errors[] = __('The email address you entered does not match any of the Ads in our system.', 'AWPCP');
            }
        } else if ( $form['attempts'] == 0 ) {
            $errors = array();
        }

        // if $ads is non-empty then $errors is empty
        if ( !empty( $ads ) ) {
            $access_keys_sent = $this->send_access_keys( $ads, $errors );
        } else {
            $access_keys_sent = false;
        }

        if ( !$access_keys_sent ) {
            $send_access_key_url = add_query_arg( array( 'step' => 'send-access-key' ), $this->url() );

            $messages = $this->messages;
            $hidden = array('attempts' => $form['attempts'] + 1);
            $params = compact( 'form', 'hidden', 'messages', 'errors', 'send_access_key_url' );
            $template = AWPCP_DIR . '/frontend/templates/page-edit-ad-send-access-key-step.tpl.php';

            return $this->render($template, $params);
        } else {
            return $this->edit_ad_step(false);
        }
    }

    public function send_access_keys($ads, &$errors=array()) {
        $ad = reset( $ads );

        $recipient = "{$ad->ad_contact_name} <{$ad->ad_contact_email}>";
        $template = AWPCP_DIR . '/frontend/templates/email-send-all-ad-access-keys.tpl.php';

        $message = new AWPCP_Email;
        $message->to[] = $recipient;
        $message->subject = get_awpcp_option( 'resendakeyformsubjectline' );

        $message->prepare($template,  array(
            'ads' => $ads,
            'introduction' => get_awpcp_option('resendakeyformbodymessage'),
        ));

        if ($message->send()) {
            $this->messages[] = sprintf( __( 'The access keys were sent to %s.', 'AWPCP' ), esc_html( $recipient ) );
            return true;
        } else {
            $errors[] = sprintf( __( 'There was an error trying to send the email to %s.', 'AWPCP' ), esc_html( $recipient ) );
            return false;
        }
    }
}
