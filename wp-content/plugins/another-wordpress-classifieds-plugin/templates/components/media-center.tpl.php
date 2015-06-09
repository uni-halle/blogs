<div class="awpcp-media-center">
    <?php $media_uploader = awpcp_media_uploader_component(); ?>
    <?php echo $media_uploader->render( $media_uploader_configuration ); ?>

    <?php $messages = awpcp_messages_component(); ?>
    <?php echo $messages->render( array( 'media-uploader', 'media-manager', 'thumbnails-generator' ) ); ?>

    <?php echo awpcp_attachment_background_color_explanation(); ?>

    <?php $media_manager = awpcp_media_manager_component(); ?>
    <?php echo $media_manager->render( $files, $media_manager_configuration ); ?>

    <div class="awpcp-thumbnails-generator" data-nonce="<?php echo esc_attr( wp_create_nonce( 'awpcp-upload-generated-thumbnail-for-listing-' . $listing->ad_id ) ); ?>">
        <video preload="none" muted="muted" width="0" height="0"></video>
        <canvas></canvas>
    </div>
</div>
