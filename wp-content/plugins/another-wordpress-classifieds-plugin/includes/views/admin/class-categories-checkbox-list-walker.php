<?php

if ( class_exists( 'AWPCP_CategoriesListWalker' ) ) {

/**
 * XXX: I came with this idea too late, but to generate the hierarchical
 * list of checkboxes we could have just created a basic walker that
 * inherits from Walker, instead of inheriting from the more complex
 * AWPCP_CategoriesListWalker.
 *
 * Please refactor this code the next time you have to work on this part
 * of the plugin.
 */
class AWPCP_CategoriesCheckboxListWalker extends AWPCP_CategoriesListWalker {

    public function configure( $options = array() ) {
        $options = wp_parse_args( $options, array(
            'selected' => array(),
            'field_name' => 'categories',

            'first_level_ul_class' => '',
            'second_level_ul_class' => '',
            'first_level_element_wrapper' => 'label',
            'first_level_element_wrapper_class' => 'selectit',
            'second_level_element_wrapper' => 'label',
            'second_level_element_wrapper_class' => 'selectit',
        ) );

        return parent::configure( $options );
    }

    protected function list_container() {
        return '[categories-list]';
    }

    protected function element( $category, $depth, $args, $current_object_id ) {
        $element = '<input type="checkbox" id="in-category-[category-id]" name="[field-name][]" value="[category-id]" [checked]> [category-name]';

        $element = str_replace( '[category-id]', $category->id, $element );
        $element = str_replace( '[category-name]', esc_attr( $category->name ), $element );

        $value = $category->id;
        $selected_values = $this->options['selected'];

        $element = str_replace( '[checked]', in_array( $value, $selected_values ) ? 'checked="checked"' : '', $element );
        $element = str_replace( '[field-name]', $this->options['field_name'], $element );

        return $element;
    }

    private function render_checked_attribute( $value, $selected_values ) {
        return ;
    }
}

}
