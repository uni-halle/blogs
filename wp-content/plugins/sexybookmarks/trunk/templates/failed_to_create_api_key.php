<?php if ( current_user_can( 'manage_options' ) ){ ?>
  <div class="shareaholic-wrap-container" style="padding: 0 20px 0px 15px; background-color: #45a147; margin: 25px 0px 20px -18px;">
    <div style="margin: 0px 8px 0 4px; float: left;"><img src="<?php echo SHAREAHOLIC_ASSET_DIR; ?>img/check.png" width=56 height=50 /></div>
    <div class="shareaholic-text-container" style="color: #fff; text-shadow: 0px 1px 1px rgba(0,0,0,0.4); font-size: 14px; display: table-cell;">
      <p>
        <strong><?php echo sprintf(__('Action required: You\'ve installed Shareaholic for WordPress.  We\'re ready when you are. %sGet started now &raquo;%s', 'shareaholic'), '<a href="admin.php?page=shareaholic-settings" class="button-secondary">', '</a>'); ?></strong>
      </p>
    </div>
  </div>
  <div style="clear:both;"></div>
<?php } ?>