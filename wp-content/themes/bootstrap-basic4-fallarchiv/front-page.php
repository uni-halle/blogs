<?php
/** 
 * Template Name: Startseite
 * The page template file.<br>
 * This file works as display page content (post type "page") and its comments.
 * 
 * @package bootstrap-basic4
 */

$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
// begins template. -------------------------------------------------------------------------
get_header();
get_sidebar();  
 
 ?> 
                <main id="main" class="<?php echo $Bsb4Design->mainContainerClasses();?> mx-md-auto" role="main">
                    <?php
                    if (have_posts()) {
                        
                        while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/content', 'page');
                            echo "\n\n";

                            $Bsb4Design->pagination();
                            echo "\n\n";

                            // If comments are open or we have at least one comment, load up the comment template
                            if (comments_open() || '0' != get_comments_number()) {
                                comments_template();
                            }
                            echo "\n\n";
                        }// endwhile;

                        
                        unset($Bsb4Design);
                    } else {
                        get_template_part('template-parts/section', 'no-results');
                    }// endif;
                    ?> 
                </main>
<?php 
get_sidebar('right');
get_footer();