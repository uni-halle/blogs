<div id="quick-start-guide-notice" class="update-nag awpcp-sticky-notice awpcp-sticky-error clearfix">
    <?php $url = awpcp_get_admin_upgrade_url(); ?>
    <p class="align-center"><strong>
        <?php _e( 'Manual Upgrade Required', 'AWPCP' ); ?></strong>
        <br>
        <?php _e( 'AWPCP features are currently disabled because the plugin needs you to perform a manual upgrade before continuing.', 'AWPCP' ); ?>
        <?php echo sprintf( _x( 'Please go to the AWPCP Admin section to %s.', 'Please go to the AWPCP Admin section to <button>Upgrade</button>.', 'AWPCP' ), sprintf( '<a class="button button-primary" href="%s">%s</a>', $url, __( 'Upgrade', 'AWPCP' ) ) ); ?></a>
    </p>
</div>