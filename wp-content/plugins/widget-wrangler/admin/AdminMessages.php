<?php

namespace WidgetWrangler;

/**
 * Class AdminMessages
 * @package WidgetWrangler
 */
class AdminMessages {

	/**
	 * Where messages are stored in the options table.
	 *
	 * @var string
	 */
	public $option_name = 'ww_admin_messages';

	/**
	 * Array of stored messages.
	 *
	 * @var array
	 */
	public $messages = array();

	/**
	 * Private AdminMessages constructor.
	 */
	private function __construct() {
		$this->messages = get_option($this->option_name, array());
	}

	/**
	 * Singleton.
	 *
	 * @return AdminMessages
	 */
	public static function instance() {
		static $instance = null;

		if ($instance) {
			return $instance;
		}

		$instance = new self();
		return $instance;
	}

	/**
	 * Save the messages.
	 */
	function save() {
		update_option($this->option_name, $this->messages, false);
	}

	/**
	 * Get messages for the current user and clear them.
	 *
	 * @return array
	 */
	function get() {
		$uid = wp_get_current_user()->ID;

		if ( !empty( $this->messages[ $uid ] ) ){
			$messages = array_values( $this->messages[ $uid ] );
			unset( $this->messages[ $uid ] );
			$this->save();

			return $messages;
		}

		return array();
	}

	/**
	 * Add a new message for the current user.
	 *
	 * @param $message
	 * @param $type
	 */
	function add( $message, $type ) {
		$uid = wp_get_current_user()->ID;
		$hash = md5($message.$type);

		$this->messages[$uid][$hash] = array(
			'message' => $message,
			'type' => $type,
			'timestamp' => time(),
		);

		$this->save();
	}
}
