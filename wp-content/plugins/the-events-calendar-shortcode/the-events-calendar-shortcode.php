<?php
/***
 Plugin Name: The Events Calendar Shortcode
 Plugin URI: https://eventcalendarnewsletter.com/the-events-calendar-shortcode/
 Description: An addon to add shortcode functionality for <a href="http://wordpress.org/plugins/the-events-calendar/">The Events Calendar Plugin (Free Version) by Modern Tribe</a>.
 Version: 1.3
 Author: Event Calendar Newsletter
 Author URI: https://eventcalendarnewsletter.com/the-events-calendar-shortcode/
 Contributors: Brainchild Media Group, Reddit user miahelf, tallavic, hejeva2
 License: GPL2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Avoid direct calls to this file
if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Events calendar shortcode addon main class
 *
 * @package events-calendar-shortcode
 * @author Brian Hogg
 * @version 1.0.10
 */

if ( ! class_exists( 'Events_Calendar_Shortcode' ) ) {

class Events_Calendar_Shortcode
{
	/**
	 * Current version of the plugin.
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.3';

	private $admin_page = null;

	const MENU_SLUG = 'ecs-admin';

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see	 add_shortcode()
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 1000 );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_action_links' ) );
		add_shortcode( 'ecs-list-events', array( $this, 'ecs_fetch_events' ) );
	} // END __construct()

	public function add_menu_page() {
		if ( ! class_exists( 'Tribe__Settings' ) or ! method_exists( Tribe__Settings::instance(), 'should_setup_pages' ) or ! Tribe__Settings::instance()->should_setup_pages() ) {
			return;
		}

		$page_title = esc_html__( 'Shortcode', 'ecs' );
		$menu_title = esc_html__( 'Shortcode', 'tribe-common' );
		$capability = apply_filters( 'ecs_admin_page_capability', 'install_plugins' );

		$where = Tribe__Settings::instance()->get_parent_slug();

		$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::MENU_SLUG, array( $this, 'do_menu_page' ) );

		add_action( 'admin_print_styles-' . $this->admin_page, array( $this, 'enqueue' ) );
		add_action( 'admin_print_styles', array( $this, 'enqueue_submenu_style' ) );
	}

	public function enqueue() {
		wp_enqueue_style( 'ecs-admin-css', plugins_url( 'static/ecs-admin.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_script( 'ecs-admin-js', plugins_url( 'static/ecs-admin.js', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Function to add a small CSS file to add some colour to the Shortcode submenu item
	 */
	public function enqueue_submenu_style() {
		wp_enqueue_style( 'ecs-submenu-css', plugins_url( 'static/ecs-submenu.css', __FILE__ ), array(), self::VERSION );
	}

	public function do_menu_page() {
		include dirname( __FILE__ ) . '/templates/admin-page.php';
	}

	public function add_action_links( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'edit.php?post_type=tribe_events&page=ecs-admin' ) . '">Settings</a>',
			'<a target="_blank" style="color:#3db634; font-weight: bold;" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode/?utm_source=plugin-list&utm_medium=upgrade-link&utm_campaign=plugin-list&utm_content=action-link">Upgrade</a>',
		);
		return array_merge( $links, $mylinks );
	}

	/**
	 * Fetch and return required events.
	 * @param  array $atts 	shortcode attributes
	 * @return string 	shortcode output
	 */
	public function ecs_fetch_events( $atts ) {
		/**
		 * Check if events calendar plugin method exists
		 */
		if ( !function_exists( 'tribe_get_events' ) ) {
			return;
		}

		global $wp_query, $post;
		$output = '';

		$atts = shortcode_atts( apply_filters( 'ecs_shortcode_atts', array(
			'cat' => '',
			'month' => '',
			'limit' => 5,
			'eventdetails' => 'true',
			'time' => null,
			'past' => null,
			'venue' => 'false',
			'author' => null,
			'message' => 'There are no upcoming events at this time.',
			'key' => 'End Date',
			'order' => 'ASC',
			'viewall' => 'false',
			'excerpt' => 'false',
			'thumb' => 'false',
			'thumbwidth' => '',
			'thumbheight' => '',
			'contentorder' => apply_filters( 'ecs_default_contentorder', 'title, thumbnail, excerpt, date, venue', $atts ),
			'event_tax' => '',
		), $atts ), $atts, 'ecs-list-events' );

		// Category
		if ( $atts['cat'] ) {
			if ( strpos( $atts['cat'], "," ) !== false ) {
				$atts['cats'] = explode( ",", $atts['cat'] );
				$atts['cats'] = array_map( 'trim', $atts['cats'] );
			} else {
				$atts['cats'] = $atts['cat'];
			}

			$atts['event_tax'] = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'tribe_events_cat',
					'field' => 'name',
					'terms' => $atts['cats'],
				),
				array(
					'taxonomy' => 'tribe_events_cat',
					'field' => 'slug',
					'terms' => $atts['cats'],
				)
			);
		}

		// Past Event
		$meta_date_compare = '>=';
		$meta_date_date = current_time( 'Y-m-d H:i:s' );

		if ( $atts['time'] == 'past' || !empty( $atts['past'] ) ) {
			$meta_date_compare = '<';
		}

		// Key
		if ( str_replace( ' ', '', trim( strtolower( $atts['key'] ) ) ) == 'startdate' ) {
			$atts['key'] = '_EventStartDate';
		} else {
			$atts['key'] = '_EventEndDate';
		}
		// Date
		$atts['meta_date'] = array(
			array(
				'key' => $atts['key'],
				'value' => $meta_date_date,
				'compare' => $meta_date_compare,
				'type' => 'DATETIME'
			)
		);

		// Specific Month
		if ( $atts['month'] == 'current' ) {
			$atts['month'] = date( 'Y-m' );
		}
		if ($atts['month']) {
			$month_array = explode("-", $atts['month']);
			
			$month_yearstr = $month_array[0];
			$month_monthstr = $month_array[1];
			$month_startdate = date( "Y-m-d", strtotime( $month_yearstr . "-" . $month_monthstr . "-01" ) );
			$month_enddate = date( "Y-m-01", strtotime( "+1 month", strtotime( $month_startdate ) ) );

			$atts['meta_date'] = array(
				array(
					'key' => $atts['key'],
					'value' => array($month_startdate, $month_enddate),
					'compare' => 'BETWEEN',
					'type' => 'DATETIME'
				)
			);
		}

		$posts = tribe_get_events( apply_filters( 'ecs_get_events_args', array(
			'post_status' => 'publish',
			'hide_upcoming' => true,
			'posts_per_page' => $atts['limit'],
			'tax_query'=> $atts['event_tax'],
			'meta_key' => $atts['key'],
			'orderby' => 'meta_value',
			'author' => $atts['author'],
			'order' => $atts['order'],
			'meta_query' => array( $atts['meta_date'] ),
		), $atts ) );

		if ( $posts ) {
			$output .= apply_filters( 'ecs_start_tag', '<ul class="ecs-event-list">', $atts );
			$atts['contentorder'] = explode( ',', $atts['contentorder'] );

			foreach( $posts as $post ) {
				setup_postdata( $post );

				$output .= apply_filters( 'ecs_event_start_tag', '<li class="ecs-event">', $atts, $post );

				// Put Values into $output
				foreach ( apply_filters( 'ecs_event_contentorder', $atts['contentorder'], $atts, $post ) as $contentorder ) {
					switch ( trim( $contentorder ) ) {
						case 'title':
							$output .= apply_filters( 'ecs_event_title_tag_start', '<h4 class="entry-title summary">', $atts, $post ) .
											'<a href="' . tribe_get_event_link() . '" rel="bookmark">' . apply_filters( 'ecs_event_list_title', get_the_title(), $atts, $post ) . '</a>' .
							           apply_filters( 'ecs_event_title_tag_end', '</h4>', $atts, $post );
							break;

						case 'thumbnail':
							if ( self::isValid( $atts['thumb'] ) ) {
								$thumbWidth = is_numeric($atts['thumbwidth']) ? $atts['thumbwidth'] : '';
								$thumbHeight = is_numeric($atts['thumbheight']) ? $atts['thumbheight'] : '';
								if( !empty( $thumbWidth ) && !empty( $thumbHeight ) ) {
									$output .= apply_filters( 'ecs_event_thumbnail', get_the_post_thumbnail( get_the_ID(), apply_filters( 'ecs_event_thumbnail_size', array( $thumbWidth, $thumbHeight ), $atts, $post ) ), $atts, $post );
								} else {
									if ( $thumb = get_the_post_thumbnail( get_the_ID(), apply_filters( 'ecs_event_thumbnail_size', 'medium', $atts, $post ) ) ) {
										$output .= apply_filters( 'ecs_event_thumbnail_link_start', '<a href="' . tribe_get_event_link() . '">', $atts, $post );
										$output .= apply_filters( 'ecs_event_thumbnail', $thumb, $atts, $post );
										$output .= apply_filters( 'ecs_event_thumbnail_link_end', '</a>', $atts, $post );
									}
								}
							}
							break;

						case 'excerpt':
							if ( self::isValid( $atts['excerpt'] ) ) {
								$excerptLength = is_numeric($atts['excerpt']) ? $atts['excerpt'] : 100;
								$output .= apply_filters( 'ecs_event_excerpt_tag_start', '<p class="ecs-excerpt">', $atts, $post ) .
								           apply_filters( 'ecs_event_excerpt', self::get_excerpt( $excerptLength ), $atts, $post ) .
								           apply_filters( 'ecs_event_excerpt_tag_end', '</p>', $atts, $post );
							}
							break;

						case 'date':
							if ( self::isValid( $atts['eventdetails'] ) ) {
								$output .= apply_filters( 'ecs_event_date_tag_start', '<span class="duration time">', $atts, $post ) .
								           apply_filters( 'ecs_event_list_details', tribe_events_event_schedule_details(), $atts, $post ) .
								           apply_filters( 'ecs_event_date_tag_end', '</span>', $atts, $post );
							}
							break;

						case 'venue':
							if ( self::isValid( $atts['venue'] ) ) {
								$output .= apply_filters( 'ecs_event_venue_tag_start', '<span class="duration venue">', $atts, $post ) .
								           apply_filters( 'ecs_event_venue_at_tag_start', '<em> ', $atts, $post ) .
								           apply_filters( 'ecs_event_venue_at_text', __( 'at', 'the-events-calendar-shortcode' ), $atts, $post ) .
								           apply_filters( 'ecs_event_venue_at_tag_end', ' </em>', $atts, $post ) .
								           apply_filters( 'ecs_event_list_venue', tribe_get_venue(), $atts, $post ) .
								           apply_filters( 'ecs_event_venue_tag_end', '</span>', $atts, $post );
							}
							break;
						case 'date_thumb':
							if ( self::isValid( $atts['eventdetails'] ) ) {
								$output .= apply_filters( 'ecs_event_date_thumb', '<div class="date_thumb"><div class="month">' . tribe_get_start_date( null, false, 'M' ) . '</div><div class="day">' . tribe_get_start_date( null, false, 'j' ) . '</div></div>', $atts, $post );
							}
							break;
						default:
							$output .= apply_filters( 'ecs_event_list_output_custom', '', trim( $contentorder ), $atts, $post );
					}
				}
				$output .= apply_filters( 'ecs_event_end_tag', '</li>', $atts, $post );
			}
			$output .= apply_filters( 'ecs_end_tag', '</ul>', $atts );

			if( self::isValid( $atts['viewall'] ) ) {
				$output .= apply_filters( 'ecs_view_all_events_tag_start', '<span class="ecs-all-events">', $atts ) .
				           '<a href="' . apply_filters( 'ecs_event_list_viewall_link', tribe_get_events_link(), $atts ) .'" rel="bookmark">' . translate( 'View All Events', 'tribe-events-calendar' ) . '</a>';
				$output .= apply_filters( 'ecs_view_all_events_tag_end', '</span>' );
			}

		} else { //No Events were Found
			$output .= apply_filters( 'ecs_no_events_found_message', translate( $atts['message'], 'tribe-events-calendar' ), $atts );
		} // endif

		wp_reset_query();

		return $output;
	}

	/**
	 * Checks if the plugin attribute is valid
	 *
	 * @since 1.0.5
	 *
	 * @param string $prop
	 * @return boolean
	 */
	public static function isValid( $prop )
	{
		return ( $prop !== 'false' );
	}

	/**
	 * Fetch and trims the excerpt to specified length
	 *
	 * @param integer $limit Characters to show
	 * @param string $source  content or excerpt
	 *
	 * @return string
	 */
	private function get_excerpt( $limit, $source = null )
	{
		$excerpt = get_the_excerpt();
		if( $source == "content" ) {
			$excerpt = get_the_content();
		}

		$excerpt = preg_replace( " (\[.*?\])", '', $excerpt );
		$excerpt = strip_tags( strip_shortcodes($excerpt) );
		$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
		if ( strlen( $excerpt ) > $limit ) {
			$excerpt = substr( $excerpt, 0, $limit );
			$excerpt .= '...';
		}

		return $excerpt;
	}
}

}

/**
 * Instantiate the main class
 *
 * @since 1.0.0
 * @access public
 *
 * @var	object	$events_calendar_shortcode holds the instantiated class {@uses Events_Calendar_Shortcode}
 */
global $events_calendar_shortcode;
$events_calendar_shortcode = new Events_Calendar_Shortcode();
