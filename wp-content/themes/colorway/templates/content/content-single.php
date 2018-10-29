<div id="post">

    <header class="entry-header">
        <?php the_title(sprintf('<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
    </header><!-- .entry-header -->

    <ul class="post_meta">
        <?php if (inkthemes_get_option('singlepage_author','on')=='on') { ?>
            <li class="posted_by"><span><?php esc_html_e('By', 'colorway'); ?> </span><?php the_author_posts_link(); ?></li>
        <?php } ?>
        <?php if (inkthemes_get_option('singlepage_categories','on')=='on') { ?>
            <li class="posted_in"><?php esc_html_e('Posted in ', 'colorway') . the_category(', '); ?></li>
        <?php } ?>
        <?php if (inkthemes_get_option('singlepage_date','on')=='on') { ?>
            <li class="post_date"><?php esc_html_e('On ', 'colorway') . the_time('F j, Y'); ?></li>
        <?php } ?>
        <?php if (inkthemes_get_option('singlepage_comments','on')=='on') { ?>
            <li class="postc_comment"><?php comments_popup_link(__('No Comments.', 'colorway'), __('1 Comment.', 'colorway'), __('% Comments.', 'colorway')); ?></li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
    <?php the_content(); ?>
    <div class="clear"></div>
    <div class="tags">
        <?php the_tags(__('Post Tagged with ', 'colorway'), ', ', ''); ?>
    </div>
    <div class="clear"></div>
    <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . 'Pages:' . '</span>', 'after' => '</div>')); ?>

</div>