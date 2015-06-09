<?php

require_once(AWPCP_DIR . '/includes/helpers/page.php');


/**
 * @since  2.1.4
 */
class AWPCP_ReplyToAdPage extends AWPCP_Page {

    private $ad = null;

    public $messages = array();

    public function __construct($page='awpcp-reply-to-ad', $title=null) {
        parent::__construct($page, is_null($title) ? __('Reply to Ad', 'AWPCP') : $title);
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
            'awpcp_sender_name' => __( 'Please enter your name.', 'AWPCP' ),
            'awpcp_sender_email' => __( 'Please enter your email address.', 'AWPCP' ),
            'awpcp_contact_message' => __( 'The message cannot be empty.', 'AWPCP' ),
            'captcha' => __( 'Please type in the result of the operation.', 'AWPCP' ),
        ) );

        return $this->_dispatch();
    }

    protected function _dispatch($default=null) {
        $action = $this->get_current_action();

        if (get_awpcp_option('reply-to-ad-requires-registration') && !is_user_logged_in()) {
            $message = __('Only registered users can reply to Ads. If you are already registered, please login below in order to reply to the Ad.', 'AWPCP');
            return $this->render('content', awpcp_login_form($message, awpcp_current_url()));
        }

        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __('The specified Ad does not exist.', 'AWPCP');
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
        $user_information = awpcp_users_collection()->find_by_id( get_current_user_id() );

        if ( isset( $user_information->display_name ) && ! empty( $user_information->display_name ) ) {
            $posted_data['awpcp_sender_name'] = $user_information->display_name;
        } else if ( isset( $user_information->user_login ) && ! empty( $user_information->user_login ) ) {
            $posted_data['awpcp_sender_name'] = $user_information->user_login;
        } else if ( isset( $user_information->username ) && ! empty( $user_information->username ) ) {
            $posted_data['awpcp_sender_name'] = $user_information->username;
        }

        $posted_data['awpcp_sender_email'] = $user_information->user_email;

        return $posted_data;
    }

    protected function validate_posted_data($data, &$errors=array()) {
        if (empty($data['awpcp_sender_name'])) {
            $errors['awpcp_sender_name'] = __('Please enter your name.', 'AWPCP');
        }

        if (empty($data['awpcp_sender_email'])) {
            $errors['awpcp_sender_email'] = __('Please enter your email.', 'AWPCP');
        } else if (!isValidEmailAddress($data['awpcp_sender_email'])) {
            $errors['ad_contact_email'] = __("The email address you entered was not a valid email address. Please check for errors and try again.", "AWPCP");
        }

        if (empty($data['awpcp_contact_message'])) {
            $errors['awpcp_contact_message'] = __('There was no text in your message. Please enter a message.', 'AWPCP');
        }

        if (get_awpcp_option('useakismet')) {
            $spam_filter = awpcp_listing_reply_spam_filter();

            if ( $spam_filter->is_spam( $data ) ) {
                $errors['awpcp_contact_message'] = __( 'Your message was flagged as spam. Please contact the administrator of this site.', 'AWPCP' );
            }
        }

        if ( get_awpcp_option( 'captcha-enabled' ) ) {
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
                'captcha' => get_awpcp_option( 'captcha-enabled' ),
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
            $sender = $nameofsite;
            $from = awpcp_admin_sender_email_address();
        }

        /* send email to admin */
        if (get_awpcp_option('notify-admin-about-contact-message')) {
            $subject = __('Notification about a response regarding Ad: %s', 'AWPCP');
            $subject = sprintf($subject, $ad_title);

            ob_start();
                include(AWPCP_DIR . '/frontend/templates/email-reply-to-ad-admin.tpl.php');
                $admin_body = ob_get_contents();
            ob_end_clean();

            $admin_email = awpcp_admin_recipient_email_address();
            $result = awpcp_process_mail($from, $admin_email, $subject, $admin_body, $sender, $sender_email);
        }

        /* send email to user */ {
            $subject = sprintf("%s %s: %s", get_awpcp_option('contactformsubjectline'),
                                            _x('regarding', 'reply email', 'AWPCP'),
                                            $ad_title);

            ob_start();
                include(AWPCP_DIR . '/frontend/templates/email-reply-to-ad-user.tpl.php');
                $body = ob_get_contents();
            ob_end_clean();

            $sendtoemail = get_adposteremail($ad->ad_id);
            $result = awpcp_process_mail( $from, $sendtoemail, trim($subject), $body, $sender, $sender_email );
        }

        if ($result) {
            $message = __("Your message has been sent.","AWPCP");
            return $this->render('content', awpcp_print_message($message));
        } else {
            $this->messages[] = __("There was a problem encountered during the attempt to send your message. Please try again and if the problem persists, please contact the system administrator.","AWPCP");
            return $this->contact_form($form, $errors);
        }
    }
}
