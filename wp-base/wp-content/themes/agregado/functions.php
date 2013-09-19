<?php

$themename = "Agregado Theme";
$shortname = "Agregado Theme";

if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'sidebar-home',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="widgettitle">',
'after_title' => '</h3>',
));
register_sidebar(array('name'=>'sidebar-interior',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="widgettitle">',
'after_title' => '</h3>',
));

function wpguy_initial_cap($content){
 
    // Regular Expression, matches a single letter
    // * even if it's inside a link tag.
    $searchfor = '/>(<a [^>]+>)?([^<\s])/';
 
    // The string we're replacing the letter for
    $replacewith = '>$1<span class="drop">$2</span>';
 
    // Replace it, but just once (for the very first letter of the post)
    $content = preg_replace($searchfor, $replacewith, $content, 1);
 
    // Return the result
    return $content;
}
 
// Add this function to the WordPress hook
add_filter('the_content', 'wpguy_initial_cap');
add_filter('the_excerpt', 'wpguy_initial_cap');

/*   Adapated from Andy Staines' Paged Navigation plugin - http://stuff.yellowswordfish.com/paged-navigation/ */

define('PNSHOW', 10);

function paged_menu()
{
	if(!is_page()) 
	{
		if(is_single())
		{
			$npost = get_next_post(false);
			if ( $npost )
			{
				$title = apply_filters('the_title', $npost->post_title);
				$string = '<a href="'. get_permalink($npost->ID) .'">';
				echo 'Next Item: '.$string.$title.'</a><br />';
			}

			$ppost = get_previous_post(false);
			if ( $ppost )
			{
				$title = apply_filters('the_title', $ppost->post_title);
				$string = '<a href="'. get_permalink($ppost->ID) .'">';
				echo 'Previous item: '.$string.$title.'</a>';
			} 

			$title = 'Back to the Front Page';
			$string = '<a href="'. get_option('home') .'">';
			echo '<br />'.$string.$title.'</a>';
		} else {

			echo '<div class="pagenavigationbox">';
			$nav = pn_navigation();
			echo '</div>';
			
		}
	}
}

function pn_navigation()
{
	global $paged, $wp_query, $max_page;

	$nav = array();
	
	if ( !$max_page ) 
	{
		$max_page = $wp_query->max_num_pages;
	}
	if( !$paged ) 
	{
		$paged = 1;
	}

	$nav['next'] = pn_next($paged, $max_page);
	echo ('<span class="pagenavcurrent">'.$paged.'</span>');
	$nav['previous'] = pn_previous($paged, $max_page);
	
	return $nav;
}

function pn_next($paged, $max_page)
{
	$start = ($paged - PNSHOW);
	if($start < 1) $start = 1;
	$end = ($paged - 1);

	if($start > 1)
	{
		$out.= '<a class="pagelink" href="'.get_pagenum_link(1).'">1</a><span class="pagenav">...</span>';
	}

	if($end > 0)
	{	
		for($i = $start; $i <= $end; $i++)
		{
			$out.= '<a class="pagelink" href="'.get_pagenum_link($i).'">'.$i.'</a>';
		}
	} else {
		$end = 0;
	}

	echo ($out);
	return $end;
}		

function pn_previous($paged, $max_page)
{
	$start = ($paged + 1);
	$end = ($paged + PNSHOW);
	if($end > $max_page) $end = $max_page;

	if($start <= $max_page)
	{
		for($i = $start; $i <= $end; $i++)
		{
			$out.= '<a class="pagelink" href="'.get_pagenum_link($i).'">'.$i.'</a>';
		}
		if($end < $max_page)
		{
			$out.= '<span class="pagenav">...</span><a class="pagelink" href="'.get_pagenum_link($max_page).'">'.$max_page.'</a>';
		}
	} else {
		$start = 0;
	}
			
	echo ($out);
	return $start;
}

/* Adapted from Kieran Delaney's SimpleLife plugin - http://kierandelaney.net/blog/projects/simplelife/ */ 

//create options page
function agregadoOptions() {
   if (function_exists('add_theme_page')) {
		add_theme_page('', 'Agregado Options', 8, basename(__FILE__), 'agregadoOptionsPage');
    }
}

function agregadoOptionsPage() {
  if (isset($_POST['info_update'])) { ?>
		<div id="message" class="updated fade">
		<p><strong>
<?php
                if($_POST['l_flickr']){
                   update_option('l_flickr', $_POST['l_flickr']);
                }else{
                   update_option('l_flickr', '');
                }

                if ( isset( $_POST['l_flickr_thumbs'] ) ) {
			update_option('l_flickr_thumbs', 'true' );
		}else{
			update_option('l_flickr_thumbs', 'false' );
		}

                if ( isset( $_POST['l_flickr_title'] ) ) {
			update_option('l_flickr_title', 'true' );
		}else{
			update_option('l_flickr_title', 'false' );
		}

                if($_POST['l_delicious']){
                   update_option('l_delicious', $_POST['l_delicious']);
                }else{
                   update_option('l_delicious', '');
                }

                if($_POST['l_twitter']){
                   update_option('l_twitter', $_POST['l_twitter']);
                }else{
                   update_option('l_twitter', '');
                }

                if($_POST['l_pownce']){
                   update_option('l_pownce', $_POST['l_pownce']);
                }else{
                   update_option('l_pownce', '');
                }

                if($_POST['l_magnolia']){
                   update_option('l_magnolia', $_POST['l_magnolia']);
                }else{
                   update_option('l_magnolia', '');
                }

                if($_POST['l_goodreads']){
                   update_option('l_goodreads', $_POST['l_goodreads']);
                }else{
                   update_option('l_goodreads', '');
                }

                if($_POST['l_lastfm']){
                   update_option('l_lastfm', $_POST['l_lastfm']);
                }else{
                   update_option('l_lastfm', '');
                }

                if($_POST['l_facebook']){
                   update_option('l_facebook', $_POST['l_facebook']);
                }else{
                   update_option('l_facebook', '');
                }

                if($_POST['lifestream_feed1']){
                   update_option('lifestream_feed1', $_POST['lifestream_feed1']);
                    if(!$_POST['lifestream_ico1']){
                    _e('Warning: You\'ve defined an additional feed - but you\'ve not chosen an icon!<br />', 'English');
                    }
                   update_option('lifestream_ico1', $_POST['lifestream_ico1']);
                }else{
                   update_option('lifestream_feed1', '');
                   update_option('lifestream_ico1', $_POST['lifestream_ico1']);
                }

                if($_POST['lifestream_feed2']){
                   update_option('lifestream_feed2', $_POST['lifestream_feed2']);
                    if(!$_POST['lifestream_ico2']){
                    _e('Warning: You\'ve defined an additional feed - but you\'ve not chosen an icon!<br />', 'English');
                    }
                   update_option('lifestream_ico2', $_POST['lifestream_ico2']);
                }else{
                   update_option('lifestream_feed2', '');
                   update_option('lifestream_ico2', $_POST['lifestream_ico2']);
                }

                if($_POST['lifestream_feed3']){
                   update_option('lifestream_feed3', $_POST['lifestream_feed3']);
                    if(!$_POST['lifestream_ico3']){
                    _e('Warning: You\'ve defined an additional feed - but you\'ve not chosen an icon!<br />', 'English');
                    }
                   update_option('lifestream_ico3', $_POST['lifestream_ico3']);
                }else{
                   update_option('lifestream_feed3', '');
                   update_option('lifestream_ico3', $_POST['lifestream_ico3']);
                }

                if($_POST['lifestream_feed4']){
                   update_option('lifestream_feed4', $_POST['lifestream_feed4']);
                    if(!$_POST['lifestream_ico4']){
                    _e('Warning: You\'ve defined an additional feed - but you\'ve not chosen an icon!<br />', 'English');
                    }
                   update_option('lifestream_ico4', $_POST['lifestream_ico4']);
                }else{
                   update_option('lifestream_feed4', '');
                   update_option('lifestream_ico4', $_POST['lifestream_ico4']);
                }

                if($_POST['lifestream_flimit']){
                   update_option('lifestream_flimit', $_POST['lifestream_flimit']);
                }else{
                   update_option('lifestream_flimit', '0');
                }

                if($_POST['lifestream_cache']){
                   update_option('lifestream_cache', $_POST['lifestream_cache']);
                }else{
                   update_option('lifestream_cache', '0');
                }

                if($_POST['lifestream_date']){
                   update_option('lifestream_date', $_POST['lifestream_date']);
                }else{
                   update_option('lifestream_date', 'n/j/y');
                }

                if($_POST['contact_email']){
                   update_option('contact_email', $_POST['contact_email']);
                }else{
                   update_option('contact_email', '');
                }
		
                if($_POST['contact_subject']){
                   update_option('contact_subject', $_POST['contact_subject']);
                }else{
                   update_option('contact_subject', '');
                }
		
                if($_POST['contact_success']){
                   update_option('contact_success', $_POST['contact_success']);
                }else{
                   update_option('contact_success', '');
                }
		
?>OPTIONS UPDATED
                </strong></p>
                </div>
<?php } ?>

  	<div class="wrap">
	<form method="post">
        <h2>Agregado Options</h2>

	<h3>General Settings</h3>
	<table class="form-table">
	<tr>
		<td><label for="lifestream_flimit">Max No. Of Items To Show (0 = Unlimited):</label></td>
		<td><input type="text" name="lifestream_flimit" id="lifestream_flimit" maxlength="2" size="2" value="<?php if(get_option('lifestream_flimit')){ echo get_option('lifestream_flimit');} else { echo '0';} ?>" /></td>
	</tr>
        <tr>
		<td><label for="lifestream_cache">Cache Feeds For (Mins):</label></td>
		<td><input type="text" name="lifestream_cache" id="lifestream_cache" maxlength="2" size="2" value="<?php if(get_option('lifestream_cache')){ echo get_option('lifestream_cache');} else { echo '0';} ?>" /></td>
	</tr>
	<tr>
		<td><label for="lifestream_date">Date Format:</label><br /><a href="http://uk3.php.net/date">Date/Time Format Documentation</a></td>
		<td><input type="text" name="lifestream_date" id="lifestream_date" maxlength="10" size="10" value="<?php echo get_option('lifestream_date'); ?>" /><br />Output: <strong><?php echo date(get_option('lifestream_date')); ?></strong></td>
	</tr>
	</table>

	<h3>Flickr Photos</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_flickr">Your Flickr ID (Try <a href="http://idgettr.com/">idgettr</a>):</label></td>
		<td><input type="text" name="l_flickr" id="l_flickr" maxlength="20" size="20" value="<?php if(get_option('l_flickr')) echo get_option('l_flickr'); ?>" /></td>
	</tr>
	<tr>
		<td><label><input name="l_flickr_thumbs" id="l_flickr_thumbs" value="true" type="checkbox" <?php if ( get_option('l_flickr_thumbs') == 'true' ) echo ' checked="checked" '; ?> /> Show Flickr Thumbnails</label></td>
	</tr>          
	<tr>
		<td><label><input name="l_flickr_title" id="l_flickr_title" value="true" type="checkbox" <?php if ( get_option('l_flickr_title') == 'true' ) echo ' checked="checked" '; ?> /> Show Photo Titles (Sometimes ugly next to thumbnails...)</label></td>
	</tr>      
	</table>

        <h3>Delicious Bookmarks</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_delicious">Your Delicious Username:</label></td><td><input type="text" name="l_delicious" id="l_delicious" maxlength="20" size="20" value="<?php echo get_option('l_delicious'); ?>" /></td> </tr>
	</table>

        <h3>Magnolia Bookmarks</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_magnolia">Your Magnolia Username:</label></td><td><input type="text" name="l_magnolia" id="l_magnolia" size="20" value="<?php echo get_option('l_magnolia'); ?>" /></td> </tr>
	</table>

	<h3>Twitter Status Updates</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_twitter">Your Twitter Feed:</label></td>
		<td><input type="text" name="l_twitter" id="l_twitter" maxlength="60" size="60" value="<?php echo get_option('l_twitter'); ?>" /></td>
	</tr>
	</table>

        <h3>Pownce Public Notes</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_pownce">Your Pownce Username:</label></td><td><input type="text" name="l_pownce" id="l_pownce" size="20" value="<?php echo get_option('l_pownce'); ?>" /></td> </tr>
	</table>

	<h3>Good Reads Book Reviews</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_goodreads">Your Good Reads Book Reviews Feed:</label></td>
		<td><input type="text" name="l_goodreads" id="l_goodreads" size="60" value="<?php echo get_option('l_goodreads'); ?>" /></td>
	</tr>
	</table>

	<h3>Last.fm Music</h3>
	<table class="form-table">
	<tr>
		<td><label for="l_lastfm">Your Last.fm Username:</label></td>
		<td><input type="text" name="l_lastfm" id="l_lastfm" maxlength="20" size="20" value="<?php echo get_option('l_lastfm'); ?>" /></td>
	</tr>
	</table>


	<h3>Facebook Status Updates</h3>
	<table class="form-table">
        <tr>
		<td><label for="l_facebook">Facebook Feed Address:</label></td>
		<td><input type="text" name="l_facebook" id="l_facebook" size="60" value="<?php echo get_option('l_facebook'); ?>" /></td>
	</tr>
	<tr>
	  	 <td colspan=2>In your facebook profile, go to your mini feed and choose "see all" from the top right. Now choose "Status Stories" from the right hand menu. Finally, bottom of the right hand menu, copy the link location of "My Status."</td>
	</tr>
	</table>

	<h3>Extra Feed 1</h3>
	<table class="form-table">
	<tr>
		<td><label for="lifestream_feed1">Additional feed address:</label></td>
		<td><input type="text" name="lifestream_feed1" id="lifestream_feed1" size="60" value="<?php echo get_option('lifestream_feed1'); ?>" /></td>
	</tr>
	<tr>
		<td>Feed Icon Name (including extension):<br />Upload icon to <code>themes/agregado/images</code> directory</td>
		<td><input type="text" id="lifestream_ico1" name="lifestream_ico1" size="15" <?php if(get_option('lifestream_ico1')) echo 'style="background: '. get_option('lifestream_ico1') .';"'; ?> value="<?php echo get_option('lifestream_ico1') ?>"></td>
	</tr>
	</table>

	<h3>Extra Feed 2</h3>
	<table class="form-table">
	<tr>
		<td><label for="lifestream_feed2">Additional feed address:</label></td>
		<td><input type="text" name="lifestream_feed2" id="lifestream_feed2" size="60" value="<?php echo get_option('lifestream_feed2'); ?>" /></td>
	</tr>
	<tr>
		<td>Feed Icon Name (including extension):<br />Upload icon to <code>themes/agregado/images</code> directory</td>
		<td><input type="text" id="lifestream_ico2" name="lifestream_ico2" size="15" <?php if(get_option('lifestream_ico2')) echo 'style="background: '. get_option('lifestream_ico2') .';"'; ?> value="<?php echo get_option('lifestream_ico2') ?>"></td>
	</tr>
	</table>

	<h3>Extra Feed 3</h3>
	<table class="form-table">
	<tr>
		<td><label for="lifestream_feed3">Additional feed address:</label></td>
		<td><input type="text" name="lifestream_feed3" id="lifestream_feed3" size="60" value="<?php echo get_option('lifestream_feed3'); ?>" /></td>
	</tr>
	<tr>
		<td>Feed Icon Name (including extension):<br />Upload icon to <code>themes/agregado/images</code> directory</td>
		<td><input type="text" id="lifestream_ico3" name="lifestream_ico3" size="15" <?php if(get_option('lifestream_ico3')) echo 'style="background: '. get_option('lifestream_ico3') .';"'; ?> value="<?php echo get_option('lifestream_ico3') ?>"></td>
	</tr>
	</table>

	<h3>Extra Feed 4</h3>
	<table class="form-table">
	<tr>
		<td><label for="lifestream_feed4">Additional feed address:</label></td>
		<td><input type="text" name="lifestream_feed4" id="lifestream_feed4" size="60" value="<?php echo get_option('lifestream_feed4'); ?>" /></td>
	</tr>
	<tr>
		<td>Feed Icon Name (including extension):<br />Upload icon to <code>themes/agregado/images</code> directory</td>
		<td><input type="text" id="lifestream_ico4" name="lifestream_ico4" size="15" <?php if(get_option('lifestream_ico4')) echo 'style="background: '. get_option('lifestream_ico4') .';"'; ?> value="<?php echo get_option('lifestream_ico4') ?>"></td>
	</tr>
	</table>

	<h3>Contact Form Settings</h3>
	<table class="form-table">
	<tr>
		<td><label for="contact_email">Your email address:</label></td>
		<td><input type="text" name="contact_email" id="contact_email" size="60" value="<?php echo get_option('contact_email'); ?>" /></td>
	</tr>
	<tr>
		<td><label for="contact_subject">Contact email subject:</label></td>
		<td><input type="text" name="contact_subject" id="contact_subject" size="60" value="<?php echo get_option('contact_subject'); ?>" /></td>
	</tr>
	<tr>
		<td><label for="contact_success">Contact success message:</label></td>
		<td><input type="text" name="contact_success" id="contact_success" size="60" value="<?php echo get_option('contact_success'); ?>" /></td>
	</tr>
	</table>

     <div class="submit"><input type="submit" name="info_update" value="<?php _e('Update Options', 'English'); ?> &raquo;" /></div>
</form>
</div>

<?php   
}

function lifestream(){

	// suppress warnings if curl is not installed
	error_reporting(0);
?>

<!-- Lifestream -->
<ul id="mycarousel" class="jcarousel jcarousel-skin-tango">

<?php
	$simplepie_args = array();
	$feed_urls = array(
		'l_delicious' => 'http://delicious.com/rss/',
		'l_magnolia' => 'http://ma.gnolia.com/rss/full/people/',
		'l_lastfm' => 'http://ws.audioscrobbler.com/1.0/user/',
		'l_flickr' => 'http://api.flickr.com/services/feeds/photos_public.gne?id=',
		'l_pownce' => 'http://pownce.com/feeds/all/',
		'l_facebook' => '',
		'l_twitter' => '',
		'l_goodreads' => '',
		'lifestream_feed1' => '',
		'lifestream_feed2' => '',
		'lifestream_feed3' => '',
		'lifestream_feed4' => ''
		);

	foreach ($feed_urls as $option=>$url) {
		$option_value = get_option($option);
		if (strlen($option_value)) {
			$simple_url = $url . $option_value;
			if ($option == 'l_lastfm') {
				$simple_url .= '/recenttracks.rss';
			}
			if ($option == 'l_pownce') {
				$simple_url .= '.rss';
			}
			if ($option == 'l_flickr') {
				$simple_url .= '&lang=en-us&format=rss_200';
			}
			$simplepie_args[] = $simple_url;
		}
	}

	$feeds = new SimplePie($simplepie_args, TEMPLATEPATH . '/includes/cache', 60*get_option('lifestream_cache'));

	// Set up date variable.
	$stored_date = '';
 
	// Initialize a counter for giving unique ids to the items.
	$count = 1;

	// Go through all of the items in the feed
	foreach ($feeds->get_items(0,get_option('lifestream_flimit')) as $item) {

		$smpflickimg = '';

		// What is the date of the current feed item?
		$item_date = $item->get_date(get_option('lifestream_date'));

		// Is the item's date the same as what is already stored?
		// - Yes? Don't display it again because we've already displayed it for this date.
		// - No? So we have something different.  We should display that.

		if ($stored_date != $item_date) {
			// Since they're different, let's replace the old stored date with the new one
			$stored_date = $item_date;
 			// Display it on the page
			echo '<li class="date">' . $stored_date . '</li>' . "\r\n";
		}

		// Retrieve the item's parent feed address so we can assign classes accordingly.

		$feed = $item->get_feed();
		$address = $feed->subscribe_url();

		if(strstr($address, 'flickr')) {
    			$class = 'flickr';
    			$description = $item->get_description();
    			$smpflickimg = preg_match('/img src="(.*)\.jpg"/', $description, $matches);
    			$smpflickimg = $matches[1].".jpg";
    			if($smpflickimg == '.jpg'){
      				$smpflickimg = '';
    			}
    			// Hardcode size square "s", thumb "t", small "m", medium "", large "b" or original "o"?
    			$size = "m";

			// Alter the path to point to the required size of the image
    			if ($size != "") {
        			$smpflickimg = preg_replace('/_m\.jpg/', '_'.$size.'.jpg', $smpflickimg);
    			}
			else {
				$smpflickimg = preg_replace('/_m\.jpg/', '.jpg', $smpflickimg);
    			}
			$smpflickimg = preg_replace('/width="[0-9]+"/', '', $smpflickimg);
			$smpflickimg = preg_replace('/height="[0-9]+"/', '', $smpflickimg);
			unset($matches);
		} 
		elseif(strstr($address, 'delicious')) {
			$class = 'delicious';
		}
		elseif(strstr($address, 'facebook')) {
			$class = 'facebook';
		}
		elseif(strstr($address, 'flickr')) {
			$class = 'flickr';
		}
		elseif(strstr($address, 'audioscrobbler')) {
			$class = 'lastfm';
		}
		elseif(strstr($address, 'twitter')) {
			$class = 'twitter';
		}
		elseif(strstr($address, 'ma.gnolia')) {
			$class = 'magnolia';
		}
		elseif(strstr($address, 'pownce')) {
			$class = 'pownce';
		}
		elseif(strstr($address, 'goodreads')) {
			$class = 'goodreads';
		}
		elseif(strstr($address, get_option('lifestream_feed1'))) {
			$class = 'lifestream_feed1';
		}
		elseif(strstr($address, get_option('lifestream_feed2'))) {
			$class = 'lifestream_feed2';
		}
		elseif(strstr($address, get_option('lifestream_feed3'))) {
			$class = 'lifestream_feed3';
		}
		elseif(strstr($address, get_option('lifestream_feed4'))) {
			$class = 'lifestream_feed4';
		}

		// Display the feed item

		echo '<li class="' . $class . '" id="item-' . $count . '"><a href="' . $item->get_permalink() . '">';

		if($smpflickimg !== '' && get_option('l_flickr_thumbs') == 'true'){
			echo '<img src="' . $smpflickimg . '" alt="" />';
		}
		elseif($class !== 'flickr'){
			echo $item->get_title(); 
		}

		if($class == 'flickr' && get_option('l_flickr_title') == 'true'){
			echo $item->get_title(); 
		}

		echo '</a></li>' . "\r\n";

		$count = ++$count;
	}
?>
</ul>
<?php 
}

//set initial defaults for feeds
add_option('l_flickr', '');
add_option('l_flickr_thumbs', 'true');
add_option('l_flickr_title', 'false');
add_option('l_delicious', '');
add_option('l_twitter', '');
add_option('l_magnolia', '');
add_option('l_pownce', '');
add_option('l_goodreads', '');
add_option('l_lastfm', '');
add_option('l_facebook', '');
add_option('lifestream_feed1', '');
add_option('lifestream_ico1', '');
add_option('lifestream_feed2', '');
add_option('lifestream_ico2', '');
add_option('lifestream_feed3', '');
add_option('lifestream_ico3', '');
add_option('lifestream_feed4', '');
add_option('lifestream_ico4', '');
add_option('lifestream_flimit', '0');
add_option('lifestream_cache', '60');
add_option('lifestream_date', 'n/j/y');
add_option('contact_email', '');
add_option('contact_subject', '');
add_option('contact_success', '');

//load simplepie and check for a cache directory
if(!class_exists("SimplePie")){
	include_once('includes/simplepie.inc');
	if(!file_exists(TEMPLATEPATH . "/includes/cache/")) {
		mkdir(TEMPLATEPATH . "/includes/cache/", 0777);	
		//just in case...
		chmod(TEMPLATEPATH . "/includes/cache/", 0777);
	}
}

//add menu
add_action('admin_menu', 'agregadoOptions');
	
?>
