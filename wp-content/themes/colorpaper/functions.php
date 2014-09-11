<?php 
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h5 class="widgettitle">',
        'after_title' => '</h5>',
    ));
	

/* 00 - SIDEBAR WIDGETS
/* ----------------------------------------------*/

function get_popular($limit = 7) {
	global $wpdb;
	
	$getposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'post' ORDER BY comment_count DESC LIMIT 0,".$limit);	
	foreach($getposts as $thepost) {
		echo '<li><a href="'.get_permalink($thepost->ID).'">'.$thepost->post_title.'</a></li>';
	}
}

/*
function get_comments($limit = 7, $stops = 65) {
	global $wpdb;

	$getcomments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date DESC LIMIT 0,".$limit);
	
	foreach($getcomments as $thecomments) {
		if ( strlen ( $thecomments->comment_content ) <= $stops ) {
			$comment = $thecomments->comment_content;
		} else {
			$comment = substr($thecomments->comment_content, 0, strrpos(substr($thecomments->comment_content, 0, $stops), ' ')) . '...';
		}
		
		echo '<li><a href="'.get_permalink($thecomments->comment_post_ID).'"><span class="light"><strong>'.$thecomments->comment_author.'</strong> said </span> '.$comment.'</a></li>';
	}
}
*/

function get_featured ($category) {
	query_posts('category_name='.$category); if (have_posts()) : while (have_posts()) : the_post();
		echo'<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
	endwhile; endif;
}

function get_recent($limit) {
	query_posts('showposts='.$limit); if (have_posts()) : while (have_posts()) : the_post();
		echo'<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
	endwhile; endif;
}

/* 00 - POST OFFSET
/* ----------------------------------------------*/

function my_post_limit($limit) { 
	global $paged, $myOffset;
	
	if (empty($paged)) {
			$paged = 1;
	}
	
	$postperpage = intval(get_option('posts_per_page'));
	$pgstrt = ( ( intval( $paged ) -1 ) * $postperpage ) + $myOffset . ', ';
	$limit = 'LIMIT '.$pgstrt.$postperpage;
	return $limit;
}

function total_pages() {
	global $wpdb;
	$mySearch = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'post' and post_status = 'publish'");
	
	$count = 0;
	
	foreach($mySearch as $post) {
		$count++;
	}

	$postperpage = intval(get_option('posts_per_page'));
	
	$NumResults = ceil(($count) / $postperpage );
	echo $NumResults;
}

/* 00 - CUSTOM THEME OPTIONS
/* ----------------------------------------------*/

$themename = "Color Paper";

$cats_array = get_categories('hide_empty=0');
$categories = array();

foreach ($cats_array as $cats) {
	$categories[$cats->cat_ID] = $cats->cat_name;
}

$options = array (
	array(
		"type" => "section",
		"name" => "General Options"),
	array(
		"name" => "About Message",
		"id" => "about_message",
		"type" => "textarea",
		"std" => "About me. Edit this in the options panel.",
		"description" => "The message that will show on the right sidebar. Leave blank to disclude it from the sidebar. <strong>HTML is allowed</strong>."),
	array(
		"type" => "section",
		"name" => "AJAX Tabs (Requires v. 2.6.2)"),
	array(
		"name" => "Popular Limit",
		"id" => "popular_limit",
		"type" => "select",
		"std" => "7",
		"options" => array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10"),
		"description" => "Number of popular articles to show in the AJAX tab box. <strong>Requires WordPress Version 2.6.2</strong>"),
	array(
		"name" => "Comments Limit",
		"id" => "comments_limit",
		"type" => "select",
		"std" => "7",
		"options" => array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10"),
		"description" => "Number of recent comments to show in the AJAX tab box. <strong>Requires WordPress Version 2.6.2</strong>"),
	array(
		"name" => "Featured Category",
		"id" => "featured_cat",
		"type" => "select",
		"options" => $categories,
		"description" => "The name of the category to be featured in the AJAX tab box. <strong>Requires WordPress Version 2.6.2</strong>"),
	array(
		"type" => "section",
		"name" => "General Sidebar"),
	array(
		"name" => "Recent Limit",
		"id" => "recent_limit",
		"type" => "select",
		"std" => "6",
		"options" => array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10"),
		"description" => "Number of recent post to show in the sidebar. (Does not display on homepage.)"),
	array(
		"name" => "Show Flickr Feed",
		"id" => "show_flickr",
		"type" => "select",
		"std" => "Yes",
		"options" => array("Yes", "No"),
		"description" => "Show the Sidebar Flickr Photo Stream?"),
	array(
		"name" => "Flickr Title",
		"id" => "flickr_title",
		"type" => "text",
		"std" => "Photo Stream",
		"description" => "Enter the title to appear above the Flickr Photo Stream"),
	array(
		"name" => "Flickr ID",
		"id" => "flickr_id",
		"type" => "text",
		"std" => "",
		"description" => "Your Flickr User ID"),
	array(
		"name" => "Flickr Stream Count",
		"id" => "flickr_count",
		"type" => "select",
		"std" => "6",
		"options" => array("3", "6", "9"),
		"description" => "Show the Sidebar Flickr Photo Stream?"),
	array(
		"name" => "Sidebar Ad",
		"id" => "side_ad",
		"type" => "textarea",
		"std" => "",
		"description" => "Place any advertisement code here. <strong>HTML is allowed</strong>")
);

function theme_add_admin() {
	global $themename, $shortname, $options;

	if ( $_GET['page'] == basename(__FILE__) ) {
    	if ( 'save' == $_REQUEST['action'] ) {
			foreach ($options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
			}
			
			foreach ($options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { 
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); 
				} else { 
					delete_option( $value['id'] ); 
				} 
			}
			
			header("Location: admin.php?page=functions.php&saved=true");
			
			die;

        } 
	}

	add_menu_page($themename." Options", $themename." Options", 'edit_themes', basename(__FILE__), 'theme_admin');

}

function theme_admin() {
	
	global $themename, $shortname, $options;

	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
?>
		<div id="wpwrap">
			<div id="wpcontent">
				<div id="wpbody">
					<div class="wrap">
						<div id="poststuff">
							<form method="post">
								<div class="submitbox" id="submitlink">
									<div id="previewview">
										<span style="color:#FFFFFF; font-weight:bold;">Settings</span>
									</div>
									<div class="inside">
										<p>Modify the following settings to adjust the theme to your likings.</p>
									</div>
	
									<p class="submit">
										<input name="save" type="submit" value="Save changes" />    
										<input type="hidden" name="action" value="save" />
									</p>
								</div>
								
								<div id="post-body">
									<?php foreach ($options as $value) { ?>
										<?php if($value['type'] == "section") { ?>
											<h2><?php echo $value['name']; ?></h2>
										<?php } 
											switch ( $value['type'] ) {
											case 'text':
										?>
											<div id="namediv" class="stuffbox">
												<h3><label for="link_name"><?php echo $value['name']; ?></label></h3>
												<div class="inside">
													<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes( get_settings( $value['id'] ) ); } else { echo stripslashes( $value['std'] ); } ?>" size="90" />
													<p><?php echo $value['description']; ?></p>
												</div>
											</div>
										<?php										
											break;
											case 'select' :
										?>
											<div id="namediv" class="stuffbox">
												<h3><label for="link_name"><?php echo $value['name']; ?></label></h3>
												<div class="inside">
													<select name="<?php echo $value['id']; ?>">
													<?php foreach ($value['options'] as $option) { ?>
                										<option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
                									<?php } ?>
													</select>
													<p><?php echo $value['description']; ?></p>
												</div>
											</div>
										<?php
											break;
											case 'textarea' :
										?>
											<div id="namediv" class="stuffbox">
												<h3><label for="link_name"><?php echo $value['name']; ?></label></h3>
												<div class="inside">
													<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="90" rows="5"><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes( get_settings( $value['id'] ) ); } else { echo stripslashes( $value['std'] ); } ?></textarea>
													<p><?php echo $value['description']; ?></p>
												</div>
											</div>
										<?php
											break;									
											}
										?>
									<?php } ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
}


global $options, $value;

foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { 
		$$value['id'] = stripslashes( nl2br( $value['std'] ) ); 
	} else { 
		$$value['id'] = stripslashes( get_settings( nl2br( $value['id'] ) ) ); 
	} 
}

$value = array();

add_action('admin_menu', 'theme_add_admin'); 

?>