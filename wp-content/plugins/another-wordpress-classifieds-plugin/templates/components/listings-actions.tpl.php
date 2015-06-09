<div class="awpcp-listing-actions-component">
<?php foreach ( $actions as $action ): ?>
    <?php echo $action->render( $listing, $config ); ?>
<?php endforeach; ?>
</div>
