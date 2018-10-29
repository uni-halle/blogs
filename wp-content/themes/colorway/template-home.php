<?php
/**
 * Template Name: Home Page
 */
?>
<?php
get_header();
?>

<!--Start Slider-->
<?php
if (inkthemes_get_option('colorway_home_page_slider') != 'off') {
    get_template_part('templates/slit_slider');
} else {
    ?>
    <div class="heading_section"></div>
<?php }
?>
<div class="clearfix"></div>
<!--End Slider-->
<!--Start Content Grid-->
<div class="row content">
    <div class="content-wrapper">
        <div class="home inkthemes_mainheading">
            <center>
                <h2>
                    <?php //if (inkthemes_get_option('inkthemes_mainheading') != '') { ?>
                    <?php echo esc_html(inkthemes_get_option('inkthemes_mainheading', __('Design is not just what it looks like and feels like. Design is how it works.', 'colorway'))); ?>
                    <?php //}  ?>
                </h2>
            </center>
        </div>
        <!--        <div class="clearfix"></div>-->
        <?php if (inkthemes_get_option('feature_on_off', 'on') != 'off') { ?>
            <div id="content">

                <?php
                $left = '';
                $right = '';
                $feature2 = '';

                $n = inkthemes_get_option('colorway_feature_select', 4);

                $class = '';
                switch ($n) {
                    case '1':
                        $class = 'col-md-12';
                        $left = 'col-md-4';
                        $right = 'col-md-8';
                        $feature = 'feature-area1';
                        break;
                    case '2':
                        $class = 'col-md-6 col-sm-6';
                        $feature = 'feature-area2';
                        break;
                    case '3':
                        $class = 'col-md-4 col-sm-6';
                        $feature = 'feature-area3';
                        break;
                    case '4':
                        $class = 'col-md-3 col-sm-3';
                        break;
                    default:
                        $class = 'col-md-3 col-sm-3';
                        break;
                }

                if ((inkthemes_get_option('inkthemes_fimg1') || inkthemes_get_option('inkthemes_fimg2') || inkthemes_get_option('inkthemes_fimg3') || inkthemes_get_option('inkthemes_fimg4')) || inkthemes_get_option('colorway_dummy_data', 'off') != 'off') {
                    ?>
                    <div class="row columns">

                        <?php for ($i = 1; $i <= $n; $i++) { ?>
                            <div class="<?php echo esc_attr($class); ?>">
                                <div class="one_fourth<?php echo esc_attr($i) ?> animated inkthemes_fimg<?php echo esc_attr($i); ?> <?php
                                if (!isset($feature) || $feature != '') {
                                    echo esc_attr($feature);
                                }
                                ?>" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">
                                    <a href="<?php echo esc_url(inkthemes_get_option('inkthemes_link' . $i, '#')); ?>" class="bigthumbs">
                                        <div class='img_thumb_feature <?php
                                        if (!isset($left) || $left != '') {
                                            echo esc_attr($left);
                                        }
                                        ?>'><span></span>
                                            <img src="<?php echo esc_url(inkthemes_get_option('inkthemes_fimg' . $i, 'https://inkthemes.com/wpthemes/wp-content/uploads/sites/5/2014/08/Small-2.jpg')); ?>"/>
                                        </div>
                                    </a>
                                    <div class="content <?php
                                    if (!isset($right) || $right != '') {
                                        echo esc_attr($right);
                                    }
                                    ?>">
                                        <h6 class="feat_head inkthemes_headline<?php echo esc_attr($i); ?>"><a href="<?php echo esc_url(inkthemes_get_option('inkthemes_link' . $i, '#')); ?>"><?php echo esc_html(inkthemes_get_option('inkthemes_headline' . $i, __('Power of Easiness', 'colorway'))); ?></a></h6>
                                        <div class="inkthemes_feature<?php echo esc_attr($i) ?>"><p><?php echo esc_html(inkthemes_get_option('inkthemes_feature' . $i, __('This ColorWay Wordpress Theme gives you the easiness of building your site without any coding skills required.', 'colorway'))); ?></p></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>  
                    <div class="border-feature"></div>
                <?php } else {
                    ?>
                    <div><?php esc_html_e('Please go to Appearance-> Customize-> Feature Area Settings and add atleast one image in any feature box to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></div>
                     <div class="border-feature"></div>
                <?php } ?> 
                <div class="clearfix"></div>
            <?php } ?>
            <?php if (inkthemes_get_option('colorway_home_page_blog_post') != 'off') { ?>
                <div class="row feature_blog_content">
                    <div class="col-md-6 testimonial_div animated fade_left">
                        <?php if (is_active_sidebar('home-page-right-feature-widget-area')) : ?>
                            <div class="sidebar home">
                                <?php dynamic_sidebar('home-page-right-feature-widget-area'); ?>
                            </div>
                        <?php else : ?>			
                            <div class="feature_widget">
                                <h4 class="inkthemes_col_head"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_col_head', __('Widgetized Area', 'colorway')))); ?></h4>
                                <?php if (inkthemes_get_option('inkthemes_widget_desc') != '') { ?>
                                    <div class="feature_widget_desc"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_col_desc'))); ?></div>
                                    <?php
                                } else {
                                    if (inkthemes_get_option('colorway_dummy_data') == 'on') {
                                        ?>
                                        <div class="feature_widget_desc">
                                            <img class="widget_img" src="https://inkthemes.com/wpthemes/wp-content/uploads/sites/5/2014/08/Slider-4.jpg" />
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div><?php esc_html_e('Please go to Appearance-> Widgets and add atleast one widget to the Home Page Left Feature Widget Area to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>	
                    </div>
                    <div class="col-md-6 blog_slider">  
                        <div class="blog_slider_wrapper animated fade_right">
                            <div class="flexslider_blog">
                                <h4 class="inkthemes_blog_head"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_blog_head', __('Latest From The Blog', 'colorway')))); ?></h4>
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
                                                    
                                                }
                                                ?>
                                            </div>
                                            <div class="flex_content"> 
                                                <h6><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e('Permanent Link to ', 'colorway') . the_title_attribute(); ?>"><?php the_title(); ?></a></h6>
                                                <p><?php the_excerpt(); ?></p>

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
                 <div class="border-feature"></div>
                <div class="clearfix"></div>
            <?php } ?>
            <?php if (inkthemes_get_option('colorway_testimonial_status', 'on') == 'on') { ?>


                <div class="testimonial_item_container"> 
                    <div class="testimonial_heading_container animated fading"> 
                        <h2 class="inkthemes_testimonial_main_head"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_main_head', __('Our Customer Love Us', 'colorway')))); ?></h2>
                        <h4 class="inkthemes_testimonial_main_desc"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_main_desc', __('Read the reviews of some of our  Customers.', 'colorway')))); ?></h4>
                    </div>
                    <?php
                    if ((inkthemes_get_option('inkthemes_testimonial_img') || inkthemes_get_option('inkthemes_testimonial_img_2') || inkthemes_get_option('inkthemes_testimonial_img_3')) || inkthemes_get_option('colorway_dummy_data') == 'on') {
                        ?>
                        <div class="testimonial_item_content"> 
                            <div class="col-md-4 col-sm-4 testimonial_col_wrap">
                                <div class="testimonial_item animated fading inkthemes_testimonial center" style="-webkit-animation-delay: .4s; -moz-animation-delay: .4s; -o-animation-delay: .4s; -ms-animation-delay: .4s;">  
                                    <p class="testm_descbox"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial', __('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway')))); ?></p>
                                    <div class="testimonial_item_inner inkthemes_testimonial_img">  
                                        <img src="<?php echo esc_url(stripslashes(inkthemes_get_option('inkthemes_testimonial_img', get_template_directory_uri() . "/assets/images/testimonial.jpg"))); ?>"  />
                                        <div class="testimonial_name_wrapper">  
                                            <p><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_name', __('Robin Chang', 'colorway')))); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 testimonial_col_wrap">
                                <div class="testimonial_item animated fading inkthemes_testimonial_2 center" style="-webkit-animation-delay: .8s; -moz-animation-delay: .8s; -o-animation-delay: .8s; -ms-animation-delay: .8s;">    
                                    <p class="testm_descbox"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_2', __('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway')))); ?></p>
                                    <div class="testimonial_item_inner inkthemes_testimonial_img_2">  
                                        <img src="<?php echo esc_url(stripslashes(inkthemes_get_option('inkthemes_testimonial_img_2', get_template_directory_uri() . "/assets/images/testimonial.jpg"))); ?>"  />
                                        <div class="testimonial_name_wrapper">  
                                            <p><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_name_2', __('Rown Wisely', 'colorway')))); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 testimonial_col_wrap">
                                <div class="testimonial_item animated fading inkthemes_testimonial_3 center" style="-webkit-animation-delay: 1.2s; -moz-animation-delay: 1.2s; -o-animation-delay: 1.2s; -ms-animation-delay: 1.2s;">    
                                    <p class="testm_descbox"><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_3', __('Create and Manage multiple contact forms using single dashboard. You can show Form on any single/every page of your website. You can also collect payments, leads and much more...', 'colorway')))); ?></p>
                                    <div class="testimonial_item_inner inkthemes_testimonial_img_3">  
                                        <img src="<?php echo esc_url(stripslashes(inkthemes_get_option('inkthemes_testimonial_img_3', get_template_directory_uri() . "/assets/images/testimonial.jpg"))); ?>"  />
                                        <div class="testimonial_name_wrapper">  
                                            <p><?php echo esc_html(stripslashes(inkthemes_get_option('inkthemes_testimonial_name_3', __('Jex Polack', 'colorway')))); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <p><?php esc_html_e('Please go to Appearance-> Customize-> Testimonial Area Settings and add atleast one image in any Testimonial box to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></p>
                    <?php }
                    ?>        
                </div>
                <div class="clearfix"></div>
            <?php } ?>
        </div>

    </div>
    <div class="clearfix"></div>
    <!--End Content Grid-->
</div>
</div>
</div>

<!--End Container Div-->
<?php get_footer(); ?>
