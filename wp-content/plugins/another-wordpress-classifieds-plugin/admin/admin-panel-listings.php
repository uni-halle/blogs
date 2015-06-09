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
        $title = $title ? $title : awpcp_admin_page_title( __( 'Manage Listings', 'AWPCP' ) );

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
        $is_moderator = awpcp_current_user_is_moderator();

        $actions = array();

        $actions['view'] = array(__('View', 'AWPCP'), $this->url(array('action' => 'view', 'id' => $ad->ad_id)));
        $actions['edit'] = array(__('Edit', 'AWPCP'), $this->url(array('action' => 'edit', 'id' => $ad->ad_id)));
        $actions['trash'] = array(__('Delete', 'AWPCP'), $this->url(array('action' => 'delete', 'id' => $ad->ad_id)));

        if ( $is_moderator ) {
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

        if ( $ad->is_about_to_expire() || $ad->has_expired() ) {
            $hash = awpcp_get_renew_ad_hash( $ad->ad_id );
            $params = array( 'action' => 'renew', 'id' => $ad->ad_id, 'awpcprah' => $hash );
            $actions['renwew-ad'] = array( __( 'Renew Ad', 'AWPCP' ), $this->url( $params ) );
        }

        if ($images = $ad->count_image_files()) {
            $label = __( 'Manage Images', 'AWPCP' );
            $url = $this->url(array('action' => 'manage-images', 'id' => $ad->ad_id));
            $actions['manage-images'] = array($label, array('', $url, " ($images)"));
        } else if ( awpcp_are_images_allowed() ) {
            $actions['add-image'] = array(__('Add Images', 'AWPCP'), $this->url(array('action' => 'add-image', 'id' => $ad->ad_id)));
        }

        if ( $is_moderator && ! $ad->disabled ) {
            $fb = AWPCP_Facebook::instance();
            if ( ! awpcp_get_ad_meta( $ad->ad_id, 'sent-to-facebook' ) && $fb->get( 'page_id' ) ) {
                $actions['send-to-facebook'] = array(
                    __( 'Send to Facebook', 'AWPCP' ),
                    $this->url( array(
                        'action' => 'send-to-facebook',
                        'id' => $ad->ad_id
                    ) )
                );
            } else if ( ! awpcp_get_ad_meta( $ad->ad_id, 'sent-to-facebook-group' ) && $fb->get( 'group_id' ) ) {
                $actions['send-to-facebook'] = array(
                    __( 'Send to Facebook Group', 'AWPCP' ),
                    $this->url( array(
                        'action' => 'send-to-facebook',
                        'id' => $ad->ad_id
                    ) )
                );
            }
        }

        $actions = apply_filters( 'awpcp-admin-listings-table-actions', $actions, $ad, $this );

        if ( $is_moderator && isset( $_REQUEST['filterby'] ) && $_REQUEST['filterby'] == 'new' ) {
            $actions['mark-reviewed'] = array(
                __( 'Mark Reviewed', 'AWPCP' ),
                $this->url( array( 'action' => 'mark-reviewed', 'id' => $ad->ad_id ) ),
            );
        }

        if (is_array($filter)) {
            $actions = array_intersect_key($actions, array_combine($filter, $filter));
        }

        return $actions;
    }

    public function dispatch() {
        $this->id = awpcp_request_param('id', false);
        $action = $this->get_current_action();

        $moderator_actions = array(
            'enable', 'approvead', 'bulk-enable',
            'disable', 'rejectad', 'bulk-disable',
            'remove-featured', 'bulk-remove-featured',
            'make-featured', 'bulk-make-featured',
            'mark-verified',
            'mark-paid',
            'send-key',
            'mark-reviewed',
            'bulk-renew',
            'send-to-facebook', 'bulk-send-to-facebook',
            'unflag',
            'spam', 'bulk-spam',

            'approve-file', 'reject-file',
        );

        if ( ! awpcp_current_user_is_moderator() && in_array( $action, $moderator_actions ) ) {
            awpcp_flash(_x('You do not have sufficient permissions to perform that action.', 'admin listings', 'AWPCP'), 'error');
            $action = 'index';
        }

        return $this->render_page( $action );
    }

    private function render_page( $action ) {
        switch ($action) {
            case 'view':
                return $this->listing_action( 'view_ad' );
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

            case 'mark-verified':
                return $this->mark_as_verified();
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

            case 'mark-reviewed':
                return $this->listing_action( 'mark_reviewed' );
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
            case 'approve-file':
            case 'reject-file':
                return $this->listing_action( 'manage_images' );
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
                return $this->handle_custom_listing_actions( $action );
                break;
        }
    }

    private function listing_action( $callback ) {
        $listing_id = awpcp_request()->get_ad_id();

        if ( empty( $listing_id ) ) {
            awpcp_flash( __( 'No Ad ID was specified.', 'AWPCP' ), 'error' );
            return $this->redirect( 'index' );
        }

        try {
            $listing = awpcp_listings_collection()->get( $listing_id );
        } catch ( AWPCP_Exception $e ) {
            awpcp_flash( __( "The specified Ad doesn't exists.", 'AWPCP' ), 'error' );
            return $this->redirect( 'index' );
        }

        return call_user_func( array( $this, $callback ), $listing );
    }

    public function view_ad( $ad ) {
        $category_name = get_adcatname( $ad->ad_category_id );
        $category_url = $this->url( array( 'showadsfromcat_id' => $ad->ad_category_id ) );

        $content = showad($ad->ad_id, $omitmenu=1);
        $links = $this->links(
            $this->actions(
                $ad,
                array( 'edit', 'enable', 'disable', 'spam', 'make-featured', 'remove-featured' )
            )
        );

        $params = array(
            'ad' => $ad,
            'category' => array(
                'name' => $category_name,
                'url' => $category_url,
            ),
            'links' => $links,
            'content' => $content,
        );

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

        if ( $result = awpcp_listings_api()->unflag_listing( $ad ) ) {
            awpcp_flash(__('The Ad has been unflagged.', 'AWPCP'));
        }

        return $this->redirect('index');
    }

    public function mark_as_verified() {
        $ad = AWPCP_Ad::find_by_id( $this->id );

        awpcp_listings_api()->verify_ad( $ad );
        awpcp_flash( __( 'The Ad was marked as verified.', 'AWPCP' ) );

        return $this->redirect( 'index' );
    }

    public function mark_as_paid() {
        $ad = AWPCP_Ad::find_by_id($this->id);

        $ad->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_COMPLETED;
        if ($result = $ad->save()) {
            awpcp_flash(__('The Ad has been marked as paid.', 'AWPCP'));
        }

        return $this->redirect('index');
    }

    public function renew_ad() {
        $page = awpcp_renew_listings_admin_page();
        $page->dispatch();
        return $this->redirect( 'index' );
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

    public function mark_reviewed( $listing ) {
        if ( awpcp_update_ad_meta( $listing->ad_id, 'reviewed', true ) ) {
            awpcp_flash( sprintf( __( 'The listing was marked as reviewed.', 'AWPCP' ), esc_html( $recipient ) ) );
        } else {
            awpcp_flash( sprintf( __( "The listing couldn't marked as reviewed.", 'AWPCP' ), esc_html( $recipient ) ) );
        }
        return $this->redirect( 'index' );
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

    public function manage_images( $listing ) {
        $allowed_files = awpcp_listing_upload_limits()->get_listing_upload_limits( $listing );

        $params = array(
            'listing' => $listing,
            'files' => awpcp_media_api()->find_by_ad_id( $listing->ad_id ),
            'media_manager_configuration' => array(
                'nonce' => wp_create_nonce( 'awpcp-manage-listing-media-' . $listing->ad_id ),
                'allowed_files' => $allowed_files,
                'show_admin_actions' => awpcp_current_user_is_moderator(),
            ),
            'media_uploader_configuration' => array(
                'listing_id' => $listing->ad_id,
                'nonce' => wp_create_nonce( 'awpcp-upload-media-for-listing-' . $listing->ad_id ),
                'allowed_files' => $allowed_files,
            ),
            'urls' => array(
                'view-listing' => $this->url( array( 'action' => 'view', 'id' => $listing->ad_id ) ),
                'listings' => $this->url( array( 'id' => null ) ),
            ),
        );

        echo $this->render( AWPCP_DIR . '/templates/admin/listings-media-center.tpl.php', $params );
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

        $user = wp_get_current_user();
        $selected = awpcp_request_param('selected');

        $deleted = 0;
        $failed = 0;
        $non_existent = 0;
        $unauthorized = 0;
        $not_allowed_for_moderators = 0;
        $total = count( $selected );

        foreach ($selected as $id) {
            try {
                $listing = awpcp_listings_collection()->get( $id );
            } catch ( AWPCP_Exception $e ) {
                $non_existent = $non_existent + 1;
                continue;
            }

            if ( ! awpcp_listing_authorization()->is_current_user_allowed_to_edit_listing( $listing ) ) {
                $unauthorized = $unauthorized + 1;
                continue;
            }

            if ( awpcp_current_user_is_moderator() && $listing->user_id != $user->ID ) {
                $not_allowed_for_moderators = $not_allowed_for_moderators + 1;
                continue;
            }

            $errors = array();
            deletead( $id, '', '', $force=true, $errors );

            if ( empty( $errors ) ) {
                $deleted = $deleted + 1;
            } else {
                $failed = $failed + 1;
            }
        }

        if ( $deleted > 0 && $failed > 0 ) {
            awpcp_flash( sprintf( __( '%d of %d Ads were deleted. %d generated errors.', 'AWPCP' ), $deleted,$total, $failed ) );
        } else if ( $deleted > 0 ) {
            awpcp_flash( sprintf( __( '%d of %d Ads were deleted.', 'AWPCP' ), $deleted, $total ) );
        }

        if ( $non_existent > 0 ) {
            awpcp_flash( sprintf( __( "%d of %d Ads don't exist.", 'AWPCP' ), $non_existent, $total ), 'error' );
        }

        if ( $unauthorized > 0 ) {
            awpcp_flash( sprintf( __( "%d of %d Ads weren't deleted because you are not authorized.", 'AWPCP' ), $non_existent, $total ), 'error' );
        }

        if ( $not_allowed_for_moderators > 0 ) {
            awpcp_flash( sprintf( __( "%d of %d Ads weren't deleted because Moderator uses are not allowed to use Bulk Delete operation to remove other users listings.", 'AWPCP' ), $not_allowed_for_moderators, $total ), 'error' );
        }

        return $this->redirect('index');
    }

    public function send_to_facebook() {
        $page = awpcp_send_listing_to_facebook_admin_page();
        $page->dispatch();
        return $this->redirect('index');
    }

    public function index() {
        $table = $this->get_table();

        $table->prepare_items();
        $template = AWPCP_DIR . '/admin/templates/admin-panel-listings.tpl.php';

        echo $this->render( $template, array( 'table' => $table ) );
    }

    public function ajax() {
        $id = awpcp_post_param('id', 0);

        try {
            $listing = awpcp_listings_collection()->get( $id );
        } catch ( AWPCP_Exception $e ) {
            $message = _x( "The specified Ad doesn't exists.", 'ajax delete ad', 'AWPCP' );
            $response = json_encode( array( 'status' => 'error', 'message' => $message ) );
            return $this->ajax_response( $response );
        }

        if ( ! awpcp_listing_authorization()->is_current_user_allowed_to_edit_listing( $listing ) ) {
            return false;
        }

        $errors = array();

        if ( isset( $_POST['remove'] ) ) {
            $result = deletead($id, $adkey='', $editemail='', $force=true, $errors);

            if (empty($errors)) {
                $response = json_encode(array('status' => 'success'));
            } else {
                $response = json_encode(array('status' => 'error', 'message' => join('<br/>', $errors)));
            }
        } else {
            $columns = 10;
            ob_start();
                include(AWPCP_DIR . '/admin/templates/delete_form.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();
            $response = json_encode(array('status' => 'success', 'html' => $html));
        }

        return $this->ajax_response( $response );
    }

    private function ajax_response( $response ) {
        header('Content-Type: application/json');
        echo $response;
        exit();
    }

    private function handle_custom_listing_actions( $action ) {
        try {
            $listing = awpcp_listings_collection()->get( $this->id );
        } catch ( AWPCP_Exception $e ) {
            awpcp_flash( __( "The specified listing doesn't exists.", 'AWPCP' ), 'error' );
            return $this->index();
        }

        $output = apply_filters( "awpcp-custom-admin-listings-table-action-$action", null, $listing );

        if ( is_null( $output ) ) {
            awpcp_flash("Unknown action: $action", 'error');
            return $this->index();
        } else if ( is_array( $output ) && isset( $output['redirect'] ) ) {
            return $this->render_page( $output['redirect'] );
        } else {
            return $output;
        }
    }
}
