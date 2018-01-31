<div class="changecategoryselect">
    <form class="awpcp-category-selector" method="post" action="<?php echo esc_attr( $action_url ); ?>">
        <?php foreach ( $hidden as $field_name => $value ): ?>
        <input type="hidden" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
        <?php endforeach; ?>

    <div class="awpcp-category-dropdown-container <?php echo $dropdown_container_class; ?>">
            <?php $dropdown = new AWPCP_CategoriesDropdown(); ?>
            <?php echo $dropdown->render( $category_dropdown_params ); ?>

            <?php if ( $use_multiple_dropdowns ): ?>
            <input class="button" type="submit" value="<?php echo esc_attr( __( 'Change Category', 'another-wordpress-classifieds-plugin' ) ); ?>" />
            <?php endif; ?>
        </div>
    </form>
</div>
