<?php
$footer = '';
$a = get_option('footer-layout', 85);
switch ($a) {
    case 'inline-footer':
        $footer = 'footer_inline ';
        $copyright = "copy-text col-md-4";
        $social_icon = "col-md-3";
        $navigation = "col-md-5";
        break;
    case 'diable-footer':
        $footer = "display";
        $footer_main = "main-margin";
        break;
    case 'center-footer':
        $footer = 'footer-center ';
        $fnavi = 'foot-navig ';
        $copyright = "col-md-12";
        $social_icon = "col-md-12";
        $navigation = "col-md-12 ";
        break;
    default:
        $footer = 'footer_inline';
        $copyright = "copy-text col-md-4";
        $social_icon = "col-md-3";
        $navigation = "col-md-5";
        $fnavi ='';
}
$b = get_option('container-layout');
switch ($b) {
    case 'container':
        $container = 'container';
        break;
    case 'fullwidth-container':
        $container = 'container-fluid foot-content';
        break;
    default:
             $container = 'container-fluid foot-content';
}
?>
</div>
</div>
</div>
<!--Start Footer container-->
<?php if (inkthemes_get_option('footer_col_on_off') != 'off') { ?>
    <div class="<?php echo esc_attr($container); ?> footer-container <?php
    if ($footer_main != '') {
        echo esc_attr($footer_main);
    } else {
        echo esc_attr($footer_main);
    }
    ?>" style='background-repeat: no-repeat; background-size: cover;background-image:url("<?php
         if (inkthemes_get_option('inkthemes_footerbg') != '') {
             echo esc_attr(inkthemes_get_option('inkthemes_footerbg'));
         }
         ?>")'>
    <div class="<?php if($container == 'container-fluid foot-content'){ echo 'container'; } ?>">
            <div class="row footer">
                <?php
                /* A sidebar in the footer? Yep. You can can customize
                 * your footer with four columns of widgets.
                 */
                get_template_part('templates/footer/sidebar', 'footer');
                ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php } ?>
<!--End footer container-->

<?php if ($footer != '') { ?> 

    <!--Start footer navigation-->
    <div class="<?php echo esc_attr($container); ?> footer-navi <?php
    
    if ($footer != '') {
        echo esc_attr($footer);
    } else {
        echo esc_attr($footer);
    }
    ?>">
      <div class="<?php if($container == 'container-fluid foot-content'){ echo 'container'; } ?>">
            <div class="row">
                <div id="inline1" class="navigation <?php
                if ($navigation != '') {
                    echo esc_attr($navigation);
                } else {
                    echo esc_attr($navigation);
                }
                ?>">
                    <ul class="footer_des">
                        <li><a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(get_bloginfo('name')); ?> -
                                <?php bloginfo('description'); ?>
                            </a></li>
                    </ul>                
                </div>
                <div id="inline2" class="social-icons colorway_twitter <?php
                if ($social_icon != '') {
                    echo esc_attr($social_icon);
                } else {
                    echo esc_attr($social_icon);
                }
                ?>">
                         <?php if (inkthemes_get_option('colorway_twitter') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('colorway_twitter')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/twitter-icon.png" alt="twitter" title="Twitter"/></a>
                    <?php } else { ?>
                    <?php } ?>
                    <?php if (inkthemes_get_option('colorway_facebook') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('colorway_facebook')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/facebook-icon.png" alt="facebook" title="facebook"/></a>
                    <?php } else { ?>
                    <?php } ?>
                    <?php if (inkthemes_get_option('colorway_rss') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('colorway_rss')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/rss-icon.png" alt="rss" title="rss"/></a>
                    <?php } else { ?>
                    <?php } ?>
                    <?php if (inkthemes_get_option('colorway_linkedin') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('colorway_linkedin')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/linked.png" alt="linkedin" title="linkedin"/></a>
                    <?php } else { ?>
                    <?php } ?>
                    <?php if (inkthemes_get_option('colorway_stumble') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('colorway_stumble')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/stumbleupon.png" alt="stumble" title="stumble"/></a>
                    <?php } else { ?>
                    <?php } ?>
                    <?php if (inkthemes_get_option('colorway_digg') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('colorway_digg')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/digg.png" alt="digg" title="digg"/></a>
                    <?php } ?>

                    <?php if (inkthemes_get_option('inkthemes_flickr') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('inkthemes_flickr')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/flickr.png" alt="flickr" title="flickr"/></a>
                    <?php } ?>

                    <?php if (inkthemes_get_option('inkthemes_instagram') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('inkthemes_instagram')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/Instagram.png" alt="insta" title="insta"/></a>
                    <?php } ?>

                    <?php if (inkthemes_get_option('inkthemes_pinterest') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('inkthemes_pinterest')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/Pinterest.png" alt="pinterest" title="pinterest"/></a>
                    <?php } ?>

                    <?php if (inkthemes_get_option('inkthemes_tumblr') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('inkthemes_tumblr')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/Tumblr.png" alt="tmblr" title="tmblr"/></a>
                    <?php } ?>

                    <?php if (inkthemes_get_option('inkthemes_youtube') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('inkthemes_youtube')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/youtube.png" alt="tmblr" title="tmblr"/></a>
                    <?php } ?>

                    <?php if (inkthemes_get_option('inkthemes_google') != '') { ?>
                        <a href="<?php echo esc_attr(inkthemes_get_option('inkthemes_google')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/google.png" alt="google" title="google"/></a>
                    <?php } ?>

                </div>
                <div id="inline3" class="right-navi <?php
                if ($fnavi != '') {
                    echo esc_attr($fnavi);
                } else {
                    echo esc_attr($fnavi);
                }if (!isset($copyright) || $copyright != '') {
                    echo esc_attr($copyright);
                }
                ?>">               
                    <p><a href="<?php echo esc_url('https://www.inkthemes.com/market/colorway-wp-theme/'); ?>" rel="nofollow"><?php esc_html_e('ColorWay Wordpress Theme by InkThemes.com', 'colorway'); ?></a></p>
                </div> 
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php } ?>
</div>
<!--End Footer navigation-->
<div class="footer_space"></div>

<?php wp_footer(); ?>
</body>
</html>