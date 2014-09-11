<div id="sidebar">
<ul>

<?php if ( !function_exists('dynamic_sidebar')
|| !dynamic_sidebar('sidebar-interior') ) : ?>
<?php endif; ?>



<?php
// this is where 3 headlines from the current category get printed	  
if ( is_single() ) :
global $post;
$categories = get_the_category();
foreach ($categories as $category) :
?>

<?php
$posts = get_posts('numberposts=3&exclude=' . $GLOBALS['current_id'] . '&category='. $category->term_id);
if(count($posts) > 1) {
?>
<li>
<div class="widget">
<h3>More <em>in</em> '<?php echo $category->name; ?>'</h3>
<ul>

<?php foreach($posts as $post) : ?>
<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>

</ul>
</div>
</li>
<?php } ?>

<?php endforeach; ?>
<?php endif; ?>

<li>
<?php include (TEMPLATEPATH . "/tag-cloud.php"); ?>
</li>

<li>
<?php include (TEMPLATEPATH . "/recent-comments.php"); ?>
   </li>

</ul>
</div>

