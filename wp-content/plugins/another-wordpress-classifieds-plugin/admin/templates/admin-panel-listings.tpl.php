<form method="post" action="<?php echo esc_attr($this->url(array('action' => false))) ?>">
    <?php echo awpcp_html_hidden_fields( $this->params ); ?>

    <?php $url = $this->url( array( 'action' => 'place-ad' ) ); ?>
    <?php $label = __( 'Place Ad', 'another-wordpress-classifieds-plugin' ); ?>
    <div><a class="button-primary" title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_attr( $url ); ?>" accesskey="s"><?php echo $label; ?></a></div>

    <?php echo $table->views() ?>

    <div class="awpcp-search-container clearfix">
    <?php echo $table->search_box(__('Search Ads', 'another-wordpress-classifieds-plugin'), 'ads') ?>
    <?php echo $table->get_search_by_box() ?>
    </div>

    <?php echo $table->display() ?>
</form>
