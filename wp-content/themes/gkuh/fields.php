<?php
function rad_labs_category_colors(){
    if ( !class_exists( 'RadLabs_Category_Colors' ) )
        return;

    $meta_sections = array();

    // Color Meta Box
    $meta_sections[] = array(
        'title'      => 'Select Category Color',
        'taxonomies' => array('category'),
        'id'         => 'rl_category_meta',
        'fields'     => array(
            array(
                'name' => 'Color Picker',
                'id'   => 'rl_cat_color',
                'type' => 'color',
            ),
        ),
    );

    foreach ($meta_sections as $meta_section){
        new RadLabs_Category_Colors( $meta_section );
    }
}

add_action( 'admin_init', 'rad_labs_category_colors' );