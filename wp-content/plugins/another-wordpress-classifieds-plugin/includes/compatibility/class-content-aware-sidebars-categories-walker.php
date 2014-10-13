<?php

if ( class_exists( 'CAS_Walker_Checklist' ) ) {

function awpcp_content_aware_sidebars_categories_walker( $field_name ) {
    return new AWPCP_ContentAwareSidebarsCategoriesWalker( $field_name, 'awpcp-category', array( 'parent' => 'parent', 'id' => 'id' ) );
}

class AWPCP_ContentAwareSidebarsCategoriesWalker extends CAS_Walker_Checklist {

    private $field_name;

    public function __construct( $field_name, $tree_type, $fields ) {
        parent::__construct( $tree_type, $fields );
        $this->field_name = $field_name;
    }

    public function start_el( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $output .= '<li><label class="selectit"><input value="' . $category->id . '" type="checkbox" name="cas_condition[' . $this->field_name . '][]" value="' . $category->id . '" />' . $category->name . '</label></li>' . "\n";
    }
}

}
