<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

<div id="pages">
<h3>Pages</h3>
<ul>
    <?php wp_list_pages('title_li='); ?>
</ul>
</div>

<h3>Search</h3>
<p class="searchinfo">search site archives</p>
<form id="searchform" method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<div>
    <input type="text" name="s" id="s" size="15" title="Enter keyword to search" /><br />
    <input type="submit" value="Search" title="Click to search archives" />
</div>
</form>

<h3>Blogroll</h3>
<ul>
<?php get_links('-1', '<li>', '</li>', '', 0, 'name', 0, 0, -1, 0); ?>
</ul>

<h3>Archives</h3>
<ul>
<?php wp_get_archives('type=monthly'); ?>
</ul>

<h3>Categories</h3>
<ul>
    <?php wp_list_cats(); ?>
</ul>	

<h3>Meta</h3>
<ul>
    <li><?php // wp_register(); ?></li>
    <li><?php wp_loginout(); ?></li>
    <li><a href="feed:<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>">Site Feed</a></li>
    <li><a href="feed:<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>">Comments Feed</a></li>
    <li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress.org</a></li>
    <?php wp_meta(); ?>
</ul>
<?php endif; ?>

<?php if (function_exists('wp_theme_switcher')) { ?>
<h3>Themes</h3>
<div class="themes">
<?php wp_theme_switcher(); ?>
</div>
<?php } ?>

</div>
