<?php
/**
 * Makes a custom Widget for Displaying Ads
 *
 * Learn more: http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */

if ( ! function_exists( 'catchbox_widgets_init' ) ):
/**
 * Register our sidebars and widgetized areas.
 *
 * @since Catch Box 1.0
 */
function catchbox_widgets_init() {

	register_widget( 'catchbox_adwidget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'catch-box' ),
		'id' => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => "</section>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'catch-box' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'catch-box' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'catch-box' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'catch-box' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'catch-box' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'catch-box' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
endif; // catchbox_widgets_init
add_action( 'widgets_init', 'catchbox_widgets_init' );


class catchbox_adwidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'widget_catchbox_adwidget', // Base ID
			__( 'CT: Adspace Widget', 'catch-box' ), // Name
			array( 'description' => __( 'Use this widget to add any type of Ad as a widget. ', 'catch-box' ) ) // Args
		);
	}


	/**
	 * Creates the form for the widget in the back-end which includes the Title , adcode, image, alt
	 * $instance Current settings
	 */
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'adcode' => '', 'image' => '', 'href' => '', 'alt' => '' ) );
		$title = esc_attr( $instance[ 'title' ] );
		$adcode = esc_textarea( $instance[ 'adcode' ] );
		$image = esc_url( $instance[ 'image' ] );
		$href = esc_url( $instance[ 'href' ] );
		$alt = esc_attr( $instance[ 'alt' ] );
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','catch-box'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <?php if ( current_user_can( 'unfiltered_html' ) ) : // Only show it to users who can edit it ?>
            <p>
                <label for="<?php echo $this->get_field_id('adcode'); ?>"><?php _e('Ad Code:','catch-box'); ?></label>
                <textarea name="<?php echo $this->get_field_name('adcode'); ?>" class="widefat" id="<?php echo $this->get_field_id('adcode'); ?>"><?php echo $adcode; ?></textarea>
            </p>
            <p><strong>or</strong></p>
        <?php endif; ?>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image Url:','catch-box'); ?></label>
        <input type="text" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image; ?>" class="widefat" id="<?php echo $this->get_field_id('image'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('href'); ?>"><?php _e('Link URL:','catch-box'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('href'); ?>" value="<?php echo esc_url( $href ); ?>" class="widefat" id="<?php echo $this->get_field_id('href'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alt text:','catch-box'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('alt'); ?>" value="<?php echo $alt; ?>" class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" />
        </p>
        <?php
	}

	/**
	 * update the particular instant
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * $new_instance New settings for this instance as input by the user via form()
	 * $old_instance Old settings for this instance
	 * Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['adcode'] = wp_kses_stripslashes($new_instance['adcode']);
		$instance['image'] = esc_url_raw($new_instance['image']);
		$instance['href'] = esc_url_raw($new_instance['href']);
		$instance['alt'] = sanitize_text_field($new_instance['alt']);

		return $instance;
	}

	/**
	 * Displays the Widget in the front-end.
	 *
	 * $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		$title = !empty( $instance['title'] ) ? $instance[ 'title' ] : '';
		$adcode = !empty( $instance['adcode'] ) ? $instance[ 'adcode' ] : '';
		$image = !empty( $instance['image'] ) ? $instance[ 'image' ] : '';
		$href = !empty( $instance['href'] ) ? $instance[ 'href' ] : '';
		$alt = !empty( $instance['alt'] ) ? $instance[ 'alt' ] : '';


		echo $before_widget;
		if ( '' != $title ) {
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		} else {
			echo '<span class="paddingtop"></span>';
		}

		if ( '' != $adcode ) {
			echo $adcode;
		} else {
			?><a href="<?php echo $href; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" /></a><?php
		}
		echo $after_widget;
	}

}