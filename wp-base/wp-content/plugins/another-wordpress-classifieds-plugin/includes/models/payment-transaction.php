<?php

class AWPCP_Payment_Transaction {

	// public static $PAYMENT_STATUS_UNKNOWN = 'Unknown';
	// public static $PAYMENT_STATUS_INVALID = 'Invalid';
	// // public static $PAYMENT_STATUS_FAILED = 'Failed';
	// // public static $PAYMENT_STATUS_PENDING = 'Pending';
	// // public static $PAYMENT_STATUS_COMPLETED = 'Completed';
	// public static $PAYMENT_STATUS_SUBSCRIPTION_CANCELED = 'Canceled';

	const STATUS_NEW = 'New';
	const STATUS_OPEN = 'Open';
    const STATUS_READY = 'Ready';
	const STATUS_CHECKOUT = 'Checkout';
	const STATUS_PAYMENT = 'Payment';
	const STATUS_PAYMENT_COMPLETED = 'Payment Completed';
	const STATUS_COMPLETED = 'Completed';

    const PAYMENT_STATUS_CANCELED = 'Canceled';
    const PAYMENT_STATUS_NOT_VERIFIED = 'Not Verified';
    const PAYMENT_STATUS_UNKNOWN = 'Unknown';
    const PAYMENT_STATUS_INVALID = 'Invalid';
	const PAYMENT_STATUS_FAILED = 'Failed';
	const PAYMENT_STATUS_PENDING = 'Pending';
	const PAYMENT_STATUS_COMPLETED = 'Completed';
    const PAYMENT_STATUS_NOT_REQUIRED = 'Not Required';

    const PAYMENT_TYPE_MONEY = 'money';
    const PAYMENT_TYPE_CREDITS = 'credits';

	public static $defaults;

    private $in_database = false;

	private $status;
	private $items = array();
	private $data = array();

	public $id;
	public $user_id;

	public $payment_status;
    public $payment_gateway;
    public $payer_email;

	public $errors = array();

	public $created;
	public $completed;
	public $updated;

    public $version;

	public function __construct($args=array(), $in_database=false) {
        $this->in_database = $in_database;

		if (!is_array(self::$defaults)) {
			self::$defaults = array(
				'id' => null,
				'user_id' => null,
				'status' => self::STATUS_NEW,
				'payment_status' => null,
                'payment_gateway' => null,
                'payer_email' => null,
				'items' => array(),
				'data' => array(),
				'errors' => array(),
				'created' => null,
				'updated' => null,
				'completed' => null,
                'version' => 2,
			);
		}

		$args = array_merge(self::$defaults, $args);

		foreach (self::$defaults as $name => $value) {
			$this->$name = maybe_unserialize($args[$name]);
		}
	}

    public static function query($args) {
        global $wpdb;

        extract(wp_parse_args($args, array(
            'fields' => '*',
            'status' => null,
            'created' => null,
            'conditions' => array(),
        )));

        $query = 'SELECT %s FROM ' . AWPCP_TABLE_PAYMENTS . ' ';

        if ($fields == 'count') {
            $query = sprintf($query, 'COUNT(id)');
        } else {
            $query = sprintf($query, $fields);
        }

        if (is_array($status) && !empty($status)) {
            $conditions[] = sprintf("status IN ('%s')", join("','", $status));
        } else if (!is_null($status)) {
            $conditions[] = $wpdb->prepare('status = %s', $status);
        }

        if (is_array($created) && !empty($created)) {
            $conditions[] = $wpdb->prepare(sprintf('created %s %%s', $created[0]), $created[1]);
        } else if (!is_null($created)) {
            $conditions[] = $wpdb->prepare('created = %s', $created);
        }

        $query = sprintf("%s WHERE %s", $query, join(' AND ', $conditions));
        $query = sprintf("%s ORDER BY id ASC", $query);

        if ($fields == 'count') {
            return $wpdb->get_var($query, ARRAY_A);
        } else {
            $results = array();
            foreach ($wpdb->get_results($query, ARRAY_A) as $item) {
                $results[] = new AWPCP_Payment_Transaction($item, true);
            }

            return $results;
        }
    }

	public static function find_by_id($id) {
        global $wpdb;

        if (!empty($id)) {
            $conditions = array($wpdb->prepare('id = %s', $id));
            $results = self::query(array('conditions' => $conditions));
        } else {
            $results = array();
        }

        return empty($results) ? null : array_shift($results);
	}

	public static function find_or_create($id) {
		$transaction = AWPCP_Payment_Transaction::find_by_id($id);

		if (is_null($transaction)) {
			$unique = awpcp_array_data('UNIQUE_ID', rand(), $_SERVER);
			$id = md5($unique . microtime() . wp_salt());
			$transaction = new AWPCP_Payment_Transaction(array('id' => $id));
		}

		return $transaction;
	}

	/**
	 * @since 2.1.4
	 */
	public function save() {
		global $wpdb;

		$now = current_time('mysql');
		$this->created = $this->created ? $this->created : $now;
		$this->updated = $now;

		$data = array();
		foreach (self::$defaults as $name => $value) {
			$data[$name] = maybe_serialize($this->$name);
		}

        // remove empty value for DATE and DATETIME columns to avoid
        // MySQL STRICT mode issues
        foreach (array('created', 'updated', 'completed') as $name) {
            if (empty($data[$name])) {
                unset($data[$name]);
            }
        }

		if ($this->in_database) {
			$result = $wpdb->update(AWPCP_TABLE_PAYMENTS, $data, array('id' => $this->id));
		} else {
			$result = $wpdb->insert(AWPCP_TABLE_PAYMENTS, $data);
            $this->in_database = (bool) $result;
		}

		return $result;
	}

    public function delete() {
        global $wpdb;

        $query = 'DELETE FROM ' . AWPCP_TABLE_PAYMENTS . ' WHERE id = %s';

        return $wpdb->query($wpdb->prepare($query, $this->id));
    }

	/* Transaction Status */

	private function verify_open_conditions(&$errors) {
		if (get_awpcp_option('enable-credit-system') && empty($this->user_id)) {
			$errors[] = __('The transaction must be assigned to a WordPress user.', 'AWPCP');
			return false;
		}

		return true;
	}

    private function verify_ready_to_checkout_conditions(&$errors) {
        $items = count($this->get_items());
		if ($items === 0) {
			$errors[] = __('The transaction has no items.', 'AWPCP');
			return false;
		}

        $balance = 0;
        $has_enough_credit = $this->user_has_enough_credit($balance);

        if (!$has_enough_credit) {
            $payments = awpcp_payments_api();
            $plan = $payments->get_credit_plan($this->get('credit-plan'));

            if (is_null($plan)) {
                $message = __('The amount of credit in your account is not enough to pay for the selected items. Please choose one of the available Credit Plans in addition to the other items you are about to buy or add credit to your account from your Profile Page.<br>You need %d extra credit.', 'AWPCP');
            } else {
                $message = __('The selected Credit Plan is not enough to pay for the selected items. Please choose a bigger Credit Plan or add credit to your account from your Profile Page.<br>You need %d extra credit.', 'AWPCP');
            }

            $errors[] = sprintf($message, - $balance);
            return false;
        }

        // see if we can skip payment due to zero-priced items
        $totals = $this->get_totals();
        $money = (float) $totals['money'];
        // $credits = (int) $totals['credits'];

        if ($items > 0 && $money <= 0.0 && $has_enough_credit) {
            $this->payment_status = self::PAYMENT_STATUS_NOT_REQUIRED;
        }

        return true;
    }

    private function verify_checkout_conditions(&$errors) {
        return true;
	}

    public function verify_payment_conditions(&$errors) {
        $payment_method = awpcp_payments_api()->get_transaction_payment_method($this);

        if (!$this->payment_is_not_required() && is_null($payment_method)) {
            $errors[] = __('You must select a payment method.', 'AWPCP');
            return false;
        }

        return true;
    }

    public function verify_payment_completed_conditions(&$errors) {
        if (empty($this->payment_status)) {
            $errors[] = __('The payment status for this transaction hasn\'t been defined.', 'AWPCP');
            return false;
        }

        return true;
    }

    public function verify_completed_conditions(&$errors) {
        // TODO: code me!s
        return true;
    }

    /**
     * Created to bypass verifications made during a status transition.
     *
     * Payment Methods are not available during upgrade, so the transaction
     * is unable to verify if the specified payment method exists and hence
     * is impossible to mark a transaction as completed.
     *
     * This method should be used in upgrade code.
     */
    public function _set_status($status) {
        $this->status = $status;
    }

    public function set_status($status, &$errors) {
        $allowed = true;
        $verify = array();

        switch ($status) {
            case self::STATUS_COMPLETED:
                $verify[] = self::STATUS_COMPLETED;
            case self::STATUS_PAYMENT_COMPLETED:
                $verify[] = self::STATUS_PAYMENT_COMPLETED;
            case self::STATUS_PAYMENT:
                $verify[] = self::STATUS_PAYMENT;
            case self::STATUS_CHECKOUT:
                $verify[] = self::STATUS_CHECKOUT;
            case self::STATUS_READY:
                $verify[] = self::STATUS_READY;
            case self::STATUS_OPEN:
                $verify[] = self::STATUS_OPEN;
            case self::STATUS_NEW:
                $verify[] = self::STATUS_NEW;
                break;

            default:
                $allowed = false;
        }

        // if ( $allowed && in_array( self::STATUS_NEW, $verify ) ) {
        //     $allowed = $allowed;
        // }
        if ( $allowed && in_array( self::STATUS_OPEN, $verify ) ) {
            $allowed = $this->verify_open_conditions($errors);
        }
        if ( $allowed && in_array( self::STATUS_READY, $verify ) ) {
            $allowed = $this->verify_ready_to_checkout_conditions($errors);
        }
        if ( $allowed && in_array( self::STATUS_CHECKOUT, $verify ) ) {
            $allowed = $this->verify_checkout_conditions($errors);
        }
        if ( $allowed && in_array( self::STATUS_PAYMENT, $verify ) ) {
            $allowed = $this->verify_payment_conditions($errors);
        }
        if ( $allowed && in_array( self::STATUS_PAYMENT_COMPLETED, $verify ) ) {
            $allowed = $this->verify_payment_completed_conditions($errors);
        }
        if ( $allowed && in_array( self::STATUS_COMPLETED, $verify ) ) {
            $allowed = $this->verify_completed_conditions($errors);
        }

        if ($allowed) $this->status = $status;

        return $allowed;
    }

    public function get_status() {
    	return $this->status;
    }

	public function is_new() {
		return $this->status === self::STATUS_NEW;
	}

    public function is_open() {
        return $this->status === self::STATUS_OPEN;
    }

	public function is_ready_to_checkout() {
		return $this->status === self::STATUS_READY;
	}

    public function is_doing_checkout() {
        return $this->status === self::STATUS_CHECKOUT;
    }

    public function is_processing_payment() {
        return $this->status === self::STATUS_PAYMENT;
    }

    public function is_payment_completed() {
        return $this->status === self::STATUS_PAYMENT_COMPLETED;
    }

    public function is_completed() {
        return $this->status === self::STATUS_COMPLETED;
    }

	/* Payment Status */

	public function payment_is_not_required() {
		return $this->payment_status === self::PAYMENT_STATUS_NOT_REQUIRED;
	}

    public function payment_is_completed() {
        return $this->payment_status === self::PAYMENT_STATUS_COMPLETED;
    }

    public function payment_is_pending() {
        return $this->payment_status === self::PAYMENT_STATUS_PENDING;
    }

    public function payment_is_failed() {
        return $this->payment_status === self::PAYMENT_STATUS_FAILED;
    }

    public function payment_is_canceled() {
        return $this->payment_status === self::PAYMENT_STATUS_CANCELED;
    }

    public function payment_is_invalid() {
        return $this->payment_status === self::PAYMENT_STATUS_INVALID;
    }

    public function payment_is_not_verified() {
        return $this->payment_status === self::PAYMENT_STATUS_NOT_VERIFIED;
    }

    public function payment_is_unknown() {
        return $this->payment_status === self::PAYMENT_STATUS_UNKNOWN;
    }

    public function was_payment_successful() {
        return $this->payment_is_completed()
            || $this->payment_is_pending()
            || $this->payment_is_not_required();
    }

	/* Data */

	public function get($name, $default=null) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		return $default;
	}

	public function set($name, $value) {
		$this->data[$name] = $value;
	}

	/* Items */

	public function get_items() {
		return $this->items;
	}

	public function add_item($id, $name, $description, $payment_type, $amount) {
		$item = new stdClass();
		$item->id = $id;
		$item->name = $name;
		$item->description = $description;
		$item->payment_type = $payment_type;
		$item->amount = $amount;

		$this->items[] = $item;
	}

    public function remove_item($id) {
        $index = null;
        foreach ($this->items as $i => $item) {
            if (strcmp($item->id, $id) === 0) {
                $index = $i;
                break;
            }
        }

        if (!is_null($index)) {
            unset($this->items[$index]);
        }

        return !is_null($index);
    }

	public function remove_all_items() {
		$this->items = array();
	}

	public function get_item($index) {
		if (isset($this->items[$index])) {
			return $this->items[$index];
		}
		return null;
	}

	public function get_totals() {
		$credits = $money = 0;

		foreach ($this->items as $item) {
			if ($item->payment_type == 'money')
				$money += $item->amount;
			if ($item->payment_type == 'credits')
				$credits += $item->amount;
		}

		return compact('money', 'credits');
	}

    public function get_total_amount() {
        $totals = $this->get_totals();
        return $totals['money'];
    }

    public function user_has_enough_credit(&$balance=null) {
        if ( awpcp_user_is_admin( $this->user_id ) ) return true;

        $totals = $this->get_totals();
        $credits = $totals['credits'];

        // no need for credits
        if ( $credits === 0 ) return true;

        $payments = awpcp_payments_api();

        if ( !$payments->is_credit_accepted() )
            return false;

        $balance = $payments->get_account_balance($this->user_id);
        $plan = $payments->get_credit_plan($this->get('credit-plan'));

        $balance = $balance - $credits;
        if ($balance < 0) {
            if ( is_null( $plan ) ) return false;
            $balance = $balance + $plan->credits;
            if ( $balance < 0 ) return false;
        }

        return true;
    }
}
