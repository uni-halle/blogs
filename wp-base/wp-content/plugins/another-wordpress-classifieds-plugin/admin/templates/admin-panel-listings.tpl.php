<form method="post" action="<?php echo esc_attr($this->url(array('action' => false))) ?>">
    <?php foreach ($this->params as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach ?>

    <?php $url = $this->url( array( 'action' => 'place-ad' ) ); ?>
    <?php $label = __( 'Place Ad', 'AWPCP' ); ?>
    <div><a class="button-primary" title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_attr( $url ); ?>" accesskey="s"><?php echo $label; ?></a></div>

    <?php echo $table->views() ?>
    <?php echo $table->search_box(__('Search Ads', 'AWPCP'), 'ads') ?>
    <?php echo $table->get_search_by_box() ?>
    <?php echo $table->display() ?>
</form>
