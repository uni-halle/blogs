<?php

abstract class AWPCP_PaymentGateway {

    const INTEGRATION_BUTTON = 'button';
    const INTEGRATION_CUSTOM_FORM = 'custom-form';
    const INTEGRATION_REDIRECT = 'redirect';

    public function __construct($slug, $name, $description, $icon) {
        $this->slug = $slug;
        $this->name = $name;
        $this->description = $description;
        $this->icon = $icon;
    }

    protected function sanitize_billing_information($data) {
        if (strlen($data['exp_year']) === 2) {
            $data['exp_year'] = "20{$data['exp_year']}";
        }

        return $data;
    }

    protected function get_posted_billing_information() {
        $data['country'] = awpcp_post_param('country');
        $data['credit_card_number'] = awpcp_post_param('credit_card_number');
        $data['credit_card_type'] = awpcp_post_param('credit_card_type');
        $data['exp_month'] = awpcp_post_param('exp_month');
        $data['exp_year'] = awpcp_post_param('exp_year');
        $data['csc'] = awpcp_post_param('csc');

        $data['first_name'] = awpcp_post_param('first_name');
        $data['last_name'] = awpcp_post_param('last_name');
        $data['address_1'] = awpcp_post_param('address_1');
        $data['address_2'] = awpcp_post_param('address_2');
        $data['city'] = awpcp_post_param('city');
        $data['state'] = awpcp_post_param('state');
        $data['postal_code'] = awpcp_post_param('postal_code');
        $data['email'] = awpcp_post_param('email');

        $data['direct-payment-step'] = awpcp_post_param('direct-payment-step');
        $data['transaction_id'] = awpcp_post_param('transaction_id');
        $data['step'] = awpcp_post_param('step');

        return $this->sanitize_billing_information($data);
    }

    protected function validate_posted_billing_information($data, &$errors=array()) {
        if (empty($data['country'])) {
            $errors['country'] = __('The Country is required', 'AWPCP');
        }

        if (empty($data['credit_card_number'])) {
            $errors['credit_card_number'] = __('The Credit Card Number is required.', 'AWPCP');
        }

        if (empty($data['exp_month'])) {
            $errors['exp_month'] = __('The Expiration Month is required.', 'AWPCP');
        }

        if (empty($data['exp_year'])) {
            $errors['exp_year'] = __('The Expiration Year is required.', 'AWPCP');
        }

        if (empty($data['csc'])) {
            $errors['csc'] = __('The Card Security Code is required.', 'AWPCP');
        }

        if (empty($data['first_name'])) {
            $errors['first_name'] = __('The First Name is required.', 'AWPCP');
        }

        if (empty($data['last_name'])) {
            $errors['last_name'] = __('The Last Name is required.', 'AWPCP');
        }

        if (empty($data['address_1'])) {
            $errors['address_1'] = __('The Address Line 1 is required.', 'AWPCP');
        }

        if (empty($data['city'])) {
            $errors['city'] = __('The City is required.', 'AWPCP');
        }

        if (in_array($data['country'], array('US', 'CA', 'AU')) && empty($data['state'])) {
            $errors['state'] = __('The State is required.', 'AWPCP');
        }

        if (empty($data['postal_code'])) {
            $errors['postal_code'] = __('The Postal Code is required.', 'AWPCP');
        }

        if (empty($data['email'])) {
            $errors['email'] = __('The Email is required.', 'AWPCP');
        }

        return empty($errors);
    }

    protected function get_user_info($user_id=false) {
        $data = awpcp_get_user_data($user_id);

        $translations = array(
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            'city' => 'city',
            'address_1' => 'address',
        );

        foreach ($translations as $field => $key) {
            $info[$field] = awpcp_get_property($data, $key);
        }

        return $info;
    }

    protected function render_billing_form($transaction, $data=array(), $hidden=array(), $errors=array()) {
        wp_enqueue_script('awpcp-billing-form');

        if ($transaction->user_id && empty($data) && is_user_logged_in()) {
            $data = $this->get_user_info($transaction->user_id);
        }

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-billing-form.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public abstract function get_integration_type();
    public abstract function process_payment($transaction);
    public abstract function process_payment_notification($transaction);
    public abstract function process_payment_completed($transaction);
    public abstract function process_payment_canceled($transaction);
}
