<?php
/**
 * User Ad Management Panel functions
 */

require_once(AWPCP_DIR . '/admin/user-panel-listings.php');


class AWPCP_User_Panel {

	public function __construct() {
        $this->account = awpcp_account_balance_page();
        $this->listings = new AWPCP_UserListings();

		add_action('awpcp_add_menu_page', array($this, 'menu'));
	}

	/**
	 * Register Ad Management Panel menu
	 */
	public function menu() {
        /* Profile Menu */

        // We are using read as an alias for edit_classifieds_listings. If a user can `read`,
        // he or she can `edit_classifieds_listings`.
        $capability = 'read';

        // Account Balance
        if (awpcp_payments_api()->credit_system_enabled() && !awpcp_current_user_is_admin()) {
            $parts = array($this->account->title, $this->account->menu, $this->account->page);
            $hook = add_users_page($parts[0], $parts[1], $capability, $parts[2], array($this->account, 'dispatch'));
            add_action("admin_print_styles-{$hook}", array($this->account, 'scripts'));
        }

        $current_user_is_non_admin_moderator = awpcp_current_user_is_moderator() && ! awpcp_current_user_is_admin();

		if ( get_awpcp_option( 'enable-user-panel' ) != 1 || $current_user_is_non_admin_moderator ) {
            return;
        }

		/* Ad Management Menu */

		$slug = 'awpcp-panel';
		$title = sprintf(__('%s Ad Management Panel', 'AWPCP'), get_bloginfo());
		$menu = __('Ad Management', 'AWPCP');
		$hook = add_menu_page($title, $menu, $capability, $slug, array($this->listings, 'dispatch'), MENUICO);

		// Listings
		$title = __('Manage Ad Listings', 'AWPCP');
		$menu = __('Listings', 'AWPCP');
		$hook = add_submenu_page($slug, $title, $menu, $capability, $slug, array($this->listings, 'dispatch'));
		add_action("admin_print_styles-{$hook}", array($this->listings, 'scripts'));

		do_action('awpcp_panel_add_submenu_page', $slug, $capability);
	}
}
