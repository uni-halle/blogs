<?php
/* Register custom menus. */
add_action( 'init', 'omega_register_menus' );

/* Register sidebars. */
add_action( 'widgets_init', 'omega_register_sidebars' );

/* Add default theme settings */
add_filter( 'omega_default_theme_settings', 'omega_set_default_theme_settings');

add_action( 'wp_enqueue_scripts', 'omega_scripts' );
add_action( 'wp_head', 'omega_styles' );

/* Load the primary menu. */
add_action( 'omega_before_header', 'omega_get_primary_menu' );
add_action( 'omega_before_primary_menu', 'omega_menu_icon');

/* Header actions. */
add_action( 'omega_header', 'omega_header_markup_open', 5 );
add_action( 'omega_header', 'omega_branding' );
add_action( 'omega_header', 'omega_header_markup_close', 15 );

/* footer insert to the footer. */
add_action( 'omega_footer', 'omega_footer_markup_open', 5 );
add_action( 'omega_footer', 'omega_footer_insert' );
add_action( 'omega_footer', 'omega_footer_markup_close', 15 );

/* load content */
add_action( 'omega_content', 'omega_content');

add_action( 'omega_before_loop', 'omega_archive_title'); 
add_action( 'omega_after_loop', 'omega_content_nav'); 
add_action( 'omega_after_loop', 'comments_template');  // Loads the comments.php template.  

/* Add the title, byline, and entry meta before and after the entry.*/
add_action( 'omega_before_entry', 'omega_entry_header' );
add_action( 'omega_entry', 'omega_entry' );
add_action( 'omega_after_entry', 'omega_entry_footer' );

/* Add the primary sidebars after the main content. */
add_action( 'omega_after_main', 'omega_primary_sidebar' );

add_filter( 'omega_footer_insert', 'omega_default_footer_insert' );

add_filter( 'comment_form_defaults', 'omega_custom_comment_form' );


/**
 * Registers nav menu locations.
 *
 * @since  0.9.0
 * @access public
 * @return void
 */
function omega_register_menus() {
	register_nav_menu( 'primary',   _x( 'Primary', 'nav menu location', 'omega' ) );
}

/**
 * Registers sidebars.
 *
 * @since  0.9.0
 * @access public
 * @return void
 */

function omega_register_sidebars() {
	omega_register_sidebar(
		array(
			'id'          => 'primary',
			'name'        => _x( 'Primary', 'sidebar', 'omega' ),
			'description' => __( 'The main sidebar. It is displayed on either the left or right side of the page based on the chosen layout.', 'omega' )
		)
	);
}

/**
 * Adds custom default theme settings.
 *
 * @since 0.3.0
 * @access public
 * @param array $settings The default theme settings.
 * @return array $settings
 */

function omega_set_default_theme_settings( $settings ) {

	$settings = array(
		'comments_pages'       	=> 0,
		'content_archive'       => 'full',
		'content_archive_limit'	=> 0,
		'post_thumbnail' 		=> 1,
		'more_text'      		=> '[Read more...]',
		'no_more_scroll'		=> 1,
		'image_size'           	=> 'large',
	);

	return $settings;

}


function omega_header_markup_open() {
	echo '<header ' . omega_get_attr('header') . '>';
}


function omega_header_markup_close() {
	echo '</header><!-- .site-header -->';
}

function omega_footer_markup_open() {
	echo '<footer ' . omega_get_attr('footer') . '>';
}


function omega_footer_markup_close() {
	echo '</footer><!-- .site-footer -->';
}

/**
 * Dynamic element to wrap the site title and site description. 
 */
function omega_branding() {
	get_template_part( 'partials/title', 'area' );	
}

/**
 * default footer insert filter
 */
function omega_default_footer_insert( $settings ) {

	/* If there is a child theme active, use [child-link] shortcode to the $footer_insert. */
	if ( !is_child_theme() ) {
		return '<p class="copyright">' . __( 'Copyright &#169; ', 'omega' ) . date_i18n( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '.</p>' . "\n\n" . '<p class="credit">' . omega_get_theme_name() . __( ' WordPress Theme by ', 'omega' ) . omega_get_author_uri() . '</p>';		
	} else {
		return '<p class="copyright">' . __( 'Copyright &#169; ', 'omega' ) . date_i18n( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '.</p>' . "\n\n" . '<p class="credit">' . omega_get_child_theme_link() . __( ' WordPress Theme by ', 'omega' ) . omega_get_author_uri() . '</p>';		
	}
	

}

/**
 * Loads footer content
 */
function omega_footer_insert() {
	
	echo '<div class="footer-content footer-insert">';
	
	if ( $footer_insert = get_theme_mod( 'custom_footer' ) ) {
		echo omega_apply_atomic_shortcode( 'footer_content', $footer_insert );		
	} else {
		echo omega_apply_atomic_shortcode( 'footer_content', apply_filters( 'omega_footer_insert','') );
	}
	
	echo '</div>';
}

/**
 * Loads the menu-primary.php template.
 */
function omega_get_primary_menu() {
	get_template_part( 'partials/menu', 'primary' );
}

/**
 * print menu icon
 */
function omega_menu_icon() {
	echo '<a href="#" id="menu-icon" class="menu-icon"><span></span></a>';
}

/**
 * Display primary sidebar
 */
function omega_primary_sidebar() {
	get_sidebar();
}

/**
 * Display the default entry header.
 */
function omega_entry_header() {
	get_template_part( 'partials/entry', 'header' );	
}

/**
 * Display the default entry metadata.
 */
function omega_entry() {
	get_template_part( 'partials/entry' );	
}

function omega_excerpt_more( $more ) {
	return ' ... <span class="more"><a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . get_theme_mod( 'more_text', '[Read more...]' ) . '</a></span>';
}
add_filter('excerpt_more', 'omega_excerpt_more');

/**
 * Display the default entry footer.
 */
function omega_entry_footer() {
	if ( 'post' == get_post_type() ) get_template_part( 'partials/entry', 'footer' );
}

/**
 * Enqueue scripts and styles
 */
function omega_scripts() {
	wp_enqueue_style( 'omega-style', get_stylesheet_uri() );
}

/**
 * Insert conditional script / style for the theme used sitewide.
 */
function omega_styles() {
?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
<?php 
}

/**
 * Display navigation to next/previous pages when applicable
 */
function omega_content_nav() {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( (!$next && !$previous) )
			return;
	}

	if ( is_singular() && !get_theme_mod( 'single_nav', 0 ) ) {
		return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
	
	?>
	<nav role="navigation" id="nav-below" class="navigation  <?php echo $nav_class; ?>">

	<?php 
	if ( is_single() && get_theme_mod( 'single_nav', 0 ) ) : // navigation links for single posts 
		get_template_part( 'partials/single', 'nav' );
   	elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages 
		loop_pagination();
	endif; 
	?>

	</nav><!-- #nav-below -->
	<?php
}

function omega_archive_title() {
	if(is_archive() || is_search() ) {
	?>

		<header class="page-header">
			<h1 class="archive-title">
				<?php
					if ( is_category() ) :
						single_cat_title();

					elseif ( is_search() ) :
						printf( __( 'Search Results for: %s', 'omega' ), '<span>' . get_search_query() . '</span>' );

					elseif ( is_tag() ) :
						single_tag_title();

					elseif ( is_author() ) :
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( __( 'Author: %s', 'omega' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */
						rewind_posts();

					elseif ( is_day() ) :
						printf( __( 'Day: %s', 'omega' ), '<span>' . get_the_date() . '</span>' );

					elseif ( is_month() ) :
						printf( __( 'Month: %s', 'omega' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

					elseif ( is_year() ) :
						printf( __( 'Year: %s', 'omega' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

					else :
						_e( 'Archives', 'omega' );

					endif;
				?>
			</h1>
			<?php
				// Show an optional term description.
				$term_description = term_description();
				if ( ! empty( $term_description ) ) :
					printf( '<div class="taxonomy-description">%s</div>', $term_description );
				endif;
			?>
		</header><!-- .page-header -->

	<?php 
	}
}

function omega_content() {
	get_template_part( 'partials/content' );	
}

function omega_custom_comment_form($fields) {
	$fields['comment_notes_after'] = ''; //Removes Form Allowed Tags Box
return $fields;
}


// add disqus compatibility
if (function_exists('dsq_comments_template')) {
	remove_filter( 'comments_template', 'dsq_comments_template' );
	add_filter( 'comments_template', 'dsq_comments_template', 12 ); // You can use any priority higher than '10'	
}