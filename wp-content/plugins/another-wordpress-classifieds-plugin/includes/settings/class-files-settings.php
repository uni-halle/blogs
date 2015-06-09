<?php

function awpcp_files_settings() {
    return new AWPCP_FilesSettings( awpcp_file_types() );
}

class AWPCP_FilesSettings {

    private $file_types;

    public function __construct( $file_types ) {
        $this->file_types = $file_types;
    }

    public function register_settings( $settings ) {
        $group = $settings->add_group( _x( 'Images/Attachments', 'name of Files settings section', 'AWPCP' ), 'attachments-settings', 50 );

        // Section: Uploads Directory

        $key = $settings->add_section( $group, __( 'Uploads Directory', 'AWPCP' ), 'uploads-directory', 10, array( $settings, 'section' ) );

        $permissions = array( '0755' => '0755', '0777' => '0777' );

        $settings->add_setting( $key, 'uploadfoldername', __( 'Uploads folder name', 'AWPCP' ), 'textfield', 'uploads', __( 'Upload folder name. (Folder must exist and be located in your wp-content directory)', 'AWPCP' ) );
        $settings->add_setting( $key, 'upload-directory-permissions', __( 'File permissions for uploads directory', 'AWPCP' ), 'select', '0755', __( 'File permissions applied to the uploads directory and sub-directories so that the plugin is allowed to write to those directories.', 'AWPCP' ), array( 'options' => $permissions ) );

        // Section: Image Settings

        $key = $settings->add_section( $group, __( 'Images', 'AWPCP' ), 'image', 20, array( $settings, 'section' ) );

        $image_extensions = $this->file_types->get_file_extensions_in_group( 'image' );
        $legacy_allow_images_setting = $settings->get_option( 'imagesallowdisallow', true );

        if ( $legacy_allow_images_setting ) {
            $default_image_extenstions = $image_extensions;
        } else {
            $default_image_extenstions = array();
        }

        awpcp_register_allowed_extensions_setting(
            $settings,
            $key,
            array(
                'name' => 'allowed-image-extensions',
                'label' => __( 'Allowed image extensions', 'AWPCP' ),
                'choices' => $image_extensions,
                'default' => $default_image_extenstions,
            )
        );

        $settings->add_setting( $key, 'imagesapprove', __( 'Hide images until admin approves them', 'AWPCP' ), 'checkbox', 0, '');
        $settings->add_setting( $key, 'awpcp_thickbox_disabled', __( 'Disable AWPCP Lightbox feature', 'AWPCP' ), 'checkbox', 0, __( 'Turn off the lightbox/thickbox element used by AWPCP. Some themes cannot handle it and a conflict results.', 'AWPCP' ) );
        $settings->add_setting( $key, 'show-click-to-enlarge-link', __( 'Show click to enlarge link?', 'AWPCP' ), 'checkbox', 1, '' );
        $settings->add_setting( $key, 'imagesallowedfree', __( 'Number of images allowed if payments are disabled (Free Mode)', 'AWPCP' ), 'textfield', 4, __( 'If images are allowed and payments are disabled, users will be allowed upload this amount of images.', 'AWPCP' ) );

        $options = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4);

        $settings->add_setting( $key, 'display-thumbnails-in-columns', __( 'Number of columns of thumbnails to show in Show Ad page.', 'AWPCP' ), 'select', 0, __( 'Zero means there will be as many thumbnails as possible per row.', 'AWPCP' ), array( 'options' => $options ) );

        // Section: Image File Size Settings

        $key = $settings->add_section($group, __('Image File Size', 'AWPCP'), 'image-file-size', 30, array($settings, 'section'));

        $settings->add_setting( $key, 'maximagesize', __( 'Maximum file size per image', 'AWPCP' ), 'textfield', '1000000', __( 'Maximum file size, in bytes, for files user can upload to system. 1 MB = 1000000 bytes. You can google "x MB to bytes" to get an accurate conversion.', 'AWPCP' ) );
        $settings->add_setting( $key, 'minimagesize', __( 'Minimum file size per image', 'AWPCP' ), 'textfield', '300', __( 'Minimum file size, in bytes, for files user can upload to system. 1 MB = 1000000 bytes. You can google "x MB to bytes" to get an accurate conversion.', 'AWPCP' ) );
        $settings->add_setting( $key, 'imgminwidth', __( 'Minimum image width', 'AWPCP' ), 'textfield', '640', __( 'Minimum width for images.', 'AWPCP' ) );
        $settings->add_setting( $key, 'imgminheight', __( 'Minimum image height', 'AWPCP' ), 'textfield', '480', __( 'Minimum height for images.', 'AWPCP' ) );
        $settings->add_setting( $key, 'imgmaxwidth', __( 'Maximum image width', 'AWPCP' ), 'textfield', '640', __( 'Maximum width for images. Images wider than settings are automatically resized upon upload.', 'AWPCP' ) );
        $settings->add_setting( $key, 'imgmaxheight', __( 'Maximum image height', 'AWPCP' ), 'textfield', '480', __( 'Maximum height for images. Images taller than settings are automatically resized upon upload.', 'AWPCP' ) );

        // Section: Image Settings - Primary Images

        $key = $settings->add_section( $group, __( 'Primary Image', 'AWPCP'), 'primary-image', 40, array( $this, 'primary_image_section_header' ) );

        $settings->add_setting( $key, 'displayadthumbwidth', __( 'Thumbnail width (Ad Listings page)', 'AWPCP' ), 'textfield', '80', __( 'Width of the thumbnail for the primary image shown in Ad Listings view.', 'AWPCP' ) );
        $settings->add_setting( $key, 'primary-image-thumbnail-width', __( 'Thumbnail width (Primary Image)', 'AWPCP' ), 'textfield', '200', __( 'Width of the thumbnail for the primary image shown in Single Ad view.', 'AWPCP' ) );
        $settings->add_setting( $key, 'primary-image-thumbnail-height', __( 'Thumbnail height (Primary Image)', 'AWPCP' ), 'textfield', '200', __( 'Height of the thumbnail for the primary image shown in Single Ad view.', 'AWPCP' ) );
        $settings->add_setting( $key, 'crop-primary-image-thumbnails', __( 'Crop primary image thumbnails?', 'AWPCP' ), 'checkbox', 1, _x('If you decide to crop thumbnails, images will match exactly the dimensions in the settings above but part of the image may be cropped out. If you decide to resize, image thumbnails will be resized to match the specified width and their height will be adjusted proportionally; depending on the uploaded images, thumbnails may have different heights.', 'settings', 'AWPCP' ) );

        // Section: Image Settings - Thumbnails

        $key = $settings->add_section( $group, __( 'Thumbnails', 'AWPCP' ), 'thumbnails', 50, array( $this, 'thumbnails_section_header' ) );

        $settings->add_setting( $key, 'imgthumbwidth', __( 'Thumbnail width', 'AWPCP' ), 'textfield', '125', __( 'Width of the thumbnail images.', 'AWPCP' ) );
        $settings->add_setting( $key, 'imgthumbheight', __( 'Thumbnail height', 'AWPCP' ), 'textfield', '125', __( 'Height of the thumbnail images.', 'AWPCP' ) );
        $settings->add_setting( $key, 'crop-thumbnails', __( 'Crop thumbnail images?', 'AWPCP' ), 'checkbox', 1, _x( 'If you decide to crop thumbnails, images will match exactly the dimensions in the settings above but part of the image may be cropped out. If you decide to resize, image thumbnails will be resized to match the specified width and their height will be adjusted proportionally; depending on the uploaded images, thumbnails may have different heights.', 'settings', 'AWPCP' ) );
    }

    public function primary_image_section_header() {
        echo __( 'The primary image is the one displayed as the thumbnail in the list view and the largest one shown when the detail view of the listing is clicked.', 'AWPCP' );
    }

    public function thumbnails_section_header() {
        echo __( 'These are the remaining images that are not primary ones, if you have more than one image allowed per listing.', 'AWPCP' );
    }
}
