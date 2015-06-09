<?php

function awpcp_media_manager_component() {
    return new AWPCP_MediaManagerComponent( awpcp()->js, awpcp()->settings );
}

class AWPCP_MediaManagerComponent {

    private $javascript;
    private $settings;

    public function __construct( $javascript, $settings ) {
        $this->javascript = $javascript;
        $this->settings = $settings;
    }

    public function render( $files = array(), $options = array() ) {
        $options['files'] = $this->prepare_files( $files );

        $this->javascript->set( 'media-manager-data', $options );

        return $this->render_component();
    }

    private function prepare_files( $files ) {
        $files_info = array();

        foreach ( $files as $file ) {
            $files_info[] = array(
                'id' => $file->id,
                'name' => $file->name,
                'listingId' => $file->ad_id,
                'enabled' => $file->enabled,
                'status' => $file->status,
                'mimeType' => $file->mime_type,
                'isImage' => $file->is_image(),
                'isPrimary' => $file->is_primary(),
                'thumbnailUrl' => $file->get_url( 'thumbnail' ),
                'iconUrl' => $file->get_icon_url(),
                'url' => $file->get_url(),
            );
        }

        return $files_info;
    }

    private function render_component() {
        $thumbnails_width = $this->settings->get_option( 'imgthumbwidth' );

        ob_start();
        include( AWPCP_DIR . '/templates/components/media-manager.tpl.php' );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
