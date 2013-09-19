<?
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/**
 * Show the Page Settings for Admin oanel
 *
 * @since 2.7.0
 *
 */
	
$option_page_id = SHORTNAME."_exclude_page";


function obwp_pages_settings()
{
    global $themename, $options, $option_page_id;

	$options = array (
				array(	"name" => "Page Menu Settings",
						"type" => "heading"),
				
				array(	"html" => "<table cellpadding=\"5\" cellspacing=\"10\">".page_rows2(NULL, 1, 1000000)."</table>",
						"desc" => "When you uncheck Parent Page and if you don't want to show Sub Pages, you need to uncheck All Sub Pages too.<br /><br />",
			    		"id" => $option_page_id)
																														
		  );
	
	
	obwp_add_admin_pages('obwp-settings-pages.php');
}
	


/**
 * Save all sittings and call page showing function
 *
 * @since 2.7.0
 *
 */	
function obwp_add_admin_pages($file) {

    global $themename, $options;

    if ( $_GET['page'] == $file ) {
		
		$options_id = $options[1]['id'];

        if ( 'save' == $_REQUEST['action'] ) {
				
				if(isset($_REQUEST[$options_id]) && is_array($_REQUEST[$options_id]))
				{
					$include_page = $_REQUEST[$options_id];
					$pages = get_pages(array());
					$exclude_page = array();
					if ( !empty($pages) ) {
				
						for($i=0;$i<count($pages);$i++)
						{
							if(!in_array($pages[$i]->ID,$include_page))
								$exclude_page[] = $pages[$i]->ID;
						}
					}
					$exclude_page = join(',', $exclude_page);

					update_option($options_id, $exclude_page );
				}
				else{
					//if all unchecked, add all pages
					$pages = get_pages(array());
					$exclude_page = array();
					if ( !empty($pages) ) {
				
						for($i=0;$i<count($pages);$i++)
						{
							$exclude_page[] = $pages[$i]->ID;
						}
					}
					$exclude_page = join(',', $exclude_page);
					update_option($options_id, $exclude_page );
				}
				
                $_REQUEST['saved']='true';
				?>
				<script language="javascript" type="text/javascript">
					location.href='<?php echo get_option('home'); ?>/wp-admin/admin.php?page=<?php echo $file; ?>&saved=true';
				</script>
				<?

        } else if( 'reset' == $_REQUEST['action'] ) {

            delete_option($options_id);
			
                $_REQUEST['saved']='true';
				?>
				<script language="javascript" type="text/javascript">
					location.href='<?php echo get_option('home'); ?>/wp-admin/admin.php?page=<?php echo $file; ?>&saved=true';
				</script>
				<?

        }
    }
	
	obwp_admin();
}	



/*
 * displays pages in hierarchical order with paging support
 */
/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $pages
 * @param unknown_type $pagenum
 * @param unknown_type $per_page
 * @return unknown
 */
function page_rows2($pages, $pagenum = 1, $per_page = 20) {
	global $wpdb, $option_page_id;

	$level = 0;
	$html = '';
	if ( ! $pages ) {
		$pages = get_pages( array('sort_column' => 'menu_order') );

		if ( ! $pages )
			return false;
	}

	/*
	 * arrange pages into two parts: top level pages and children_pages
	 * children_pages is two dimensional array, eg.
	 * children_pages[10][] contains all sub-pages whose parent is 10.
	 * It only takes O(N) to arrange this and it takes O(1) for subsequent lookup operations
	 * If searching, ignore hierarchy and treat everything as top level
	 */
	if ( empty($_GET['s']) ) {

		$top_level_pages = array();
		$children_pages = array();

		foreach ( $pages as $page ) {

			// catch and repair bad pages
			if ( $page->post_parent == $page->ID ) {
				$page->post_parent = 0;
				$wpdb->query( $wpdb->prepare("UPDATE $wpdb->posts SET post_parent = '0' WHERE ID = %d", $page->ID) );
				clean_page_cache( $page->ID );
			}

			if ( 0 == $page->post_parent )
				$top_level_pages[] = $page;
			else
				$children_pages[ $page->post_parent ][] = $page;
		}

		$pages = &$top_level_pages;
	}

	$count = 0;
	$start = ($pagenum - 1) * $per_page;
	$end = $start + $per_page;

	foreach ( $pages as $page ) {
		if ( $count >= $end )
			break;

		if ( $count >= $start )
			echo "\t" . display_page_row2( $html, $page, $level );

		$count++;

		if ( isset($children_pages) )
			_page_rows2( $html, $children_pages, $count, $page->ID, $level + 1, $pagenum, $per_page );
	}

	// if it is the last pagenum and there are orphaned pages, display them with paging as well
	if ( isset($children_pages) && $count < $end ){
		foreach( $children_pages as $orphans ){
			foreach ( $orphans as $op ) {
				if ( $count >= $end )
					break;
				if ( $count >= $start )
					echo "\t" . display_page_row2( $html, $op, 0 );
				$count++;
			}
		}
	}
	
	return $html;
}



/*
 * display one row if the page doesn't have any children
 * otherwise, display the row and its children in subsequent rows
 */
/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $page
 * @param unknown_type $level
 */
function display_page_row2( &$html, $page, $level = 0 ) {
	global $post, $option_page_id;

	$post = $page;
	setup_postdata($page);

	$page->post_title = wp_specialchars( $page->post_title );
	$pad = str_repeat( '&#8212', $level );
	$id = (int) $page->ID;
	$posts_columns = get_column_headers('edit-pages');
	$hidden = get_hidden_columns('edit-pages');
	$title = _draft_or_post_title();
	$html .= '<tr>';

foreach ($posts_columns as $column_name=>$column_display_name) {
	
	$page_id = get_the_ID();
		
	switch ($column_name) {

	case 'cb':
		$exclude_page = obwp_get_meta($option_page_id);
		$exclude_page = explode(',',$exclude_page);
		
		if(!in_array($page_id,$exclude_page))
			$checked = 'checked="checked"';
		else
			$checked = '';
			
		$html .= '<td><input type="checkbox" id="'.$option_page_id.$page_id.'" name="'.$option_page_id.'[]" value="'.$page_id.'" '.$checked.' /></td>';
		break;
	case 'title':
		$edit_link = get_edit_post_link( $page->ID );
		$html .= '<td><label for="'.$option_page_id.$page_id.'">'.$pad.$title.'</label>&nbsp;&nbsp;&nbsp;<a style="font-size:10px" href="'.$edit_link.'" title="'. attribute_escape(sprintf(__('Edit "%s"'), $title)).'">Edit</a></td>';
		break;

	default:
		break;
	}
}
?>

</tr>

<?php
}

/*
 * Given a top level page ID, display the nested hierarchy of sub-pages
 * together with paging support
 */
/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $children_pages
 * @param unknown_type $count
 * @param unknown_type $parent
 * @param unknown_type $level
 * @param unknown_type $pagenum
 * @param unknown_type $per_page
 */
function _page_rows2( &$html, &$children_pages, &$count, $parent, $level, $pagenum, $per_page ) {
	global $option_page_id;

	if ( ! isset( $children_pages[$parent] ) )
		return;

	$start = ($pagenum - 1) * $per_page;
	$end = $start + $per_page;

	foreach ( $children_pages[$parent] as $page ) {

		if ( $count >= $end )
			break;

		// If the page starts in a subtree, print the parents.
		if ( $count == $start && $page->post_parent > 0 ) {
			$my_parents = array();
			$my_parent = $page->post_parent;
			while ( $my_parent) {
				$my_parent = get_post($my_parent);
				$my_parents[] = $my_parent;
				if ( !$my_parent->post_parent )
					break;
				$my_parent = $my_parent->post_parent;
			}
			$num_parents = count($my_parents);
			while( $my_parent = array_pop($my_parents) ) {
				echo "\t" . display_page_row2( $html, $my_parent, $level - $num_parents );
				$num_parents--;
			}
		}

		if ( $count >= $start )
			echo "\t" . display_page_row2( $html, $page, $level );

		$count++;

		_page_rows2( $html, $children_pages, $count, $page->ID, $level + 1, $pagenum, $per_page );
	}

	unset( $children_pages[$parent] ); //required in order to keep track of orphans
}

?>