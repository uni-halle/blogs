<div class="postbox">
    <div class="inside">
        <ul class="awpcp-admin-manage-links">
            <li class="label"><?php echo esc_html( __( 'Manage Links', 'AWPCP' ) ); ?>:</li>
            <li><a href="<?php echo $urls['view-listing']; ?>"><?php echo esc_html( __( 'View Listing' ) ); ?></a></li>
            <li><a href="<?php echo $urls['listings']; ?>"><?php echo esc_html( __( 'Return to Listings' ) ); ?></a></li>
        </ul>
    </div>
</div>

<div class="postbox">
    <div class="inside">

    <?php include( AWPCP_DIR . '/templates/components/media-center.tpl.php' ); ?>

    </div>
</div>
