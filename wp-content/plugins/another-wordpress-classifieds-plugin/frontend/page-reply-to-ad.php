<?php

require_once(AWPCP_DIR . '/includes/helpers/page.php');


/**
 * @since  2.1.4
 */
class AWPCP_ReplyToAdPage extends AWPCP_Page {

    private $ad = null;

    public $messages = array();

    public function __construct($page='awpcp-reply-to-ad', $title=null) {
        parent::__construct($page, is_null($title) ? __('Reply to Ad', 'another-wordpress-classifieds-plugin') : $title);
    }

    public function get_current_action($default='contact') {
        return awpcp_request_param('a', $default);
    }

    public function get_ad() {
        if (is_null($this->ad)) {
            $ad_id = absint(awpcp_request_param('i'));
            if ($ad_id === 0 && get_awpcp_option('seofriendlyurls')) {
                $permalinks = get_option('permalink_structure');
                if (!empty($permalinks)) {
                    $ad_id = absint(get_query_var('id'));
                }
            }

            $this->ad = AWPCP_Ad::find_by_id($ad_id);
        }

        return $this->ad;
    }

    public function url($params=array()) {
        $url = awpcp_get_page_url('reply-to-ad-page-name');
        return add_query_arg( urlencode_deep( $params ), $url );
    }

    public function dispatch() {
        wp_enqueue_script('awpcp-page-reply-to-ad');

        $awpcp = awpcp();
        $awpcp->js->localize( 'page-reply-to-ad', array(
            'awpcp_sender_name' => __( 'Please enter your name.', 'another-wordpress-classifieds-plugin' ),
            'awpcp_sender_email' => __( 'Please enter your email address.', 'another-wordpress-classifieds-plugin' ),
            'awpcp_contact_message' => __( 'The message cannot be empty.', 'another-wordpress-classifieds-plugin' ),
            'captcha' => __( 'Please type in the result of the operation.', 'another-wordpress-classifieds-plugin' ),
        ) );

        return $this->_dispatch();
    }

    protected function _dispatch($default=null) {
        $action = $this->get_current_action();

        if (get_awpcp_option('reply-to-ad-requires-registration') && !is_user_logged_in()) {
            $message = __('Only registered users can reply to Ads. If you are already registered, please login below in order to reply to the Ad.', 'another-wordpress-classifieds-plugin');
            return $this->render('content', awpcp_login_form($message, awpcp_current_url()));
        }

        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __('The specified Ad does not exist.', 'another-wordpress-classifieds-plugin');
            return $this->render('content', awpcp_print_error($message));
        }

        switch ($action) {
            case 'contact':
                return $this->contact_step();
            case 'docontact1':
            default:
                return $this->process_contact_form();
        }
    }

    protected function get_posted_data() {
        $posted_data = array(
            'awpcp_sender_name' => awpcp_request_param('awpcp_sender_name'),
            'awpcp_sender_email' => awpcp_request_param('awpcp_sender_email'),
            'awpcp_contact_message' => awpcp_request_param('awpcp_contact_message'),
        );

        if ( is_user_logged_in() ) {
            $posted_data = $this->overwrite_sender_information( $posted_data );
        }

        return $posted_data;
    }

    /**
     * @since 3.3
     */
    private function overwrite_sender_information( $posted_data ) {
        $user_information = awpcp_users_collection()->find_by_id(
            get_current_user_id(), array( 'public_name', 'user_email' )
        );

        $posted_data['awpcp_sender_name'] = $user_information->public_name;
        $posted_data['awpcp_sender_email'] = $user_information->user_email;

        return $posted_data;
    }

    protected function validate_posted_data($data, &$errors=array()) {
        if (empty($data['awpcp_sender_name'])) {
            $errors['awpcp_sender_name'] = __('Please enter your name.', 'another-wordpress-classifieds-plugin');
        }

        if (empty($data['awpcp_sender_email'])) {
            $errors['awpcp_sender_email'] = __('Please enter your email.', 'another-wordpress-classifieds-plugin');
        } else if ( ! awpcp_is_valid_email_address( $data['awpcp_sender_email'] ) ) {
            $errors['ad_contact_email'] = __("The email address you entered was not a valid email address. Please check for errors and try again.", 'another-wordpress-classifieds-plugin');
        }

        if (empty($data['awpcp_contact_message'])) {
            $errors['awpcp_contact_message'] = __('There was no text in your message. Please enter a message.', 'another-wordpress-classifieds-plugin');
        }

        if ( get_awpcp_option( 'use-akismet-in-reply-to-listing-form' ) ) {
            $spam_filter = awpcp_listing_reply_spam_filter();

            if ( $spam_filter->is_spam( $data ) ) {
                $errors['awpcp_contact_message'] = __( 'Your message was flagged as spam. Please contact the administrator of this site.', 'another-wordpress-classifieds-plugin' );
            }
        }

        if ( get_awpcp_option( 'captcha-enabled-in-reply-to-listing-form' ) ) {
            $captcha = awpcp_create_captcha( get_awpcp_option( 'captcha-provider' ) );

            $error = '';
            if ( !$captcha->validate( $error ) ) {
                $errors['captcha'] = $error;
            }
        }

        return empty($errors);
    }

    protected function contact_step() {
        return $this->contact_form( $this->get_posted_data() );
    }

    protected function contact_form($form, $errors=array()) {
        $ad = $this->get_ad();
        $ad_link = sprintf('<strong><a href="%s">%s</a></strong>', url_showad($ad->ad_id), $ad->get_title());

        $params = array(
            'messages' => $this->messages,
            'hidden' => array(
                'a' => 'docontact1',
                'ad_id' => $ad->ad_id,
            ),
            'form' => $form,
            'errors' => $errors,
            'ad_link' => $ad_link,
            'ui' => array(
                'disable-sender-fields' => get_awpcp_option( 'reply-to-ad-requires-registration' ),
                'captcha' => get_awpcp_option( 'captcha-enabled-in-reply-to-listing-form' ),
            ),
        );

        $template = AWPCP_DIR . '/frontend/templates/page-reply-to-ad.tpl.php';

        return $this->render($template, $params);
    }

    protected function process_contact_form() {
        global $nameofsite;

        $ad = $this->get_ad();

        $form = array_merge( $this->get_posted_data(), array( 'ad_id' => $ad->ad_id ) );
        $errors = array();

        if (!$this->validate_posted_data($form, $errors)) {
            return $this->contact_form($form, $errors);
        }

        $ad_title = $ad->get_title();
        $ad_url = url_showad($ad->ad_id);

        $sender_name = stripslashes($form['awpcp_sender_name']);
        $sender_email = stripslashes($form['awpcp_sender_email']);
        $message = awpcp_strip_html_tags(stripslashes($form['awpcp_contact_message']));


        if (get_awpcp_option('usesenderemailinsteadofadmin')) {
            $sender = awpcp_strip_html_tags($sender_name);
            $from = $sender_email;
        } else {
            $sender = awpcp_admin_sender_name();
            $from = awpcp_admin_sender_email_address();
        }

        /* send email to admin */
        if (get_awpcp_option('notify-admin-about-contact-message')) {
            $subject = __('Notification about a response regarding Ad: %s', 'another-wordpress-classifieds-plugin');

            $template = AWPCP_DIR . '/frontend/templates/email-reply-to-ad-admin.tpl.php';
            $params = compact( 'ad_url', 'ad_title', 'message', 'sender_name', 'sender_email', 'nameofsite' );

            $email = new AWPCP_Email();

            $email->subject = sprintf( $subject, $ad_title );
            $email->to = awpcp_admin_recipient_email_address();
            $email->from = awpcp_format_email_address( $from, $sender );
            $email->headers['Reply-To'] = awpcp_format_email_address( $sender_email, $sender_name );
            $email->prepare( $template, $params );

            $result = $email->send();
        }

        /* send email to user */ {
            $subject = sprintf("%s %s: %s", get_awpcp_option('contactformsubjectline'),
                                            _x('regarding', 'reply email', 'another-wordpress-classifieds-plugin'),
                                            $ad_title);

            // TODO: Update email templates so that 1. placehoders can be used freely
            //       and 2. modules can define what placeholders are available and when/where.
            $placeholders = array(
                'bp_user_profile_url', 'bp_user_listings_url', 'bp_username',
                'bp_current_user_profile_url', 'bp_current_user_listings_url', 'bp_current_username',
            );

            $body_template = get_awpcp_option( 'contactformbodymessage' );
            $body = awpcp_replace_placeholders( $placeholders, $ad, $body_template, 'reply-to-listing' );

            $template = AWPCP_DIR . '/frontend/templates/email-reply-to-ad-user.tpl.php';
            $params = compact( 'body', 'ad_title', 'ad_url', 'message', 'sender_name', 'sender_email', 'nameofsite' );

            $email = new AWPCP_Email();

            $email->subject = trim( $subject );
            $email->to = awpcp_format_recipient_address( get_adposteremail( $ad->ad_id ) );
            $email->from = awpcp_format_email_address( $from, $sender );
            $email->headers['Reply-To'] = awpcp_format_email_address( $sender_email, $sender_name );
            $email->prepare( $template, $params );

            $result = $email->send();
        }

        if ($result) {

            $view_listing_link = sprintf( '<a href="%s">%s</a>', $ad_url, $ad_title );

            $message = __( 'Your message has been sent. Return to <view-listing-link>.', 'another-wordpress-classifieds-plugin' );
            $message = str_replace( '<view-listing-link>', '<strong>' . $view_listing_link . '</strong>', $message );
            return $this->render('content', awpcp_print_message($message));
        } else {
            $this->messages[] = __("There was a problem encountered during the attempt to send your message. Please try again and if the problem persists, please contact the system administrator.",'another-wordpress-classifieds-plugin');
            return $this->contact_form($form, $errors);
        }
    }
}
