<?php

require_once( AWPCP_DIR . '/includes/helpers/admin-page.php' );

require_once( AWPCP_DIR . '/admin/class-media-manager.php' );

require_once( AWPCP_DIR . '/admin/admin-panel-listings-place-ad-page.php' );
require_once( AWPCP_DIR . '/admin/admin-panel-listings-edit-ad-page.php' );
require_once( AWPCP_DIR . '/admin/admin-panel-listings-renew-ad-page.php' );
require_once( AWPCP_DIR . '/admin/admin-panel-listings-table.php' );


/**
 * @since 2.1.4
 */
class AWPCP_Admin_Listings extends AWPCP_AdminPageWithTable {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings';
        $title = $title ? $title : __('AWPCP Classifieds Management System - Manage Ad Listings', 'AWPCP');
        parent::__construct($page, $title, __('Listings', 'AWPCP'));

        $this->table = null;

        add_action('wp_ajax_awpcp-listings-delete-ad', array($this, 'ajax'));
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();
    }

    /**
     * Handler for admin_print_styles hook associated to this page.
     */
    public function scripts() {
        // necessary in the Place Ad operation
        wp_enqueue_style('awpcp-frontend-style');
        wp_enqueue_script('awpcp-admin-listings');

        awpcp()->js->localize( 'admin-listings', 'delete-message', __( 'Are you sure you want to delete the selected Ads?', 'AWPCP' ) );
        awpcp()->js->localize( 'admin-listings', 'cancel', __( 'Cancel', 'AWPCP' ) );
    }

    protected function params_blacklist() {
        // we don't need all this in our URLs, do we?
        return array(
            'a', 'action2', 'action', // action and bulk actions
            'selected', // selected rows for bulk actions
            '_wpnonce',
            '_wp_http_referer'
        );
    }

    public function get_current_action($default=null) {
        $blacklist = $this->params_blacklist();

        // return current bulk-action, if one was selected
        if (!$this->action)
            $this->action = $this->get_table()->current_action();

        if (!$this->action) {
            $action = awpcp_request_param('a', 'index');
            $action = awpcp_request_param('action', $action);
            $this->action = $action;
        }

        if (!isset($this->params) || empty($this->params)) {
            wp_parse_str($_SERVER['QUERY_STRING'], $_params);
            $this->params = array_diff_key($_params, array_combine($blacklist, $blacklist));
        }

        return $this->action;
    }

    public function get_table() {
        if ( is_null( $this->table ) ) {
            $this->table = new AWPCP_Listings_Table( $this, array( 'screen' => 'classifieds_page_awpcp-admin-listings' ) );
        }
        return $this->table;
    }

    public function actions($ad, $filter=false) {
        $admin = awpcp_current_user_is_admin();
        $actions = array();

        $actions['view'] = array(__('View', 'AWPCP'), $this->url(array('action' => 'view', 'id' => $ad->ad_id)));
        // $actions['open'] = array(__('Open', 'AWPCP'), url_showad($ad->ad_id));
        $actions['edit'] = array(__('Edit', 'AWPCP'), $this->url(array('action' => 'edit', 'id' => $ad->ad_id)));
        $actions['trash'] = array(__('Delete', 'AWPCP'), $this->url(array('action' => 'delete', 'id' => $ad->ad_id)));

        if ($admin) {
            if ($ad->disabled)
                $actions['enable'] = array(__('Enable', 'AWPCP'), $this->url(array('action' => 'enable', 'id' => $ad->ad_id)));
            else
                $actions['disable'] = array(__('Disable', 'AWPCP'), $this->url(array('action' => 'disable', 'id' => $ad->ad_id)));

            if ($ad->flagged)
                $actions['unflag'] = array(__('Unflag', 'AWPCP'), $this->url(array('action' => 'unflag', 'id' => $ad->ad_id)));

            if (get_awpcp_option('useakismet'))
                $actions['spam'] = array('SPAM', $this->url(array('action' => 'spam', 'id' => $ad->ad_id)));

            $has_featured_ads = function_exists('awpcp_featured_ads');
            if ($has_featured_ads && $ad->is_featured_ad)
                $actions['remove-featured'] = array(__('Remove Featured', 'AWPCP'), $this->url(array('action' => 'remove-featured', 'id' => $ad->ad_id)));
            else if ($has_featured_ads)
                $actions['make-featured'] = array(__('Make Featured', 'AWPCP'), $this->url(array('action' => 'make-featured', 'id' => $ad->ad_id)));

            $actions['send-key'] = array(__('Send Access Key', 'AWPCP'), $this->url(array('action' => 'send-key', 'id' => $ad->ad_id)));
        }

        if ( $ad->is_about_to_expire() ) {
            $hash = awpcp_get_renew_ad_hash( $ad->ad_id );
            $params = array( 'action' => 'renew', 'id' => $ad->ad_id, 'awpcprah' => $hash );
            $actions['renwew-ad'] = array( __( 'Renew Ad', 'AWPCP' ), $this->url( $params ) );
        }

        if ($images = $ad->count_image_files()) {
            $label = __( 'Manage Images', 'AWPCP' );
            $url = $this->url(array('action' => 'manage-images', 'id' => $ad->ad_id));
            $actions['manage-images'] = array($label, array('', $url, " ($images)"));
        } else if (get_awpcp_option('imagesallowdisallow') == 1) {
            $actions['add-image'] = array(__('Add Images', 'AWPCP'), $this->url(array('action' => 'add-image', 'id' => $ad->ad_id)));
        }

        if ( $admin ) {
            $fb = AWPCP_Facebook::instance();
            if ( $fb->get( 'page_token', '' ) && !awpcp_get_ad_meta( $ad->ad_id, 'sent-to-facebook' ) ) {
                $actions['send-to-facebook'] = array(
                    __( 'Send to Facebook', 'AWPCP' ),
                    $this->url( array(
                        'action' => 'send-to-facebook',
                        'id' => $ad->ad_id
                    ) )
                );
            }
        }

        $actions = apply_filters( 'awpcp-admin-listings-table-actions', $actions, $ad, $this );

        if (is_array($filter)) {
            $actions = array_intersect_key($actions, array_combine($filter, $filter));
        }

        return $actions;
    }

    public function dispatch() {
        $this->id = awpcp_request_param('id', false);
        $action = $this->get_current_action();

        $protected_actions = array(
            'enable', 'approvead', 'bulk-enable',
            'disable', 'rejectad', 'bulk-disable',
            'remove-featured', 'bulk-remove-featured',
            'make-featured', 'bulk-make-featured',
            'mark-paid',
            'send-key',
            'bulk-renew',
            'bulk-send-to-facebook',
            'unflag',
            'spam', 'bulk-spam',
        );

        if (!awpcp_current_user_is_admin() && in_array($action, $protected_actions)) {
            awpcp_flash(_x('You do not have sufficient permissions to perform that action.', 'admin listings', 'AWPCP'), 'error');
            $action = 'index';
        }

        switch ($action) {
            case 'view':
                return $this->view_ad();
                break;

            case 'place-ad':
                return $this->place_ad();
                break;

            case 'edit':
            case 'dopost1':
                return $this->edit_ad();
                break;

            case 'bulk-disable':
            case 'rejectad':
            case 'disable':
                return $this->disable_ad();
                break;

            case 'bulk-enable':
            case 'approvead':
            case 'enable':
                return $this->enable_ad();
                break;

            case 'unflag':
                return $this->unflag_ad();
                break;

            case 'mark-paid':
                return $this->mark_as_paid();
                break;

            case 'bulk-renew':
            case 'renew-ad':
            case 'renew':
                return $this->renew_ad();
                break;

            case 'bulk-spam':
            case 'spam':
                return $this->mark_as_spam();
                break;

            case 'bulk-make-featured':
            case 'make-featured':
                return $this->make_featured_ad();
                break;

            case 'bulk-remove-featured':
            case 'remove-featured':
                return $this->make_non_featured_ad();
                break;

            case 'send-key':
                return $this->send_access_key();
                break;

            case 'add-image':
            case 'manage-images':
            case 'set-primary-image':
            case 'deletepic':
            case 'rejectpic':
            case 'approvepic':
                return $this->manage_images();
                break;

            case 'bulk-delete':
                return $this->delete_selected_ads();

            case 'bulk-send-to-facebook':
            case 'send-to-facebook':
                return $this->send_to_facebook();
                break;

            case -1:
            case 'index':
                return $this->index();
                break;

            default:
                awpcp_flash("Unknown action: $action", 'error');
                return $this->index();
                break;
        }
    }

    public function view_ad() {
        $ad = AWPCP_Ad::find_by_id($this->id);

        if (is_null($ad)) {
            if ($this->id)
                $message = __("The specified Ad doesn't exists.", 'AWPCP');
            else
                $message = __("No Ad ID was specified.", 'AWPCP');
            awpcp_flash($message, 'error');
            return $this->redirect('index');
        }

        $category_id = get_adcategory($ad->ad_id);
        $category_url = $this->url(array('showadsfromcat_id' => $category_id));
        $content = showad($ad->ad_id, $omitmenu=1);
        $links = $this->links($this->actions($ad, array('edit', 'enable', 'disable',
                                                        'spam', 'make-featured', 'remove-featured')));

        $params = array(
            'ad' => $ad,
            'category' => array(
                'name' => get_adcatname($category_id),
                'url' => $category_url),
            'links' => $links,
            'content' => $content);

        $template = AWPCP_DIR . '/admin/templates/admin-panel-listings-view.tpl.php';

        echo $this->render($template, $params);
    }

    public function place_ad() {
        $page = new AWPCP_AdminListingsPlaceAd();
        return $page->dispatch();
    }

    public function edit_ad() {
        $page = new AWPCP_AdminListingsEditAd();
        return $page->dispatch('details');
    }

    protected function bulk_action($handler, $success, $failure) {
        $selected = awpcp_request_param( 'selected', array( $this->id ) );

        foreach ( array_filter( $selected ) as $id ) {
            $ad = AWPCP_Ad::find_by_id($id);
            if ( call_user_func( $handler, $ad ) ) {
                $processed[] = $ad;
            } else {
                $failed[] = $ad;
            }
        }

        $passed = isset( $processed ) ? count( $processed ) : 0;
        $failed = isset( $failed ) ? count( $failed ) : 0;

        if ( $passed === 0 && $failed === 0 ) {
            awpcp_flash( __( 'No Ads were selected.', 'AWPCP' ), 'error' );
        } else {
            $message_ok = sprintf( call_user_func( $success, $passed ), $passed );
            $message_error = sprintf( call_user_func( $failure, $failed ), $failed );

            if ( $passed > 0 && $failed > 0) {
                $message = _x( '%s and %s.', 'Listing bulk operations: <message-ok> and <message-error>.', 'AWPCP' );
                awpcp_flash( sprintf( $message, $message_ok, $message_error ), 'error' );
            } else if ( $passed > 0 ) {
                awpcp_flash( $message_ok . '.' );
            } else if ( $failed > 0 ) {
                awpcp_flash( ucfirst( $message_error . '.' ), 'error' );
            }
        }

        return $this->redirect('index');
    }

    public function disable_ad_action($ad) {
        return !is_null( $ad ) && $ad->disable();
    }

    public function disable_ad_success($n) {
        return _n( '%d Ad was disabled', '%d Ads were disabled', $n, 'AWPCP' );
    }

    public function disable_ad_failure($n) {
        return __( 'there was an error trying to disable %d Ads', 'AWPCP' );
    }

    public function disable_ad() {
        return $this->bulk_action(
            array( $this, 'disable_ad_action' ),
            array( $this, 'disable_ad_success' ),
            array( $this, 'disable_ad_failure' )
        );
    }

    public function enable_ad_action($ad) {
        if ( ! is_null( $ad ) && $ad->enable() ) {
            $is_ad_owner = AWPCP_Ad::belongs_to_user( $ad->ad_id, wp_get_current_user()->ID );
            if ( ! $is_ad_owner && get_awpcp_option( 'send-ad-enabled-email' ) ) {
                awpcp_ad_enabled_email( $ad );
            }
            return true;
        }
        return false;
    }

    public function enable_ad_success($n) {
        return _n( '%d was enabled', '%d Ads were enabled', $n, 'AWPCP' );
    }

    public function enable_ad_failure($n) {
        return __( 'there was an error trying to enable %d Ads', 'AWPCP' );
    }

    public function enable_ad() {
        return $this->bulk_action(
            array( $this, 'enable_ad_action' ),
            array( $this, 'enable_ad_success' ),
            array( $this, 'enable_ad_failure' )
        );
    }

    public function unflag_ad() {
        $ad = AWPCP_Ad::find_by_id($this->id);

        if ($result = $ad->unflag()) {
            awpcp_flash(__('The Ad has been unflagged.', 'AWPCP'));
        }

        return $this->redirect('index');
    }

    public function mark_as_paid() {
        $ad = AWPCP_Ad::find_by_id($this->id);

        $ad->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_COMPLETED;
        if ($result = $ad->save()) {
            awpcp_flash(__('The Ad has been marked as paid.', 'AWPCP'));
        }

        return $this->redirect('index');
    }

    public function renew_ad_action($ad) {
        $term = awpcp_payments_api()->get_ad_payment_term( $ad );

        if ( !$ad->has_expired() && !$ad->is_about_to_expire() ) {
            return false;
        }

        if ( $term->ad_can_be_renewed( $ad ) ) {
            $ad->renew();
            $ad->save();

            awpcp_send_ad_renewed_email( $ad );

            // MOVE inside Ad::renew() ?
            do_action('awpcp-renew-ad', $ad->ad_id, null);

            return true;
        } else {
            return false;
        }
    }

    public function renew_ad_success($n) {
        return _n('%d Ad was renewed', '%d Ads were renewed', $n, 'AWPCP');
    }

    public function renew_ad_failure($n) {
        return __('there was an error trying to renew %d Ads', 'AWPCP');
    }

    public function renew_ad() {
        return $this->bulk_action(
            array( $this, 'renew_ad_action' ),
            array( $this, 'renew_ad_success' ),
            array( $this, 'renew_ad_failure' )
        );
    }

    public function mark_as_spam_action($ad) {
        return !is_null( $ad ) && $ad->mark_as_spam();
    }

    public function mark_as_spam_success($n) {
        return _n('%d Ad were marked as SPAM and removed', '%d Ads were marked as SPAM and removed', $n, 'AWPCP');
    }

    public function mark_as_spam_failure($n) {
        return __('there was an error trying to mark %d Ad as SPAM', 'AWPCP');
    }

    public function mark_as_spam() {
        return $this->bulk_action(
            array( $this, 'mark_as_spam_action' ),
            array( $this, 'mark_as_spam_success' ),
            array( $this, 'mark_as_spam_failure' )
        );
    }

    public function make_featured_ad_action($ad) {
        return ! is_null( $ad ) && $ad->set_featured_status( true );
    }

    public function make_featured_ad_success($n) {
        return _n( '%d Ad was set as fatured', '%d Ads were set as featured', $n, 'AWPCP' );
    }

    public function make_featured_ad_failure($n) {
        return __( 'there was an error trying to set %d Ads as featured', 'AWPCP' );
    }

    public function make_featured_ad() {
        return $this->bulk_action(
            array( $this, 'make_featured_ad_action' ),
            array( $this, 'make_featured_ad_success' ),
            array( $this, 'make_featured_ad_failure' )
        );
    }

    public function make_non_featured_ad_action($ad) {
        return ! is_null( $ad ) && $ad->set_featured_status( false );
    }

    public function make_non_featured_ad_success($n) {
        return _n( '%d Ad was set as non-fatured', '%d Ads were set as non-featured', $n, 'AWPCP' );
    }

    public function make_non_featured_ad_failure($n) {
        return __( 'there was an error trying to set %d Ads as non-featured', 'AWPCP' );
    }

    public function make_non_featured_ad() {
        return $this->bulk_action(
            array( $this, 'make_non_featured_ad_action' ),
            array( $this, 'make_non_featured_ad_success' ),
            array( $this, 'make_non_featured_ad_failure' )
        );
    }

    public function send_access_key() {
        global $nameofsite;

        $ad = AWPCP_Ad::find_by_id($this->id);

        $recipient = "{$ad->ad_contact_name} <{$ad->ad_contact_email}>";
        $template = AWPCP_DIR . '/frontend/templates/email-send-ad-access-key.tpl.php';

        $message = new AWPCP_Email;
        $message->to[] = $recipient;
        $message->subject = sprintf('Access Key for "%s"', $ad->get_title());

        $message->prepare($template,  array(
            'ad' => $ad,
            'nameofsite' => $nameofsite,
        ));

        if ($message->send()) {
            awpcp_flash(sprintf(__('The access key was sent to %s.', 'AWPCP'), esc_html($recipient)));
        } else {
            awpcp_flash(sprintf(__('There was an error trying to send the email to %s.', 'AWPCP'), esc_html($recipient)));
        }

        return $this->redirect('index');
    }

    public function manage_images() {
        echo awpcp_media_manager()->dispatch( $this );
    }

    public function delete_ad() {
        // TODO: Use AWPCP_Ad::delete() method.
        $message = deletead($this->id, '', '');
        awpcp_flash($message);

        return $this->redirect('index');
    }

    public function delete_selected_ads() {
        if (!wp_verify_nonce(awpcp_request_param('_wpnonce'), 'bulk-awpcp-listings'))
            return $this->index();

        $current_user_is_admin = awpcp_current_user_is_admin();
        $user = wp_get_current_user();
        $selected = awpcp_request_param('selected');

        $deleted = 0;
        $failed = 0;
        $total = count( $selected );

        foreach ($selected as $id) {
            if ( AWPCP_Ad::belongs_to_user($id, $user->ID) || $current_user_is_admin ) {
                $errors = array();
                deletead($id, '', '', $force=true, $errors);

                if (empty($errors)) {
                    $deleted = $deleted + 1;
                } else {
                    $failed = $failed + 1;
                }
            }
        }

        if ( $deleted > 0 && $failed > 0 ) {
            awpcp_flash( sprintf( __( '%d of %d Ads were deleted. %d generated errors.', 'AWPCP' ), $deleted,$total, $failed ) );
        } else {
            awpcp_flash( sprintf( __( '%d of %d Ads were deleted.', 'AWPCP' ), $deleted, $total ) );
        }

        return $this->redirect('index');
    }

    public function send_to_facebook() {
        $is_admin = awpcp_current_user_is_admin();

        if ( !$is_admin )
            return $this->redirect('index');

        $ads = isset( $_REQUEST['selected'] ) ? $_REQUEST['selected'] : ( isset( $_REQUEST['id'] ) ? array( $_REQUEST['id'] ) : array() );

        if ( !$ads )
            return $this->redirect('index');

        $total = count( $ads );
        $sent = 0;
        $failed = 0;

        $fb = AWPCP_Facebook::instance();
        $fb->set_access_token( 'page_token' );        

        foreach ( $ads as $ad_id ) {
            $ad = AWPCP_Ad::find_by_id( $ad_id );

            if ( !$ad || awpcp_get_ad_meta( $ad_id, 'sent-to-facebook', true ) == 1 ) {
                $failed++;
                continue;
            }

            // TODO: add a blurb of content?
            $ad_image = awpcp_media_api()->get_ad_primary_image( $ad );
            $thumbnail = $ad_image ? $ad_image->get_url( 'primary' ) : '';

            $data = array( 'link' => url_showad( $ad->ad_id ),
                           'name' => $ad->ad_title,
                           'picture' => $thumbnail );

            try {
                $response = $fb->api_request( '/' . $fb->get( 'page_id' ) . '/links',
                                              'POST',
                                              $data );

                if ( $response && isset( $response->id ) ) {
                    awpcp_update_ad_meta( $ad_id, 'sent-to-facebook', 1 );
                    $sent++;
                } else {
                    $failed++;
                }
            } catch (Exception $e) {
                $error = true;
                $failed++;
            }
        }

        if ( isset( $error ) && $error ) {
            $msg = str_replace( '<a>',
                                '<a href="' . admin_url( 'admin.php?page=awpcp-settings&g=facebook-settings' ) . '">',
                                __( 'AWPCP can not post to Facebook because your credentials are invalid or have expired. Please check your <a>settings</a>.', 'AWPCP' ) );
            awpcp_flash( $msg, 'error' );            
        }

        if ( $sent > 0 && $failed > 0 ) {
            awpcp_flash( sprintf( __( '%d of %d Ads were sent to Facebook. %d generated errors.', 'AWPCP' ), $sent, $total, $failed ) );
        } else {
            awpcp_flash( sprintf( __( '%d of %d Ads were sent to Facebook. %d generated errors.', 'AWPCP' ), $sent, $total, $failed ) );
        }

        return $this->redirect('index');
    }

    public function index() {
        $template = AWPCP_DIR . '/admin/templates/admin-panel-listings.tpl.php';
        $this->table->prepare_items();

        echo $this->render($template, array('table' => $this->table));
    }

    public function ajax() {
        global $current_user;
        get_currentuserinfo();

        $id = awpcp_post_param('id', 0);
        $is_admin_user = awpcp_current_user_is_admin();

        // if user can't modify this Ad, do nothing and show list of ads
        if (!$is_admin_user && !AWPCP_Ad::belongs_to_user($id, $current_user->ID)) {
            return false;
        }

        $errors = array();

        if (is_null(AWPCP_Ad::find_by_id($id))) {
            $message = _x("The specified Ad doesn't exists.", 'ajax delete ad', 'AWPCP');
            $response = json_encode(array('status' => 'error', 'message' => $message));
        } else if (isset($_POST['remove'])) {
            $result = deletead($id, $adkey='', $editemail='', $force=true, $errors);

            if (empty($errors)) {
                $response = json_encode(array('status' => 'success'));
            } else {
                $response = json_encode(array('status' => 'error', 'message' => join('<br/>', $errors)));
            }
        } else {
            $columns = $is_admin_user ? 10 : 10;
            ob_start();
                include(AWPCP_DIR . '/admin/templates/delete_form.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();
            $response = json_encode(array('status' => 'success', 'html' => $html));
        }

        header('Content-Type: application/json');
        echo $response;
        exit();
    }
}
