<?php

function awpcp_tag_renderer() {
    return new AWPCP_TagRenderer();
}

class AWPCP_TagRenderer {

    public function render_tag( $tag_name, $attributes ) {
        $pieces = array();

        foreach ( $attributes as $attribute_name => $attribute_value ) {
            $pieces[] = sprintf( '%s="%s"', $attribute_name, esc_attr( $attribute_value ) );
        }

        // http://wiki.whatwg.org/wiki/FAQ#Should_I_close_empty_elements_with_.2F.3E_or_.3E.3F
        return '<' . $tag_name . ' ' . implode( ' ', $pieces ) . ( current_theme_supports( 'html5' ) ? '>' : ' />' );
    }
}
