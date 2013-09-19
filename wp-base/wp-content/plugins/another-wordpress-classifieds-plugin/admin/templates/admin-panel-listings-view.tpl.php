<div style="padding:20px" class="postbox">
    <div style="padding:4px 0px;; margin-bottom:5px;">

        <?php if (awpcp_current_user_is_admin()): ?>
        <strong><?php _e('Access Key', 'AWPCP'); ?></strong>: <?php echo $ad->get_access_key(); ?>
        <?php endif; ?>

        &raquo; <a href="<?php echo esc_attr($this->url(array('action' => false))) ?>"><?php _e('Return to Listings', 'AWPCP') ?></a>

    </div>

    <div style="padding:4px 0px;; margin-bottom:10px;">

        <b><?php _e('Category', 'AWPCP') ?></b>:
        <a href="<?php echo esc_attr($category['url']) ?>"><?php echo $category['name'] ?></a> &raquo;

        <b><?php _e('Manage Listing', 'AWPCP') ?></b>:
        <?php
            $a = array();
            foreach ($links as $label => $link)
                $a[] = $link;
            echo join(' | ', $a);
        ?>

    </div>

    <?php echo $content ?>
</div>
