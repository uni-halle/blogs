<?php

function awpcp_media_uploader_component() {
    return new AWPCP_MediaUploaderComponent( awpcp_file_validation_errors(), awpcp()->js );
}

class AWPCP_MediaUploaderComponent {

    private $validation_errors;
    private $javascript;

    public function __construct( $validation_errors, $javascript ) {
        $this->validation_errors = $validation_errors;
        $this->javascript = $javascript;
    }

    public function render( $configuration ) {
        $configuration = wp_parse_args( $configuration, array(
            'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
            'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
        ) );

        $this->javascript->set( 'media-uploader-data', $configuration );

        $this->javascript->localize( 'media-uploader-strings', array(
            'upload-restrictions-images-others-videos' => __( 'You can upload <images-left> images of up to <images-max-file-size> each, <videos-left> videos of up to <videos-max-file-size> each and <others-left> other files of up to <others-max-file-size> each.', 'AWPCP' ),
            'upload-restrictions-images-others' => __( 'You can upload <images-left> images of up to <images-max-file-size> each and <others-left> other files (no videos) of up to <others-max-file-size> each.', 'AWPCP' ),
            'upload-restrictions-images-videos' => __( 'You can upload <images-left> images of up to <images-max-file-size> each and <videos-left> videos of up to <videos-max-file-size> each.', 'AWPCP' ),
            'upload-restrictions-others-videos' => __( 'You can upload <videos-left> videos of up to <videos-max-file-size> each and <others-left> other files (no images) of up to <others-max-file-size> each.', 'AWPCP' ),
            'upload-restrictions-images' => __( 'You can upload <images-left> images of up to <images-max-file-size> each.', 'AWPCP' ),
            'upload-restrictions-others' => __( 'You can upload <others-left> files (no videos or images) of up to <others-max-file-size> each.', 'AWPCP' ),
            'upload-restrictions-videos' => __( 'You can upload <videos-left> videos of up to <videos-max-file-size> each.', 'AWPCP' ),
            'cannot-add-more-files' => $this->validation_errors->get_cannot_add_more_files_of_type_error_message(),
            'file-is-too-large' => $this->validation_errors->get_file_is_too_large_error_message(),
        ) );

        return $this->render_component();
    }

    private function render_component() {
        ob_start();
        include( AWPCP_DIR . '/templates/components/media-uploader.tpl.php' );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
