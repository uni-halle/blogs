<!DOCTYPE html>
<html lang="de">
<head>
<link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() );?>/img/fav.png">
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Das ist die Startseite der Studiengänge Master Politikwissenschaft der Martin-Luther-Universität Halle-Wittenberg. Hier finden Sie Informationen zu den Studiengängen." />
<title>
		<?php bloginfo('name', '  ');
		wp_title();?>
</title>

<!--Bootstrap CSS-->
<link rel="stylesheet" type="text/css" media="screen"  href="<?php echo esc_url( get_template_directory_uri() );?>/dist/css/bootstrap.min.css">
<link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

<!--Custom CSS-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo esc_url( get_template_directory_uri() );?>/style/flexslider.css">
<link rel="stylesheet" type="text/css" media="screen"  href="<?php echo esc_url( get_template_directory_uri() );?>/style.css">
<link rel="stylesheet" type="text/css" media="print"  href="<?php echo esc_url( get_template_directory_uri() );?>/print.css">

<!--Jacascript // JQuery // Bootstrap JS -->
<link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_template_directory_uri() );?>/dist/css/bootstrap.min.js">
<script src="<?php echo esc_url( get_template_directory_uri() );?>/script/jq.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() );?>/script/jquery.flexslider-min.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() );?>/script/global.js"></script>
</head>

<body>
<section class="sliderwrap">
<h2 class="sr-only">Hintergrundslider</h2>
   <!--Slider-->
    <?php 
    $args = array(
        'src'	=> $src,
        'post_type' 	=> 'slides',
        'orderby' 		=> 'menu_order',
        'post_per_page' => -1
    );
    $slides = new WP_Query( $args);
        if( $slides->have_posts() ) :?>
            <div class="flexslider">
                <ul class="slides">
                <?php while( $slides->have_posts() ) : $slides->the_post();?>
                    <li>
                      <?php the_post_thumbnail('featured-thumbnail'); ?>
                    
                   
                    </li>
                <?php endwhile; ?>
                </ul>
                </div>	
        <?php endif; ?>
</section>


<header>
    <div class="container">
       <ul class="sr-only">
           <li><a href="#hauptmenu">Zum Hauptmenu, wählen Sie einen Studiengang!</a></li>
           <li><a href="infotext">Zum Beschreibungstext.</a></li>
       </ul>
        <div class="logo"><a href="<?php home_url() ?>"><img src="<?php echo esc_url( get_template_directory_uri() );?>/img/logo_land.svg" alt="Masterstudium 
Politikwissenschaften in Halle"></a></div>
        
          
            <ul id="hauptmenu" class="land_main_nav">
                <li tabindex="1" class="main_nav_item"><a href="/masterpolitikwissenschaften/?p=15">MA 120</a></li>
                <li tabindex="2" class="main_nav_item"><a href="/masterpolitikwissenschaften/?p=17">MA 45/75</a></li>
            </ul>
            
        
    </div>
</header>
<?php wp_head();?>