<?php if (!empty($message)) { echo $message; } ?>

<?php if ($action == 'confirm'): ?>

<p>
    <?php echo __( 'Thank you for using AWPCP. You have arrived at this page by clicking the Uninstall link. If you are certain you wish to uninstall the plugin, please click the link below to proceed.', 'another-wordpress-classifieds-plugin' ); ?>
</p>
<p><strong><?php echo __( 'PLEASE NOTE:  When you click the button below, ALL your data related to the plugin including your ads, images and everything else created by the plugin will be permanently deleted.', 'another-wordpress-classifieds-plugin' ); ?>&nbsp;<em><u><?php echo __( 'We cannot recover the data after you click this.', 'another-wordpress-classifieds-plugin' ); ?></u></em></strong>
</p>

<h3><?php echo esc_html( __( 'BEFORE YOU CLICK THE BUTTON BELOW &mdash; read carefully in case you want to extract your data first!', 'another-wordpress-classifieds-plugin' ) ); ?></h3>

<ol>
    <li><?php _e("If you plan to use the data created by the plugin please export the data from your mysql database before clicking the uninstall link.", 'another-wordpress-classifieds-plugin'); ?></li>
    <?php $message = __( 'If you want to keep your user uploaded images, please download <dirname> to your local drive for later use or rename the folder to something else so the uninstaller can bypass it.', 'another-wordpress-classifieds-plugin' ); ?>
    <li><?php echo str_replace( '<dirname>', '<code>' . $dirname . '</code>', $message ); ?></li>
</ol>

<p>
    <?php $href = add_query_arg(array('action' => 'uninstall'), $url); ?>
    <a class="button button-primary" href="<?php echo esc_url( $href ); ?>"><?php _e( 'Proceed with Uninstalling Another Wordpress Classifieds Plugin', 'another-wordpress-classifieds-plugin' ); ?></a>
</p>

<?php elseif ($action == 'uninstall'): ?>

<h3><?php _e("Almost done... one more step!", 'another-wordpress-classifieds-plugin'); ?></h3>

<p>
    <?php $href = admin_url('plugins.php?deactivate=true'); ?>
    <a class="button button-primary" href="<?php echo $href ?>"><?php _e("Please click here to complete the uninstallation process", 'another-wordpress-classifieds-plugin'); ?></a>
</p>

<?php endif ?>
