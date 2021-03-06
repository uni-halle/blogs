<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>
        
		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/library/images/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
        <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>
        
    <?php         
// get id of current page        
    $currentid = get_the_ID();
    
    //call pagelist function for further reference
    $pagelist = page_list_by_main_nav();  
    
    //get $currentcat as current topical category
    foreach($pagelist as $page){
        if($page['id'] == $currentid) {
            $currentcat = $page['topical_catid'];
        }
    };
    
    ?>    
    
    <!-- call function for color categories from functions.php  -->

    <?php
    if(function_exists('rl_color')){
        if($currentcat!=0){
            $rl_category_color = rl_color($currentcat);
        }
        elseif(is_front_page){
            $rl_category_color = '#fff';
        }
        else $rl_category_color = '#008d9a';
    }
?>
        
<!-- set the category color classes with the respective color (which is set in the admin area as category setting) -->        
        <style>
            /* add classes where the COLOR property should have category color */
            .ccolor, .sf-with-ul:after, .themenuebersicht li a:before, .themenuebersicht li a:hover:before, .prevname a:hover, .nextname a:hover {color: <?php echo $rl_category_color; ?>;}
            
            /* arrow after 'Themen' in Menu */
            .sf-with-ul:after {color:
                <?php 
                    if( is_front_page() ){
                        echo '#008d9a';
                    } 
                    else $rl_category_color; 
                ?>
                ;}
            
            /* add classes where the BACKGROUND-COLOR property should have category color */
            .ccolorbgrd, .themenuebersicht li a:after, .widget .themenuebersicht .sidebar-current-item a:before, .themenuebersicht a:hover:before {background-color: <?php echo $rl_category_color; ?>;}
            
            /* add classes where the border-color property should have category color */
            .ccolorborder, .themenuebersicht li a:before, .themenuebersicht li a:before, .entry-content blockquote {border-color: <?php echo $rl_category_color; ?>;}
            
            /* other classes with category color styles */
            .svgbutton:hover {fill: <?php echo $rl_category_color; ?>;}
            
            <?php //list all category menu item classes and give color to the marker element defined in _base.scss
             

            ?>
    
        </style>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">
            
			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

                <!--   flexbox container             -->
                <div id="inner-header" class="maincontainer">

					<?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
					<a href="<?php echo home_url(); ?>" rel="nofollow" class="logolink"><img class="logo" src="<?php echo get_template_directory_uri(); ?>/library/images/Logo_GKUHp.png"></a>

					<?php // if you'd like to use the site description you can un-comment it below ?>
					<?php // bloginfo('description'); ?>

<div class="navcontainer">
					<nav role="navigation" class="nav" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<?php wp_nav_menu(array(
    					         'container' => false,                           // remove nav container
    					         'container_class' => 'menu cf',                 // class of container (should you choose to use it)
    					         'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
    					         'menu_class' => 'nav top-nav cf sf-menu',               // adding custom nav class
    					         'theme_location' => 'main-nav',                 // where it's located in the theme
    					         'before' => '',                                 // before the menu
        			               'after' => '',                                  // after the menu
        			               'link_before' => '',                            // before each link
        			               'link_after' => '',                             // after each link
        			               'depth' => 2,                                   // limit the depth of the nav to second level 
    					         'fallback_cb' => ''                             // fallback function (if there is one)
						)); ?>

					</nav>
    </div>
                    
</div>

</header>
