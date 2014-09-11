<?php
/*
Plugin Name: Multiple Twitter Widgets
Description: Allows for multiple twitter widgets to be displayed.
Version: 1.0
Author: Patrick Chia
Author URI: http://patrickchia.com/
Plugin URI: http://patrick.bloggles.info/plugins/
Tags: wpmu, wordpressmu, widgets, twitter
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mypatricks@gmail.com&item_name=Donate%20to%20Patrick%20Chia&item_number=1242543308&amount=15.00&no_shipping=0&no_note=1&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8&return=http://patrick.bloggles.info/
*/

function wp_widget_twitter($args, $widget_args = 1) {
	extract($args, EXTR_SKIP);
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract($widget_args, EXTR_SKIP);

	$options = get_option('widget_twitter');

	if ( !isset($options[$number]) )
		return;

	if ( isset($options[$number]['error']) && $options[$number]['error'] )
		return;

	$account = $options[$number]['account'];
	$url = 'https://twitter.com/statuses/user_timeline/'.$account.'.rss';
	
	if ( empty($account) )
		return;

	require_once(ABSPATH . WPINC . '/rss.php');

	$rss = fetch_rss($url);

	$link = clean_url(strip_tags($rss->channel['link']));
	while ( strstr($link, 'http') != $link )
		$link = substr($link, 1);
	$desc = attribute_escape(strip_tags(html_entity_decode($rss->channel['description'], ENT_QUOTES)));
	$title = $options[$number]['title'];
	if ( empty($title) )
		$title = htmlentities(strip_tags($rss->channel['title']));
	if ( empty($title) )
		$title = $desc;
	if ( empty($title) )
		$title = __('Unknown Feed');
	$title = apply_filters('widget_title', $title );
	$account = apply_filters('widget_account', $account );

	$title = "<a class='rsswidget' href='$link' title='$desc'>$title</a>";

	echo $before_widget;
	echo $before_title . $title . $after_title;

	wp_widget_twitter_output( $rss, $options[$number] );

	echo $after_widget;
	echo "<!-- Widgets by Patrick Chia http://patrickchia.com/ -->";
}

function wp_widget_twitter_output( $rss, $args = array() ) {
	if ( is_string( $rss ) ) {
		require_once(ABSPATH . WPINC . '/rss.php');
		if ( !$rss = fetch_rss($rss) )
			return;
	} elseif ( is_array($rss) && isset($rss['url']) ) {
		require_once(ABSPATH . WPINC . '/rss.php');
		$args = $rss;
		if ( !$rss = fetch_rss($rss['url']) )
			return;
	} elseif ( !is_object($rss) ) {
		return;
	}

	$args = wp_parse_args( $args );
	extract( $args, EXTR_SKIP );

	$items = (int) $items;
	if ( $items < 1 || 10 < $items )
		$items = 10;

	$show_date = true;

	if ( is_array( $rss->items ) && !empty( $rss->items ) ) {
		$rss->items = array_slice($rss->items, 0, $items);
		echo '<ul>';
		foreach ( (array) $rss->items as $item ) {
			while ( strstr($item['link'], 'http') != $item['link'] )
				$item['link'] = substr($item['link'], 1);
			$link = clean_url(strip_tags($item['link']));
			$title = attribute_escape(strip_tags($item['title']));
			if ( empty($title) )
				$title = $item['title'];
			$desc = '';
			if ( isset( $item['description'] ) && is_string( $item['description'] ) )
				$desc = str_replace(array("\n", "\r"), ' ', attribute_escape(strip_tags(html_entity_decode($item['description'], ENT_QUOTES))));
			elseif ( isset( $item['summary'] ) && is_string( $item['summary'] ) )
				$desc = str_replace(array("\n", "\r"), ' ', attribute_escape(strip_tags(html_entity_decode($item['summary'], ENT_QUOTES))));
			if ( 360 < strlen( $desc ) )
				$desc = wp_html_excerpt( $desc, 360 ) . ' [&hellip;]';

			$date = '';
			if ( $show_date ) {
				if ( isset($item['pubdate']) )
					$date = $item['pubdate'];
				elseif ( isset($item['published']) )
					$date = $item['published'];

				if ( $date ) {
					if ( $date_stamp = strtotime( $date ) )
						$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date_stamp ) . '</span>';
					else
						$date = '';
				}
			}
			$title = " ".substr(strstr($title,': '), 2, strlen($title))." ";
			$title = twitter_users($title);
			$title = twitter_hyperlinks($title);

			if ( $link == '' ) {
				echo "<li>$title{$date}{$summary}{$author}</li>";
			} else {
				echo "<li>$title <a class='rsswidget' rel='nofollow' href='$link'>";
					printf(__('%s ago'), human_time_diff(strtotime($item['pubdate'], time() ) ) );
				echo "</a>{$summary}{$author} {$date}</li>";
			}
}
		echo '</ul>';
	} else {
		echo '<ul><li>' . __( 'No response from Twitter.' ) . '</li></ul>';
	}
}

function twitter_users($text) {
	$text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
	return $text;
}

function twitter_hyperlinks($text) {
	$text = preg_replace("/\s([a-zA-Z]+:\/\/[a-z][a-z0-9\_\.\-]*[a-z]{2,6}[a-zA-Z0-9\/\*\-\?\&\%]*)([\s|\.|\,])/i"," <a href=\"$1\" class=\"twitter-link\">$1</a>$2", $text);
	$text = preg_replace("/\s(www\.[a-z][a-z0-9\_\.\-]*[a-z]{2,6}[a-zA-Z0-9\/\*\-\?\&\%]*)([\s|\.|\,])/i"," <a href=\"http://$1\" class=\"twitter-link\">$1</a>$2", $text);      
	$text = preg_replace("/\s([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})([\s|\.|\,])/i"," <a href=\"mailto://$1\" class=\"twitter-link\">$1</a>$2", $text);    
	return $text;
}

function wp_widget_twitter_control($widget_args) {
	global $wp_registered_widgets;
	static $updated = false;

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract($widget_args, EXTR_SKIP);

	$options = get_option('widget_twitter');
	if ( !is_array($options) )
		$options = array();

	$accounts = array();
	foreach ( (array) $options as $option )
		if ( isset($option['account']) )
			$urls[$option['account']] = true;

	if ( !$updated && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['sidebar']) ) {
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( (array) $this_sidebar as $_widget_id ) {
			if ( 'wp_widget_twitter' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "twitter-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
					unset($options[$widget_number]);
			}
		}

		foreach( (array) $_POST['widget-twitter'] as $widget_number => $widget_twitter ) {
			if ( !isset($widget_twitter['account']) && isset($options[$widget_number]) ) // user clicked cancel
				continue;
			$widget_twitter = stripslashes_deep( $widget_twitter );
			$account = attribute_escape($widget_twitter['account']);
			$options[$widget_number] = wp_widget_twitter_process( $widget_twitter, !isset($accounts[$account]) );
		}

		update_option('widget_twitter', $options);
		$updated = true;
	}

	if ( -1 == $number ) {
		$title = '';
		$account = '';
		$items = 10;
		$error = false;
		$number = '%i%';
	} else {
		extract( (array) $options[$number] );
	}

	wp_widget_twitter_form( compact( 'number', 'title', 'account', 'items', 'error' ) );
}

function wp_widget_twitter_form( $args, $inputs = null ) {

	$default_inputs = array( 'account' => true, 'title' => true, 'items' => true );
	$inputs = wp_parse_args( $inputs, $default_inputs );
	extract( $args );
	extract( $inputs, EXTR_SKIP);

	$number		= attribute_escape( $number );
	$title		= attribute_escape( $title );
	$account	= attribute_escape( $account );
	$items		= (int) $items;
	if ( $items < 1 || 10 < $items )
		$items  = 10;

	if ( $inputs['title'] ) :
?>
	<p>
		<label for="twitter-title-<?php echo $number; ?>"><?php _e('Title:'); ?>
			<input class="widefat" id="twitter-title-<?php echo $number; ?>" name="widget-twitter[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
		</label>
	</p>
<?php endif; if ( $inputs['account'] ) : ?>
	<p>
		<label for="twitter-account-<?php echo $number; ?>"><?php _e('Twitter username:'); ?>
			<input class="widefat" id="twitter-account-<?php echo $number; ?>" name="widget-twitter[<?php echo $number; ?>][account]" type="text" value="<?php echo $account; ?>" />
		</label>
	</p>
<?php endif; if ( $inputs['items'] ) : ?>
	<p>
		<label for="twitter-show-<?php echo $number; ?>"><?php _e('Number of updates to show:'); ?>
			<select id="twitter-show-<?php echo $number; ?>" name="widget-twitter[<?php echo $number; ?>][items]">
				<?php
					for ( $i = 1; $i <= 10; ++$i )
						echo "<option value='$i' " . ( $items == $i ? "selected='selected'" : '' ) . ">$i</option>";
				?>
			</select>
		</label>
	</p>
	<input type="hidden" name="widget-twitter[<?php echo $number; ?>][submit]" value="1" />
<?php
	endif;
	foreach ( array_keys($default_inputs) as $input ) :
		if ( 'hidden' === $inputs[$input] ) :
			$id = str_replace( '_', '-', $input );
?>
	<input type="hidden" id="twitter-<?php echo $id; ?>-<?php echo $number; ?>" name="widget-twitter[<?php echo $number; ?>][<?php echo $input; ?>]" value="<?php echo $$input; ?>" />
<?php
		endif;
	endforeach;
}

function wp_widget_twitter_process( $widget_twitter, $check_feed = true ) {
	$items = (int) $widget_twitter['items'];
	if ( $items < 1 || 10 < $items )
		$items = 10;

	$account	= trim(strip_tags( $widget_twitter['account'] ));
	$title		= trim(strip_tags( $widget_twitter['title'] ));
	$url		= 'https://twitter.com/statuses/user_timeline/'.$account.'.rss';

	if ( $check_feed ) {
		require_once(ABSPATH . WPINC . '/rss.php');
		$rss = fetch_rss($url);
		$error = false;
		$link = '';
		if ( !is_object($rss) ) {
			$url = wp_specialchars(__('Error: could not find the username.'), 1);
			$error = sprintf(__('Error in RSS %1$d'), $widget_number );
		} else {
			$link = clean_url(strip_tags($rss->channel['link']));
			while ( strstr($link, 'http') != $link )
				$link = substr($link, 1);
		}
	}

	return compact( 'title', 'account', 'link', 'items', 'error' );
}


function wp_widget_twitter_register() {
	if ( !$options = get_option('widget_twitter') )
		$options = array();
	$widget_ops = array('classname' => 'widget_twitter', 'description' => __( 'Display your Twitter updates' ));
	$control_ops = array('width' => 250, 'height' => 200, 'id_base' => 'twitter');
	$name = __('Twitter');

	$id = false;
	foreach ( (array) array_keys($options) as $o ) {
		if ( !isset($options[$o]['account']) || !isset($options[$o]['title']) || !isset($options[$o]['items']) )
			continue;
		$id = "twitter-$o"; // Never never never translate an id
		wp_register_sidebar_widget($id, $name, 'wp_widget_twitter', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'wp_widget_twitter_control', $control_ops, array( 'number' => $o ));
	}

	if ( !$id ) {
		wp_register_sidebar_widget( 'twitter-1', $name, 'wp_widget_twitter', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'twitter-1', $name, 'wp_widget_twitter_control', $control_ops, array( 'number' => -1 ) );
	}
}


function wp_twitter_init() {
	if ( !is_blog_installed() )
		return;

	wp_widget_twitter_register();
}

add_action('init', 'wp_twitter_init', 1);

?>