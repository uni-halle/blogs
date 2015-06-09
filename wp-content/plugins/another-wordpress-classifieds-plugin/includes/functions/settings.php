<?php

function awpcp_register_allowed_extensions_setting( $settings, $section, $setting ) {
    $config = array(
        'multiple' => true,
        'choices' => array_combine( $setting['choices'], $setting['choices'] ),
    );

    $settings->add_setting( $section, $setting['name'], $setting['label'], 'choice', $setting['default'], '', $config );
}
