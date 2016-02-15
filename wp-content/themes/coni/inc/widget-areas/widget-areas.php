<?php
	
	/*
	Sidebar
	===================================
	*/

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'coni' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	
	/*
	Front Page Sections
	===================================
	*/
	register_sidebar( array(
		'name'          => esc_html__( 'Front Page - Services Section', 'coni' ),
		'id'            => 'services-section',
		'description'   => esc_html__( 'This is the Services Section in the Front Page. Here Add the "Coni - Service widget"', 'coni' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Front Page - Phone Section', 'coni' ),
		'id'            => 'phone-section',
		'description'   => esc_html__( 'This is the Phone Section in the Front Page. Here Add the "Coni - Service widget"', 'coni' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Front Page - Clients Section', 'coni' ),
		'id'            => 'clients-section',
		'description'   => esc_html__( 'This is the Clients Section in the Front Page. Here Add the "Coni - Client widget"', 'coni' ),
		'before_widget' => '<div class="widget %s clients-logo wow flipInX">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Front Page - Pricing Section', 'coni' ),
		'id'            => 'pricing-section',
		'description'   => esc_html__( 'This is the Pricing Section in the Front Page. Here Add the "Coni - Pricing widget"', 'coni' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );


?>