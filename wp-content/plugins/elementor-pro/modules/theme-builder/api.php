<?php

function elementor_theme_do_location( $location ) {
	return ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->do_location( $location );
}

function elementor_location_exits( $location, $check_match = false ) {
	return ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->location_exits( $location, $check_match );
}
