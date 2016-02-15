<?php
$coni_enable_section = get_theme_mod( 'coni_pricing_enable', true );
if ( $coni_enable_section || is_customize_preview() ) :
?>
<div id="pricing-section" class="pricing-section" <?php if( false == $coni_enable_section ): echo 'style="display: none;"'; endif ?>>
    <h2 class="section-title wow fadeIn"><?php echo esc_html( get_theme_mod( 'coni_pricing_title', __( 'Pricing', 'coni' ) ) ); ?></h2>

    <div class="cd-pricing-container cd-has-margins">

        <ul class="cd-pricing-list">

            <?php
            if ( is_active_sidebar( 'pricing-section' ) ){

                dynamic_sidebar( 'pricing-section' );

            }else{
                $widget_instance = array(
                    'title' => esc_html__( 'Basic', 'coni' ),
                    'currency' => esc_html__( '$', 'coni' ), 
                    'price' => esc_html__( '15', 'coni' ),
                    'duration' => esc_html_x( 'mo', 'Month abbreviation', 'coni' ),
                    'features' => sprintf( __( '<em>256MB</em> Memory%s<em>1</em> User%s<em>1</em> Website%s<em>1</em> Domain%s<em>Unlimited</em> Bandwidth%s<em>24/7</em> Support', 'coni' ), PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL ),
                    'link_title' => 'Select',
                    'link' => '#',
                    'featured' => 'false'
                    );
                the_widget( 'coni_Pricing', $widget_instance, array( 'before_widget' => '', 'after_widget' => '' ) );

                $widget_instance = array(
                    'title' => esc_html__( 'Popular', 'coni' ),
                    'currency' => esc_html__( '$', 'coni' ), 
                    'price' => esc_html__( '25', 'coni' ),
                    'duration' => esc_html_x( 'mo', 'Month abbreviation', 'coni' ),
                    'features' => sprintf( __( '<em>512MB</em> Memory%s<em>3</em> User%s<em>5</em> Website%s<em>7</em> Domain%s<em>Unlimited</em> Bandwidth%s<em>24/7</em> Support', 'coni' ), PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL ),
                    'link_title' => 'Select',
                    'link' => '#',
                    'featured' => 'true'
                    );
                the_widget( 'coni_Pricing', $widget_instance, array( 'before_widget' => '', 'after_widget' => '' ) );

                $widget_instance = array(
                    'title' => esc_html__( 'Premier', 'coni' ),
                    'currency' => esc_html__( '$', 'coni' ), 
                    'price' => esc_html__( '35', 'coni' ),
                    'duration' => esc_html_x( 'mo', 'Month abbreviation', 'coni' ),
                    'features' => sprintf( __( '<em>1024MB</em> Memory%s<em>5</em> User%s<em>10</em> Website%s<em>10</em> Domain%s<em>Unlimited</em> Bandwidth%s<em>24/7</em> Support', 'coni' ), PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL ),
                    'link_title' => 'Select',
                    'link' => '#',
                    'featured' => 'false'
                    );
                the_widget( 'coni_Pricing', $widget_instance, array( 'before_widget' => '', 'after_widget' => '' ) );
            }
            ?>
            
        </ul> <!-- .cd-pricing-list -->
    </div> <!-- .cd-pricing-container -->

</div><!-- pricing-section -->
<?php endif ?>