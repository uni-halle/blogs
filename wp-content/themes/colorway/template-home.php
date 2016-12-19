<?php
/**
 * Template Name: Home Page
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
                    <?php // if (inkthemes_get_option('inkthemes_mainheading') != '') { ?>
                    <?php echo inkthemes_get_option('inkthemes_mainheading', __('Design is not just what it looks like and feels like. Design is how it works.', 'colorway')); ?>
                    <?php // } ?>
                </h2>
            </center>
        </div>
        <div class="clear"></div>
        <div  id="content">
            <?php
            if ((inkthemes_get_option('inkthemes_fimg1') || inkthemes_get_option('inkthemes_fimg2') || inkthemes_get_option('inkthemes_fimg3') || inkthemes_get_option('inkthemes_fimg4')) || inkthemes_get_option('colorway_dummy_data') == 'on') {
                ?>
                <div class="columns">
                    <div class="one_fourth animated" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">
                        <a href="<?php echo inkthemes_get_option('inkthemes_link1', '#'); ?>" class="bigthumbs">
                            <div class='img_thumb_feature'><span></span>
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg1', get_template_directory_uri() . '/images/1.jpg'); ?>"/>
                            </div>
                        </a>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link1', '#'); ?>"><?php echo inkthemes_get_option('inkthemes_headline1', __('Power of Easiness', 'colorway')); ?></a></h2>
                        <p><?php echo inkthemes_get_option('inkthemes_feature1', __('This Colorway Wordpress Theme gives you the easiness of building your site without any coding skills required.', 'colorway')); ?></p>
                    </div>
                    <div class="one_fourth middle animated" style="-webkit-animation-delay: .8s; -moz-animation-delay: .8s; -o-animation-delay: .8s; -ms-animation-delay: .8s;">
                        <a href="<?php echo inkthemes_get_option('inkthemes_link2', '#'); ?>" class="bigthumbs">
                            <div class='img_thumb_feature'><span></span>
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg2', get_template_directory_uri() . '/images/2.jpg'); ?>"/>
                            </div>
                        </a>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link2', '#'); ?>"><?php echo inkthemes_get_option('inkthemes_headline2', __('Power of Speed', 'colorway')); ?></a></h2>
                        <p><?php echo inkthemes_get_option('inkthemes_feature2', __('The Colorway Wordpress Theme is highly optimized for Speed. So that your website opens faster than any similar themes.', 'colorway')); ?></p>
                    </div>
                    <div class="one_fourth animated" style="-webkit-animation-delay: 1.2s; -moz-animation-delay: 1.2s; -o-animation-delay: 1.2s; -ms-animation-delay: 1.2s;">
                        <a href="<?php echo inkthemes_get_option('inkthemes_link3', '#'); ?>" class="bigthumbs">
                            <div class='img_thumb_feature'><span></span>			
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg3', get_template_directory_uri() . '/images/3.jpg'); ?>"/>
                            </div>
                        </a>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link3', '#'); ?>"><?php echo inkthemes_get_option('inkthemes_headline3', __('Power of SEO', 'colorway')); ?></a></h2>
                        <p><?php echo inkthemes_get_option('inkthemes_feature3', __('Visitors to the Website are very highly desirable. With the SEO Optimized Themes, You get more traffic from Google.', 'colorway')); ?></p>
                    </div>
                    <div class="one_fourth last animated" style="-webkit-animation-delay: 1.6s; -moz-animation-delay: 1.6s; -o-animation-delay: 1.6s; -ms-animation-delay: 1.6s;">
                        <a href="<?php echo inkthemes_get_option('inkthemes_link4', '#'); ?>" class="bigthumbs">
                            <div class='img_thumb_feature'><span></span>			
                                <img src="<?php echo inkthemes_get_option('inkthemes_fimg4', get_template_directory_uri() . '/images/4.jpg'); ?>"/>
                            </div>
                        </a>
                        <h2><a href="<?php echo inkthemes_get_option('inkthemes_link4', '#'); ?>"><?php echo inkthemes_get_option('inkthemes_headline3', __('Ready Contact Form', 'colorway')); ?></a></h2>
                        <p><?php echo inkthemes_get_option('inkthemes_feature4', __('Let your visitors easily contact you. The builtin readymade contact form makes it easier for clients to contact.', 'colorway')); ?></p>
                    </div>
                </div>   
            <?php } else {
                ?>
                <div><?php _e('Please go to Appearance-> Customize-> Feature Area Settings and add atleast one image in any feature box to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></div>
            <?php } ?>
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
                            <h2><?php echo stripslashes(inkthemes_get_option('inkthemes_widget_head', __('Widgetized Area', 'colorway'))); ?></h2>
                            <?php if (inkthemes_get_option('inkthemes_widget_desc') != '') { ?>
                                <div class="feature_widget_desc"><?php echo stripslashes(inkthemes_get_option('inkthemes_widget_desc')); ?></div>
                                <?php
                            } else {
                                if (inkthemes_get_option('colorway_dummy_data') == 'on') {
                                    ?>
                                    <div class="feature_widget_desc">
                                        <img class="widget_img" src="<?php echo get_template_directory_uri(); ?>/images/widget_img.png" />
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div><?php _e('Please go to Appearance-> Widgets and add atleast one widget to the Home Page Left Feature Widget Area to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></div>
                                <?php
                                }
                            }
                            ?>
                        </div>
    <?php endif; ?>	
                </div>
                <div class=" grid_12 blog_slider omega">  
                    <div class="blog_slider_wrapper animated fade_right">
                        <div class="flexslider_blog">
                            <h2><?php echo stripslashes(inkthemes_get_option('inkthemes_blog_head', __('Latest From The Blog', 'colorway'))); ?></h2>
                            <ul class="slides">			
                                <?php
                                $post_limit = stripslashes(inkthemes_get_option('inkthemes_blog_posts', get_option('posts_per_page')));
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
                    <h2><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_main_head', __('Our Customer Love Us', 'colorway'))); ?></h2>
                    <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_main_desc', __('Read the reviews of some of our  Customers.', 'colorway'))); ?></p>
                </div>
                <?php
                if ((inkthemes_get_option('inkthemes_testimonial_img') || inkthemes_get_option('inkthemes_testimonial_img_2') || inkthemes_get_option('inkthemes_testimonial_img_3')) || inkthemes_get_option('colorway_dummy_data') == 'on') {
                    ?>
                    <div class="testimonial_item_content"> 
                        <div class="testimonial_item animated fading" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">  
                            <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial', __('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway'))); ?></p>
                            <div class="testimonial_item_inner">  
                                <img src="<?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_img', get_template_directory_uri() . "/images/testimonial.jpg")); ?>"  />
                                <div class="testimonial_name_wrapper">  
                                    <span><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_name', __('Robin Chang', 'colorway'))); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial_item animated fading" style="-webkit-animation-delay: .8s; -moz-animation-delay: .8s; -o-animation-delay: .8s; -ms-animation-delay: .8s;">    
                            <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_2', __('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway'))); ?></p>
                            <div class="testimonial_item_inner">  
                                <img src="<?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_img_2', get_template_directory_uri() . "/images/testimonial.jpg")); ?>"  />
                                <div class="testimonial_name_wrapper">  
                                    <span><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_name_2', __('Rown Wisely', 'colorway'))); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial_item animated fading" style="-webkit-animation-delay: 1.2s; -moz-animation-delay: 1.2s; -o-animation-delay: 1.2s; -ms-animation-delay: 1.2s;">    
                            <p><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_3', __('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway'))); ?></p>
                            <div class="testimonial_item_inner">  
                                <img src="<?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_img_3', get_template_directory_uri() . "/images/testimonial.jpg")); ?>"  />
                                <div class="testimonial_name_wrapper">  
                                    <span><?php echo stripslashes(inkthemes_get_option('inkthemes_testimonial_name_3', __('Jex Polack', 'colorway'))); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <p><?php _e('Please go to Appearance-> Customize-> Testimonial Area Settings and add atleast one image in any Testimonial box to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></p>
                <?php }
                ?>        
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
