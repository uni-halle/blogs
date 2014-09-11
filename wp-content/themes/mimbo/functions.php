<?php


//  TABLE OF CONTENTS

//  Localization Initialize
//  Archive Pagination
//	Dynamic Titles
//  Dynamic Body Classes
//  Widgets
//  Admin Panel
//  Get-post-image
//  Comment output
//  Comment microid



/* Localization Initialize ********************************************/

load_theme_textdomain('Mimbo');


/* Archive Pagination ********************************************/

function my_post_limit($limit) {
	global $paged, $myOffset, $postsperpage;
	if(empty($paged)) {
		$paged = 1;
	}
	$pgstrt = ((intval($paged) -1) * $postsperpage) + $myOffset . ', ';
	$limit = 'LIMIT '.$pgstrt.$postsperpage;
	return $limit;
}


/* Dynamic Titles ********************************************/
function dynamictitles() {
	
	if ( is_single() ) {
      wp_title('');
      echo (' | ');
      bloginfo('name');
 
} else if ( is_page() || is_paged() ) {
      bloginfo('name');
      wp_title('|');
 
} else if ( is_author() ) {
      bloginfo('name');
      wp_title(' | '.__('Author','Mimbo').'');	  
	  
} else if ( is_category() ) {
      bloginfo('name');
      wp_title(' | '.__('Archive for','Mimbo').'');
      ('');

} else if ( is_tag() ) {
      bloginfo('name');
      echo (' | '.__('Tag archive for','Mimbo').'');
      wp_title('');

} else if ( is_archive() ) {
      bloginfo('name');
      echo (' | '.__('Archive for','Mimbo').'');
      wp_title('');

} else if ( is_search() ) {
      bloginfo('name');
      echo (' | '.__('Search Results','Mimbo').'');
 
} else if ( is_404() ) {
      bloginfo('name');
      echo (' | '.__('404 Error (Page Not Found)','Mimbo').'');
	  
} else if ( is_home() ) {
      bloginfo('name');
      echo (' | ');
      bloginfo('description');
 
} else {
      bloginfo('name');
      echo (' | ');
      echo (''.$blog_longd.'');
}
}



// Generates semantic classes for BODY element, courtesy of Thematic and Sandbox
function bodystyle( $print = true ) {
	global $wp_query, $current_user;
	
	// Generic semantic classes for what type of content is displayed
	is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
	is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
	is_archive()     ? $c[] = 'archive'    : null;
	is_date()        ? $c[] = 'by-date'       : null;
	is_search()      ? $c[] = 'search'     : null;
	is_paged()       ? $c[] = 'paged'      : null;
	is_attachment()  ? $c[] = 'attachment' : null;
	is_404()         ? $c[] = 'four04'     : null; // CSS does not allow a digit as first character

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			thematic_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, '' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 'category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 'tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		if ( is_page_template() ) // Hat tip to Ian, themeshaper.com
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// Search classes for results or no results
	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) {
			$c[] = 'search-results';
		} else {
			$c[] = 'search-no-results';
		}
		rewind_posts();
	}

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get('paged') ) || ( $page = $wp_query->get('page') ) ) && $page > 1 ) {
		$c[] = 'paged-' . $page;
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;
		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class',  $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print($c) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function thematic_date_classes( $t, &$c, $p = '' ) {
	
}






/* Widgets ********************************************/

if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Mimbo Sidebar',
        'before_widget' => '<div class="widget clearfloat">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));


/* Admin Panel ********************************************/

add_action('admin_menu', 'openbook_add_theme_page');

function openbook_init() {
	add_option('mimic_right_cat', '1,2,3');
	add_option('mimic_nb_right_cat', '2');
add_option('openbook_cats', '2');
add_option('openbook_featured_posts','2');
}

function openbook_add_theme_page() {
	if(get_option('openbook_featured_cat') == '') {
		openbook_init();
	}
	
	if ($_GET['page'] == basename(__FILE__)) {
		if ( 'save' == $_REQUEST['action'] ) {
			//jbj starts 
if(isset($_REQUEST['openbook_cats'])) {
  update_option('openbook_cats', $_REQUEST['openbook_cats']);
 } 
	
			if(isset($_REQUEST['openbook_featured_posts'])) {
				update_option('openbook_featured_posts', $_REQUEST['openbook_featured_posts']);
			}

			header("Location: themes.php?page=functions.php&saved=true");
			die;			
		}
		add_action('admin_head', 'openbook_theme_page_head');
	}
	add_theme_page(__('Mimbo Options','Mimbo'), __('Mimbo Options','Mimbo'), 'edit_themes', basename(__FILE__), 'openbook_theme_page');
} // done with initial request handling

function openbook_theme_page_head() {
	// header stuff, css and the like
?>

	<style type="text/css">
		div#openbook-div {
			display: block;
		}
		div#openbookCaption {
			border-top: 1px solid #0d324f;
			margin: 10px 20px 0px 10px;
			padding: 10px;
			text-align: center;
		}
		div#openbookCaption p {
			text-align: center;
		}
		
		.optiontable th {
		text-align:right
		}
		
		div#openbook tr {
		margin:15px 0;
		}
		
		img#openbookLogo {
			border: 0; margin: 0; padding: 0;
			display: block;
			width: 174px; height: 51px;
			text-decoration: none;
		}
	</style><?php
} // end openbook theme page head

function openbook_theme_page() {
	if ( $_REQUEST['saved'] ) echo '
						<div id="message" class="updated fade">
						<p><strong> '.__('Options Saved','Mimbo').'</strong></p>
						</div>';
?><div class="wrap">
	<div id="openbook-div">
		<h2><?php _e('Mimbo Options','Mimbo'); ?></h2>
		<form name="openbook" method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
			<input type="hidden" name="action" value="save" />
			<table class="optiontable">
				<tbody>
				<tr>
					<th valign="top"><?php _e('Select featured categories:','Mimbo'); ?></th>
					<td>
					
<select name="openbook_cats[]" multiple="multiple" style="width:250px;height:120px;">
<?php $kittens = get_categories(); ?>
   <?php foreach ($kittens as $cat){
          if (in_array($cat->term_id, get_option('openbook_cats'))) {
	  echo '<option value="'.$cat->term_id.'" selected="selected">'.$cat->name.'</option>';	  
} else {
  echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';	 
}
}
?>
</select>
</td>
				</tr>
				<tr>
					<th><?php _e('Number of posts per featured category:','Mimbo'); ?></th>
					<td>
					<?php $nb_posts = get_option('openbook_featured_posts'); ?>
					<select name="openbook_featured_posts" class="code">
						<?php for($i=1;$i<31;$i++) { 
							if ($i == $nb_posts) {
								echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
							} else {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
								
						} ?>
					</td>
				</tr>
			</tbody>
			</table>
		<p class="submit">
			<input type="submit" name="Save" value="<?php _e('Apply','Mimbo'); ?> &raquo;" />
		</p>
		</form>
		
	</div>
</div>	
<?php
} 


function post_strip($where) {
	global $myPosts, $wpdb;
	$where .= " AND $wpdb->posts.ID not in($myPosts) "; 
	return $where;
}



/* For Getting the Image ********************************************/

//GET-POST-IMAGE script by Tim McDaniels, written for the Mimbo theme
	

function get_post_image ($post_id=0, $width=0, $height=0, $img_script='') {
	global $wpdb;
	if($post_id > 0) {

		 // select the post content from the db

		 $sql = 'SELECT post_content FROM ' . $wpdb->posts . ' WHERE id = ' . $wpdb->escape($post_id);
		 $row = $wpdb->get_row($sql);
		 $the_content = $row->post_content;
		 if(strlen($the_content)) {

			  // use regex to find the src of the image

			preg_match("/<img src\=('|\")(.*)('|\") .*( |)\/>/", $the_content, $matches);
			if(!$matches) {
				preg_match("/<img class\=\".*\" title\=\".*\" src\=('|\")(.*)('|\") .*( |)\/>/U", $the_content, $matches);
			}
			$the_image = '';
			$the_image_src = $matches[2];
			$frags = preg_split("/(\"|')/", $the_image_src);
			if(count($frags)) {
				$the_image_src = $frags[0];
			}

			  // if src found, then create a new img tag

			  if(strlen($the_image_src)) {
				   if(strlen($img_script)) {

					    // if the src starts with http/https, then strip out server name

					    if(preg_match("/^(http(|s):\/\/)/", $the_image_src)) {
						     $the_image_src = preg_replace("/^(http(|s):\/\/)/", '', $the_image_src);
						     $frags = split("\/", $the_image_src);
						     array_shift($frags);
						     $the_image_src = '/' . join("/", $frags);
					    }
					    $the_image = '<img alt="" src="' . $img_script . $the_image_src . '" />';
				   }
				   else {
					    $the_image = '<img alt="" src="' . $the_image_src . '" width="' . $width . '" height="' . $height . '" />';
				   }
			  }
			  return $the_image;
		 }
	}
}




//Callback functions for comment output

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard clearfloat">
         <?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>
			
			<div class="commentmetadata">
        <?php printf(__('<cite class="fn">%s</cite>','Mimbo'), get_comment_author_link()) ?>
		 
		 
	 <div class="comment-date"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
	  <?php printf(__('%1$s &bull; %2$s'), get_comment_date(),  get_comment_time()) ?></a>
	  <?php edit_comment_link(__('(Edit)','Mimbo')) ?>
	  </div>
	  </div>
	  
      </div><!--END COMMENT-AUTHOR-->
	  
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.','Mimbo') ?></em>
         <br />
      <?php endif; ?>

      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
        }
		
		
// add a microid to all the comments
function comment_add_microid($classes) {
	$c_email=get_comment_author_email();
	$c_url=get_comment_author_url();
	if (!empty($c_email) && !empty($c_url)) {
		$microid = 'microid-mailto+http:sha1:' . sha1(sha1('mailto:'.$c_email).sha1($c_url));
		$classes[] = $microid;
	}
	return $classes;	
}
add_filter('comment_class','comment_add_microid');
		
	?>