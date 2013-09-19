<?php

/**
 * @since 2.1.4
 */
class AWPCP_PaymentTerm {

    const INTERVAL_DAY = 'D';
    const INTERVAL_WEEK = 'W';
    const INTERVAL_MONTH = 'M';
    const INTERVAL_YEAR = 'Y';

    protected $defaults;

    public $type;

    public $id;
    public $name;
    public $description;
    public $duration_amount;
    public $duration_interval;
    public $price;
    public $credits;
    public $categories;

    public $title_characters;
    public $characters;
    public $images;
    public $ads;

    public $recurring;

    public function __construct($data) {
        $data = $this->normalize($data);
        $data = $this->sanitize($data);
        $this->update($data, true);
    }

    public static function get_duration_intervals() {
        return array(
            self::INTERVAL_DAY,
            self::INTERVAL_WEEK,
            self::INTERVAL_MONTH,
            self::INTERVAL_YEAR
        );
    }

    public static function get_duration_interval_label($interval, $amount=2) {
        switch ($interval) {
            case self::INTERVAL_DAY:
                $label = _nx('Day', 'Days', $amount, 'payment terms', 'AWPCP');
                break;
            case self::INTERVAL_WEEK:
                $label = _nx('Week', 'Weeks', $amount, 'payment terms', 'AWPCP');
                break;
            case self::INTERVAL_MONTH:
                $label = _nx('Month', 'Months', $amount, 'payment terms', 'AWPCP');
                break;
            case self::INTERVAL_YEAR:
                $label = _nx('Year', 'Years', $amount, 'payment terms', 'AWPCP');
                break;
        }

        return $label;
    }

    protected function &get_default_properties() {
        if (!is_array($this->defaults)) {
            $this->defaults = array(
                'id' => null,
                'name' => null,
                'description' => null,
                'duration_amount' => 30,
                'duration_interval' => self::INTERVAL_DAY,
                'price' => null,
                'credits' => null,
                'categories' => array(),
                'title_characters' => 0,
                'characters' => 0,
                'images' => 0,
                'ads' => 1,
                'featured' => 0,
                'private' => 0,
            );
        }

        return $this->defaults;
    }

    protected function &normalize(&$data) {
        $defaults = $this->get_default_properties();
        foreach ($defaults as $name => $default) {
            // do not use awpcp_array_data, it does not plays well with values
            // that a make empty() false.
            $data[$name] = isset($data[$name]) ? $data[$name] : $default;
        }
        return $data;
    }

    protected function &sanitize(&$data) {
        $data['categories'] = array_filter($data['categories']);
        $data['duration_amount'] = (int) $data['duration_amount'];
        $data['images'] = (int) $data['images'];
        $data['title_characters'] = (int) $data['title_characters'];
        $data['characters'] = (int) $data['characters'];
        $data['credits'] = (int) $data['credits'];
        $data['price'] = (float) $data['price'];
        $data['ads'] = (int) $data['ads'];
        $data['featured'] = (bool) $data['featured'];
        $data['private'] = (bool) $data['private'];
        return $data;
    }

    protected function validate($data, &$errors=array()) {
        if (empty($data['name']))
            $errors[] = __('The name of the plan is required.', 'AWPCP');

        if ($data['duration_amount'] < 0)
            $errors[] = __('The duration amount must be equal or greater than zero.', 'AWPCP');

        if (!in_array($data['duration_interval'], self::get_duration_intervals()))
            $errors[] = __('The duration interval is invalid.', 'AWPCP');

        if ($data['images'] < 0)
            $errors[] = __('The number of images allowed must be equal or greater than zero.', 'AWPCP');

        if ($data['characters'] < 0)
            $errors[] = __('The number of characters allowed must be equal or greater than zero.', 'AWPCP');

        if ( $data['title_characters'] < 0 )
            $errors[] = __( 'The number of characters allowed in the title must be equal or greater than zero.', 'AWPCP' );

        if ($data['credits'] < 0)
            $errors[] = __('The number of credits must be greater than zero.', 'AWPCP');

        if ($data['price'] < 0)
            $errors[] = __('The price must be equal or greater than zero.', 'AWPCP');

        return empty($errors);
    }

    public function update($data) {
        foreach ($this->get_default_properties() as $name => $default) {
            // do not use awpcp_array_data, it doesn't plays well with values
            // that make empty() false.
            if (isset($data[$name])) $this->$name = $data[$name];
        }
    }

    /**
     * Used to determine if a payment term can be used in the given
     * transaction. The default behavior always returns true but
     * sub-classes can overwrite the method to support other scenearios.
     * @param   AWPCP_Payment_Transaction   $transaction
     * @return  boolean
     */
    public function is_suitable_for_transaction($transaction) {
        return true;
    }

    /**
     * Used to determine if the given Ad can be renewed using
     * this payment term. The default behavior always returns true but
     * sub-classes can overwrite the method to support other scenearios.
     * @since   3.0
     * @param   AWPCP_AD    $ad
     * @return  boolean
     */
    public function ad_can_be_renewed($ad) {
        return true;
    }

    /**
     * Calculates a date adding this term's duration to the given
     * start date.
     *
     * @param  long $start_date     A timestamp
     * @return string               MySQL date string
     */
    public function calculate_end_date($start_date=null) {
        $amount = $this->duration_amount;

        switch ($this->duration_interval) {
            case self::INTERVAL_DAY:
                $interval = 'days';
                $amount = $amount == 0 ? 3650 : $amount;
                break;
            case self::INTERVAL_WEEK:
                $interval = 'weeks';
                $amount = $amount == 0 ? 3520 : $amount;
                break;
            case self::INTERVAL_MONTH:
                $interval = 'months';
                $amount = $amount == 0 ? 31200 : $amount;
                break;
            case self::INTERVAL_YEAR:
                $interval = 'years';
                $amount = $amount == 0 ? 310 : $amount;
                break;
        }

        return awpcp_time( strtotime( "+ $amount $interval", $start_date ), 'mysql' );
    }

    public function requires_payment() {
        return $this->price == 0 && $this->credits == 0;
    }

    public function get_name() {
        return stripslashes($this->name);
    }

    public function get_allowed_ads_count() {
        return $this->ads;
    }

    public function get_duration_interval() {
        return self::get_duration_interval_label($this->duration_interval, $this->duration_amount);
    }

    public function get_duration() {
        $amount = $this->duration_amount;
        $interval = $this->get_duration_interval();
        return sprintf("%d %s", $amount, $interval);
    }

    public function get_characters_allowed() {
        return $this->characters;
    }

    public function get_characters_allowed_in_title() {
        return $this->title_characters;
    }
}
