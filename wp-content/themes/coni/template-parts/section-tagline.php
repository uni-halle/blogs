<?php
$bck_color = get_theme_mod( 'coni_tagline_bck_color', '#68d2ae' );
?>
<?php
$coni_enable_section = get_theme_mod( 'coni_tagline_enable', true );
if ( $coni_enable_section || is_customize_preview() ) :
?>
<div id="tagline-section" class="tagline-section" style="background-color: <?php echo esc_attr( $bck_color ); ?>;" <?php if( false == $coni_enable_section ): echo 'style="display: none;"'; endif ?>>
    <a href="<?php echo esc_url( get_theme_mod( 'coni_tagline_link_url', '#' ) ); ?>" class="wow fadeIn">
    <h2 class="tagline"><?php echo esc_html( get_theme_mod( 'coni_tagline_text', __( 'Start captivating the attention of your client', 'coni' ) ) ); ?></h2>
    <span><?php echo esc_html( get_theme_mod( 'coni_tagline_sub_text', __( 'Cras justo odio, dapibus ac facilisis in, egestas eget quam', 'coni' ) ) ); ?></span>
    </a>
</div><!-- tagline-section -->
<?php endif ?>