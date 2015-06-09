<div class="postbox">
    <div class="inside">
        <ul class="awpcp-admin-manage-links">
            <li class="label"><?php _x( 'Manage Links', 'media-manager', 'AWPCP' ); ?>:</li>
            <li><a href="<?php echo $urls['view-listing']; ?>"><?php _x( 'View Listing', 'media-manager', 'AWPCP' ); ?></a></li>
            <li><a href="<?php echo $urls['listings']; ?>"><?php _x( 'Return to Listings', 'media-manager', 'AWPCP' ); ?></a></li>
        </ul>
    </div>
</div>

<div class="postbox">
    <div class="inside">

<h3><?php echo sprintf( _x( 'Upload files for Ad %s.', 'media manager', 'AWPCP' ), '&laquo;' . $ad->get_title() . '&raquo;' ); ?></h3>

<form class="awpcp-media-manager-upload-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="awpcp_action" value="add_image">
    <input type="hidden" name="action" value="add-image">
    <?php echo wp_nonce_field('awpcp_upload_image'); ?>

    <input type="file" name="awpcp_add_file" data-bind="value: file">
    <input class="button" type="submit" name="awpcp_submit_file" value="<?php echo _x( 'Add File', 'media manager', 'AWPCP' ); ?>" data-bind="enable: file">
</form>

    </div>
</div>

<div class="postbox">
    <div class="inside">

<h3><?php echo sprintf( _x( 'Existing files for Ad %s.', 'media manager', 'AWPCP' ), '&laquo;' . $ad->get_title() . '&raquo;' ); ?></h3>

<?php echo awpcp_attachment_background_color_explanation(); ?>

<?php foreach ( $groups as $group => $files ): ?>

<h4><?php echo esc_html( strtoupper( $group ) ); ?></h4>

<ul class="awpcp-media-manager-file-list clearfix">
    <?php foreach ( $files as $file ): ?>

    <li class="<?php echo esc_attr( awpcp_get_file_extension( $file->name ) ); ?>">
        <div class="awpcp-media-manager-file clearfix <?php echo strtolower( $file->status ); ?> <?php echo $file->enabled ? 'enabled' : 'disabled'; ?>">

            <div class="awpcp-media-manager-file-thumbnail">
        <?php if ( $file->is_image() ): ?>
                <img src="<?php echo $file->get_url( 'thumbnail' ); ?>" />
        <?php else: ?>
                <a href="<?php echo $file->get_url(); ?>" title="<?php echo esc_attr( $file->name ); ?>" target="_blank">
                    <img src="<?php echo $file->get_icon_url(); ?>" />
                </a>
                <a href="<?php echo $file->get_url(); ?>" title="<?php echo esc_attr( $file->name ); ?>" target="_blank"><?php echo esc_html( $file->name ); ?></a>
        <?php endif; ?>
            </div>

            <form action="<?php echo $urls['endpoint']; ?>" method="post" name="<?php echo $file->id; ?>">
            <?php foreach( $hidden as $name => $value ): ?>
                <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
            <?php endforeach; ?>
                <input type="hidden" name="picid" value="<?php echo $file->id; ?>" />
                <input type="hidden" name="action" value="" />

                <ul class="awcp-media-manager-file-actions">
            <?php foreach( $actions as $action => $label ): ?>

                <?php if ( ( ! $file->is_image() || $file->is_primary() ) && $action == 'set-primary-image' ) continue; ?>

                <?php if ( $file->enabled && $action == 'approvepic' ) continue; ?>

                <?php if ( ! $file->enabled && $action == 'rejectpic' ) continue; ?>

                <?php if ( $file->is_approved() && $action == 'approve-file' ) continue; ?>

                <?php if ( ( $file->is_awaiting_approval() || $file->is_rejected() ) && $action == 'reject-file' ) continue; ?>

                    <li><a class="<?php echo $action; ?>" href="#" data-action="<?php echo $action; ?>" title="<?php echo $label; ?>"></a></li>
            <?php endforeach; ?>
            <?php if ( $file->is_image() && $file->is_primary() ): ?>
                    <li><span class="primary-image"></span></li>
            <?php endif; ?>
                </ul>
            </form>

        </div>
    </li>

    <?php endforeach; ?>
</ul>

<?php endforeach; ?>

    </div>
</div>
