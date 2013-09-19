<?php get_header(); ?>

<div class="content-title">
    Latest entries
    <a href="javascript: void(0);" id="mode"<?php if ($_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a>
</div>

<?php query_posts(array(
        'post__not_in' => $exl_posts,
        'paged' => $paged,
    )
); ?>

<?php get_template_part('loop'); ?>

<?php wp_reset_query(); ?>

<?php get_template_part('pagination'); ?>

<?php get_footer(); ?>
