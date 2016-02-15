<?php
$testimonial_image = wp_get_attachment_image_src( absint( get_theme_mod( 'coni_testimonial_image' ) ), 'full' );
$testimonial_image = $testimonial_image[0];
if ( empty( $testimonial_image ) ) {
	$testimonial_image = get_template_directory_uri() . '/images/img.jpg';
}
?>
<?php
$coni_enable_section = get_theme_mod( 'coni_testimonial_enable', true );
if ( $coni_enable_section || is_customize_preview() ) :
?>
<div id="testimonials-section" class="testimonials-section" style="background-image: url(<?php echo $testimonial_image; ?>);" <?php if( false == $coni_enable_section ): echo 'style="display: none;"'; endif ?>>
    <div class="testimonials-wrap js-flickity wow fadeIn" data-flickity-options='{ "cellAlign": "center", "contain": true, "prevNextButtons": false, "pageDots": true, "autoPlay": 5000 }'>
    	<?php
        $args = array(
            'post_type' => 'jetpack-testimonial',
            'posts_per_page' => -1
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) { $the_query->the_post();

                echo '<div class="testimonial">';
                    echo '<blockquote cite="' . esc_attr( get_the_title() ) . '">';
                    	the_content();
                    echo '</blockquote>';
                    the_title( '<p class="testimonial-cite">', '</p>' );
                echo '</div>';

            }//while

        }else{// if have posts

            for ( $k = 0 ; $k < 3; $k++ ){
                echo '<div class="testimonial"><blockquote cite="John">';
                echo esc_html__( '"We are an agency passionate about what we do, providing a great service to small businesses. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus."', 'coni' );
                echo '</blockquote>';
                $wp_kses_args = array(
                    'span' => array(),
                );
                $testimonial_cite = wp_kses( __( 'John Smith <span>CEO, Coni Inc.</span>', 'coni' ), $wp_kses_args );
                echo '<p class="testimonial-cite">' . $testimonial_cite . '</p>';
                echo '</div>';
            }

        }
        wp_reset_postdata();
        ?>
    </div><!-- testimonials-wrap -->

</div><!-- testimonials-section -->
<?php endif ?>