<?php

/**
 * @since 3.0.2
 */
class AWPCP_AccountBalancePage extends AWPCP_BuyCreditsPage {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct( $steps, $request ) {
        parent::__construct( $steps, $request );

        $this->page = 'awpcp-user-account';
        $this->menu = $this->title = __( 'Account Balance', 'AWPCP' );
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();
    }

    public function scripts() {
        wp_enqueue_style( 'awpcp-frontend-style' );
    }

    public function dispatch() {
        echo parent::dispatch();
    }

    protected function render_user_not_allowed_error() {
        $this->errors[] = __( "Administrator users are not allowed to access this page. They can't add or remove credits to their accounts.", 'AWPCP' );
        $this->render_page_error();
    }
}

function awpcp_account_balance_page() {
    $request = new AWPCP_Request();
    $steps = awpcp_account_balance_page_steps( awpcp_payments_api() );

    return new AWPCP_AccountBalancePage( $steps, $request );
}

function awpcp_account_balance_page_steps( $payments ) {
    return array_merge(
        array(
            'summary' => new AWPCP_AccountBalancePageSummaryStep( $payments ),
        ),
        awpcp_buy_credit_page_steps( $payments )
    );
}
