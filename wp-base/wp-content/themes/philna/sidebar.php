</div><!--content end-->
<div id="sidebar">
<?php
$options = get_option('philna_options');
if( $options['showcase_content'] && (
($options['showcase_registered'] && $user_ID) || 
($options['showcase_commentator'] && !$user_ID && isset($_COOKIE['comment_author_'.COOKIEHASH])) || 
($options['showcase_visitor'] && !$user_ID && !isset($_COOKIE['comment_author_'.COOKIEHASH]))
) ) : 
?>
<?php if(!is_bot()):?>
<div class="sidebar-top  sidebar_notice">
<?php if($options['showcase_caption']) : ?>
<?php if($options['showcase_title']){ echo "<h3>";echo($options['showcase_title']);echo "</h3>";}?>
<?php endif; ?>
<div id="sidebar_notice">
<?php echo($options['showcase_content']); ?>
</div>
</div>
<?endif;?>
<?endif;?>

<div class="sidebar-top  widget_ posts">
<!-- posts -->
<?php
if (is_single()) {
$posts_widget_title =_e('<h3>Recent Posts</h3>','philna');
} else {
$posts_widget_title =_e('<h3>Random Posts</h3>','philna');
}
?>
<?php echo $posts_widget_title; ?>
<ul>
<?php
if (is_single()) {
$posts = get_posts('numberposts=5&orderby=post_date');
} else {
$posts = get_posts('numberposts=5&orderby=rand');
}
foreach($posts as $post) {
setup_postdata($post);
echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
}
$post = $posts[0];
?>
</ul>
</div>

<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('north_sidebar')):?>
<?php if(!is_home()):?>
<?php if (function_exists('yinheli_most_commented')) : ?>
<div class="widget">
	<h3><?php _e('Most commented','philna')?></h3>
		<ul id="yinheli_most_commented">
		<?php yinheli_most_commented(8,30);?>
		</ul>
</div>
<?php endif;?>
<?php endif;?>


<div class="widget">
	<h3><?php _e('Recent Comments','philna')?></h3>
<?php if(function_exists('wp_recentcomments')) :?>
<ul id="recentcomments">
<?php wp_recentcomments('limit=8&length=16&post=false&smilies=true&trackback=false'); ?>
</ul>
<?php else:?>
	<?php if(function_exists('yinheli_recentcomments')) :?>
		<ul id="yinheli_recentcomments">
		<?php yinheli_recentcomments();?>		
		</ul>
<?php endif; endif;?>	
</div>
<?php endif;//north_sidebar?>

<div class="widget">

<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('east_sidebar')):?>

	<div class="widget_right">
		<h3><?php _e('Categories','philna')?></h3>
			<ul>
			<?php wp_list_cats('sort_column=name&optioncount=0&depth=1'); ?>
			</ul>
	</div>
<?php endif;//east_sidebar?>

<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('west_sidebar')):?>

	<div class="widget_left">
		<h3><?php _e('Archives','philna')?></h3>
			<ul>
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
	</div>

<?php endif;//west_sidebar?>

<div class="fixed"></div>
</div>


<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('south_sidebar')):?>

<div class="widget widget_tag_cloud">
	<h3><?php _e('Tag Cloud','philna')?></h3>
	<?php wp_tag_cloud('smallest=10&largest=16'); ?>
</div>

<?php if(is_home() && is_first_page()):?>
<div class="widget">
	<h3>Links</h3>
		<ul id="widget-links">
		<?php wp_list_bookmarks('title_li=&categorize=0&orderby=rand&limit=8'); ?>
		</ul>
<div class="fixed"></div>
</div>
<?php endif;?>


<div class="widget">
<h3><?php _e('Meta','philna')?></h3>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
</ul>
</div>

<?php endif;//south_sidebar?>

</div>

