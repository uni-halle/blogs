<?php ShareaholicAdmin::show_header(); ?>
<div class='wrap'>
  <div id="icon-options-general" class="icon32"></div>
  <h2><?php _e('Advanced Settings', 'shareaholic'); ?></h2>
  <div style="margin-top:20px;"></div>
    
  <div class='unit size4of5' style="min-height:300px;">
    <span class="helper"><i class="icon-star"></i> <?php echo sprintf(__('You rarely should need to edit the settings on this page.', 'shareaholic')); ?> <?php _e('After changing any Shareaholic advanced setting, it is good practice to clear any WordPress caching plugins (if you are using one, like W3 Total Cache or WP Super Cache).', 'shareaholic'); ?></p></span>
    
    <form name='advanced_settings' method='post' action='<?php echo $action ?>'>
    <?php wp_nonce_field($action, 'nonce_field') ?>
    <input type='hidden' name='already_submitted' value='Y'>
      <div class='clear'>
        <fieldset class="app">
        <legend><h2><?php _e('Advanced', 'shareaholic'); ?></h2></legend>
          <input type='checkbox' id='tracking' name='shareaholic[disable_tracking]' class='check'
            <?php if (isset($settings['disable_tracking'])) { ?>
              <?php echo ($settings['disable_tracking'] == 'on' ? 'checked' : '') ?>
              <?php } ?>>
            <label style="display: inline-block; font-size:12px;" for="tracking"><?php echo sprintf(__('Disable Analytics', 'shareaholic')); ?> <?php echo sprintf(__('(it is recommended NOT to disable analytics)', 'shareaholic')); ?></label>
          <br />
          <input type='checkbox' id='og_tags' name='shareaholic[disable_og_tags]' class='check'
            <?php if (isset($settings['disable_og_tags'])) { ?>
              <?php echo ($settings['disable_og_tags'] == 'on' ? 'checked' : '') ?>
              <?php } ?>>
            <label style="display: inline-block; font-size:12px;" for="og_tags"><?php echo sprintf(__('Disable <code>Open Graph</code> tags', 'shareaholic')); ?> <?php echo sprintf(__('(it is recommended NOT to disable open graph tags)', 'shareaholic')); ?></label>
            <br />
          <input type='checkbox' id='admin_bar' name='shareaholic[disable_admin_bar_menu]' class='check'
            <?php if (isset($settings['disable_admin_bar_menu'])) { ?>
              <?php echo ($settings['disable_admin_bar_menu'] == 'on' ? 'checked' : '') ?>
              <?php } ?>>
            <label style="display: inline-block; font-size:12px;" for="admin_bar"><?php echo sprintf(__('Disable Admin Bar Menu (requires page refresh)', 'shareaholic')); ?></label>
          <br/>
          <input type='checkbox' id='debugger' name='shareaholic[disable_debug_info]' class='check'
            <?php if (isset($settings['disable_debug_info'])) { ?>
              <?php echo ($settings['disable_debug_info'] == 'on' ? 'checked' : '') ?>
              <?php } ?>>
            <label style="display: inline-block; font-size:12px;" for="debugger"><?php echo sprintf(__('Disable Debugger (it is recommended NOT to disable the debugger)', 'shareaholic')); ?></label>
          <br/>
          <input type='checkbox' id='share_counts' name='shareaholic[disable_internal_share_counts_api]' class='check'
            <?php if (isset($settings['disable_internal_share_counts_api'])) { ?>
              <?php echo ($settings['disable_internal_share_counts_api'] == 'on' ? 'checked' : '') ?>
              <?php } ?>>
            <label style="display: inline-block; font-size:12px;" for="share_counts"><?php echo sprintf(__('Disable server-side Share Counts API', 'shareaholic')); ?> <?php echo sprintf(__('(unless there are issues with calling the service, it is recommended NOT to disable this API)', 'shareaholic')); ?></label>
          <div class='clear' style="padding-top:10px;"></div>
          <input type='submit' onclick="this.value='<?php echo sprintf(__('Saving Changes...', 'shareaholic')); ?>';" value='<?php echo sprintf(__('Save Changes', 'shareaholic')); ?>'>
        </fieldset>
      </div> 
    </form>
    
    <div class='clear'></div>  
    
    <fieldset class="app">
      <legend><h2><?php _e('Server Connectivity', 'shareaholic'); ?></h2></legend>
      <?php if (ShareaholicUtilities::connectivity_check() == "SUCCESS") { ?>
        <span class="key-status passed"><?php  _e('All Shareaholic servers are reachable', 'shareaholic'); ?></span>
        <div class="key-description"><?php _e('Shareaholic should be working correctly.', 'shareaholic'); ?> <?php _e('All Shareaholic servers are accessible.', 'shareaholic'); ?></div>  
      <?php } else { // can't connect to any server ?>
        <span class="key-status failed"><?php _e('Unable to reach any Shareaholic server', 'shareaholic'); ?></span> <a href="#" onClick="window.location.reload(); this.innerHTML='<?php _e('Checking...', 'shareaholic'); ?>';"><?php _e('Re-check', 'shareaholic'); ?></a>
        <div class="key-description"><?php echo sprintf( __('A network problem or firewall is blocking all connections from your web server to Shareaholic.com.  <strong>Shareaholic cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Shareaholic and firewalls</a>. Let us <a href="#" onclick="%s">know</a> too, so we can follow up!'), 'http://blog.shareaholic.com/shareaholic-hosting-faq/', 'SnapEngage.startLink();','</a>'); ?></div>
      <?php } ?>
      <?php if (ShareaholicUtilities::share_counts_api_connectivity_check() == 'SUCCESS') { ?>
        <span class="key-status passed"><?php  _e('Server-side Share Counts API is reachable', 'shareaholic'); ?></span>
        <div class="key-description"><?php _e('The server-side Share Counts API should be working correctly.', 'shareaholic'); ?> <?php _e('All servers and services needed by the API are accessible.', 'shareaholic'); ?></div>
      <?php } else { // can't connect to any server ?>
        <span class="key-status failed"><?php _e('Unable to reach the server-side Share Count API', 'shareaholic'); ?></span> <a href="#" onClick="window.location.reload(); this.innerHTML='<?php _e('Checking...', 'shareaholic'); ?>';"><?php _e('Re-check', 'shareaholic'); ?></a>
        <div class="key-description"><?php echo sprintf( __('A network problem or firewall is blocking connections from your web server to various Share Count APIs.  <strong>The API cannot work correctly until this is fixed.</strong>  If you continue to face this issue, please contact <a href="#" onclick="%s">us</a> and we will follow up! In the meantime, if you disable the server-side Share Counts API from the Advanced options above, Shareaholic will default to using client-side APIs for share counts successfully -- so nothing to worry about!'), 'SnapEngage.startLink();'); ?></div>
      <?php } ?>
    </fieldset>
    
    <div class='clear'></div>
    
    <fieldset class="app">
      <legend><h2><?php _e('Your Shareaholic Site ID', 'shareaholic'); ?></h2></legend>
      <?php if (ShareaholicUtilities::get_option('api_key')){
        echo '<code>'.ShareaholicUtilities::get_option('api_key').'</code>';
      } else {
        _e('Not set.', 'shareaholic');
      } ?>
    </fieldset>
    
    <div class='clear'></div>
    
    <form name='reset_settings' method='post' action='<?php echo $action ?>'>
      <?php wp_nonce_field($action, 'nonce_field') ?>
      <input type='hidden' name='reset_settings' value='Y'>
      <fieldset class="app">
        <legend><h2><?php _e('Reset', 'shareaholic'); ?></h2></legend>
        <?php _e('This will reset all of your settings and start you from scratch. This can not be undone.', 'shareaholic'); ?>
        <div class='clear'></div>  
        <input type='submit' onclick="this.value='<?php _e('Resetting Plugin...', 'shareaholic'); ?>';" value='<?php _e('Reset Plugin', 'shareaholic'); ?>'>        
      </fieldset>
      
      <div class='clear' style="padding-bottom:35px;"></div>
      
    </form>    
  </div>
</div>
<?php ShareaholicAdmin::show_footer(); ?>
<?php ShareaholicAdmin::include_snapengage(); ?>