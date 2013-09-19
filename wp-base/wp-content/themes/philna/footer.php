<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js"></script>
<script type="text/javascript" src="http://view.jquery.com/trunk/plugins/color/jquery.color.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/yinheli.js"></script>
<?php get_sidebar(); $options = get_option('philna_options');?>
</div><!--container end-->
<div class="fixed"></div>
</div><!--page end-->
</div><!--wall end-->
</div>
<div id="footerbg">
<div id="footer">
<?php if($options['show_welcome'] && !is_bot()) yinheli_welcome();?>
<div id="foot-cotent">
<span id="totop"><a href="#header">Top</a></span>

<a id="powered" href="http://wordpress.org">wordpress</a>
<div id="copyright">
<?php
global $wpdb;
$post_datetimes = $wpdb->get_results("SELECT YEAR(post_date_gmt) AS year FROM wp_posts WHERE post_date_gmt > 1949 ORDER BY post_date_gmt ASC");
$firstpost_year = $post_datetimes[0]->year;
$lastpost_year = $post_datetimes[count($post_datetimes)-1]->year;

$copyright = __('Copyright &copy; ', 'philna') . $firstpost_year;
if($firstpost_year != $lastpost_year) {
$copyright .= '-'. $lastpost_year;
}
echo $copyright;
?>
 <?php bloginfo('name'); ?> <?php echo $options['footer_content']; ?>
</div>
<div id="themeinfo">
<?php _e('Theme by <a href="http://philna.com">yinheli</a>. Valid <a href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a> and <a href="http://jigsaw.w3.org/css-validator/">CSS 3</a>.', 'philna');?>
<?php if($options['show_online_counter']) include("yinheli_user_online.php");?>
</div>
<?if(!is_bot()):?>
<?php if($_COOKIE["comment_author_" . COOKIEHASH]!=""): ?><!-- js rewrite the title -->
<?php if (is_home()) : ?>
<script type="text/javascript">
document.title = "<?php printf(__('Hi! %s, Welcome back to '), $_COOKIE["comment_author_" . COOKIEHASH]) ?>" + document.title
</script>
<?php elseif (is_single()) : ?>
<script type="text/javascript">
document.title = "<?php printf(__('%s, you are reading '), $_COOKIE["comment_author_" . COOKIEHASH]) ?>" + document.title
</script>
<?php endif; ?>
<?php endif; ?>
<?endif;?>
<?php wp_footer(); ?>
<?php global $user_level;if( $user_level>8):?>
<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
<?php endif;?>
</div>
</div>
</div>
</body>
</html>
