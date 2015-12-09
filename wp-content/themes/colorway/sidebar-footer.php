<?php
/**
 * The Footer widget areas.
 *
 */
?>
<?php
/* The footer widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
if (!is_active_sidebar('first-footer-widget-area') && !is_active_sidebar('second-footer-widget-area') && !is_active_sidebar('third-footer-widget-area') && !is_active_sidebar('fourth-footer-widget-area')
)
    return;
// If we get this far, we have widgets. Let do this.
?>
<div class="grid_6 alpha">
    <div class="common animated" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">
        <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
            <?php dynamic_sidebar('first-footer-widget-area'); ?>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6">
    <div class="common animated" style="-webkit-animation-delay: .8s; -moz-animation-delay: .8s; -o-animation-delay: .8s; -ms-animation-delay: .8s;">
        <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
            <?php dynamic_sidebar('second-footer-widget-area'); ?>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6">
    <div class="common animated" style="-webkit-animation-delay: 1.2s; -moz-animation-delay: 1.2s; -o-animation-delay: 1.2s; -ms-animation-delay: 1.2s;">
        <?php if (is_active_sidebar('third-footer-widget-area')) : ?>
            <?php dynamic_sidebar('third-footer-widget-area'); ?>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6 omega">
    <div class="common animated" style="-webkit-animation-delay: 1.6s; -moz-animation-delay: 1.6s; -o-animation-delay: 1.6s; -ms-animation-delay: 1.6s;">
        <?php if (is_active_sidebar('fourth-footer-widget-area')) : ?>
            <?php dynamic_sidebar('fourth-footer-widget-area'); ?>
        <?php endif; ?>
    </div>
</div>
