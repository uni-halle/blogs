<?php

function awpcp_payments_api() {
    static $payments = null;

    if ( is_null( $payments ) ) {
        $payments = new AWPCP_PaymentsAPI( new AWPCP_Request() );
    }

    return $payments;
}

class AWPCP_PaymentsAPI {

    private $request = null;

    private $terms = null;
    private $types = array();
    private $methods = array();

    private $cache = array();

    public function __construct( /*AWPCP_Request*/ $request = null ) {
        if ( ! is_null( $request ) ) {
            $this->request = $request;
        } else {
            $this->request = new AWPCP_Request;
        }

        add_action( 'init', array( $this, 'register_payment_term_types' ), 9999 );
        add_action( 'init', array( $this, 'register_payment_methods' ), 9999 );

        if ( is_admin() ) {
            add_action( 'admin_init', array( $this, 'wp' ), 1 );
        } else {
            add_action( 'template_redirect', array( $this, 'wp' ), 1 );
        }

        add_action('awpcp-transaction-status-updated', array($this, 'update_account_balance'), 10, 1);
    }

    public function register_payment_term_types() {
        do_action('awpcp-register-payment-term-types', $this);
    }

    public function register_payment_methods() {
        do_action('awpcp-register-payment-methods', $this);
    }

    private function get_url($action, $transaction) {
        if (get_option('permalink_structure')) {
            return home_url("/awpcpx/payments/$action/{$transaction->id}");
        } else {
            $params = array(
                'awpcpx' => true,
                'module' => 'payments',
                'action' => $action,
                'awpcp-txn' => $transaction->id
            );
            return add_query_arg( urlencode_deep( $params ), home_url('index.php'));
        }
    }

    public function get_return_url($transaction) {
        return $this->get_url('return', $transaction);
    }

    public function get_notify_url($transaction) {
        return $this->get_url('notify', $transaction);
    }

    public function get_cancel_url($transaction) {
        return $this->get_url('cancel', $transaction);
    }

    public function payments_enabled() {
        return get_awpcp_option('freepay') == 1;
    }

    public function credit_system_enabled() {
        if (!$this->payments_enabled())
            return false;
        return get_awpcp_option('enable-credit-system') == 1;
    }

    public function is_credit_accepted() {
        return in_array( AWPCP_Payment_Transaction::PAYMENT_TYPE_CREDITS, $this->get_accepted_payment_types() );
    }

    /* Credit Plans */

    /**
     * Handler for awpcp-transaction-status-updated action
     *
     * XXX: Make sure the user has enough credit to pay for the plans.
     *  We already check that at the beginning of the transaction but I
     *  think is necessary to check again here.
     *  We need a way to mark individual items as paid or unpaid so
     *  other parts of the plugin can decide what to do.
     */
    public function update_account_balance($transaction) {
        if ($transaction->is_completed() && $transaction->was_payment_successful()) {
            if (awpcp_user_is_admin($transaction->user_id))
                return;

            $credit_plan = $this->get_transaction_credit_plan($transaction);

            if (!is_null($credit_plan)) {
                $balance = $this->get_account_balance($transaction->user_id);
                $this->set_account_balance($transaction->user_id, $balance + $credit_plan->credits);
            }

            $totals = $transaction->get_totals();
            if ($totals['credits'] > 0) {
                $balance = $this->get_account_balance($transaction->user_id);
                $this->set_account_balance($transaction->user_id, $balance - $totals['credits']);
            }
        }
    }

    public function set_account_balance($user_id, $balance) {
        if (is_null($user_id) && is_user_logged_in())
            $user_id = wp_get_current_user()->ID;

        if (is_null($user_id)) return false;

        return update_user_meta($user_id, 'awpcp-account-balance', $balance);
    }

    public function get_account_balance($user_id=null) {
        if (is_null($user_id) && is_user_logged_in())
            $user_id = wp_get_current_user()->ID;

        if (is_null($user_id)) return 0;

        return (double) get_user_meta($user_id, 'awpcp-account-balance', true);
    }

    public function add_credit($user_id, $amount) {
        $balance = $this->get_account_balance($user_id);
        return $this->set_account_balance($user_id, $balance + max(0, $amount));
    }

    public function remove_credit($user_id, $amount) {
        $balance = $this->get_account_balance($user_id);
        return $this->set_account_balance($user_id, $balance - max(0, $amount));
    }

    public function format_account_balance($user_id=null) {
        return number_format($this->get_account_balance($user_id), 0);
    }

    public function get_credit_plans() {
        return AWPCP_CreditPlan::find();
    }

    public function get_credit_plan($id) {
        return AWPCP_CreditPlan::find_by_id($id);
    }

    public function get_transaction_credit_plan($transaction) {
        return $this->get_credit_plan($transaction->get('credit-plan'));
    }

    /* Payment Terms */

    public function register_payment_term_type($type) {
        if (is_a($type, 'AWPCP_PaymentTermType'))
            $this->types[$type->slug] = $type;
    }

    public function get_payment_term_type($term_type) {
        if (!isset($this->types[$term_type]))
            return null;
        return $this->types[$term_type];
    }

    public function get_payment_term($term_id, $term_type) {
        if (!isset($this->types[$term_type]))
            return null;
        return $this->types[$term_type]->find_by_id($term_id);
    }

    public function get_transaction_payment_term($transaction) {
        $term_type = $transaction->get('payment-term-type');
        $term_id = $transaction->get('payment-term-id');

        return $this->get_payment_term($term_id, $term_type);
    }

    public function get_payment_terms() {
        if (is_array($this->terms)) return $this->terms;

        $this->terms = array();
        foreach ($this->types as $slug => $type) {
            $this->terms[$slug] = $type->get_payment_terms();
        }

        return $this->terms;
    }

    public function get_user_payment_terms($user_id) {
        $terms = array();
        foreach ($this->types as $slug => $type)
            $terms[$slug] = $type->get_user_payment_terms($user_id);
        return $terms;
    }

    public function get_ad_payment_term($ad) {
        return $this->get_payment_term($ad->adterm_id, $ad->payment_term_type);
    }

    public function payment_term_requires_payment($term) {
        $credits = intval($this->credit_system_enabled() ? $term->credits : 0);
        $money = floatval($term->price);

        return $money > 0 || $credits > 0;
    }

    /**
     * @since 3.0.2
     */
    public function get_accepted_payment_types() {
        $payment_type = get_awpcp_option( 'accepted-payment-type', false );

        $payment_types = array();
        if ( 'money' === $payment_type || 'both' === $payment_type ) {
            $payment_types[] = AWPCP_Payment_Transaction::PAYMENT_TYPE_MONEY;
        }
        if ( 'credits' === $payment_type || 'both' === $payment_type ) {
            $payment_types[] = AWPCP_Payment_Transaction::PAYMENT_TYPE_CREDITS;
        }

        return $payment_types;
    }

    /* Payment Gateways */

    public function register_payment_method($gateway) {
        if (is_a($gateway, 'AWPCP_PaymentGateway'))
            $this->methods[$gateway->slug] = $gateway;
    }

    public function get_payment_methods() {
        return $this->methods;
    }

    public function get_payment_method($slug) {
        if (!isset($this->methods[$slug]))
            return null;
        return $this->methods[$slug];
    }

    public function get_transaction_payment_method($transaction) {
        return $this->get_payment_method($transaction->get('payment-method', ''));
    }

    /* Transactions Management */

    public function get_transaction() {
        return $this->get_transaction_with_method( 'find_by_id' );
    }

    private function get_transaction_with_method( $method_name ) {
        if ( ! isset( $this->current_transaction ) ) {
            $this->current_transaction = null;
        }

        if ( is_null( $this->current_transaction ) ) {
            $transaction_id = $this->request->param( 'transaction_id' );
            $this->current_transaction = call_user_func( array( 'AWPCP_Payment_Transaction', $method_name ), $transaction_id );
        }

        return $this->current_transaction;
    }

    public function get_or_create_transaction() {
        return $this->get_transaction_with_method( 'find_or_create' );
    }

    /**
     * TODO: should throw an exception if the status can't be set
     */
    private function set_transaction_status($transaction, $status, &$errors) {
        if ($result = $transaction->set_status($status, $errors)) {
            do_action('awpcp-transaction-status-updated', $transaction, $status, $errors);
        }

        $transaction->save();

        return $result;
    }

    public function set_transaction_status_to_open($transaction, &$errors=array()) {
        return $this->set_transaction_status($transaction, AWPCP_Payment_Transaction::STATUS_OPEN, $errors);
    }

    public function set_transaction_status_to_ready_to_checkout($transaction, &$errors=array()) {
        return $this->set_transaction_status($transaction, AWPCP_Payment_Transaction::STATUS_READY, $errors);
    }

    public function set_transaction_status_to_checkout($transaction, &$errors=array()) {
        return $this->set_transaction_status($transaction, AWPCP_Payment_Transaction::STATUS_CHECKOUT, $errors);
    }

    public function set_transaction_status_to_payment($transaction, &$errors=array()) {
        return $this->set_transaction_status($transaction, AWPCP_Payment_Transaction::STATUS_PAYMENT, $errors);
    }

    public function set_transaction_status_to_payment_completed($transaction, &$errors=array()) {
        return $this->set_transaction_status($transaction, AWPCP_Payment_Transaction::STATUS_PAYMENT_COMPLETED, $errors);
    }

    public function set_transaction_status_to_completed($transaction, &$errors=array()) {
        return $this->set_transaction_status($transaction, AWPCP_Payment_Transaction::STATUS_COMPLETED, $errors);
    }

    public function set_transaction_credit_plan($transaction) {
        if (!$this->credit_system_enabled())
            return;

        // grab Credit Plan information
        $plan = $this->get_credit_plan(awpcp_post_param('credit_plan', 0));

        if (!is_null($plan)) {
            $transaction->set('credit-plan', $plan->id);

            $transaction->add_item(
                $plan->id,
                $plan->name,
                $plan->description,
                AWPCP_Payment_Transaction::PAYMENT_TYPE_MONEY,
                $plan->price
            );
        }
    }

    public function set_transaction_payment_method($transaction) {
        $payment_method = $this->get_payment_method(awpcp_post_param('payment_method', ''));

        if ( !is_null( $payment_method ) ) {
            $transaction->set('payment-method', $payment_method->slug);
        }
    }

    public function process_transaction($transaction) {
        do_action('awpcp-process-payment-transaction', $transaction);
    }

    public function process_payment_request($action) {
        $transaction = AWPCP_Payment_Transaction::find_by_id( get_query_var( 'awpcp-txn' ) );

        if (is_null($transaction)) {
            $messages[] = __('The specified payment transaction doesn\'t exists. We can\'t process your payment.', 'AWPCP');
            $messages[] = __('Please contact customer service if you are viewing this message after having made a payment. If you have not tried to make a payment and you are viewing this message, it means this message is being shown in error and can be disregarded.', 'AWPCP');
            $messages[] = __('Return to <a href="%s">home page</a>.', 'AWPCP');
            wp_die(sprintf('<p>' . join('</p><p>', $messages) . '</p>', home_url()));
        }

        $payment_method = $this->get_transaction_payment_method($transaction);

        if (is_null($payment_method)) {
            $messages[] = __("The payment method associated with this transaction is not available at this time. We can't process your payment.", 'AWPCP');
            $messages[] = __('Please contact customer service if you are viewing this message after having made a payment. If you have not tried to make a payment and you are viewing this message, it means this message is being shown in error and can be disregarded.', 'AWPCP');
            $messages[] = __('Return to <a href="%s">home page</a>.', 'AWPCP');
            wp_die(sprintf('<p>' . join('</p><p>', $messages) . '</p>', home_url()));
        }

        switch ($action) {
            case 'return':
                $payment_method->process_payment_completed($transaction);
                return $this->process_payment_completed($transaction);

            case 'cancel':
                $payment_method->process_payment_canceled($transaction);
                return $this->process_payment_completed($transaction);

            case 'notify':
                $payment_method->process_payment_notification($transaction);
                return $this->process_payment_completed($transaction, false);
        }
    }

    public function process_payment_completed($transaction, $redirect=true) {
        $errors = array();

        // only attempt to complete the payment if we are in a previous state
        // IPN notifications are likely to be associated to transactions that
        // are already completed.
        if (!$transaction->is_payment_completed() && !$transaction->is_completed()) {
            $this->set_transaction_status_to_payment_completed($transaction, $errors);

            if (!empty($errors)) {
                $transaction->errors['payment-completed'] = $errors;
            } else {
                unset($transaction->errors['payment-completed']);
            }
        }

        $this->process_transaction($transaction);
        $transaction->save();

        if ($redirect) {
            $url = $transaction->get('redirect', $transaction->get('success-redirect'));
            $url = add_query_arg('step', 'payment-completed', $url);
            $url = add_query_arg('transaction_id', $transaction->id, $url);
            wp_redirect( esc_url_raw( $url ) );
        }

        exit();
    }

    public function process_payment() {
        if ( ! ( $id = awpcp_post_param( 'transaction_id', false ) ) ) return;

        $transaction = AWPCP_Payment_Transaction::find_by_id($id);

        if ( !is_null( $transaction ) && $transaction->is_doing_checkout() ) {
            $this->set_transaction_payment_method($transaction);
            $this->process_transaction($transaction);

            $errors = array();

            $this->set_transaction_status_to_payment($transaction, $errors);

            if ($transaction->payment_is_not_required() && empty($errors)) {
                $this->set_transaction_status_to_payment_completed($transaction, $errors);

                if (empty($errors)) {
                    return; // nothing else to do here, pass control to the (api) user
                }
            }

            if (empty($errors)) {
                // no errors, so we must have a payment method defined
                $payment_method = $this->get_transaction_payment_method($transaction);
                $result = array('output' => $payment_method->process_payment($transaction));
            } else {
                // most likely the payment method hasn't been properly set
                $result = array('errors' => $errors);
            }

            $this->cache[$transaction->id] = $result;

        } else if (!is_null($transaction) && $transaction->is_processing_payment()) {
            $this->process_transaction($transaction);

            $payment_method = $this->get_transaction_payment_method($transaction);
            $result = array('output' => $payment_method->process_payment($transaction));
            $this->cache[$transaction->id] = $result;
        }
    }

    public function wp() {
        $awpcpx = $this->request->get_query_var( 'awpcpx' );
        $module = $this->request->get_query_var( 'awpcp-module', $this->request->get_query_var( 'module' ) );
        $action = $this->request->get_query_var( 'awpcp-action', $this->request->get_query_var( 'action' ) );

        if ($awpcpx && $module == 'payments' && !empty($action)) {
            return $this->process_payment_request($action);
        } else {
            return $this->process_payment();
        }
    }

    /* Render functions */

    public function render_account_balance() {
        if (!$this->credit_system_enabled())
            return '';

        $balance = $this->format_account_balance();
        $text = sprintf( __( 'You currently have %s credits in your account.', 'AWPCP' ), $balance );

        return awpcp_print_message( $text );
    }

    public function render_payment_terms_form_field($transaction, $table, $form_errors) {
        $items = $table->get_items();

        $show_payment_terms = true;
        $accepted_payment_types = $this->get_accepted_payment_types();

        // do not show payment terms if payments are disabled and there is only
        // one payment term available (the Free Listing fee);
        if ( count( $items ) === 1 && !$this->payments_enabled() ) {
            if ( $items[0]->type === AWPCP_FeeType::TYPE && $items[0]->id === 0 ) {
                $show_payment_terms = false;
            }
        }

        ob_start();
            include( AWPCP_DIR . '/frontend/templates/payments-payment-terms-form-field.tpl.php' );
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * @since  2.2.2
     */
    public function render_credit_plans_table($transaction=null, $table_only=false) {
        if (!$this->credit_system_enabled() || !$this->is_credit_accepted() )
            return '';

        $credit_plans = $this->get_credit_plans();
        $selected = is_null($transaction) ? '' : $transaction->get('credit-plan');

        if ( empty( $credit_plans ) ) {
            return '';
        }

        $column_names = array(
            'plan' => _x( 'Plan', 'credit plans table', 'AWPCP' ),
            'description' => _x( 'Description', 'credit plans table', 'AWPCP' ),
            'credits' => _x( 'Credits', 'credit plans table', 'AWPCP' ),
            'price' => _x( 'Price', 'credit plans table', 'AWPCP' ),
        );

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-credit-plans-table.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function render_transaction_items($transaction) {
        $show_credits = get_awpcp_option('enable-credit-system');

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-transaction-items-table.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return apply_filters('awpcp-render-transaction-items', $html, $transaction);
    }

    public function render_transaction_errors($transaction) {
        $errors = array();
        foreach ($transaction->errors as $index => $error) {
            if (is_array($error)) {
                $errors = array_merge($errors, array_map('awpcp_print_error', $error));
            } else {
                $errors[] = awpcp_print_error($error);
            }
        }
        return join("\n", $errors);
    }

    public function render_payment_methods($transaction) {
        $payment_methods = $this->get_payment_methods();
        $payment_method = $transaction->get('payment-method');

        if ( count( $payment_methods ) === 1 ) {
            $payment_method = reset( $payment_methods )->slug;
        }

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-payment-methods-table.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function render_checkout_payment_template($output, $message, $transaction) {
        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-checkout-payment-page.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function render_checkout_page($transaction, $hidden=array()) {
        $payment_method = $this->get_transaction_payment_method($transaction);
        $attempts = awpcp_post_param('attempts', 0);

        $result = awpcp_array_data($transaction->id, array(), $this->cache);

        if (is_null($payment_method) || isset($result['errors'])) {
            $transaction_errors = awpcp_array_data('errors', array(), $result);

            ob_start();
                include(AWPCP_DIR . '/frontend/templates/payments-checkout-page.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();

        } else if (isset($result['output'])) {
            $integration = $payment_method->get_integration_type();
            if ($integration === AWPCP_PaymentGateway::INTEGRATION_BUTTON) {
                $message = _x('Please use the button below to complete your payment.', 'checkout-payment page', 'AWPCP');
                $html = $this->render_checkout_payment_template($result['output'], $message, $transaction);
            } else if ($integration === AWPCP_PaymentGateway::INTEGRATION_CUSTOM_FORM) {
                $html = $result['output'];
            } else if ($integration === AWPCP_PaymentGateway::INTEGRATION_REDIRECT) {
                $html = $result['output'];
            }
        }

        return $html;
    }

    public function render_payment_completed_page($transaction, $action='', $hidden=array()) {
        $success = false;

        if ($transaction->payment_is_completed() || $transaction->payment_is_pending()) {
            $title = __('Payment Completed', 'AWPCP');

            if ($transaction->payment_is_completed())
                $text = __('Your Payment has been processed successfully. Please press the button below to continue with the process.', 'AWPCP');
            else if ($transaction->payment_is_pending())
                $text = __('Your Payment has been processed successfully. However is still pending approvation from the payment gateway. Please press the button below to continue with the process.', 'AWPCP');

            $success = true;

        } else if ($transaction->payment_is_not_required()) {
            $title = __('Payment Not Required', 'AWPCP');
            $text = __('No Payment is required for this transaction. Please press the button below to continue with the process.', 'AWPCP');

            $success = true;

        } else if ($transaction->payment_is_failed()) {
            $title = __('Payment Failed', 'AWPCP');
            $text = __("Your Payment has been processed successfully. However, the payment gateway didn't return a payment status that allows us to continue with the process. Please contact the website administrator to solve this issue.", 'AWPCP');

        } else if ($transaction->payment_is_canceled()) {
            $title = __('Payment Canceled', 'AWPCP');
            $text = __("The Payment transaction was canceled. You can't post an Ad this time.", 'AWPCP');

        // } else if ($transaction->payment_is_invalid() || $transaction->payment_is_not_verified()) {
        } else {
            $title = __('Payment Error', 'AWPCP');
            $text = __("There was an error processing your payment. The payment status couldn't be found. Please contact the website admin to solve this issue.", 'AWPCP');
        }

        $redirect = $transaction->get('redirect');
        $hidden = array_merge($transaction->get('redirect-data'), $hidden);

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-payment-completed-page.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function render_payment_completed_page_title($transaction) {
        if ($transaction->was_payment_successful()) {
            return __('Payment Completed', 'AWPCP');
        } else if ($transaction->payment_is_canceled()) {
            return __('Payment Canceled', 'AWPCP');
        } else {
            return __('Payment Failed', 'AWPCP');
        }
    }
}
