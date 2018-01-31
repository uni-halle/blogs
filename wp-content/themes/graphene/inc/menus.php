<?php
/***************
 * Custom Menu * 
 ***************/
 
 
/**
 * Customise navigation menu codes
 */
class Graphene_Walker_Nav_Menu extends Walker_Nav_Menu {
	
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}
	
}


/**
 * Modify menu item classes
 */
function graphene_nav_menu_css_class( $classes, $item, $args ){
	
	if ( has_nav_menu( $args->theme_location ) ) {
		if ( in_array( 'current-menu-item', $classes ) ) $classes[] = 'active';
		if ( ( $item->menu_item_parent ) && in_array( 'menu-item-has-children', $classes ) ) $classes[] = 'dropdown-submenu';
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'graphene_nav_menu_css_class', 10, 3 );


/**
 * Modify menu item attributes
 */
function graphene_nav_menu_link_attributes( $atts, $item, $args ){
	
	$depth = ( is_object( $args ) ) ? $args->depth : $args['depth'];
	$classes = ( is_object( $item ) ) ? $item->classes : $item; // If this is default menu, $item is actually $css_class of the link
	$atts['class'] = '';

	/* Dropdown submenu */
	if ( in_array( 'menu-item-has-children', $classes ) && $depth > 1 ) {
		if ( 
			( is_object( $args ) && $item->menu_item_parent == 0 ) 
		||	( is_array( $args ) && ! in_array( 'dropdown-submenu', $classes ) )
		) {
			$atts['class'] = 'dropdown-toggle';
			$atts['data-toggle'] = 'dropdown';
			$atts['data-submenu'] = '1';
			$atts['data-depth'] = $depth;
			$atts['data-hover'] = 'dropdown';	/* Dropdown on hover */
			$atts['data-delay'] = '0';
		}
	}
	
	if ( is_object( $item ) ) {
		if ( $item->description ) $atts['class'] .= ' has-desc';
		if ( get_post_meta( $item->ID, 'menu-item-icon', true ) ) $atts['class'] .= ' has-icon';
	}
	
	if ( ! $atts['class'] ) unset ( $atts['class'] );
	else $atts['class'] = trim( $atts['class'] );
	
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'graphene_nav_menu_link_attributes', 10, 3 );


/**
 * Modify final menu item output
 */
function graphene_walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
	
	$max_depth = ( is_object( $args ) ) ? $args->depth : $args['depth'];
	$classes = ( is_object( $item ) ) ? $item->classes : $item; // If this is default menu, $item is actually $css_class of the link
	global $menus_with_desc; if ( ! $menus_with_desc ) $menus_with_desc = array();
	
	/* Menu icon */
	if ( is_object( $item ) ) {
		$icon = get_post_meta( $item->ID, 'menu-item-icon', true );
		if ( $icon ) {
			$matches = array();
			$item_output = preg_replace( '/(<a\s[^>]*[^>]*>)(.*)(<\/a>)/siU', '$1<i class="fa fa-' . strtolower( $icon ) . '"></i> ${2}$3', $item_output );
		}
	}
	
	/* Chevron for dropdown menu */
	$theme_location = ( is_object( $args ) ) ? $args->theme_location : $args['theme_location'];
	if ( in_array( 'menu-item-has-children', $classes ) && $max_depth > 1 && $theme_location != 'footer-menu' ) {
		if ( $depth == 0 ) $item_output = preg_replace( '/(<a\s[^>]*[^>]*>)(.*)(<\/a>)/siU', '$1$2 <i class="fa fa-chevron-down"></i>$3', $item_output );
		else $item_output = preg_replace( '/(<a\s[^>]*[^>]*>)(.*)(<\/a>)/siU', '$1$2 <i class="fa fa-chevron-right"></i>$3', $item_output );
	}
	
	/* Menu description */
	if ( is_object( $item ) && in_array( $args->theme_location, array( 'Header Menu', 'secondary-menu' ) ) ) {
		if ( $item->description && strlen( $item->description ) < 100 ) {
			$item_output = preg_replace( '/(<a\s[^>]*[^>]*>)(.*)(<\/a>)/siU', '$1$2 <span class="desc">' . $item->description . '</span>$3', $item_output );
			if ( $depth == 0 ) $menus_with_desc[$args->theme_location] = 1;
		}
	}
	
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'graphene_walker_nav_menu_start_el', 10, 4 );


/**
 * Add custom class to menu wrapper for menu with descriptions
 */
function graphene_menu_class( $nav_menu, $args ) {
	global $menus_with_desc;
	if ( $menus_with_desc && array_key_exists( $args->theme_location, $menus_with_desc ) ) {
		$nav_menu = str_replace( $args->menu_class, $args->menu_class . ' has-desc', $nav_menu );
	}
	return $nav_menu;
}
add_filter( 'wp_nav_menu', 'graphene_menu_class', 10, 2 );


/**
 * Add custom fields to custom menu
 */
class graphene_Menu_Item_Custom_Fields {

	protected static $fields = array();
	
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

		self::$fields = array(
			'icon' => __( 'FontAwesome Icon Name', 'graphene' ),
		);
	}


	/**
	 * Save custom field value
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		foreach ( self::$fields as $_key => $label ) {
			$key = sprintf( 'menu-item-%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				$value = sanitize_text_field( trim( $_POST[ $key ][ $menu_item_db_id ] ) );
			} else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) update_post_meta( $menu_item_db_id, $key, $value );
			else delete_post_meta( $menu_item_db_id, $key );
		}
	}


	/**
	 * Print field
	 */
	public static function _fields( $id, $item, $depth, $args ) {
		foreach ( self::$fields as $_key => $label ) :
			$key   = sprintf( 'menu-item-%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );
			?>
				<p class="description description-wide <?php echo esc_attr( $class ) ?>">
					<?php printf(
						'<label for="%1$s">%2$s<br /><input type="text" id="%1$s" class="widefat %1$s" name="%3$s" value="%4$s" /></label><br />' 
						. sprintf( __( 'Choose from 580+ %s', 'graphene' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">' . __( 'available icons', 'graphene' ) . '</a>.' ),
						esc_attr( $id ),
						esc_html( $label ),
						esc_attr( $name ),
						esc_attr( $value )
					) ?>
				</p>
			<?php
		endforeach;
	}


	/**
	 * Add our fields to the screen options toggle
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );
		return $columns;
	}
}
graphene_Menu_Item_Custom_Fields::init();


/****************
 * Default Menu *
 ****************/
 
 
/**
 * Custom default menu based on wp_page_menu
 */
function graphene_page_menu( $args = array() ) {
	$defaults = array(
		'sort_column' 	=> 'menu_order, post_title',
		'menu_class' 	=> 'menu',
		'echo' 			=> true,
		'link_before' 	=> '',
		'link_after' 	=> ''
	);
	$args = apply_filters( 'wp_page_menu_args', wp_parse_args( $args, $defaults ) );

	$menu = '';
	$list_args = $args;

	/* Add Home link to the menu */
	$text = __( 'Home', 'graphene' );
	$class = '';
	
	if ( is_front_page() && !is_paged() ) $class = 'class="current_page_item"';
	$menu .= '<li ' . $class . '><a href="' . esc_url( home_url( '/' ) ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
	
	/* If the front page is a page, add it to the exclude list */
	if ( get_option( 'show_on_front' ) == 'page' ) {
		if ( ! empty( $list_args['exclude'] ) ) $list_args['exclude'] .= ',';
		else $list_args['exclude'] = '';

		$list_args['exclude'] .= get_option( 'page_on_front' );
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $list_args ) );

	if ( $menu ) $menu = '<ul class="' . esc_attr( $args['menu_class'] ) . '">' . $menu . '</ul>';

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] ) echo $menu;
	else return $menu;
}


/**
 * Customise default navigation menu
 *
 * Improvements over the default Walker_Page class:
 * 1. Changed all classes to match Walker_Nav_Menu
 * 2. Added link attributes filter
 * 3. Added item output filter
 */
class Graphene_Walker_Page extends Walker_Page {
	
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}
	
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'menu-item', 'menu-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'menu-item-has-children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current-menu-ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current-menu-item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current-menu-parent';
			}
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current-menu-parent';
		}
		
		$css_class = apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page );
		$css_classes = implode( ' ', $css_class );

		if ( '' === $page->post_title ) {
			$page->post_title = sprintf( __( '#%d (no title)', 'graphene' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];
		
		
		$atts = apply_filters( 'nav_menu_link_attributes', array(), $css_class, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) if ( ! empty( $value ) ) $attributes .= ' ' . $attr . '="' . $value . '"';

		/** This filter is documented in wp-includes/post-template.php */
		$item_output = $indent . sprintf(
			'<li class="%s"><a href="%s" %s>%s%s%s</a>',
			$css_classes,
			esc_url( get_permalink( $page->ID ) ),
			$attributes,
			$args['link_before'],
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after']
		);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$item_output .= " " . mysql2date( $date_format, $time );
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $css_class, $depth, $args );
	}
	
}


/**
 * Modify menu item classes
 */
function graphene_page_css_class( $classes, $page, $depth, $args, $current_page ){
	
	if ( $depth > 0 && in_array( 'menu-item-has-children', $classes ) ) $classes[] = 'dropdown-submenu';
	
	return $classes;
}
add_filter( 'page_css_class', 'graphene_page_css_class', 10, 5 );