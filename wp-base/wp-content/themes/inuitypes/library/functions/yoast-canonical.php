<?php
/*

Copyright 2009 Joost de Valk (email: joost@yoast.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*
	Changelog:
	1.0.1 		Fixed double slashes on category and tag pages
	1.0 		Initial version
*/
function yoast_guess_url($query) {
	if ($query->is_404 || $query->is_search) {
		return false;
	}
	$haspost = count($query->posts) > 0;
    $has_ut = function_exists('user_trailingslashit');

	// Copied entirely and slightly modified from Scott Yang's Permalink Redirect, http://svn.fucoder.com/fucoder/permalink-redirect/
	if (get_query_var('m')) {
        // Handling special case with '?m=yyyymmddHHMMSS'
        // Since there is no code for producing the archive links for
        // is_time, we will give up and not try to produce a link.
        $m = preg_replace('/[^0-9]/', '', get_query_var('m'));
        switch (strlen($m)) {
            case 4: // Yearly
                $link = get_year_link($m);
                break;
            case 6: // Monthly
                $link = get_month_link(substr($m, 0, 4), substr($m, 4, 2));
                break;
            case 8: // Daily
                $link = get_day_link(substr($m, 0, 4), substr($m, 4, 2),
                                     substr($m, 6, 2));
                break;
            default:
                return false;
        }
    } elseif (($query->is_single || $query->is_page) && $haspost) {
        $post = $query->posts[0];
        $link = get_permalink($post->ID);
        $link = yoast_get_paged($link);
        // WP2.2: In Wordpress 2.2+ is_home() returns false and is_page() 
        // returns true if front page is a static page.
        if ($query->is_page && ('page' == get_option('show_on_front')) && 
            $post->ID == get_option('page_on_front'))
        {
            $link = trailingslashit($link);
        }
    } elseif ($query->is_author && $haspost) {
        global $wp_version;
        if ($wp_version >= '2') {
            $author = get_userdata(get_query_var('author'));
            if ($author === false)
                return false;
            $link = get_author_link(false, $author->ID,
                $author->user_nicename);
        } else {
            // XXX: get_author_link() bug in WP 1.5.1.2
            //      s/author_nicename/user_nicename/
            global $cache_userdata;
            $userid = get_query_var('author');
            $link = get_author_link(false, $userid,
                $cache_userdata[$userid]->user_nicename);
        }
    } elseif ($query->is_category && $haspost) {
        $link = get_category_link(get_query_var('cat'));
		$link = yoast_get_paged($link);
	} else if ($query->is_tag  && $haspost) {
		$tag = get_term_by('slug',get_query_var('tag'),'post_tag');
             if (!empty($tag->term_id)) {
                    $link = get_tag_link($tag->term_id);
             } 
			 $link = yoast_get_paged($link);
   } elseif ($query->is_day && $haspost) {
        $link = get_day_link(get_query_var('year'),
                             get_query_var('monthnum'),
                             get_query_var('day'));
    } elseif ($query->is_month && $haspost) {
        $link = get_month_link(get_query_var('year'),
                               get_query_var('monthnum'));
    } elseif ($query->is_year && $haspost) {
        $link = get_year_link(get_query_var('year'));
    } elseif ($query->is_home) {
        // WP2.1: Handling "Posts page" option. In WordPress 2.1 is_home() 
        // returns true and is_page() returns false if home page has been 
        // set to a page, and we are getting the permalink of that page 
        // here.
        if ((get_option('show_on_front') == 'page') &&
            ($pageid = get_option('page_for_posts'))) 
        {
            $link = get_permalink($pageid);
			$link = yoast_get_paged($link);
			$link = trailingslashit($link);
        } else {
            $link = get_option('home');
			$link = yoast_get_paged($link);
			$link = trailingslashit($link);
        }
    } else {
        return false;
    }
	return $link;
}

function yoast_canonical_link() {
	global $wp_query;
	$url = yoast_guess_url($wp_query);
	if ($url) {
		echo '<link rel="canonical" href="'.$url.'"/>'."\n";
	}
}

function yoast_get_paged($link) {
		$page = get_query_var('paged');
        if ($page && $page > 1) {
            $link = trailingslashit($link) ."page/". "$page";
            if ($has_ut) {
                $link = user_trailingslashit($link, 'paged');
            } else {
                $link .= '/';
            }
		}
		return $link;
}
add_action('wp_head','yoast_canonical_link',10,1);
?>