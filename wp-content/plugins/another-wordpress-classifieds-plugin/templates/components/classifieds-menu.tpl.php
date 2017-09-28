<ul class="awpcp-classifieds-menu awpcp-clearfix" data-breakpoints='{"tiny": [0,400], "small": [400,500]}' data-breakpoints-class-prefix="awpcp-classifieds-menu">
<?php foreach ( $buttons as $id => $button ): ?>
    <li class="awpcp-classifieds-menu--menu-item awpcp-classifieds-menu--<?php echo esc_attr( $id ); ?>-menu-item">
        <a class="awpcp-classifieds-menu--menu-item-link button" href="<?php echo esc_url( $button['url'] ); ?>"><?php echo $button['title']; ?></a>
    </li>
<?php endforeach; ?>
</ul>
