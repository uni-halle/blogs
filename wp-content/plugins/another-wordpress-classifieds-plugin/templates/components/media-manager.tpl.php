<!-- Using template binding as workaround for conflict with imagesLoaded plugin
from Paul Irish. See https://github.com/drodenbaugh/awpcp/issues/979. -->
<div class="awpcp-media-manager" data-breakpoints-class-prefix="awpcp-media-manager" data-breakpoints='{"tiny":[0,420],"small":[420,620],"medium":[620,874], "large": [874,9999]}' data-bind="{ template: 'awpcp-media-manager-template' }"></div>

<script type="text/html" id="awpcp-media-manager-template">
    <div class="awpcp-uploaded-files-group awpcp-uploaded-images" data-bind="if: haveImages">
        <h3 class="awpcp-uploaded-files-group-title"><?php echo esc_html( __( 'Images', 'another-wordpress-classifieds-plugin' ) ); ?></h3>
        <ul class="awpcp-uploaded-files-list clearfix" data-bind="foreach: { data: images, as: 'image' }">
            <li data-bind="css: $root.getFileCSSClasses( image ), attr: { id: $root.getFileId( image ) }">
                <div class="awpcp-uploaded-file-thumbnail-container">
                    <img data-bind="attr: { src: thumbnailUrl }">
                    <div class="awpcp-progress-bar-container" data-bind="if: shouldShowProgressBar">
                        <div class="awpcp-progress-bar" data-bind="style: { width: progress() + '%' }"></div>
                    </div>
                </div>
                <ul class="awpcp-uploaded-file-actions clearfix">
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-change-status-action">
                        <label>
                            <input type="checkbox" data-bind="checked: enabled"> <?php echo esc_html( __( 'Enabled', 'another-wordpress-classifieds-plugin' ) ); ?>
                        </label>
                    </li>
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-set-as-primary-action">
                        <span>
                            <a href="#" title="<?php echo esc_attr( __( 'This is the Primary Image', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: isPrimary(), click: function() {}"></a>
                            <a href="#" title="<?php echo esc_attr( __( 'Set as Primary Image', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: !isPrimary(), click: $root.setFileAsPrimary"></a>
                        </span>
                    </li>
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-delete-action"><a title="<?php echo esc_attr( __( 'Delete Image', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.deleteFile"></a></li>
                    <li data-bind="visible: $root.showAdminActions()">
                        <div class="awpcp-uploaded-file-action awpcp-uploaded-file-toggle-action awpcp-uploaded-file-reject-action">
                            <a class="awpcp-toggle-button" title="<?php echo esc_attr( __( 'Reject Image', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.rejectFile, css: { 'awpcp-toggle-on': !isApproved(), 'awpcp-toggle-off': isApproved() }"></a>
                        </div>
                        <div class="awpcp-uploaded-file-action awpcp-uploaded-file-toggle-action awpcp-uploaded-file-approve-action">
                            <a class="awpcp-toggle-button" title="<?php echo esc_attr( __( 'Approve Image', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.approveFile, css: { 'awpcp-toggle-on': isApproved(), 'awpcp-toggle-off': !isApproved() }"></a>
                        </div>
                    </li>
                </ul>
                <span class="awpcp-spinner awpcp-spinner-visible awpcp-uploaded-file-spinner" data-bind="visible: isBeingModified"></span>
                <div class="awpcp-uploaded-file-label awpcp-uploaded-file-primary-label" data-bind="visible: isPrimary"><?php echo esc_html(  __( 'Primary Image', 'another-wordpress-classifieds-plugin' ) ); ?></div>
                <div class="awpcp-uploaded-file-label awpcp-uploaded-file-rejected-label" data-bind="visible: isRejected"><?php echo esc_html(  __( 'Rejected', 'another-wordpress-classifieds-plugin' ) ); ?></div>
            </li>
        </ul>
    </div>

    <div class="awpcp-uploaded-files-group awpcp-uploaded-videos" data-bind="if: haveVideos">
        <h3 class="awpcp-uploaded-files-group-title"><?php echo esc_html( __( 'Videos', 'another-wordpress-classifieds-plugin' ) ); ?></h3>
        <ul class="awpcp-uploaded-files-list clearfix" data-bind="foreach: { data: videos, as: 'video' }">
            <li data-bind="css: $root.getFileCSSClasses( video ), attr: { id: $root.getFileId( video ) }">
                <div class="awpcp-uploaded-file-thumbnail-container">
                    <img data-bind="attr: { src: thumbnailUrl }" width="<?php echo esc_attr( $thumbnails_width ); ?>px">
                    <div class="awpcp-progress-bar-container" data-bind="if: shouldShowProgressBar">
                        <div class="awpcp-progress-bar" data-bind="style: { width: progress() + '%' }"></div>
                    </div>
                </div>
                <ul class="awpcp-uploaded-file-actions clearfix">
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-change-status-action">
                        <label>
                            <input type="checkbox" data-bind="checked: enabled"> <?php echo esc_html( __( 'Enabled', 'another-wordpress-classifieds-plugin' ) ); ?>
                        </label>
                    </li>
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-set-as-primary-action">
                        <span>
                            <a href="#" title="<?php echo esc_attr( __( 'This is the Primary Video', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: isPrimary(), click: function() {}"></a>
                            <a href="#" title="<?php echo esc_attr( __( 'Set as Primary Video', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: !isPrimary(), click: $root.setFileAsPrimary"></a>
                        </span>
                    </li>
                    <li class="awpcp-uploaded-video-delete-action awpcp-uploaded-file-delete-action awpcp-uploaded-file-action"><a title="<?php echo esc_attr( __( 'Delete Video', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.deleteFile"></a></li>
                    <li data-bind="visible: $root.showAdminActions()">
                        <div class="awpcp-uploaded-file-action awpcp-uploaded-file-toggle-action awpcp-uploaded-file-reject-action">
                            <a class="awpcp-toggle-button" title="<?php echo esc_attr( __( 'Reject Video', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.rejectFile, css: { 'awpcp-toggle-on': !isApproved(), 'awpcp-toggle-off': isApproved() }"></a>
                        </div>
                        <div class="awpcp-uploaded-file-action awpcp-uploaded-file-toggle-action awpcp-uploaded-file-approve-action">
                            <a class="awpcp-toggle-button" title="<?php echo esc_attr( __( 'Approve Video', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.approveFile, css: { 'awpcp-toggle-on': isApproved(), 'awpcp-toggle-off': !isApproved() }"></a>
                        </div>
                    </li>
                </ul>
                <span class="awpcp-spinner awpcp-spinner-visible awpcp-uploaded-file-spinner" data-bind="visible: isBeingModified"></span>
                <div class="awpcp-uploaded-file-label awpcp-uploaded-file-primary-label" data-bind="visible: isPrimary"><?php echo esc_html(  __( 'Primary Video', 'another-wordpress-classifieds-plugin' ) ); ?></div>
                <div class="awpcp-uploaded-file-label awpcp-uploaded-file-rejected-label" data-bind="visible: isRejected"><?php echo esc_html(  __( 'Rejected', 'another-wordpress-classifieds-plugin' ) ); ?></div>
            </li>
        </ul>
    </div>

    <div class="awpcp-uploaded-files-group awpcp-uploaded-files" data-bind="if: haveOtherFiles">
        <h3 class="awpcp-uploaded-files-group-title"><?php echo esc_html( __( 'Other Files', 'another-wordpress-classifieds-plugin' ) ); ?></h3>
        <ul class="awpcp-uploaded-files-list clearfix" data-bind="foreach: { data: others, as: 'file' }">
            <li data-bind="css: $root.getFileCSSClasses( file ), attr: { id: $root.getFileId( file ) }">
                <div class="awpcp-uploaded-file-thumbnail-container">
                    <a target="_blank">
                        <img data-bind="attr: { src: iconUrl }" />
                        <span data-bind="text: name"></span>
                    </a>
                    <div class="awpcp-progress-bar-container" data-bind="if: shouldShowProgressBar">
                        <div class="awpcp-progress-bar" data-bind="style: { width: progress() + '%' }"></div>
                    </div>
                </div>
                <ul class="awpcp-uploaded-file-actions clearfix">
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-change-status-action">
                        <label>
                            <input type="checkbox" data-bind="checked: enabled"> <?php echo esc_html( __( 'Enabled', 'another-wordpress-classifieds-plugin' ) ); ?>
                        </label>
                    </li>
                    <li class="awpcp-uploaded-file-action awpcp-uploaded-file-delete-action"><a title="<?php echo esc_attr( __( 'Delete File', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.deleteFile"></a></li>
                    <li data-bind="visible: $root.showAdminActions()">
                        <div class="awpcp-uploaded-file-action awpcp-uploaded-file-toggle-action awpcp-uploaded-file-reject-action">
                            <a class="awpcp-toggle-button" title="<?php echo esc_attr( __( 'Reject File', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.rejectFile, css: { 'awpcp-toggle-on': !isApproved(), 'awpcp-toggle-off': isApproved() }"></a>
                        </div>
                        <div class="awpcp-uploaded-file-action awpcp-uploaded-file-toggle-action awpcp-uploaded-file-approve-action">
                            <a class="awpcp-toggle-button" title="<?php echo esc_attr( __( 'Approve File', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="click: $root.approveFile, css: { 'awpcp-toggle-on': isApproved(), 'awpcp-toggle-off': !isApproved() }"></a>
                        </div>
                    </li>
                </ul>
                <span class="awpcp-spinner awpcp-spinner-visible awpcp-uploaded-file-spinner" data-bind="visible: isBeingModified"></span>
                <div class="awpcp-uploaded-file-label awpcp-uploaded-file-rejected-label" data-bind="visible: isRejected"><?php echo esc_html(  __( 'Rejected', 'another-wordpress-classifieds-plugin' ) ); ?></div>
            </li>
        </ul>
    </div>
</script>
