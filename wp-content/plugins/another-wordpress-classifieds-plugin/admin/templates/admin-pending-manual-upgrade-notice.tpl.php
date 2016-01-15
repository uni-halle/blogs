<div class="update-nag awpcp-update-nag clearfix">
    <?php $url = awpcp_get_admin_upgrade_url(); ?>
    <div>
        <span class="awpcp-update-nag-title"><?php echo awpcp_admin_page_title( __( 'Manual Upgrade Required', 'another-wordpress-classifieds-plugin' ) ); ?></span>
        <p class="align-center">
            <?php $message = __( 'AWPCP features are currently disabled because the plugin needs you to perform a manual upgrade before continuing. Please <upgrade-link>go to the Classifieds admin section section to Upgrade</a> or click the button below.', 'another-wordpress-classifieds-plugin' ); ?>
            <?php echo str_replace( '<upgrade-link>', sprintf( '<a href="%s">', $url ), $message ); ?>
        </p>
        <p>
            <?php echo sprintf( '<a class="button button-primary" href="%s">%s</a>', $url, __( 'Upgrade', 'another-wordpress-classifieds-plugin' ) ); ?>
        </p>
    </div>
</div>
