<?php

abstract class AWPCP_PaymentTermType {

    public $name;
    public $slug;
    public $description;

    public function __construct($name, $slug, $description) {
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
    }

    public abstract function find_by_id($id);

    // public abstract function find_by_ad_id($id);

    public abstract function get_payment_terms();

    public abstract function get_user_payment_terms($user_id);
}
