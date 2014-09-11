<?php
/*
Plugin Name: My Page Order
Plugin URI: http://www.geekyweekly.com/mypageorder
Description: My Page Order allows you to set the order of pages through a drag and drop interface. The default method of setting the order page by page is extremely clumsy, especially with a large number of pages.
Version: 3.3.2
Author: Andrew Charlton
Author URI: http://www.geekyweekly.com
Author Email: froman118@gmail.com
*/

function mypageorder_menu()
{    
	add_pages_page(__('My Page Order', 'mypageorder'), __('My Page Order', 'mypageorder'), 'edit_pages', 'mypageorder', 'mypageorder');
}

function mypageorder_js_libs() {
	if ( isset($_GET['page']) && $_GET['page'] == "mypageorder" ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
	}
}

function mypageorder_set_plugin_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);
	// create link
	if ($file == $plugin) {
		return array_merge( $links, array( 
			'<a href="' . mypageorder_getTarget() . '">' . __('Order Pages', 'mypageorder') . '</a>',
			'<a href="http://wordpress.org/tags/my-page-order?forum_id=10">' . __('Support Forum', 'mypageorder') . '</a>',
			'<a href="http://geekyweekly.com/gifts-and-donations">' . __('Donate', 'mypageorder') . '</a>' 
		));
	}
	return $links;
}

add_filter('plugin_row_meta', 'mypageorder_set_plugin_meta', 10, 2 );
add_action('admin_menu', 'mypageorder_menu');
add_action('admin_print_scripts', 'mypageorder_js_libs');

function mypageorder()
{
global $wpdb;
$parentID = 0;

if (isset($_POST['btnSubPages'])) { 
	$parentID = $_POST['pages'];
}
elseif (isset($_POST['hdnParentID'])) { 
	$parentID = $_POST['hdnParentID'];
}

if (isset($_POST['btnReturnParent'])) { 
	$parentsParent = $wpdb->get_row( $wpdb->prepare("SELECT post_parent FROM $wpdb->posts WHERE ID = %d ", $_POST['hdnParentID'] ), ARRAY_N);
	$parentID = $parentsParent[0];
}

$success = "";
if (isset($_POST['btnOrderPages'])) { 
	$success = mypageorder_updateOrder();
}

$subPageStr = mypageorder_getSubPages($parentID);
?>

<div class='wrap'>
<form name="frmMyPageOrder" method="post" action="">
	<h2><?php _e('My Page Order', 'mypageorder') ?></h2>
	<?php echo $success; ?>
	
	<p><?php _e('Choose a page from the drop down to order its subpages or order the pages on this level by dragging and dropping them into the desired order.', 'mypageorder') ?></p>
	
	<?php
 	if($subPageStr != "") 
	{ ?>
	
	<h3><?php _e('Order Subpages', 'mypageorder') ?></h3>
	<select id="pages" name="pages">
		<?php echo $subPageStr; ?>
	</select>
	&nbsp;<input type="submit" name="btnSubPages" class="button" id="btnSubPages" value="<?php _e('Order Subpages', 'mypageorder') ?>" />
	<?php 
	} 
	?>

	<h3><?php _e('Order Pages', 'mypageorder') ?></h3>
	
	<ul id="myPageOrderList">
	<?php
	$results = mypageorder_pageQuery($parentID);
	foreach($results as $row)
		echo "<li id='id_$row->ID' class='lineitem'>".__($row->post_title)."</li>";
	?>
	</ul>

	<input type="submit" name="btnOrderPages" id="btnOrderPages" class="button-primary" value="<?php _e('Click to Order Pages', 'mypageorder') ?>" onclick="javascript:orderPages(); return true;" />
	<?php echo mypageorder_getParentLink($parentID); ?>
	&nbsp;&nbsp;<strong id="updateText"></strong>
	<br /><br />
	<p>
	<a href="http://geekyweekly.com/mypageorder"><?php _e('Plugin Homepage', 'mypageorder') ?></a>&nbsp;|&nbsp;<a href="http://geekyweekly.com/gifts-and-donations"><?php _e('Donate', 'mypageorder') ?></a>&nbsp;|&nbsp;<a href="http://wordpress.org/tags/my-page-order?forum_id=10"><?php _e('Support Forum', 'mypageorder') ?></a>
	</p>
	<input type="hidden" id="hdnMyPageOrder" name="hdnMyPageOrder" />
	<input type="hidden" id="hdnParentID" name="hdnParentID" value="<?php echo $parentID; ?>" />
</form>
</div>

<style type="text/css">
	#myPageOrderList {
		width: 90%; 
		border:1px solid #B2B2B2; 
		margin:10px 10px 10px 0px;
		padding:5px 10px 5px 10px;
		list-style:none;
		background-color:#fff;
		-moz-border-radius:3px;
		-webkit-border-radius:3px;
	}

	li.lineitem {
		border:1px solid #B2B2B2;
		-moz-border-radius:3px;
		-webkit-border-radius:3px;
		background-color:#F1F1F1;
		color:#000;
		cursor:move;
		font-size:13px;
		margin-top:5px;
		margin-bottom:5px;
		padding: 2px 5px 2px 5px;
		height:1.5em;
		line-height:1.5em;
	}
	
	.sortable-placeholder{ 
		border:1px dashed #B2B2B2;
		margin-top:5px;
		margin-bottom:5px; 
		padding: 2px 5px 2px 5px;
		height:1.5em;
		line-height:1.5em;	
	}
</style>

<script type="text/javascript">
// <![CDATA[

	function mypageorderaddloadevent(){
		jQuery("#myPageOrderList").sortable({ 
			placeholder: "sortable-placeholder", 
			revert: false,
			tolerance: "pointer" 
		});
	};

	addLoadEvent(mypageorderaddloadevent);
	
	function orderPages() {
		jQuery("#updateText").html("<?php _e('Updating Page Order...', 'mypageorder') ?>");
		jQuery("#hdnMyPageOrder").val(jQuery("#myPageOrderList").sortable("toArray"));
	}

// ]]>
</script>
<?php
}

//Switch page target depending on version
function mypageorder_getTarget() {
	global $wp_version;
	if (version_compare($wp_version, "2.999", ">"))
		return "edit.php?post_type=page&page=mypageorder";
	else
		return "edit-pages.php?page=mypageorder";
}

function mypageorder_updateOrder()
{
	if (isset($_POST['hdnMyPageOrder']) && $_POST['hdnMyPageOrder'] != "") { 
		global $wpdb;

		$hdnMyPageOrder = $_POST['hdnMyPageOrder'];
		$IDs = explode(",", $hdnMyPageOrder);
		$result = count($IDs);

		for($i = 0; $i < $result; $i++)
		{
			$str = str_replace("id_", "", $IDs[$i]);
			$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET menu_order = %d WHERE id = %d ", $i, $str));
		}

		return '<div id="message" class="updated fade"><p>'. __('Page order updated successfully.', 'mypageorder').'</p></div>';
	}
	else
		return '<div id="message" class="updated fade"><p>'. __('An error occured, order has not been saved.', 'mypageorder').'</p></div>';
}

function mypageorder_getSubPages($parentID)
{
	global $wpdb;
	
	$subPageStr = "";
	$results = mypageorder_pageQuery($parentID);
	foreach($results as $row)
	{
		$postCount=$wpdb->get_row($wpdb->prepare("SELECT count(*) as postsCount FROM $wpdb->posts WHERE post_parent = %d and post_type = 'page' AND post_status != 'trash' AND post_status != 'auto-draft' ", $row->ID) , ARRAY_N);
		if($postCount[0] > 0)
	    	$subPageStr = $subPageStr."<option value='$row->ID'>".__($row->post_title)."</option>";
	}
	return $subPageStr;
}

function mypageorder_pageQuery($parentID)
{
	global $wpdb;
	return $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_parent = %d and post_type = 'page' AND post_status != 'trash' AND post_status != 'auto-draft' ORDER BY menu_order ASC", $parentID) );
}

function mypageorder_getParentLink($parentID)
{
	if($parentID != 0)
		return "&nbsp;&nbsp;<input type='submit' class='button' id='btnReturnParent' name='btnReturnParent' value='" . __('Return to parent page', 'mypageorder') ."' />";
	else
		return "";
}

add_action('init', 'mypageorder_loadtranslation');

function mypageorder_loadtranslation() {
	load_plugin_textdomain('mypageorder', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));
}

class mypageorder_Widget extends WP_Widget {

	function mypageorder_Widget() {
		$widget_ops = array('classname' => 'widget_mypageorder', 'description' => __( 'Enhanced Pages widget provided by My Page Order', 'mypageorder') );
		$this->WP_Widget('mypageorder', __('My Page Order', 'mypageorder'), $widget_ops);	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Pages' ) : $instance['title']);
		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$sort_order = empty( $instance['sort_order'] ) ? 'asc' : $instance['sort_order'];
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];
		$exclude_tree = empty( $instance['exclude_tree'] ) ? '' : $instance['exclude_tree'];
		$include = empty( $instance['include'] ) ? '' : $instance['include'];
		$depth = empty( $instance['depth'] ) ? '0' : $instance['depth'];
		$child_of = empty( $instance['child_of'] ) ? '0' : $instance['child_of'];
		$show_date = empty( $instance['show_date'] ) ? '' : $instance['show_date'];
		$date_format = empty( $instance['date_format'] ) ? '' : $instance['date_format'];
		$meta_key = empty( $instance['meta_key'] ) ? '' : $instance['meta_key'];
		$meta_value = empty( $instance['meta_value'] ) ? '' : $instance['meta_value'];
		$show_home = empty( $instance['show_home'] ) ? '' : $instance['show_home'];
		$link_before = empty( $instance['link_before'] ) ? '' : $instance['link_before'];
		$link_after = empty( $instance['link_after'] ) ? '' : $instance['link_after'];
		$authors = empty( $instance['authors'] ) ? '' : $instance['authors'];
		$number = empty( $instance['number'] ) ? '' : $instance['number'];
		$offset = empty( $instance['offset'] ) ? '' : $instance['offset'];

		if ( $sortby != 'post_title' || $sortby != 'ID' )
			$sortby = $sortby . ', post_title';

		if($show_home != '')
		{
			$out = wp_page_menu( apply_filters('widget_pages_args', array('title_li' => '', 'echo' => 0, 'sort_column' => $sortby, 'sort_order' => $sort_order, 'exclude' => $exclude, 
					'exclude_tree' => $exclude_tree, 'include' => $include, 'depth' => $depth, 'child_of' => $child_of, 'show_date' => $show_date, 
					'date_format' => $date_format, 'meta_key' => $meta_key, 'meta_value' => $meta_value, 'link_before' => $link_before, 'link_after' => $link_after, 
					'authors' => $authors, 'number' => $number, 'offset' => $offset, 'show_home' => $show_home	) ) );
		}
		else
		{
			$out = wp_list_pages( apply_filters('widget_pages_args', array('title_li' => '', 'echo' => 0, 'sort_column' => $sortby, 'sort_order' => $sort_order, 'exclude' => $exclude, 
					'exclude_tree' => $exclude_tree, 'include' => $include, 'depth' => $depth, 'child_of' => $child_of, 'show_date' => $show_date, 
					'date_format' => $date_format, 'meta_key' => $meta_key, 'meta_value' => $meta_value, 'link_before' => $link_before, 'link_after' => $link_after, 
					'authors' => $authors, 'number' => $number, 'offset' => $offset, 'show_home' => $show_home	) ) );
		}

		if ( !empty( $out ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		?>
		<ul>
			<?php echo $out; ?>
		</ul>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID', 'post_date', 'post_modified', 'post_author', 'post_name'  ) ) ) {
			$instance['sortby'] = $new_instance['sortby'];
		} else {
			$instance['sortby'] = 'menu_order';
		}
		
		if ( in_array( $new_instance['sort_order'], array( 'asc', 'desc' ) ) ) {
			$instance['sort_order'] = $new_instance['sort_order'];
		} else {
			$instance['sort_order'] = 'asc';
		}

		$instance['exclude'] = strip_tags( $new_instance['exclude'] );
		$instance['exclude_tree'] = strip_tags( $new_instance['exclude_tree'] );
		$instance['include'] = strip_tags( $new_instance['include'] );
		$instance['depth'] = strip_tags( $new_instance['depth'] );
		$instance['child_of'] = strip_tags( $new_instance['child_of'] );
		$instance['show_date'] = strip_tags( $new_instance['show_date'] );
		$instance['date_format'] = strip_tags( $new_instance['date_format'] );
		$instance['meta_value'] = strip_tags( $new_instance['meta_value'] );
		$instance['meta_key'] = strip_tags( $new_instance['meta_key'] );
		$instance['show_home'] = strip_tags( $new_instance['show_home'] );
		$instance['link_before'] = $new_instance['link_before'];
		$instance['link_after'] = $new_instance['link_after'];
		$instance['authors'] = strip_tags( $new_instance['authors'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['offset'] = strip_tags( $new_instance['offset'] );

		return $instance;
	}
	
	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'sortby' => 'menu_order', 'sort_order' => 'asc', 'title' => '', 'exclude' => '', 'exclude_tree' => '', 'include' => '', 'depth' => '0', 'child_of' => '', 'show_date' => '', 'date_format' => '', 'meta_key' => '', 'meta_value' => '', 'link_before' => '', 'link_after' => '', 'authors' => '', 'number' => '', 'offset' => '', 'show_home' => '' ) );
		$title = esc_attr( $instance['title'] );
		$exclude = esc_attr( $instance['exclude'] );
		$exclude_tree = esc_attr( $instance['exclude_tree'] );
		$include = esc_attr( $instance['include'] );
		$depth = esc_attr( $instance['depth'] );
		$child_of = esc_attr( $instance['child_of'] );
		$show_date = esc_attr( $instance['show_date'] );
		$date_format = esc_attr( $instance['date_format'] );
		$meta_key = esc_attr( $instance['meta_key'] );
		$meta_value = esc_attr( $instance['meta_value'] );
		$show_home = esc_attr( $instance['show_home'] );
		$link_before = esc_attr( $instance['link_before'] );
		$link_after = esc_attr( $instance['link_after'] );
		$authors = esc_attr( $instance['authors'] );
		$number = esc_attr( $instance['number'] );
		$offset = esc_attr( $instance['offset'] );
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mypageorder'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:', 'mypageorder' ); ?></label>
			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
				<option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php _e('Page Order', 'mypageorder'); ?></option>
				<option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php _e('Page Title', 'mypageorder'); ?></option>
				<option value="post_date"<?php selected( $instance['sortby'], 'post_date' ); ?>><?php _e( 'Post Date', 'mypageorder' ); ?></option>
				<option value="post_modified"<?php selected( $instance['sortby'], 'post_modified' ); ?>><?php _e( 'Post Modified', 'mypageorder' ); ?></option>
				<option value="post_author"<?php selected( $instance['sortby'], 'post_author' ); ?>><?php _e( 'Author', 'mypageorder' ); ?></option>
				<option value="post_name"<?php selected( $instance['sortby'], 'post_name' ); ?>><?php _e( 'Page Slug', 'mypageorder' ); ?></option>
				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Page ID', 'mypageorder' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sort_order'); ?>"><?php _e( 'Sort Order:', 'mypageorder' ); ?></label>
			<select name="<?php echo $this->get_field_name('sort_order'); ?>" id="<?php echo $this->get_field_id('sort_order'); ?>" class="widefat">
				<option value="asc"<?php selected( $instance['sort_order'], 'asc' ); ?>><?php _e('Ascending', 'mypageorder'); ?></option>
				<option value="desc"<?php selected( $instance['sort_order'], 'desc' ); ?>><?php _e('Descending', 'mypageorder'); ?></option>
			</select>
			<br />
			<small><?php _e( 'Might only work with Page Title.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_home'); ?>"><?php _e( 'Show Home:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $show_home; ?>" name="<?php echo $this->get_field_name('show_home'); ?>" id="<?php echo $this->get_field_id('show_home'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Enter text for link to blog home, blank to hide.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $exclude; ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Page IDs, separated by commas.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('exclude_tree'); ?>"><?php _e( 'Exclude Tree:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $exclude_tree; ?>" name="<?php echo $this->get_field_name('exclude_tree'); ?>" id="<?php echo $this->get_field_id('exclude_tree'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Page IDs, separated by commas.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('include'); ?>"><?php _e( 'Include:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $include; ?>" name="<?php echo $this->get_field_name('include'); ?>" id="<?php echo $this->get_field_id('include'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Page IDs, separated by commas.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e( 'Depth:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $depth; ?>" name="<?php echo $this->get_field_name('depth'); ?>" id="<?php echo $this->get_field_id('depth'); ?>" class="widefat" />
			<br />
			<small><?php _e( '0 = Hierarchy, -1 = Flat, 1 = Top Level, 2+ = Depth.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('child_of'); ?>"><?php _e( 'Child Of:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $child_of; ?>" name="<?php echo $this->get_field_name('child_of'); ?>" id="<?php echo $this->get_field_id('child_of'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'ID of Parent Page.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e( 'Show Date:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $show_date; ?>" name="<?php echo $this->get_field_name('show_date'); ?>" id="<?php echo $this->get_field_id('show_date'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'modified or custom to use Date Format.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('date_format'); ?>"><?php _e( 'Date Format:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $date_format; ?>" name="<?php echo $this->get_field_name('date_format'); ?>" id="<?php echo $this->get_field_id('date_format'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Custom date format to use with custom Show Date.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('meta_key'); ?>"><?php _e( 'Meta Key:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $meta_key; ?>" name="<?php echo $this->get_field_name('meta_key'); ?>" id="<?php echo $this->get_field_id('meta_key'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Use with Meta Value.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('meta_value'); ?>"><?php _e( 'Meta Value:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $meta_value; ?>" name="<?php echo $this->get_field_name('meta_value'); ?>" id="<?php echo $this->get_field_id('meta_value'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Use with Meta Key.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_before'); ?>"><?php _e( 'Link Before:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $link_before; ?>" name="<?php echo $this->get_field_name('link_before'); ?>" id="<?php echo $this->get_field_id('link_before'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Text or HTML to proceed link text.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_after'); ?>"><?php _e( 'Link After:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $link_after; ?>" name="<?php echo $this->get_field_name('link_after'); ?>" id="<?php echo $this->get_field_id('link_after'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Text or HTML after link text.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('authors'); ?>"><?php _e( 'Authors:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $authors; ?>" name="<?php echo $this->get_field_name('authors'); ?>" id="<?php echo $this->get_field_id('authors'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Author IDs, seperated by comma.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $number; ?>" name="<?php echo $this->get_field_name('number'); ?>" id="<?php echo $this->get_field_id('number'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Number of pages to display.', 'mypageorder' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e( 'Offset:', 'mypageorder' ); ?></label> <input type="text" value="<?php echo $offset; ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Number of pages to skip.', 'mypageorder' ); ?></small>
		</p>
		
<?php
	}

}

function mypageorder_widgets_init() {
	register_widget('mypageorder_Widget');
}

add_action('widgets_init', 'mypageorder_widgets_init');

?>