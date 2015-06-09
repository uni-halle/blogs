<?php if (!empty($message)) { echo $message; } ?>

<?php if ($action == 'confirm'): ?>

<p>
    <?php echo __( 'Thank you for using AWPCP. You have arrived at this page by clicking the Uninstall link. If you are certain you wish to uninstall the plugin, please click the link below to proceed.', 'AWPCP' ); ?>
</p>
<p><strong><?php echo __( 'PLEASE NOTE:  When you click the button below, ALL your data related to the plugin including your ads, images and everything else created by the plugin will be permanently deleted.', 'AWPCP' ); ?>&nbsp;<em><u><?php echo __( 'We cannot recover the data after you click this.', 'AWPCP' ); ?></u></em></strong>
</p>

<h3><?php echo esc_html( __( 'BEFORE YOU CLICK THE BUTTON BELOW &mdash; read carefully in case you want to extract your data first!', 'AWPCP' ) ); ?></h3>

<ol>
    <li><?php _e("If you plan to use the data created by the plugin please export the data from your mysql database before clicking the uninstall link.", "AWPCP"); ?></li>
    <li><?php _e("If you want to keep your user uploaded images, please download $dirname to your local drive for later use or rename the folder to something else so the uninstaller can bypass it.", "AWPCP"); ?></li>
</ol>

<p>
    <?php $href = add_query_arg(array('action' => 'uninstall'), $url); ?>
    <a class="button button-primary" href="<?php echo esc_url( $href ); ?>"><?php _e( 'Proceed with Uninstalling Another Wordpress Classifieds Plugin', 'AWPCP' ); ?></a>
</p>

<?php elseif ($action == 'uninstall'): ?>

<h3><?php _e("Almost done... one more step!", "AWPCP"); ?></h3>

<p>
    <?php $href = admin_url('plugins.php?deactivate=true'); ?>
    <a class="button button-primary" href="<?php echo $href ?>"><?php _e("Please click here to complete the uninstallation process", "AWPCP"); ?></a>
</p>

<?php endif ?>
