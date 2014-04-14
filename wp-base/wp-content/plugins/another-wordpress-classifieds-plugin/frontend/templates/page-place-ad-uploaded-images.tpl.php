    <h3><?php _ex( 'Existing Images', 'upload files step', 'AWPCP' ); ?></h3>

    <p>
        <?php _ex( 'To choose one of the existing images as the primary image for your Ad, click the corresponding check icon (green means that is the primary image).', 'upload files step', 'AWPCP' ); ?>
        <?php if ( $images_left > 0 ): ?>
        <br><?php _ex( 'You can also upload a new image and mark it as primary using the fields below.', 'upload files step', 'AWPCP' ); ?>
        <?php endif ?>
    </p>

    <ul class="uploaded-images clearfix" id="uploaded-images">
    <?php foreach ( $images as $image ): ?>

        <?php $class = array($image->is_primary ? 'primary-image' : '', $image->enabled ? 'enabled' : 'disabled') ?>

        <li class="<?php echo join( ' ', array_filter( $class ) ); ?>">
            <img src="<?php echo $image->get_url( 'thumbnail' ); ?>" />

            <?php
                $is_primary_set = $is_primary_set || $image->is_primary;
                $url = add_query_arg( 'image', $image->id, $url );
            ?>

            <ul class="image-actions clearfix">
                <?php if ( $actions['enable'] && ! $image->enabled ): ?>
                <li class="label"><?php echo _x( 'Disabled', 'upload images', 'AWPCP' ); ?></li>
                <li class="enable">
                    <?php $href = add_query_arg( array( 'step' => 'upload-images', 'a' => 'enable-picture' ), $url ); ?>
                    <?php echo sprintf( $link, $href, _x( 'Enable Image (make visible)', 'upload images step', 'AWPCP' ) ); ?>
                </li>
                <?php endif ?>

                <?php if ( $actions['disable'] && $image->enabled ): ?>
                <li class="label"><?php echo _x( 'Enabled', 'upload images', 'AWPCP' ); ?></li>
                <li class="disable">
                    <?php $href = add_query_arg( array( 'step' => 'upload-images', 'a' => 'disable-picture' ), $url ); ?>
                    <?php echo sprintf( $link, $href, _x( 'Disable Image (make invisible)', 'upload images step', 'AWPCP' ) ); ?>
                </li>
                <?php endif ?>

                <li class="delete">
                    <?php $href = add_query_arg( array( 'step' => 'upload-images', 'a' => 'delete-picture' ), $url ); ?>
                    <?php echo sprintf( $link, $href, _x( 'Delete Image', 'upload images', 'AWPCP' ) ); ?>
                </li>

                <?php if ($image->is_primary): ?>
                <li class="primary">
                    <?php $href = add_query_arg( array( 'step' => 'upload-images', 'a' => 'make-not-primary' ), $url ); ?>
                    <?php echo sprintf( $link, $href, _x( 'Unset as Primary Image', 'upload images', 'AWPCP' ) ); ?>
                </li>
                <?php else: ?>
                <li class="not-primary">
                    <?php $href = add_query_arg( array( 'step' => 'upload-images', 'a' => 'make-primary' ), $url ); ?>
                    <?php echo sprintf( $link, $href, _x( 'Set as Primary Image', 'upload images', 'AWPCP' ) ); ?>
                </li>
                <?php endif ?>
            </ul>
        </li>
    <?php endforeach ?>
    </ul>