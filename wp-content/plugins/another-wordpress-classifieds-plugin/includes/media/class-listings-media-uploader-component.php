<?php

function awpcp_listings_media_uploader_component() {
    return new AWPCP_Listings_Media_Uploader_Component(
        awpcp_media_uploader_component(),
        awpcp_file_validation_errors(),
        awpcp()->js
    );
}

class AWPCP_Listings_Media_Uploader_Component {

    private $media_uploader_component;
    private $validation_errors;
    private $javascript;

    public function __construct( $media_uploader_component, $validation_errors, $javascript ) {
        $this->media_uploader_component = $media_uploader_component;
        $this->validation_errors = $validation_errors;
        $this->javascript = $javascript;
    }

    public function render( $configuration ) {
        $this->javascript->localize( 'media-uploader-strings', array(
            'upload-restrictions-images-others-videos' => __( 'You can upload <images-left> images of up to <images-max-file-size> each, <videos-left> videos of up to <videos-max-file-size> each and <others-left> other files of up to <others-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'upload-restrictions-images-others' => __( 'You can upload <images-left> images of up to <images-max-file-size> each and <others-left> other files (no videos) of up to <others-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'upload-restrictions-images-videos' => __( 'You can upload <images-left> images of up to <images-max-file-size> each and <videos-left> videos of up to <videos-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'upload-restrictions-others-videos' => __( 'You can upload <videos-left> videos of up to <videos-max-file-size> each and <others-left> other files (no images) of up to <others-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'upload-restrictions-images' => __( 'You can upload <images-left> images of up to <images-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'upload-restrictions-others' => __( 'You can upload <others-left> files (no videos or images) of up to <others-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'upload-restrictions-videos' => __( 'You can upload <videos-left> videos of up to <videos-max-file-size> each.', 'another-wordpress-classifieds-plugin' ),
            'cannot-add-more-files' => $this->validation_errors->get_cannot_add_more_files_of_type_error_message(),
            'file-is-too-large' => $this->validation_errors->get_file_is_too_large_error_message(),
        ) );

        return $this->media_uploader_component->render( $configuration );
    }
}
