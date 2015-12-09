<?php
/*
  /**
 * The main front page file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */
?>
<?php get_header(); ?>
<!--Start Slider-->
<?php
if (inkthemes_get_option('colorway_home_page_slider') != 'off') {
    get_template_part('slit_slider');
} else {
    ?>
    <div class="heading_section"></div>
<?php }
?>
<div class="clear"></div>
<!--End Slider-->
<!--Start Content Grid-->
<div class="grid_24 content">
    <div class="content-wrapper">
        <div class="content-info home">
            <center>
                <h2>
                    <?php if (inkthemes_get_option('inkthemes_mainheading') != '') { ?>
                        <?php echo inkthemes_get_option('inkthemes_mainheading'); ?>
                    <?php } else { ?>
                        <?php _e('Design is not just what it looks like and feels like. Design is how it works.', 'colorway'); ?>
                    <?php } ?>
                </h2>
            </center>
        </div>
        <div class="clear"></div>
        <div  id="content">
            <div class="columns">
                <div class="one_fourth animated" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">
                    <a href="<?php echo inkthemes_get_option('inkthemes_link1'); ?>" class="bigthumbs">
                        <div class='img_thumb_feature'><span></span>			
                            <?php if (inkthemes_get_option('inkthemes_fimg1') != '') { ?>
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg1'); ?>"/>
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/1.jpg"/>
                            <?php } ?>                    
                        </div>
                    </a>
                    <?php if (inkthemes_get_option('inkthemes_headline1') != '') { ?>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link1'); ?>"><?php echo inkthemes_get_option('inkthemes_headline1'); ?></a></h2>
                    <?php } else { ?>
                        <h2><a href="#"><?php _e('Power of Easiness', 'colorway'); ?></a></h2>
                    <?php } ?>
                    <?php if (inkthemes_get_option('inkthemes_feature1') != '') { ?>
                        <p><?php echo inkthemes_get_option('inkthemes_feature1'); ?></p>
                    <?php } else { ?>
                        <p><?php _e('This Colorway Wordpress Theme gives you the easiness of building your site without any coding skills required.', 'colorway'); ?></p>
                    <?php } ?>
                </div>
                <div class="one_fourth middle animated" style="-webkit-animation-delay: .8s; -moz-animation-delay: .8s; -o-animation-delay: .8s; -ms-animation-delay: .8s;">
                    <a href="<?php echo inkthemes_get_option('inkthemes_link2'); ?>" class="bigthumbs">
                        <div class='img_thumb_feature'><span></span>

                            <?php if (inkthemes_get_option('inkthemes_fimg2') != '') { ?>
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg2'); ?>"/>
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/2.jpg"/>
                            <?php } ?>
                        </div>
                    </a>
                    <?php if (inkthemes_get_option('inkthemes_headline2') != '') { ?>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link2'); ?>"><?php echo inkthemes_get_option('inkthemes_headline2'); ?></a></h2>
                    <?php } else { ?>
                        <h2><a href="#"><?php _e('Power of Speed', 'colorway'); ?></a></h2>
                    <?php } ?>
                    <?php if (inkthemes_get_option('inkthemes_feature2') != '') { ?>
                        <p><?php echo inkthemes_get_option('inkthemes_feature2'); ?></p>
                    <?php } else { ?>
                        <p><?php _e('The Colorway Wordpress Theme is highly optimized for Speed. So that your website opens faster than any similar themes.', 'colorway'); ?></p>
                    <?php } ?>
                </div>
                <div class="one_fourth animated" style="-webkit-animation-delay: 1.2s; -moz-animation-delay: 1.2s; -o-animation-delay: 1.2s; -ms-animation-delay: 1.2s;">
                    <a href="<?php echo inkthemes_get_option('inkthemes_link3'); ?>" class="bigthumbs"><div class='img_thumb_feature'><span></span>
                            <?php if (inkthemes_get_option('inkthemes_fimg3') != '') { ?>
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg3'); ?>"/>
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/3.jpg"/>
                            <?php } ?>
                        </div></a>
                    <?php if (inkthemes_get_option('inkthemes_headline3') != '') { ?>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link3'); ?>"><?php echo inkthemes_get_option('inkthemes_headline3'); ?></a></h2>
                    <?php } else { ?>
                        <h2><a href="#"><?php _e('Power of SEO', 'colorway'); ?></a></h2>
                    <?php } ?>
                    <?php if (inkthemes_get_option('inkthemes_feature3') != '') { ?>
                        <p><?php echo inkthemes_get_option('inkthemes_feature3'); ?></p>
                    <?php } else { ?>
                        <p><?php _e('Visitors to the Website are very highly desirable. With the SEO Optimized Themes, You get more traffic from Google.', 'colorway'); ?></p>
                    <?php } ?>
                </div>
                <div class="one_fourth last animated" style="-webkit-animation-delay: 1.6s; -moz-animation-delay: 1.6s; -o-animation-delay: 1.6s; -ms-animation-delay: 1.6s;">
                    <a href="<?php echo inkthemes_get_option('inkthemes_link4'); ?>" class="bigthumbs">
                        <div class='img_thumb_feature'><span></span>
                            <?php if (inkthemes_get_option('inkthemes_fimg4') != '') { ?>
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg4'); ?>"/>
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/4.jpg"/>
                            <?php } ?>
                        </div></a>
                    <?php if (inkthemes_get_option('inkthemes_headline4') != '') { ?>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link4'); ?>"><?php echo inkthemes_get_option('inkthemes_headline4'); ?></a></h2>
                    <?php } else { ?>
                        <h2><a href="#"><?php _e('Ready Contact Form', 'colorway'); ?></a></h2>
                    <?php } ?>
                    <?php if (inkthemes_get_option('inkthemes_feature4') != '') { ?>
                        <p><?php echo inkthemes_get_option('inkthemes_feature4'); ?></p>
                    <?php } else { ?>
                        <p><?php _e('Let your visitors easily contact you. The builtin readymade contact form makes it easier for clients to contact.', 'colorway'); ?></p>
                    <?php } ?>
                </div>
            </div>            
        </div>        
        <div class="clear"></div>
        <?php if (inkthemes_get_option('colorway_home_page_blog_post') != 'off') { ?>
            <div class="feature_blog_content">
                <div class=" grid_12 testimonial_div alpha animated fade_left">
                    <?php if (is_active_sidebar('home-page-right-feature-widget-area')) : ?>
                        <div class="sidebar home">
                            <?php dynamic_sidebar('home-page-right-feature-widget-area'); ?>
                        </div>
                    <?php else : ?>			
                        <div class="feature_widget">
                            <?php if (inkthemes_get_option('inkthemes_widget_head') != '') { ?>
                                <h2><?php echo stripslashes(inkthemes_get_option('inkthemes_widget_head')); ?></h2>
                            <?php } else { ?>
                                <h2><?php _e('Widgetized Area', 'colorway'); ?></h2>
                            <?php } ?>  
                            <?php if (inkthemes_get_option('inkthemes_widget_desc') != '') { ?>
                                <div class="feature_widget_desc"><?php echo stripslashes(inkthemes_get_option('inkthemes_widget_desc')); ?></div>
                            <?php } else { ?>
                                <div class="feature_widget_desc">
                                    <img class="widget_img" src="<?php echo get_template_directory_uri(); ?>/images/widget_img.png" />
                                </div>
                            <?php } ?>
                        </div>
                    <?php endif; ?>	
                </div>
                <div class=" grid_12 blog_slider omega">  
                    <div class="blog_slider_wrapper animated fade_right">
                        <div class="flexslider_blog">
                            <?php if (inkthemes_get_option('inkthemes_blog_head') != '') { ?>
                                <h2><?php echo stripslashes(inkthemes_get_option('inkthemes_blog_head')); ?></h2>
                            <?php } else { ?>
                                <h2><?php _e('Latest From The Blog', 'colorway'); ?></h2>
                            <?php } ?>  
                            <ul class="slides">			
                                <?php
                                if (inkthemes_get_option('inkthemes_blog_posts') != '') {
                                    $post_limit = stripslashes(inkthemes_get_option('inkthemes_blog_posts'));
                                } else {
                                    $post_limit = get_option('posts_per_page');
                                }
                                $args = array(
                                    'post_status' => 'publish',
                                    'posts_per_page' => $post_limit,
                                    'order' => 'DESC'
                                );
                                $query = new WP_Query($args);
                                ?>
                                <?php while ($query->have_posts()) : $query->the_post(); ?>
                                    <li class="blog_item">                
                                        <div class="flex_thumbnail"> <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <div class='img_thumb'><span></span>
                                                        <?php the_post_thumbnail('colorway_custom_size', array('class' => 'postimg')); ?>
                                                    </div>
                                                </a>
                                                <?php
                                            } else {
                                                echo inkthemes_main_image();
                                            }
                                            ?>
                                        </div>
                                        <div class="flex_content"> 
                                            <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to ', 'colorway') . the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                                            <?php echo inkthemes_custom_trim_excerpt(40); ?>
                                            <div class="clear"></div>
                                            <a class="read_more" href="<?php the_permalink() ?>"><?php _e('Continue Reading &rarr;', 'colorway') ?></a>		
                                        </div>
                                    </li>
                                    <?php
                                endwhile;
                                // Reset Query
                                wp_reset_query();
                                ?>  		  
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (inkthemes_get_option('colorway_testimonial_status', 'on') == 'on') { ?>
            <div class="clear"></div>
            <div class="testimonial_item_container"> 
                <div class="testimonial_heading_container animated fading"> 
                    <?php if (inkthemes_get_option('inkthemes_testimonial_main_head') != '') { ?>
                        <h2><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_main_head')); ?></h2>
                    <?php } else { ?>	
                        <h2><?php _e('Our Customer Love Us', 'colorway'); ?></h2>
                    <?php } ?>
                    <?php if (inkthemes_get_option('inkthemes_testimonial_main_desc') != '') { ?>
                        <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_main_desc')); ?></p>
                    <?php } else { ?>
                        <p><?php _e('Read the reviews of some of our  Customers.', 'colorway'); ?></p>
                    <?php } ?>
                </div>
                <div class="testimonial_item_content"> 
                    <div class="testimonial_item animated fading" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">  
                        <?php if (inkthemes_get_option('inkthemes_testimonial') != '') { ?>
                            <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial')); ?></p>
                        <?php } else { ?>	
                            <p><?php _e('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway'); ?></p>
                        <?php } ?>
                        <div class="testimonial_item_inner">  
                            <?php if (inkthemes_get_option('inkthemes_testimonial_img') != '') { ?>
                                <img src="<?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_img')); ?>"  />
                            <?php } else { ?>	
                                <img src="<?php echo get_template_directory_uri(); ?>/images/testimonial.jpg" />
                            <?php } ?>
                            <div class="testimonial_name_wrapper">  
                                <?php if (inkthemes_get_option('inkthemes_testimonial_name') != '') { ?>
                                    <span><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_name')); ?></span>
                                <?php } else { ?>	
                                    <span><?php _e('Robin Chang', 'colorway'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial_item animated fading" style="-webkit-animation-delay: .8s; -moz-animation-delay: .8s; -o-animation-delay: .8s; -ms-animation-delay: .8s;">    
                        <?php if (inkthemes_get_option('inkthemes_testimonial_2') != '') { ?>
                            <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_2')); ?></p>
                        <?php } else { ?>	
                            <p><?php _e('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway'); ?></p>
                        <?php } ?>
                        <div class="testimonial_item_inner">  
                            <?php if (inkthemes_get_option('inkthemes_testimonial_img_2') != '') { ?>
                                <img src="<?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_img_2')); ?>"  />
                            <?php } else { ?>	
                                <img src="<?php echo get_template_directory_uri(); ?>/images/testimonial.jpg" />
                            <?php } ?>
                            <div class="testimonial_name_wrapper">  
                                <?php if (inkthemes_get_option('inkthemes_testimonial_name_2') != '') { ?>
                                    <span><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_name_2')); ?></span>
                                <?php } else { ?>	
                                    <span><?php _e('Rown Wiesly', 'colorway'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial_item animated fading" style="-webkit-animation-delay: 1.2s; -moz-animation-delay: 1.2s; -o-animation-delay: 1.2s; -ms-animation-delay: 1.2s;">    
                        <?php if (inkthemes_get_option('inkthemes_testimonial_3') != '') { ?>
                            <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_3')); ?></p>
                        <?php } else { ?>	
                            <p><?php _e('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway'); ?></p>
                        <?php } ?>
                        <div class="testimonial_item_inner">  
                            <?php if (inkthemes_get_option('inkthemes_testimonial_img_3') != '') { ?>
                                <img src="<?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_img_3')); ?>"  />
                            <?php } else { ?>	
                                <img src="<?php echo get_template_directory_uri(); ?>/images/testimonial.jpg" />
                            <?php } ?>
                            <div class="testimonial_name_wrapper">  
                                <?php if (inkthemes_get_option('inkthemes_testimonial_name_3') != '') { ?>
                                    <span><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_name_3')); ?></span>
                                <?php } else { ?>	
                                    <span><?php _e('Jex Polack', 'colorway'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<!--End Content Grid-->
</div>
<!--End Container Div-->
<?php get_footer(); ?>
