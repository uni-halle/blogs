<ul class="posts">
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <li>
        <?php if (is_page() || is_single()) { ?>
        <h2 class="post-title"><?php the_title(); ?></h2>
        <?php } else { ?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php } ?>
        <?php if((!is_page() or $options['metaenabled']) or $options['txtsizechange']) { ?>
        <div class="post-meta">
            <?php if(!is_page() or $options['metaenabled']) { ?>
            <div class="meta-left">
                <p class="postdate"><?php the_time(__('M jS Y')) ?></p>
                <p class="postauthor"><?php _e('By:');?> <?php the_author(); ?></p>
            </div>
            <?php } ?>
            <div class="meta-right">
                <?php if(!is_page() or $options['metaenabled']) { ?>
                <p class="postcomments"><a href="<?php comments_link(); ?>"><?php comments_number( __( 'No comments' ), __( '1 comment' ), __( '% comments' ),  __( 'comments' )); ?></a></p>
                <?php } ?>
                <?php if((is_single() or is_page()) and $options['txtsizechange']) { ?>
                <p class="changesize" onclick="changeFontSize()" title="Increase/Decrease font size"><span id="fontsmall">A</span> <span id="fontbig">A</span></p>
                <input type="hidden" value="inc" name="fontoperation" id="fontoperation" />
                <?php } ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php } ?>
        <div class="post-content">
            <?php if((!is_single() and !is_archive() and !is_search() and $options['excerptfront']) or (is_archive() and $options['excerptarchives']) or (is_search() and $options['excerptsearch'])) { ?>
            <?php the_excerpt(); ?>
            <?php } else { ?>
            <?php the_content('<span class="readmore">Read More</span>'); ?>
            <?php } ?>
            <div class="clear"></div>
        </div>
        <?php $parts = wp_link_pages(array('before'=>'Part: ','after'=>'','link_before'=>'<span>','link_after'=>'</span>', 'echo'=>0));
        if(trim($parts) != "") { ?>
        <div class="post-pages">
            <?php echo $parts; ?>
            <div class="clear"></div>
        </div>
        <?php } ?>
        <?php if($options['showaddtoany'] and is_single()) { ?>
        <div class="sharetoany">
            <p class="sharebutton">
                <a class="a2a_dd" href="http://www.addtoany.com/share_save?linkname=&amp;linkurl=<?php the_permalink(); ?>">Share this</a>
            </p>
        </div>
        <?php } ?>
        <?php if($options['showcattag'] and !is_page()) { ?>
        <div class="post-info">
            <div class="info-left">
                <p class="post-category"><?php the_category(', ') ?></p>
            </div>
            <div class="info-right">
                <p class="post-tag"><?php the_tags('', ', ', ''); ?></p>
            </div>
            <div class="clear"></div>
        </div>
        <?php } ?>
        <?php if (function_exists('wp_list_comments')): ?>
            <?php comments_template('', true); ?>
        <?php endif; ?>
    </li>
    <?php endwhile; ?>
    <?php else : ?>
    <li>
        <?php include (TEMPLATEPATH . "/missing.php"); ?>
    </li>
    <?php endif; ?>
    <?php if(!is_single()) { ?>
    <li>
        <?php include(TEMPLATEPATH . "/navigation.php"); ?>
    </li>
    <?php } ?>
</ul>