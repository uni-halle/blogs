<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'AWPCP'); ?>:</label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
</p>

<p>
    <input type="hidden" name="<?php echo $this->get_field_name('hide-empty'); ?>" value="0" />
    <input type="checkbox" id="<?php echo $this->get_field_id('hide-empty'); ?>" name="<?php echo $this->get_field_name('hide-empty'); ?>" value="1" <?php echo $instance['hide-empty'] ? 'checked="true"' : ''; ?> />
    <label for="<?php echo $this->get_field_id('hide-empty'); ?>"><?php _e('Hide empty categories.', 'AWPCP'); ?></label>
    <br/>
    <input type="hidden" name="<?php echo $this->get_field_name('show-parents-only'); ?>" value="0" />
    <input type="checkbox" id="<?php echo $this->get_field_id('show-parents-only'); ?>" name="<?php echo $this->get_field_name('show-parents-only'); ?>" value="1" <?php echo $instance['show-parents-only'] ? 'checked="true"' : ''; ?> />
    <label for="<?php echo $this->get_field_id('show-parents-only'); ?>"><?php _e('Show parent categories only.', 'AWPCP'); ?></label>
    <br/>
    <input type="hidden" name="<?php echo $this->get_field_name('show-ad-count'); ?>" value="0" />
    <input type="checkbox" id="<?php echo $this->get_field_id('show-ad-count'); ?>" name="<?php echo $this->get_field_name('show-ad-count'); ?>" value="1" <?php echo $instance['show-ad-count'] ? 'checked="true"' : ''; ?> />
    <label for="<?php echo $this->get_field_id('show-ad-count'); ?>"><?php _e('Show Ad count.', 'AWPCP'); ?></label>
</p>