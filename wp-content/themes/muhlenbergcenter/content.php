<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */
?>

<?php if (is_category([
    'press',
    'publications',
    'projects',
    'networks',
    'material-links',
    'key-research-areas'
])) : /* Begin: Category Press and Research */ ?>
<li id="post-<?= the_ID(); ?>" class="media">
    <div class="thumbnail">
        <a href="<?= the_permalink(); ?>"
           title="<?php sprintf(_e('More about ', 'muhlenbergcenter'), the_title()); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <?= the_post_thumbnail([285,180]); ?>
            <?php else : ?>
                <img src="<?= esc_url(get_template_directory_uri()); ?>/img/teaser_press.jpg"
                     alt="teaser image" />
            <?php endif ?>
        </a>
    </div>
    <p>
        <a href="<?= the_permalink(); ?>"
           title="<?php sprintf( _e('More about ', 'muhlenbergcenter'), the_title() ); ?>">
            <?= the_title(); ?>
        </a>
    </p>
</li>

<?php elseif (is_category([
    'board-of-directors',
    'board-of-advisors',
    'staff'
])) : /* Begin: Category Members */ ?>
<li id="post-<?= the_ID(); ?>" class="person">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="thumbnail">
        <a href="<?= the_permalink(); ?>"
           title="<?php sprintf( _e('More about ', 'muhlenbergcenter'), the_title() ); ?>">
            <?= the_post_thumbnail([285, 180]); ?>
        </a>
    </div>
    <?php endif ?>
    <h3 class="person__name">
        <a href="<?= the_permalink(); ?>"
           class="person__link"
           title="<?php sprintf( _e('More about ', 'muhlenbergcenter'), the_title() ); ?>">
            <?= the_title(); ?><br/>
            <span><?= get_post_meta($post->ID, 'position', true); ?></span>
        </a>
    </h3>
    <ul class="no-bullet">
        <li><?= get_post_meta($post->ID, 'email', true); ?></li>
        <li>Phone: <?= get_post_meta($post->ID, 'phone', true); ?></li>
        <li>
            <a href="<?= the_permalink(); ?>"
               title="<?php sprintf( _e('More about ', 'muhlenbergcenter'), the_title() ); ?>">
                <?= _e('read more', 'muhlenbergcenter'); ?>
            </a>
        </li>
    </ul>
</li>
<?php else : /* End: Category Members */ ?>

<article id="post-<?= the_ID(); ?>">
    <?php if (has_post_thumbnail()) : ?>
    <div class="medium-4 columns">
        <?= the_post_thumbnail([285,180]); ?>
    </div>
    <?php endif ?>
    <div class="medium-8 columns">
        <?php
            if (is_single()) :
                the_title('<h1>', '</h1>');
            else :
                the_title(sprintf('<h2><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
            endif;
        ?>

        <div class="content">
            <?php
                the_content(sprintf(
                    __('Continue reading %s', 'muhlenbergcenter'),
                    the_title('<span class="screen-reader-text">', '</span>', false)
                ));
            ?>
        </div>
    </div>
</article>

<?php endif ?>