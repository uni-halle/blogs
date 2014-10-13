<div class="awpcp-file-manager">
    <div data-bind="if: haveFiles">
        <?php echo awpcp_attachment_background_color_explanation(); ?>
    </div>

    <div class="awpcp-uploaded-images-container" data-bind="if: haveImages">
        <h3><?php _e( 'Existing Images', 'AWPCP' ); ?></h3>

        <p>
            <?php _e( 'Use the green check mark icon to set an image as the primary image for this Ad. The primary image is shown with a green background and border. The background may be different if the image is awaiting approval or rejected (see above).', 'AWPCP' ); ?>
            <br data-bind="if: canUploadImages">
            <span data-bind="if: canUploadImages"><?php _e( 'You can upload a new image and mark it as primary using the fields below.', 'AWPCP' ); ?></span>
        </p>

        <form>
            <ul id="uploaded-images" class="awpcp-uploaded-files awpcp-uploaded-images clearfix" data-bind="foreach: images">
                <li data-bind="css: getFileClasses, attr: { id: getFileId }">
                    <div class="image-container">
                        <img data-bind="attr: { src: thumbnailUrl }" />
                    </div>
                    <ul class="image-actions clearfix">
                        <li class="status"><label><input type="checkbox" data-bind="checked: enabled"> <?php _e( 'Enabled', 'AWPCP' ); ?></label></li>
                        <li class="delete"><a title="<?php echo esc_attr( __( 'Delete Image', 'AWPCP' ) ); ?>" data-bind="click: $root.deleteFile"></a></li>
                        <li class="primary-status">
                            <input type="radio" name="primary-image" data-bind="checked: $root.primaryImageId, attr: { value: id }, visible: false">
                            <span class="primary" data-bind="if: isPrimaryImage, click: function() {}"><a href="#" title="<?php echo esc_attr( __( 'This is the Primary Image', 'AWPCP' ) ); ?>"></a></span>
                            <span class="not-primary" data-bind="ifnot: isPrimaryImage, click: $root.setImageAsPrimary"><a href="#" title="<?php echo esc_attr( __( 'Set as Primary Image', 'AWPCP' ) ); ?>"></a></span>
                        </li>
                        <li><span class="spinner spinner-hidden"></span></li>
                    </ul>
                    <div class="primary-image-label" data-bind="visible: isPrimaryImage"><?php _e( 'Primary Image', 'AWPCP' ); ?></div>
                </li>
            </ul>
        </form>
    </div>

    <div class="awpcp-uploaded-attachments-container" data-bind="if: haveAttachments">
        <h3><?php _e( 'Existing Files', 'awpcp-attachments' ); ?></h3>

        <form>
            <ul class="awpcp-uploaded-files awpcp-uploaded-attachments clearfix" data-bind="foreach: attachments">
                <li data-bind="css: getFileClasses, attr: { id: getFileId }">
                    <a target="_bloank" data-bind="attr: { href: url, title: name }">
                        <img data-bind="attr: { src: iconUrl }" />
                        <span data-bind="text: name"></span>
                    </a>
                    <ul class="image-actions clearfix">
                        <li><span class="spinner spinner-hidden"></span></li>
                        <li class="status"><label><input type="checkbox" data-bind="checked: enabled"> <?php _e( 'Enabled', 'AWPCP' ); ?></label></li>
                        <li class="delete"><a href="#" title="<?php echo esc_attr( __( 'Delete File', 'AWPCP' ) ); ?>" data-bind="click: $root.deleteFile"></a></li>
                    </ul>
                </li>
            </ul>
        </form>
    </div>
</div>
