<?php
/**
 * The main template file.
 * 
 * To override home page (for listing latest post) add home.php into the theme.<br>
 * If front page displays is set to static, the index.php file will be use.<br>
 * If front-page.php exists, it will be override any home page file such as home.php, index.php.<br>
 * To learn more please go to https://developer.wordpress.org/themes/basics/template-hierarchy/ .
 * 
 * @package bootstrap-basic4
 */


// begins template. -------------------------------------------------------------------------
get_header();
get_sidebar();
?> 
                <main id="main" class="col-md-<?php echo \BootstrapBasic4\Bootstrap4Utilities::getMainColumnSize(); ?> site-main" role="main">
<!--
	                <div id="finden">
	                    <div class="faq unit">
							<div class="faq question fokus">Erweiterte Suche</div> 
							<div class="faq answer container-fluid"> 

								<?php
								$options = get_option( 'hat_interpretation' );
								$checked = $options['1'];
								$current = 0; 
								$echo = true;
								?>
								<input name="hat_interpretation[1]" value="0" <?php checked( $checked, $current, $echo ); ?>
					
							</div>
	                    </div>
                    </div>
-->
                    
                    <?php
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
//                             the_meta();
                            get_template_part('template-parts/content', get_post_format());
                        }// endwhile;

                        $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
                        $Bsb4Design->pagination();
                        unset($Bsb4Design);
                    } else {
                        get_template_part('template-parts/section', 'no-results');
                    }// endif;
                    ?> 
                </main>
<?php
get_sidebar('right');
get_footer();