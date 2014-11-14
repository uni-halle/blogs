<?php

function awpcp_admin_page_links_builder() {
    return new AWPCP_AdminPageLinksBuilder();
}

/**
 * @since next-release
 */
class AWPCP_AdminPageLinksBuilder {

    private $link_template = '<a href="%1$s">%2$s</a>';

    /**
     * TODO: this methods is doing too much, it accepts links definitions as:
     *
     * 1. 'label' => 'url',
     * 2. 'index' => array( 'label', 'url' ),
     * 3. 'label' => array( 'before_link', 'url', 'after_link' )
     * 4. 'index' => array( 'label', array( 'before_link', 'url', 'after_link' ) )
     *
     * There is no need to do that in a single method. We should create simpler methods
     * for each case.
     */
    public function build_links( $blueprints, $selected = null ) {
        $links = array();

        foreach ($blueprints as $key => $href) {
            // to make it work with the array returned by $this->actions();
            if (is_array($href) && count($href) === 2) {
                list($label, $href) = (array) $href;
            } else {
                $label = $key;
            }

            $label = $key == $selected ? "<strong>$label</strong>" : $label;

            if (is_array($href)) {
                $links[ $key ] = $href[0] . sprintf( $this->link_template, $href[1], $label ) . $href[2];
            } else {
                $links[ $key ] = sprintf( $this->link_template, $href, $label );
            }
        }

        return $links;
    }
}
