<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Control_Tab extends Control_Base {

	public function get_type() {
		return 'tab';
	}

	public function content_template() {
		?>
			<div class="elementor-panel-tab-heading">
				{{{ data.label }}}
			</div>
		<?php
	}

	protected function get_default_settings() {
		return [
			'separator' => 'none',
		];
	}
}
