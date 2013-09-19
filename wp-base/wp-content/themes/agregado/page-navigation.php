<?php
/*
Plugin Name: Paged Navigation
Plugin URI: http://www.stuff.yellowswordfish.com/paged-navigation
Description: Replaces the Next/Previous page constructs with paged navigation 
Version: 1.0
Author: Andy Staines
Author URI: http://www.yellowswordfish.com
WordPress Version 2.0 and above
*/

/*  Copyright 2007  Andy Staines

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    For a copy of the GNU General Public License, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('PNSHOW', 4);

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
			
			if($nav['next'] != 0)
			{
				echo('<a href="'.get_pagenum_link($nav['next']).'"></a>');
			}							
			
			if($nav['previous'] != 0)
			{
				echo('<a href="'.get_pagenum_link($nav['previous']).'"></a>');
			}					

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

	echo('<span class="pagenav">PAGE '.$paged.' of '.$max_page.'</span>');
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
?>