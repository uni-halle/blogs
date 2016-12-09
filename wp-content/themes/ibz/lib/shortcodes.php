<?php

function tribe_get_event_taxonomy1( $post_id = null, $args = array() ) {
		$post_id   = Tribe__Events__Main::postIdHelper( $post_id );
		$tribe_ecp = Tribe__Events__Main::instance();
		$defaults  = array(
			'taxonomy' => $tribe_ecp->get_event_taxonomy(),
			'before'   => '<li>',
			'sep'      => '</li><li>',
			'after'    => '</li>',
		);
		$args      = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		$taxonomy = get_the_terms( $post_id, $taxonomy, $before, $sep, $after );

		return apply_filters( 'tribe_get_event_taxonomy', $taxonomy, $post_id, $args );
	}




 function maja_ecs_fetch_events( $atts )
	{
		/**
		 * Check if events calendar plugin method exists
		 */
		if ( !function_exists( 'tribe_get_events' ) ) {
			return;
		}

		global $wp_query, $post;
		$output = '';
		
		$atts = shortcode_atts( array(
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
			'contentorder' => 'title, thumbnail, excerpt, date, venue',
			'event_tax' => '',
		), $atts, 'ecs-list-events' );

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
		$meta_date_date = date( 'Y-m-d' );

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

			$month_startdate = date($month_yearstr . "-" . $month_monthstr . "-1");
			$month_enddate = date($month_yearstr . "-" . $month_monthstr . "-t");

			$atts['meta_date'] = array(
				array(
					'key' => $atts['key'],
					'value' => array($month_startdate, $month_enddate),
					'compare' => 'BETWEEN',
					'type' => 'DATETIME'
				)
			);
		}

		$posts = get_posts( array(
			'post_type' => 'tribe_events',
			'posts_per_page' => $atts['limit'],
			'tax_query'=> $atts['event_tax'],
			'meta_key' => $atts['key'],
			'orderby' => 'meta_value',
			'author' => $atts['author'],
			'order' => $atts['order'],
			'meta_query' => array( $atts['meta_date'] )
		) );
		//var_export($atts['contentorder'] );
		
		

		if ($posts) {
			$output .= '<ul class="ecs-event-list">';
			$atts['contentorder'] = explode( ',', $atts['contentorder'] );

			foreach( $posts as $post ) :
			//var_export($post);
				setup_postdata( $post );
				$defaults   = array(
			'echo'         => false,
			'label'        => '',
			'label_before' => '',
			'label_after'  => '',
			'wrap_before'  => '',
			'wrap_after'   => '',
			'before'   => '',
			'sep'      => '',
			'after'    => '',
		);
				//var_export(tribe_get_event_taxonomy(null,$defaults));
				//var_export($post);
				$output .= '<li class="ecs-event">';

				// Put Values into $output
				$h=tribe_get_event_taxonomy1(null,$defaults);
				
				foreach ( $atts['contentorder'] as $contentorder ) {
					switch ( trim( $contentorder ) ) {
						case 'title' :
							$output .= '<h4 class="entry-title summary">' .
											'<a href="' . tribe_get_event_link() . '" rel="bookmark">' . apply_filters( 'ecs_event_list_title', get_the_title(), $atts ) . '</a>
										</h4><span class="event_cat">'.$h[0]->name. '</span><br />';
							break;

						case 'thumbnail' :
							if( $atts['thumb']!==false ) {
								$thumbWidth = is_numeric($atts['thumbwidth']) ? $atts['thumbwidth'] : '';
								$thumbHeight = is_numeric($atts['thumbheight']) ? $atts['thumbheight'] : '';
								if( !empty($thumbWidth) && !empty($thumbHeight) ) {
									$output .= get_the_post_thumbnail(get_the_ID(), array($thumbWidth, $thumbHeight) );
								} else {

									$size = ( !empty($thumbWidth) && !empty($thumbHeight) ) ? array( $thumbWidth, $thumbHeight ) : 'medium';

									if ( $thumb = get_the_post_thumbnail( get_the_ID(), $size ) ) {
										$output .= '<a href="' . tribe_get_event_link() . '">';
										$output .= $thumb;
										$output .= '</a>';
									}
								}
							}
							break;
/*
						case 'excerpt' :
							if( $atts['excerpt']!==false ) {
								$excerptLength = is_numeric($atts['excerpt']) ? $atts['excerpt'] : 100;
								$output .= '<p class="ecs-excerpt">' .
												self::get_excerpt($excerptLength) .
											'</p>';
							}
							break;
*/
						case 'date' :
							if( $atts['eventdetails']!==false ) {
								$output .= '<span class="duration time">' . apply_filters( 'ecs_event_list_details', tribe_events_event_schedule_details(), $atts ) . '</span>';
							}
							break;

						case 'venue' :
							if( $atts['venue']!==false ) {
								$output .= '<span class="duration venue"><em> at </em>' . apply_filters( 'ecs_event_list_venue', tribe_get_venue(), $atts ) . '</span>';
							}
							break;
					}
				}
				$output .= '</li>';
			endforeach;
			$output .= '</ul>';
return $output;
			if( self::isValid($atts['viewall']) ) {
				$output .= '<span class="ecs-all-events"><a href="' . apply_filters( 'ecs_event_list_viewall_link', tribe_get_events_link(), $atts ) .'" rel="bookmark">' . translate( 'View All Events', 'tribe-events-calendar' ) . '</a></span>';
			}

		} else { //No Events were Found
			$output .= translate( $atts['message'], 'tribe-events-calendar' );
		} // endif

		wp_reset_query();

		return $output;
	}


add_shortcode('maja_fetch_events', 'maja_ecs_fetch_events');
class BlogPost
{
    var $date;
    var $ts;
    var $link;

    var $title;
    var $text;
}

class BlogFeed
{
    var $posts = array();

    function BlogFeed($file_or_url)
    {
        if(!eregi('^http:', $file_or_url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/shared/xml/'. $file_or_url;
        else
            $feed_uri = $file_or_url;

        $xml_source = file_get_contents($feed_uri);
        $x = simplexml_load_string($xml_source);

        if(count($x) == 0)
            return;

        foreach($x->channel->item as $item)
        {
            $post = new BlogPost();
            $post->date = (string) $item->pubDate;
            $post->ts = strtotime($item->pubDate);
            $post->link = (string) $item->link;
            $post->title = (string) $item->title;
            $post->text = (string) $item->description;

            // Create summary as a shortened body and remove images, extraneous line breaks, etc.
            $summary = $post->text;
            $summary = eregi_replace("<img[^>]*>", "", $summary);
            $summary = eregi_replace("^(<br[ ]?/>)*", "", $summary);
            $summary = eregi_replace("(<br[ ]?/>)*$", "", $summary);

            // Truncate summary line to 100 characters
            $max_len = 100;
            if(strlen($summary) > $max_len)
                $summary = substr($summary, 0, $max_len) . '...';

            $post->summary = $summary;

            $this->posts[] = $post;
        }
    }
}




/**
 * All shortcodes that can be used with Maja theme
 */

// ==============================================
// 			 LAYOUT (COLUMNS)
// ==============================================

//one fourth
function maja_one_fourth( $atts, $content = null ) {
   return '<div class="one_fourth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fourth', 'maja_one_fourth');

//one third
function maja_one_third( $atts, $content = null ) {
   return '<div class="one_third">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_third', 'maja_one_third');

//two thirds
function maja_two_thirds( $atts, $content = null ) {
   return '<div class="two_thirds">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_thirds', 'maja_two_thirds');

//three fourths
function maja_three_fourths( $atts, $content = null ) {
   return '<div class="three_fourths">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fourths', 'maja_three_fourths');

//one half
function maja_one_half( $atts, $content = null ) {
   return '<div class="one_half">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_half', 'maja_one_half');


//one
function maja_one( $atts, $content = null ) {
   return '<div class="one">' . do_shortcode($content) . '</div>';
}
add_shortcode('one', 'maja_one');

function maja_rss_reader( $atts, $content = null ) {

	$posts= new BlogFeed($content);
	$ret='<div class="rss_reader_container"><h2>'.$atts['headline'].'</h2><ul class="rss_reader">';
	foreach( $posts->posts as $v)
		$ret.='<li><h3>'.$v->title.'</h3>'.$v->text.'</li>';
	$ret.='</ul></div>';
	return $ret;
}
add_shortcode('rss_reader', 'maja_rss_reader');

function maja_panorama( $atts, $content = null ) {

	$url=get_template_directory_uri().'/panoramas/'.$atts["name"].'/Image.html?base='.get_template_directory_uri().'/panoramas/'.$atts["name"].'/&';
$ret='<div class="panorama">';
$ret.='<iframe  class="panorama" scrolling="no"  frameborder="0"  marginwidth="0" marginheight="0" src="'.$url.'" allowfullscreen mozallowfullscreen webkitallowfullscreen></iframe></div>';
	
	return $ret;
}
add_shortcode('panorama', 'maja_panorama');


function maja_intern_news( $atts, $content = null ) {

$lang = qtrans_getLanguage();
	$ret= '<div class="news_container"><h2>'.$atts['headline_'.$lang].'</h2><ul class="intern _news">';
	$intern_news = get_posts(array( 'category'=>get_cat_ID( $content)));
	foreach( $intern_news as $post ){
	$lang = qtrans_getLanguage();
 $translations = qtrans_split( $post->post_title);
 $title= $translations[$lang];
 $translations = qtrans_split( $post->post_content);
 $content= $translations[$lang];
 
	//qtrans_getLanguage()) 
	//var_export($post);
		$ret.= '<li><h3>'.$title.'</h3>'. $content.'</li>' ;

	}
	$ret.='</ul></div>';
	return $ret;
}
add_shortcode('intern_news', 'maja_intern_news');

// ==============================================
// 			 FLEX SLIDER
// ==============================================

//flex slider
function maja_slider($content) {
	ob_start();	
	include(TEMPLATEPATH . "/flex-slider.php");
	global $maja_slider;	
	$maja_slider = ob_get_clean();	
    return $maja_slider;	
}
add_shortcode('flex-slider', 'maja_slider');

//flex slider (backward compatibility with theme v1.0)
function maja_nivo($content) {
	ob_start();	
	include(TEMPLATEPATH . "/flex-slider.php");
	global $maja_nivo;	
	$maja_nivo = ob_get_clean();	
    return $maja_nivo;	
}
add_shortcode('nivo-slider', 'maja_nivo');

// ==============================================
// 			 PORTFOLIO
// ==============================================

// 2 columns
function maja_portfolio_2col($atts) {
	ob_start();
	include(TEMPLATEPATH . "/portfolio_2col.php");
	global $content;	
	$content = ob_get_clean();	
    return $content;
}
add_shortcode('portfolio-2col', 'maja_portfolio_2col');

// 3 columns
function maja_portfolio_3col($atts) {
	ob_start();
	include(TEMPLATEPATH . "/portfolio_3col.php");
	global $content;	
	$content = ob_get_clean();	
    return $content;
}
add_shortcode('portfolio-3col', 'maja_portfolio_3col');

// 4 columns
function maja_portfolio_4col($atts) {
	ob_start();
	include(TEMPLATEPATH . "/portfolio_4col.php");
	global $content;	
	$content = ob_get_clean();	
    return $content;
}
add_shortcode('portfolio-4col', 'maja_portfolio_4col');

// ==============================================
// 			 GOOGLE MAP
// ==============================================

function maja_map($atts) {
	ob_start();
	include(TEMPLATEPATH . "/google-map.php");
	$content = ob_get_clean();
    return $content;
}
add_shortcode('google-map', 'maja_map');

// ==============================================
// 			 BUTTONS, SEPARATORS...
// ==============================================

//regular button
function maja_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'link'	=> '#',
    'target'	=> '',
    'align'	=> '',
    ), $atts));

	$align = ($align) ? ' align'.$align : '';
	$target = ($target) ? ' target='.$target : '';

	$out = '<a' .$target. ' class="maja_button' .$align. '" href="' .$link. '">' .do_shortcode($content). '</a>';

    return $out;
}
add_shortcode('button', 'maja_button');

// line separator
function maja_separator() {
   return '<div class="separator"></div>';
}
add_shortcode('separator', 'maja_separator');

// clear
function maja_clear() {
   return '<div class="clear"></div>';
}
add_shortcode('clear', 'maja_clear');

// ==============================================
// 			 HEADINGS
// ==============================================

// Main title
function maja_title( $atts, $content = null ) {
   return '<h2 class="main_title">' . do_shortcode($content) . '</h2>';
}
add_shortcode('title', 'maja_title');

// Subtitle
function maja_subtitle( $atts, $content = null ) {
   return '<h3 class="subtitle">' . do_shortcode($content) . '</h3>';
}
add_shortcode('subtitle', 'maja_subtitle');


// ==============================================
// 			 LISTS (UL, OL)
// ==============================================

function maja_lists($atts, $content=null){

    extract(shortcode_atts(array(
	  'type' => 'check',
	  'type' => 'bullet',
	  'type' => 'arrow'
	  ),$atts));	  
    $ret = '<div class="lists-' . $type . '">'.do_shortcode($content);
    $ret .= '</div>';
    return $ret;
}
add_shortcode('lists', 'maja_lists');

// ==============================================
// 			 POPULAR VIDEO SERVICES
// ==============================================

/* youtube */
function youtube_video($atts, $content=null) {  
    extract(shortcode_atts( array(  
        'id' => '',  
        'width' => '475',  
        'height' => '275',
    ), $atts));  
    return '<iframe class="video_embed" width="' . $width . '" height="' . $height .'" src="http://www.youtube.com/embed/' . $id . '?rel=0" frameborder="0" allowfullscreen></iframe>';  
}  
add_shortcode('youtube', 'youtube_video');

/* vimeo */
function vimeo_video($atts, $content=null) {  
    extract(shortcode_atts( array(  
        'id' => '',  
        'width' => '475',
        'height' => '265',  
        'color' => '59a5d1'  
    ), $atts));  
    return '<iframe class="video_embed" src="http://player.vimeo.com/video/' . $id . '?color=' . $color . '" width="' . $width .'" height="' . $height . '" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>';  
}  
add_shortcode('vimeo', 'vimeo_video'); 

?>