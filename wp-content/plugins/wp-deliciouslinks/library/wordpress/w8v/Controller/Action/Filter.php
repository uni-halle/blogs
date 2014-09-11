<?php
abstract class w8v_Controller_Action_Filter extends w8v_Controller_Action_Abstract {
	public function __construct($application) {
		$this->set_type ( self::WP_FILTER );
		parent::__construct ( $application );
	}
	public function plugin_action_linksAction($links, $file) {
		if ($file != plugin_basename ( $this->application ()->filename () )) {
			return $links;
		}
		array_unshift ( $links, '<a href="options-general.php?page=Settings_' . $this->application ()->Settings ()->name . '">Settings</a>' );
		$options = $this->application ()->Settings ()->get ();
		if (! empty ( $options ['donate_link'] )) {
			array_unshift ( $links, $this->donate_button () );
		}
		if ($this->application ()->Settings ()->version != $this->application ()->Settings ()->stable_tag) {
			$links [] = '<strong style="color:#ff0000">&beta;</strong>';
		}
		return $links;
	}
	public function setup() {
		foreach ( ( array ) $this->actions () as $action ) {
			$numargs = 5;
			add_filter ( $action ['raw_title'], array ($this, "controller" ), $action ['priority'], $numargs );
		}
	}
}