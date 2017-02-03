<div id="delete-browse-categories-page-notice" class="notice notice-info is-dismissible awpcp-notice">
    <p><?php
        $message = __( 'The <browse-categories-page-name> page is no longer necessary. From now on, all listings will be displayed in the <browse-listings-page-name> page, even when they are filtered by a particular category.', 'another-wordpress-classifieds-plugin' );
        $message = str_replace( '<browse-categories-page-name>', '<strong>' . $browse_categories_page_name . '</strong>', $message );
        $message = str_replace( '<browse-listings-page-name>', '<strong>' . $browse_listings_page_name . '</strong>', $message );
        echo $message;
    ?></p>
    <p><?php
        $message = __( 'The plugin will start redirecting all traffic to the <browse-listings-page-name> page to make sure no broken links are created.', 'another-wordpress-classifieds-plugin' );
        $message = str_replace( '<browse-listings-page-name>', '<strong>' . $browse_listings_page_name . '</strong>', $message );
        echo $message;
    ?></p>
    <p><?php echo __( 'Click the button below to delete the page.', 'another-wordpress-classifieds-plugin' ); ?></p>
    <p><a class="button button-primary" href="#" data-action="delete-page" data-action-params="<?php echo esc_attr( json_encode( $action_params ) ); ?>"><?php echo __( 'Delete Page', 'another-wordpress-classifieds-plugin' ); ?></a></p>
</div>
