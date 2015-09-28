<!DOCTYPE html>
<html lang="de">
<head>
<link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() );?>/img/fav45.png">
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Willkommen auf der Startseite des Zwei-Fach-Masterstudiengangs Politikwissenschaft der Martin-Luther-Universität Halle-Wittenberg. Hier erhalten Sie alle Informationen rund um den Studiengang in Halle (Saale)." />

<title>
		<?php bloginfo('name', '  ');
		wp_title();?>
</title>

<!--Bootstrap CSS-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo esc_url( get_template_directory_uri() );?>/dist/css/bootstrap.min.css">
<link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

<!--Custom CSS-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo esc_url( get_template_directory_uri() );?>/style/flexslider.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo esc_url( get_template_directory_uri() );?>/style_45.css">
<link rel="stylesheet" type="text/css" media="print"  href="<?php echo esc_url( get_template_directory_uri() );?>/print.css">

<!--Jacascript // JQuery // Bootstrap JS -->

<script src="<?php echo esc_url( get_template_directory_uri() );?>/script/jq.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() );?>/script/jquery.flexslider-min.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() );?>/script/global.js"></script>
</head>

<body>






  
  <header>
   
              
   <div class="container logowrap">
        <ul class="sr-only">
           <li><a href="#main-nav">Zum Hauptmenu, hier können Sie die Seite navigieren</a></li>
           <li><a href="#main-content">Zum Hauptinformationsbereich.</a></li>
           <li><a href="#footer">Zum Footer, hier finden Sie das Impressum und können zum jeweils anderen Studiengang navigieren.</a></li>
       </ul>
        <div class="logo">
        <a href="/masterpolitikwissenschaften/?p=17"><img src="<?php echo esc_url( get_template_directory_uri() );?>/img/logo_45.svg" alt="Masterstudium 
    Politikwissenschaften Parlamentsfragen und Zivilgesellschaft"></a></div>
 
       <div id="menutoggle">
           <span></span>
           <span></span>
           <span></span>
            <span></span>
           <div id="close_toggle" class="glyphicon glyphicon-remove"></div>
           

    </div>   
    </div>
    

    <?php

$defaults = array(
	'theme_location'  => '',
	'menu'            => 'menu_45',
	'container'       => 'nav',
	'container_class' => 'container',
	'container_id'    => 'main_nav',
	'menu_class'      => 'menu',
	'menu_id'         => 'nav_ul',
	'echo'            => true,
	'fallback_cb'     => '',
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="nav_ul" class="closed">%3$s</ul>',
	'depth'           => 0,
	'walker'          => ''
);

wp_nav_menu( $defaults );

?>
</header>
 <main id="main-content">
     


<?php wp_head();?>


