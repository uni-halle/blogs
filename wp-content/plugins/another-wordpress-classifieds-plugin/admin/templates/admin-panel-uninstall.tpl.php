<?php if (!empty($message)) { echo $message; } ?>

<?php if ($action == 'confirm'): ?>

<?php _e("Thank you for using AWPCP. You have arrived at this page by clicking the Uninstall link. If you are certain you wish to uninstall the plugin, please click the link below to proceed. Please note that all your data related to the plugin, your ads, images and everything else created by the plugin will be destroyed.", "AWPCP"); ?>
<h3><?php _e("Important Information","AWPCP"); ?></h3>

<blockquote>
    <p>1. <?php _e("If you plan to use the data created by the plugin please export the data from your mysql database before clicking the uninstall link.", "AWPCP"); ?></p>
    <p>2. <?php _e("If you want to keep your user uploaded images, please download $dirname to your local drive for later use or rename the folder to something else so the uninstaller can bypass it.", "AWPCP"); ?></p>
</blockquote>

<?php $href = add_query_arg(array('action' => 'uninstall'), $url); ?>
<p>
<a class="button button-primary" href="<?php echo $href ?>">
    <?php _e("Proceed with Uninstalling Another Wordpress Classifieds Plugin", "AWPCP"); ?>
</a>
</p>

<?php elseif ($action == 'uninstall'): ?>

<?php $href = admin_url('plugins.php?deactivate=true'); ?>
<p><?php _e("Almost done... one more step!", "AWPCP"); ?></p>

<p>
    <a class="button button-primary" href="<?php echo $href ?>">
        <?php _e("Please click here to complete the uninstallation process", "AWPCP"); ?>
    </a>
</p>

<?php endif ?>
