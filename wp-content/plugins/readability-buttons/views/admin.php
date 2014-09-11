<!-- This file is used to markup the administration form of the widget. -->
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" placeholder="Leave blank to disable title">
</p>

<p>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_read_now'); ?>" name="<?php echo $this->get_field_name('show_read_now'); ?>" value="1" <?php checked( '1', $show_read_now ); ?>>
  <label for="<?php echo $this->get_field_id('show_read_now'); ?>"><?php _e('Read Now'); ?></label>
  <br>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_read_later'); ?>" name="<?php echo $this->get_field_name('show_read_later'); ?>" value="1" <?php checked( '1', $show_read_later ); ?>>
  <label for="<?php echo $this->get_field_id('show_read_later'); ?>"><?php _e('Read Later'); ?></label>
  <br>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_print'); ?>" name="<?php echo $this->get_field_name('show_print'); ?>" value="1" <?php checked( '1', $show_print ); ?>>
  <label for="<?php echo $this->get_field_id('show_print'); ?>"><?php _e('Print'); ?></label>
  <br>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_email'); ?>" name="<?php echo $this->get_field_name('show_email'); ?>" value="1" <?php checked( '1', $show_email ); ?>>
  <label for="<?php echo $this->get_field_id('show_email'); ?>"><?php _e('Email'); ?></label>
  <br>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_send_to_kindle'); ?>" name="<?php echo $this->get_field_name('show_send_to_kindle'); ?>" value="1" <?php checked( '1', $show_send_to_kindle ); ?>>
  <label for="<?php echo $this->get_field_id('show_send_to_kindle'); ?>"><?php _e('Send to Kindle'); ?></label>
  <br>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_vertical'); ?>" name="<?php echo $this->get_field_name('show_vertical'); ?>" value="1" <?php checked( '1', $show_vertical ); ?>>
  <label for="<?php echo $this->get_field_id('show_vertical'); ?>"><?php _e('Vertical'); ?></label>
</p>

<!-- <p>
  <label for="<?php echo $this->get_field_id('horitontal'); ?>">
    <input type="radio" name="orientation" value="0" id="<?php echo $this->get_field_id('horitontal'); ?>" checked>
    <span><?php _e('Horizontal'); ?></span>
  </label>
  <br>
  <label for="<?php echo $this->get_field_id('vertical'); ?>">
    <input type="radio" name="orientation" value="1" id="<?php echo $this->get_field_id('vertical'); ?>">
    <span><?php _e('Vertical'); ?></span>
  </label>
</p> -->

<p>
  <label for="<?php echo $this->get_field_id('text_color'); ?>"><?php _e('Text Color:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('text_color'); ?>" name="<?php echo $this->get_field_name('text_color'); ?>" type="text" value="<?php echo $text_color; ?>" placeholder="Leave blank for default color">
  <br>
  <small>Text color and background color should be in hexadecimal format WITHOUT '#', e.g. eeeeee. For more information please visit <a href="http://en.wikipedia.org/wiki/Web_colors" title="Web colors - Wikipedia, the free encyclopedia">Web colors</a> at Wikipedia.</small>
</p>
<p>
  <label for="<?php echo $this->get_field_id('bg_color'); ?>"><?php _e('Background Color:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('bg_color'); ?>" name="<?php echo $this->get_field_name('bg_color'); ?>" type="text" value="<?php echo $bg_color; ?>" placeholder="Leave blank for default color">
</p>

<p style="color: #3a87ad; background: #d9edf7; padding: 4px !important; border: 1px solid #bce8f1; border-radius: 2px; font-size: 12px; float: left; clear: left;">
	<?php _e( '<strong>Like this plugin?</strong> Consider <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=VJRCEYRS8GTYE&lc=C2&item_name=Plugin%20Donation&button_subtype=services&currency_code=USD&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted" target="_blank">donating</a>. Check out my <a href="http://profiles.wordpress.org/sparanoid/" target="_blank">other plugins</a>, too.', 'instagram-badges' ); ?>
</p>
