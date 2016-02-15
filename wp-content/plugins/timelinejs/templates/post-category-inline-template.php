<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

$category = get_category(get_queried_object_id());


// var_dump($category);exit;

$social_icon_url = plugins_url( 'img/', dirname(__FILE__) );
$category_url = esc_url( get_permalink($category->term_id) );

$category_title = $category->name;

$category_url = site_url().'/category/'.$category->slug;

$hotswap = get_field('only_display_other_timeline_hotswap', 'option');
$specific_categories = get_field('display_only_these_post_categories_as_a_timeline', 'option');

$categories = get_categories(array('type'=>'post','orderby'=>'name','taxonomy'=>'category'));

	get_header(); ?>
<script>
$(document).ready(function() {
	$('#category-hotswap').change(function() {
		window.location = $(this).val();
	});
});
</script>
<style>
	.category-hotswap {
	display: block;
    margin: auto;
	  border: 2px solid black;
	  color: black;
	  background:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50px' height='50px'><polyline points='46.139,15.518 25.166,36.49 4.193,15.519'/></svg>");
	  background-color:#3498DB;
	  background-repeat:no-repeat;
	  background-position: right 10px top 15px;
	  background-size: 16px 16px;
	  padding:12px;
	  width:auto;
	  font-family:arial,tahoma;
	  font-size:16px;
	  font-weight:bold;
	  text-align:center;
	  text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);
	  border-radius:3px;
	  -webkit-border-radius:3px;
	  -webkit-appearance: none;
	  border:0;
	  outline:0;
	  -webkit-transition:0.3s ease all;
		   -moz-transition:0.3s ease all;
		    -ms-transition:0.3s ease all;
		     -o-transition:0.3s ease all;
		        transition:0.3s ease all;
	}

.category-hotswap {border:2px solid black;background-color:#FFFFFF;}
.category-hotswap:hover {background-color:#F9F9F9;}

.sm-social-sharing {padding:10px;}
.sm-social-icon {width:40px;height:40px;}

</style>
	<div class="timeline-category">
		<br>
		<select id="category-hotswap" class="category-hotswap">
		  <optgroup label="Categories">
		  	<?php
		  	foreach($categories as $link_category) {

		  		if($hotswap && !in_array($link_category->term_id, $specific_categories)) {
		  			continue;
		  		}

		  		$selected = '';
		  		if ($category->term_id == $link_category->term_id) {
		  			$selected = ' selected="selected" ';
		  		}
		    	echo '<option value="'.site_url().'/category/'.$link_category->slug.'" '.$selected.'>'.$link_category->name.'</option>';
		    } ?>
		  </optgroup>
		</select>
		<br>
		<br>
		<?php do_shortcode('[timeline_post_category id="'.$post->ID.'" name="'.$category->slug.'" fullscreen="true"]'); ?>
		<div class="sm-social-sharing">
		   
		    <a href="http://www.facebook.com/sharer.php?u=<?php echo $category_url; ?>" target="_blank">
		        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>facebook.png" alt="Facebook" />
		    </a>
		    <a href="https://plus.google.com/share?url=<?php echo $category_url; ?>" target="_blank">
		        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>google+.png" alt="Google" />
		    </a>
		    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $category_url; ?>" target="_blank">
		        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>linkedin.png" alt="LinkedIn" />
		    </a>
		    <a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
		        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>pinterest.png" alt="Pinterest" />
		    </a>
		    <a href="http://www.tumblr.com/share/link?url=<?php echo $category_url; ?>&amp;title=Simple Share Buttons" target="_blank">
		        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>tumblr.png" alt="Tumblr" />
		    </a>
		    <a href="https://twitter.com/share?url=<?php echo $category_url; ?>&amp;text=<?php echo $category_title; ?>&amp;hashtags=storymap" target="_blank">
		        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>twitter.png" alt="Twitter" />
		    </a>

		</div>
	</div>
	<?php wp_footer(); ?>
	<?php get_footer(); ?>