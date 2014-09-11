<?php
$section_overview_title = 'Overview'; // name section overview
$page_exclusions = '44';
global $post;
if($post->ID) {
	if($post->post_parent) { // page is a child
		$parent = get_post($post->post_parent);
		if($parent->post_parent) { // if page is a grandchild
			$grandparent = get_post($parent->post_parent);
			if($grandparent->post_parent) { // if page is a greatgrandchild
				$greatgrandparent = get_post($grandparent->post_parent);
				if($greatgrandparent->post_parent) { // if page is a greatgreatgrandchild
					$greatgreatgrandparent = get_post($greatgrandparent->post_parent);
					if($greatgreatgrandparent->post_parent) { // if page is a greatgreatgreatgrandchild
						$greatgreatgreatgrandparent = get_post($greatgreatgrandparent->post_parent);
						
						$section_title = get_the_title($greatgreatgreatgrandparent->ID);
						$section_overview = '<li><a href="'.get_permalink($greatgreatgreatgrandparent->ID).'">'.$section_overview_title.'</a></li>';
						$children = wp_list_pages('sort_column=menu_order&exclude='.$page_exclusions.'&title_li=&child_of='.$greatgreatgrandparent->post_parent.'&echo=0');
					}
					else {
						$section_title = get_the_title($greatgreatgrandparent->ID);
						$section_overview = '<li><a href="'.get_permalink($greatgreatgrandparent->ID).'">'.$section_overview_title.'</a></li>';
						$children = wp_list_pages('sort_column=menu_order&exclude='.$page_exclusions.'&title_li=&child_of='.$greatgrandparent->post_parent.'&echo=0');
					}
				}
				else {
					$section_title = get_the_title($greatgrandparent->ID);
					$section_overview = '<li><a href="'.get_permalink($greatgrandparent->ID).'">'.$section_overview_title.'</a></li>';
					$children = wp_list_pages('sort_column=menu_order&exclude='.$page_exclusions.'&title_li=&child_of='.$grandparent->post_parent.'&echo=0');
				}	
			}
			else { // no greatgrandchildren around
				$section_title = get_the_title($grandparent->ID);
				$section_overview = '<li><a href="'.get_permalink($grandparent->ID).'">'.$section_overview_title.'</a></li>';
				$children = wp_list_pages('sort_column=menu_order&exclude='.$page_exclusions.'&title_li=&child_of='.$parent->post_parent.'&echo=0');
			}
		}
		else { // page off of secondary nav has children
			$section_title = get_the_title($parent->ID);
			$section_overview = '<li><a href="'.get_permalink($parent->ID).'">'.$section_overview_title.'</a></li>';
			$children = wp_list_pages('sort_column=menu_order&exclude='.$page_exclusions.'&title_li=&child_of='.$post->post_parent.'&echo=0');
		}
	}
	else {
		if(wp_list_pages("child_of=".$post->ID."&echo=0")) { // if page off of main nav has children
			//$section_title = get_the_title($post->ID);
			$section_overview = '<li class="current_page_item"><a href="'.get_permalink($post->ID).'">'.$section_overview_title.'</a></li>';
			$children = wp_list_pages('sort_column=menu_order&exclude='.$page_exclusions.'&title_li=&child_of='.$post->ID.'&echo=0');
		}
	}
}
?>
