<?php

// Add scripts and stylesheet
function enqueue_styles() {
    global $themename, $themeslug, $options;
    wp_register_style($themeslug . 'storecss', get_template_directory_uri() . '/functions/css/theme-page-style.css');
    wp_enqueue_style($themeslug . 'storecss');
}

// Add page to the menu
function inkthemes_add_menu() {
    $page = add_theme_page('InkThemes Themes Page', 'InkThemes Themes', 'administrator', 'themes', 'inkthemes_page_init');
    add_action('admin_print_styles-' . $page, 'enqueue_styles');
}

add_action('admin_menu', 'inkthemes_add_menu');

// Create the page
function inkthemes_page_init() {
    $root = get_template_directory_uri();
    $pro_theme_url = 'http://www.inkthemes.com/wp-themes/colorway-wp-theme/';
    $pro_theme_demo = 'http://inkthemes.com/wpthemes/colorwayngo/';
    $site_url = 'http://www.inkthemes.com';
    ?>
    <div id="contain">
        <div id="themesheader">
            <div style="clear: both;"></div>
        </div>
        <div class="colorway_advert" id="colorway_advert">	
            <div class="colorway_block_wrapper">
                <h3><?php _e('Colorway Pro Version Features', 'colorway'); ?></h3>
                <div class="colorway_block block_two">				
                    <ul>						
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('8 Built in Color Schemes', 'colorway'); ?></li>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('PDF/Video Documentations', 'colorway'); ?></li>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('Background Images Bonus', 'colorway'); ?></li>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('Home Page Slider', 'colorway'); ?></li>
                    </ul>
                </div>
                <div class="colorway_block block_three">
                    <ul>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('Seo optimized Theme', 'colorway'); ?></li>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('Wpml Compatible Theme', 'colorway'); ?></li>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('Improved Zoom-box', 'colorway'); ?></li>
                        <li><div class="dashicons dashicons-controls-play"></div><?php _e('Gallery & Contact Page', 'colorway'); ?></li>						
                    </ul>
                </div>
                <a href="<?php echo esc_url($pro_theme_demo); ?>" target="blank" class="btn btn-demo" rel="nofollow"><?php _e('View Pro Demo', 'colorway'); ?></a>
                <a href="<?php echo esc_url($pro_theme_url); ?>" target="_blank" class="btn btn-upgrade" rel="nofollow"><?php _e('Upgrade to Pro', 'colorway'); ?></a>
            </div>
            <div class="colorway_block block_four">				
                <img class="colorway_img_responsive " src="<?php echo get_template_directory_uri(); ?>/images/advert.png">				
            </div>
        </div>
        <div class="clear"></div>
        <div id="container" class="theme_container">
            <div class="top_banner">
                <a href="<?php echo esc_url('http://www.inkthemes.com'); ?>" target="_blank"  rel="nofollow"><img src="<?php echo get_template_directory_uri(); ?>/functions/images/ink.png"/></a>
                <h1><?php _e('join inkthemes membership', 'colorway'); ?></h1>
                <div class="ruler"></div>
                <p><?php _e('Get 100% complete access to our entire collection of 47+ WordPress Themes for only $147', 'colorway'); ?></p>
                <a class="btn" target="_blank" href="<?php echo esc_url('http://www.inkthemes.com/offers/inkthemes-membership'); ?>" target="_blank"  rel="nofollow"><?php _e('GRAB THIS OFFER $147 ONLY', 'colorway'); ?></a>
            </div>       
            <div class="page_feature_wrapper">
                <div class="page_feature">
                    <h2><?php _e('What You Will Get', 'colorway'); ?></h2>
                    <div class="ruler"></div>
                    <div class="clear"></div>
                    <div class="feature_item one odd">
                        <img src="<?php echo get_template_directory_uri(); ?>/functions/images/ico1.png" />
                        <h3><?php _e('Get Access to All InkThemes', 'colorway'); ?></h3>
                        <p><?php _e('Launch your new site in few minutes with 47+ fully functional WordPress Themes.', 'colorway'); ?></p>
                    </div> 
                    <div class="feature_item two even">
                        <img src="<?php echo get_template_directory_uri(); ?>/functions/images/ico2.png" />
                        <h3><?php _e('Forum Support', 'colorway'); ?></h3>
                        <p><?php _e('Unparalleled technical support will make your website building hassle free.', 'colorway'); ?></p>
                    </div>
                    <div class="feature_item three odd">
                        <img src="<?php echo get_template_directory_uri(); ?>/functions/images/ico3.png" />
                        <h3><?php _e('Use on Multiple Domains', 'colorway'); ?></h3>
                        <p><?php _e('Use the themes on multiple domains for yourself or for your clients.', 'colorway'); ?></p>
                    </div>
                    <div class="feature_item four even">
                        <img src="<?php echo get_template_directory_uri(); ?>/functions/images/ico4.png" />
                        <h3><?php _e('Consistent Updates', 'colorway'); ?></h3>
                        <p><?php _e('We bring your needs into action with regular updates to bring the best themes for you.', 'colorway'); ?></p>
                    </div>
                    <div class="feature_item five odd">
                        <img src="<?php echo get_template_directory_uri(); ?>/functions/images/ico5.png" />
                        <h3><?php _e('Photoshop Files', 'colorway'); ?></h3>
                        <p><?php _e('Do design edits easily. Get layered photoshop files for all the themes.', 'colorway'); ?></p>
                    </div>
                    <div class="feature_item six even">
                        <img src="<?php echo get_template_directory_uri(); ?>/functions/images/ico6.png" />
                        <h3><?php _e('One Click Install', 'colorway'); ?></h3>
                        <p><?php _e('Preloaded with sample data. You are just a click away from building astounding website.', 'colorway'); ?></p>
                    </div>
                </div> 
                <div class="theme_feature">
                    <h2><?php _e('popular wordpress Themes from inkthemes', 'colorway'); ?></h2>
                    <div class="ruler"></div>
                    <ul>
                        <li>
                            <div class="item animated">
                                <img src="<?php echo get_template_directory_uri(); ?>/functions/images/th1.png" />
                                <span class="zoom"><a href="<?php echo esc_url('http://www.inkthemes.com/wp-themes/corporate-wordpress-theme/'); ?>" target="_blank"  rel="nofollow"><?php _e('Read More', 'colorway'); ?></a></span>
                            </div>
                            <p><?php _e('Business & Blog', 'colorway'); ?></p>
                            <span class="detail"><?php _e('WordPress Themes', 'colorway'); ?></span>
                        </li>
                        <li>
                            <div class="item animated">
                                <img src="<?php echo get_template_directory_uri(); ?>/functions/images/th2.png" />
                                <span class="zoom"><a href="<?php echo esc_url('http://www.inkthemes.com/wp-themes/geocraft-directory-listing-wordpress-theme/'); ?>" target="_blank"  rel="nofollow"><?php _e('Read More', 'colorway'); ?></a></span>
                            </div>
                            <p><?php _e('Directory & Classified', 'colorway'); ?></p>
                            <span class="detail"><?php _e('WordPress Themes', 'colorway'); ?></span>
                        </li>
                        <li>
                            <div class="item animated">
                                <img src="<?php echo get_template_directory_uri(); ?>/functions/images/th3.png" />
                                <span class="zoom"><a href="<?php echo esc_url('http://www.inkthemes.com/wp-themes/videocraft-wordpress-theme/'); ?>" target="_blank"  rel="nofollow"><?php _e('Read More', 'colorway'); ?></a></span>
                            </div>
                            <p><?php _e('Video & Membership', 'colorway'); ?></p>
                            <span class="detail"><?php _e('WordPress Themes', 'colorway'); ?></span>
                        </li>
                        <li>
                            <div class="item animated">
                                <img src="<?php echo get_template_directory_uri(); ?>/functions/images/th4.png" />
                                <span class="zoom"><a href="<?php echo esc_url('http://www.inkthemes.com/wp-themes/appointway-wordpress-theme/'); ?>" target="_blank"  rel="nofollow"><?php _e('Read More', 'colorway'); ?></a></span>
                            </div>
                            <p><?php _e('Appointment & Lead', 'colorway'); ?></p>
                            <span class="detail"><?php _e('WordPress Themes', 'colorway'); ?></span>
                        </li>
                        <li>
                            <div class="item animated">
                                <img src="<?php echo get_template_directory_uri(); ?>/functions/images/th5.png" />
                                <span class="zoom"><a href="<?php echo esc_url('http://www.inkthemes.com/wp-themes/wordpress-marketplace-theme/'); ?>" target="_blank"  rel="nofollow"><?php _e('Read More', 'colorway'); ?></a></span>
                            </div>
                            <p><?php _e('Ecommerce & Sales', 'colorway'); ?></p>
                            <span class="detail"><?php _e('WordPress Themes', 'colorway'); ?></span>
                        </li>
                    </ul>
                    <h4><a href="<?php echo esc_url('http://www.inkthemes.com/wp-themes/wordpress-marketplace-theme/'); ?>" target="_blank"  rel="nofollow"><?php _e('And many More...', 'colorway'); ?></a></h4>
                </div> 	
                <div class="bottom_feature">
                    <h1><?php _e('BUMPER BONUSES (OVER $820) FOR FREE', 'colorway'); ?></h1>
                </div> 
            </div> 
        </div>
    </div>
    <?php
}
