<?php

class AWPCP_Cancel_Payment_Page {

	public function AWPCP_Cancel_Payment_Page() {
	}

	public function add_query_vars($vars) {
		if (!isset($vars['awpcp-txn'])) {
			array_push($vars, 'awpcp-txn');	
		}
    	return $vars;
	}

	public function dispatch() {
		global $wp_query;

		$transaction_id = awpcp_array_data('awpcp-txn', '', $wp_query->query_vars);
		$transaction = AWPCP_Payment_Transaction::find_by_id($transaction_id);

		$transaction_id_msg = '<br/><br/>';
		$transaction_id_msg.= sprintf(__('Your Transaction ID is %s.'), "<strong>$transaction_id</strong>");

		if (is_null($transaction)) {
			$msg = __('An error ocurred while processing your Payment Transaction. Please contact the administrator about this error.', 'AWPCP');
			$msg.= $transaction_id_msg;
			// TODO: send email?
			return $msg;
		}

		$texts = array(
			'title' => __('Payment Cancelled', 'AWPCP'),
			'text' => __("You have chosen to cancel the payment process. You can click the button below to go the checkout page again and make your payment now.", "AWPCP")
		);

		ob_start();
			include(AWPCP_DIR . 'frontend/templates/page-cancel-payment.tpl.php');
			$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function _dispatch() {
		global $wp_query;

		$transaction_id = awpcp_array_data('awpcp-txn', '', $wp_query->query_vars);

		$transaction_id_msg = '<br/><br/>';
		$transaction_id_msg.= sprintf(__('Your Transaction ID is %s.'), "<strong>$transaction_id</strong>");

		$transaction = AWPCP_Payment_Transaction::find_by_id($transaction_id);
		if (is_null($transaction)) {
			$msg = __('An error ocurred while processing your Payment Transaction. Please contact the administrator about this error.', 'AWPCP');
			$msg.= $transaction_id_msg;
			// TODO: send email?
			return $msg;
		}

		$verified = apply_filters('awpcp-payments-verify-transaction', false, $transaction);
		if (!$verified) {
			if (empty($transaction->errors)) {
				$msg = __("There appears to be a problem. Please contact customer service if you are viewing this message after having made a payment. If you have not tried to make a payment and you are viewing this message, it means this message is being shown in error and can be disregarded.","AWPCP");
				$msg.= $transaction_id_msg;
			} else {
				$msg = join('<br/><br/>', $transaction->errors);
			}
			// TODO: send email
			// $output .= abort_payment_no_email($message,$ad_id,$txn_id,$gateway);
			return $msg;
		}

		$valid = apply_filters('awpcp-payments-validate-transaction', false, $transaction);
		if (!$valid) {
			return join('<br/><br/>', $transaction->errors);
		}

		$texts = array(
			'title' => __('Step 2 of 4 - Checkout'),
			'subtitle' => __('Congratulations', 'AWPCP'),
			'text' => __('Your Payment has been processed succesfully. Please press the button below to continue with the process.', 'AWPCP')
		);

		// If you want to change the message shown in this page change this action to become a filter
		$texts = apply_filters('awpcp-payments-transaction-processed', $texts, $transaction);

		$transaction->save();

		$status = $transaction->get('status');

		ob_start();
			include(AWPCP_DIR . 'frontend/templates/page-payment-thank-you.tpl.php');
			$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}