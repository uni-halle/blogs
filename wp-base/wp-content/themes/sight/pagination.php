<?php if (get_option('paging_mode') == 'default') : ?>
    <div class="pagination">
        <?php previous_posts_link(__('Newer')); ?>
        <?php next_posts_link(__('Older')); ?>
        <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); ?>
    </div>
    <?php else : ?>
    <div id="pagination"><?php next_posts_link(__('LOAD MORE')); ?></div>
<?php endif; ?>