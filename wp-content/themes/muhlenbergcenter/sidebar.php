<?php
/**
 * The sidebar contains the primary menu
 *
 */

if ( has_nav_menu( 'primary' ) ) : ?>
<div class="contain-to-grid sticky">

    <nav class="top-bar" data-topbar data-options="mobile_show_parent_link: false" role="navigation">
        <ul class="title-area">
            <li class="name"><!--<?php bloginfo( 'name' ); ?>--></li>
            <li class="toggle-topbar menu-icon">
                <a href="#">
                    <span><?php _e( 'Menu', 'muhlenbergcenter' ); ?></span>
                </a>
            </li>
        </ul>
        <section class="top-bar-section">
            <ul class="right">
                <li>
                    <a href="#" title="<?php _e( 'Visit us at facebook', 'muhlenbergcenter' ); ?>">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/icon-facebook.png" alt="Facebook">
                    </a>
                </li>
            </ul>
            <?php
                wp_nav_menu( array(
                    'theme_location'  => 'primary',
                    'menu_class'      => 'left',
                    'container'       => '',
                    /*'container_class' => 'top-bar-section',*/
                    'walker'          => new muhlenbergcenter_Walker_Nav_Menu(),
                ) );
            ?>
        </section>
    </nav>

</div>
<?php endif; ?>
