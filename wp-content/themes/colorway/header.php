<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

        <?php
        /* We add some JavaScript to pages with the comment form
         * to support sites with threaded comments (when in use).
         */
        wp_head();
        $bgcolor = get_background_color();
        ?>

    </head>
    <body 
        <?php body_class(); ?> style='background-image="<?php
        if (inkthemes_get_option('inkthemes_bodybg') != '') {
            echo esc_attr(inkthemes_get_option('inkthemes_bodybg'));
        }
        ?>";background-color:<?php echo esc_attr($bgcolor); ?>'>       
        <?php
        global $page, $paged;
        get_header();
        $logo = '';
        $container = '';
        $a = get_option('header-layout');
        switch ($a) {
            case 'logo-menu':
                $logo = 'left';
                $menu_class = 'col-md-8 col-sm-8 menu-right';
                $logo_class = 'logo-left col-md-4 col-sm-4';
                break;
            case 'menu-logo':
                $logo = 'right';
                $logo_class = 'logo-right col-md-4 col-sm-4';
                $menu_class = 'menu-left col-md-8 col-sm-8';
                break;
            case 'content-center':
                $logo_class = 'col-md-12 col-sm-12 center-logo';
                $menu_class = 'center-menu col-md-12';
                $logo = 'center';
                break;
            default:
                $logo = 'left';
                $menu_class = 'col-md-8 col-sm-8 menu-right';
                $logo_class = 'logo-left col-md-4 col-sm-4';
        }

        $b = get_option('container-layout');
        switch ($b) {
            case 'container':
                $container = 'container-head container';
                break;
            case 'fullwidth-container':
                $container = 'container-fluid';
                break;
            default:
                $container = 'container-fluid';
        }
        ?>
        <div class="container-h <?php echo esc_attr($container); ?>" 
        <?php
        if (get_header_image() != '') {
            echo 'style="background-image:url(' . esc_url(get_header_image()) . ');' . 'background-repeat: no-repeat; background-size: cover;"';
        } else {
            
        }
        ?> >
            <div class="<?php
            if ($container == "container-fluid") {
                echo "container";
            } else {
                
            }
            ?>">
                <!--Start Header Grid-->
                <div class="row header">
                    <div class="header_con">

                        <?php if ($logo == 'right') { ?>
                            <!--Start MenuBar-->
                            <div class="menu-bar <?php
                            if (isset($menu_class)) {
                                echo esc_attr($menu_class);
                            }
                            ?>">  
                                     <?php inkthemes_nav(); ?>                       
                                <div class="clearfix"></div>
                            </div>
                            <!--End MenuBar-->

                        <?php } ?>

                        <div class="logo <?php
                        if (isset($logo_class)) {
                            echo esc_attr($logo_class);
                        }
                        ?>">
                                 <?php if (inkthemes_get_option('colorway_logo') != '') { ?>
                                     <?php if (inkthemes_get_option('logo_option') != '') { ?>
                                    <a class="colorway_logo" href="<?php echo esc_url(home_url()); ?>"><img style="width:<?php
                                        if (get_option('logo_width') != '') {
                                            echo esc_attr(get_option('logo_width')) . '%';
                                        } else {
                                            echo "70%";
                                        }
                                        ?>; height:auto;" src="<?php echo esc_url(inkthemes_get_option('colorway_logo')); ?>" alt="<?php wp_kses_post(bloginfo('name')); ?>"/></a>                              
                                                                                                        <?php } ?>
                                                                                                    <?php } ?>

                            <!--sticky header logo-->
                            <?php if (inkthemes_get_option('colorway_sticky_header') != '') { ?>
                                <?php if (inkthemes_get_option('sticky_logo_setting') != '') { ?>
                                <a class="colorway_logo_sticky sticky_logo_setting" href="<?php echo esc_url(home_url()); ?>"><img style="width:<?php
                                    if (get_option('stky_logo_width') != '') {
                                        echo esc_attr(get_option('stky_logo_width')) . '%';
                                    } else {
                                        echo "70%";
                                    }
                                    ?>; height:auto;" src="<?php echo esc_url(inkthemes_get_option('sticky_logo_setting')); ?>" alt="<?php wp_kses_post(bloginfo('name')); ?>"/></a>                              
                                <?php }} ?>
                            <hgroup>   

                                <?php if (display_header_text() != false) { ?>
                                    <a href="<?php echo esc_url(home_url()); ?>"> <h1 class="site-title" style="font-size:<?php
                                        if (get_option('title_font_size') != '') {
                                            echo esc_attr(get_option('title_font_size', '34')) . 'px';
                                        } else {
                                            echo "34px";
                                        }
                                        ?>; color: #<?php header_textcolor(); ?>"><?php echo bloginfo('name'); ?></h1></a>
                                                                                      <?php
                                                                                      $description = get_bloginfo('description', 'display');
                                                                                      if ($description || is_customize_preview()) {
                                                                                          ?>
                                        <p class="site-description" style="font-size:<?php echo esc_attr(get_option('desc_font_size', '16')) . 'px'; ?>; color: #<?php header_textcolor(); ?>"><?php echo bloginfo('description'); ?> </p>
                                    <?php } ?>
                                <?php } ?>

                            </hgroup>
                        </div>

                        <?php if ($logo == 'left' || $logo == 'center') { ?>
                            <!--Start MenuBar-->
                            <div class="menu-bar <?php
                            if (isset($menu_class)) {
                                echo esc_attr($menu_class);
                            }
                            ?>">  
                                     <?php inkthemes_nav(); ?>                       
                                <div class="clearfix"></div>
                            </div>
                            <!--End MenuBar-->
                        <?php } ?>
                        <div class="clearfix"></div>

                    </div>
                </div>

                <?php if (inkthemes_get_option('border_bottom_on_off', 'on') != 'off') { ?>
                    <div class="border"></div>
                <?php } ?>
            </div>
        </div>       
        <div class="clear"></div>
        <div class="cw-content <?php echo esc_attr($container); ?>">
            <div class="cyw-container">
                <div class="<?php
                if ($container != 'container-head container') {
                    echo 'container';
                }
                ?>">
                    <!--Start Container Div-->

