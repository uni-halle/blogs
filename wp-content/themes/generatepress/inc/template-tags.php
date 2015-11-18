<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package GeneratePress
 */
if ( ! function_exists( 'generate_paging_nav' ) ) :
	function generate_paging_nav() {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'     => $pagenum_link,
			'format'   => $format,
			'total'    => $GLOBALS['wp_query']->max_num_pages,
			'current'  => $paged,
			'mid_size' => apply_filters( 'generate_pagination_mid_size', 1 ),
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => __( '&larr; Previous', 'generate' ),
			'next_text' => __( 'Next &rarr;', 'generate' ),
		) );

		if ( $links ) :

			echo $links; 

		endif;
	}
endif;

if ( ! function_exists( 'generate_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function generate_content_nav( $nav_id ) {

	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h6 class="screen-reader-text"><?php _e( 'Post navigation', 'generate' ); ?></h6>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous"><span class="prev" title="' . __('Previous','generate') . '">%link</span></div>', '%title' ); ?>
		<?php next_post_link( '<div class="nav-next"><span class="next" title="' . __('Next','generate') . '">%link</span></div>', '%title' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><span class="prev" title="<?php _e('Previous','generate');?>"><?php next_posts_link( __( 'Older posts', 'generate' ) ); ?></span></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><span class="next" title="<?php _e('Next','generate');?>"><?php previous_posts_link( __( 'Newer posts', 'generate' ) ); ?></span></div>
		<?php endif; ?>
		
		<?php generate_paging_nav(); ?>
		<?php do_action('generate_paging_navigation'); ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // generate_content_nav

if ( ! function_exists( 'generate_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function generate_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$args['avatar_size'] = 50;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'generate' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'generate' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				<div class="comment-author-info">
					<div class="comment-author vcard">
						<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
					</div><!-- .comment-author -->

					<div class="entry-meta comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'generate' ), get_comment_date(), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( __( 'Edit', 'generate' ), '<span class="edit-link">| ', '</span>' ); ?>
						<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<span class="reply">| ',
							'after'     => '</span>',
						) ) );
						?>
					</div><!-- .comment-metadata -->
				</div><!-- .comment-author-info -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'generate' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for generate_comment()

if ( ! function_exists( 'generate_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function generate_posted_on() {

	if ( 'post' !== get_post_type() ) 
		return;
	
	$date = apply_filters( 'generate_post_date', true );
	$author = apply_filters( 'generate_post_author', true );
		
	$time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
		$time_string .= '<time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	
	// If our date is enabled, show it
	if ( $date ) :
		printf( '<span class="posted-on">%1$s</span>',
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
				esc_url( get_permalink() ),
				esc_attr( get_the_time() ),
				$time_string
			)
		);
	endif;
	
	// If our author is enabled, show it
	if ( $author ) :
		printf( ' <span class="byline">%1$s</span>',
			sprintf( '<span class="author vcard" itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="author">%1$s <a class="url fn n" href="%2$s" title="%3$s" rel="author" itemprop="url"><span class="author-name" itemprop="name">%4$s</span></a></span>',
				__( 'by','generate'),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'generate' ), get_the_author() ) ),
				esc_html( get_the_author() )
			)
		);
	endif;
	
}
endif;

if ( ! function_exists( 'generate_excerpt_more' ) ) :
/**
 * Prints the read more HTML to post excerpts
 */
	add_filter( 'excerpt_more', 'generate_excerpt_more' );
	function generate_excerpt_more( $more ) {
		return ' ... <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Read more', 'generate') . '</a>';
	}
endif;

if ( ! function_exists( 'generate_content_more' ) ) :
/**
 * Prints the read more HTML to post content using the more tag
 */
	add_filter( 'the_content_more_link', 'generate_content_more' );
	function generate_content_more( $more ) {
		$more_jump = apply_filters( 'generate_more_jump','#more-' . get_the_ID() );
		return '<p class="read-more-container"><a class="read-more content-read-more" href="'. get_permalink( get_the_ID() ) . $more_jump . '">' . __('Read more', 'generate') . '</a></p>';
	}
endif;

if ( ! function_exists( 'generate_featured_page_header_area' ) ) :
/**
 * Build the page header
 * @since 1.0.7
 */
function generate_featured_page_header_area($class)
{
	// Don't run the function unless we're on a page it applies to
	if ( ! is_singular() )
		return;
		
	// Don't run the function unless we have a post thumbnail
	if ( ! has_post_thumbnail() )
		return;
		
	?>
	<div class="<?php echo $class; ?> grid-container grid-parent">
		<?php the_post_thumbnail( apply_filters( 'generate_page_header_default_size', 'full' ), array('itemprop' => 'image') ); ?>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'generate_featured_page_header' ) ) :
/**
 * Add page header above content
 * @since 1.0.2
 */
add_action('generate_after_header','generate_featured_page_header', 10);
function generate_featured_page_header()
{
	if ( function_exists('generate_page_header') )
		return;

	if ( is_page() ) :
		
		generate_featured_page_header_area('page-header-image');
	
	endif;
}
endif;

if ( ! function_exists( 'generate_featured_page_header_inside_single' ) ) :
/**
 * Add post header inside content
 * Only add to single post
 * @since 1.0.7
 */
add_action('generate_before_content','generate_featured_page_header_inside_single', 10);
function generate_featured_page_header_inside_single()
{
	if ( function_exists('generate_page_header') )
		return;

	if ( is_single() ) :
	
		generate_featured_page_header_area('page-header-image-single');
	
	endif;
}
endif;

if ( ! function_exists( 'generate_post_image' ) ) :
/**
 * Prints the Post Image to post excerpts
 */
add_action( 'generate_after_entry_header', 'generate_post_image' );
function generate_post_image()
{
		
	// If there's no featured image, return
	if ( ! has_post_thumbnail() )
		return;
		
	// If we're not on any single post/page or the 404 template, we must be showing excerpts
	if ( ! is_singular() && ! is_404() ) {
	?>
		<div class="post-image">
			<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( apply_filters( 'generate_page_header_default_size', 'full' ), array('itemprop' => 'image') ); ?></a>
		</div>
	<?php
	}
}
endif;

if ( ! function_exists( 'generate_navigation_search' ) ) :
/**
 * Add the search bar to the navigation
 * @since 1.1.4
 */
add_action( 'generate_inside_navigation','generate_navigation_search');
function generate_navigation_search()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
		
	if ( 'enable' !== $generate_settings['nav_search'] )
		return;
			
	?>
	<form method="get" class="search-form navigation-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search', 'label', 'generate' ); ?>">
	</form>
	<?php
}
endif;

if ( ! function_exists( 'generate_menu_search_icon' ) ) :
/**
 * Add search icon to primary menu if set
 *
 * @since 1.2.9.7
 */
add_filter( 'wp_nav_menu_items','generate_menu_search_icon', 10, 2 );
function generate_menu_search_icon( $nav, $args ) 
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// If the search icon isn't enabled, return the regular nav
	if ( 'enable' !== $generate_settings['nav_search'] )
		return $nav;
	
	// If our primary menu is set, add the search icon
    if( $args->theme_location == 'primary' )
        return $nav . '<li class="search-item" title="' . _x( 'Search', 'submit button', 'generate' ) . '"><a href="#"><i class="fa fa-fw fa-search"></i></a></li>';
	
	// Our primary menu isn't set, return the regular nav
	// In this case, the search icon is added to the generate_menu_fallback() function in navigation.php
    return $nav;
}
endif;

if ( ! function_exists( 'generate_mobile_menu_search_icon' ) ) :
/**
 * Add search icon to mobile menu bar
 *
 * @since 1.3.12
 */
add_action( 'generate_inside_navigation','generate_mobile_menu_search_icon' );
function generate_mobile_menu_search_icon() 
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// If the search icon isn't enabled, return the regular nav
	if ( 'enable' !== $generate_settings['nav_search'] )
		return;
	
	?>
	<div class="mobile-bar-items">
		<?php do_action( 'generate_inside_mobile_menu_bar' ); ?>
		<span class="search-item" title="<?php _ex( 'Search', 'submit button', 'generate' ); ?>">
			<a href="#"><i class="fa fa-fw fa-search"></i></a>
		</span>
	</div><!-- .mobile-bar-items -->
	<?php
}
endif;

if ( ! function_exists( 'generate_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since 1.2.5
 */
function generate_entry_meta() 
{
	$categories = apply_filters( 'generate_show_categories', true );
	$tags = apply_filters( 'generate_show_tags', true );
	$comments = apply_filters( 'generate_show_comments', true );
	
	if ( 'post' == get_post_type() ) {

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'generate' ) );
		if ( $categories_list && $categories ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'generate' ),
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'generate' ) );
		if ( $tags_list && $tags ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'generate' ),
				$tags_list
			);
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && $comments ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'generate' ), __( '1 Comment', 'generate' ), __( '% Comments', 'generate' ) );
		echo '</span>';
	}
}
endif;

if ( ! function_exists( 'generate_categorized_blog' ) ) :
/**
 * Determine whether blog/site has more than one category.
 *
 * @since 1.2.5
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function generate_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'generate_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'generate_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so twentyfifteen_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so twentyfifteen_categorized_blog should return false.
		return false;
	}
}
endif;

if ( ! function_exists( 'generate_category_transient_flusher' ) ) :
/**
 * Flush out the transients used in {@see generate_categorized_blog()}.
 *
 * @since 1.2.5
 */
add_action( 'edit_category', 'generate_category_transient_flusher' );
add_action( 'save_post',     'generate_category_transient_flusher' );
function generate_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'generate_categories' );
}
endif;

if ( ! function_exists( 'generate_get_link_url' ) ) :
/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since 1.2.5
 *
 * @see get_url_in_content()
 *
 * @return string The Link format URL.
 */
function generate_get_link_url() {
	$has_url = get_url_in_content( get_the_content() );

	return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

if ( ! function_exists( 'generate_header_items' ) ) :
/**
 * Build the header
 *
 * Wrapping this into a function allows us to customize the order in a child theme
 *
 * @since 1.2.9.7
 */
function generate_header_items() 
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Get the title and tagline
	$title = get_bloginfo( 'title' );
	$tagline = get_bloginfo( 'description' );
	
	// If the disable title checkbox is checked, or the title field is empty, return true
	$disable_title = ( '1' == $generate_settings[ 'hide_title' ] || '' == $title ) ? true : false; 
	
	// If the disable tagline checkbox is checked, or the tagline field is empty, return true
	$disable_tagline = ( '1' == $generate_settings[ 'hide_tagline' ] || '' == $tagline ) ? true : false;
	
	// Header widget
	if ( is_active_sidebar('header') ) : ?>
		<div class="header-widget">
			<?php dynamic_sidebar( 'header' ); ?>
		</div>
	<?php endif;
		
	// Site title and tagline
	if ( false == $disable_title || false == $disable_tagline ) : ?>
		<div class="site-branding">
			<?php if ( false == $disable_title ) : ?>
				<p class="main-title" itemprop="headline"><a href="<?php echo apply_filters( 'generate_site_title_href', esc_url( home_url( '/' ) ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif;
				
			if ( false == $disable_tagline ) : ?>
				<p class="site-description"><?php echo html_entity_decode( bloginfo( 'description' ) ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif;
	
	// Site logo
	if ( ! empty( $generate_settings['logo'] ) ) : ?>
		<div class="site-logo">
			<a href="<?php echo apply_filters( 'generate_logo_href' , esc_url( home_url( '/' ) ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img class="header-image" src="<?php echo esc_url( $generate_settings['logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
		</div>
	<?php endif;
}
endif;