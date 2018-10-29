<?php
/**
 * The right sidebar.
 * 
 * @package bootstrap-basic4
 */
$bsb4Sidebars = new \BootstrapBasic4\Bsb4Sidebars();
// @Anke: hier die Klassen eintragen
$sidebarContainerClasses = 'col-md-4 col-xl-3';
?> 
<?php if ($bsb4Sidebars->shouldShowWissen($post->ID)) { ?>
  <div id="sidebar-right" class="<?php echo $sidebarContainerClasses; ?>">
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
      <!--
            <hr>
            <div class="widget widget_nav_menu">
              <h5>Themenschwerpunkte</h5>
      <?php
      wp_nav_menu(array(
          'menu' => 234 // Menu 'themen'
      ));
      ?>
            </div>
      -->
    </aside>
  </div>
<?php
}
