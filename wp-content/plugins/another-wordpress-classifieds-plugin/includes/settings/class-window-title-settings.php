<?php

function awpcp_window_title_settings() {
    return new AWPCP_WindowTitleSettings();
}

class AWPCP_WindowTitleSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section(
            'listings-settings',
            __( 'Window Title', 'another-wordpress-classifieds-plugin' ),
            'window-title',
            40,
            array( $this, 'render_section_header' )
        );

        $settings->add_setting(
            $key,
            'awpcptitleseparator',
            __( 'Window title separator', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '-',
            __( 'The character to use to separate ad details used in browser page title. Example: | / -', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'showcityinpagetitle',
            __( 'Show city in window title', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'Show city in browser page title when viewing individual Ad', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'showstateinpagetitle',
            __( 'Show state in window title', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __('Show state in browser page title when viewing individual Ad', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'showcountryinpagetitle',
            __( 'Show country in window title', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'Show country in browser page title when viewing individual Ad', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'showcountyvillageinpagetitle',
            __( 'Show county/village/other in window title', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'Show county/village/other setting in browser page title when viewing individual Ad', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'showcategoryinpagetitle',
            __( 'Show category in title', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'Show category in browser page title when viewing individual Ad', 'another-wordpress-classifieds-plugin' )
        );
    }

    public function render_section_header() {
        $introduction = _x( 'These settings affect the title shown in the title bar of the browser for the listing. You can include or remove certain elements if you wish.', 'window title settings section', 'another-wordpress-classifieds-plugin' );

        echo '<p>' . $introduction . '</p>';
    }
}
