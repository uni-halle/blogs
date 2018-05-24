<?php
/**
 * The right sidebar.
 * 
 * @package bootstrap-basic4
 */
global $bootstrapbasic4_sidebar_right_size;
$bootstrapbasic4_sidebar_right_size = 3;
$bsb4Sidebars = new \BootstrapBasic4\Bsb4Sidebars();
?> 
<div id="sidebar-right" class="col-md-<?php echo $bootstrapbasic4_sidebar_right_size; ?>">
  <?php if ($bsb4Sidebars->shouldShowWissen($post->ID)) { ?>
    <aside id="sidebar-wissen">
      <hr>
      <div class="widget widget_nav_menu">
        <h5>Methodische Hinweise</h5>
        <?php
        wp_nav_menu(array(
            'menu' => 232 // Menu 'Wissen'
        ));
        ?>
      </div>
      <hr>
      <div class="widget widget_nav_menu">
        <h5>Themenschwerpunkte</h5>
        <?php
        wp_nav_menu(array(
            'menu' => 234 // Menu 'themen'
        ));
        ?>
      </div>
    </aside>
<?php } ?>
</div>
<?php
