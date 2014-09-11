<?php
/**
 * 
 * @author alexhermann
 *
 */

$fields = array();
$list_menu_plugins = array();
require(dirname(dirname(__FILE__)).'/forms/plugins_menu.php');
require(dirname(dirname(__FILE__)).'/forms/like_button.php');
require(dirname(dirname(__FILE__)).'/forms/like_box.php');
require(dirname(dirname(__FILE__)).'/forms/activity_box.php');
require(dirname(dirname(__FILE__)).'/forms/shared_activity_box.php');
require(dirname(dirname(__FILE__)).'/forms/login_button.php');
require(dirname(dirname(__FILE__)).'/forms/comments_box.php');

$fields = apply_filters('AWD_facebook_plugins_form', $fields);
if(!is_array($fields)){
	$fields = array();
}
$list_menu_plugins = apply_filters('AWD_facebook_plugins_menu', $list_menu_plugins);
if(!is_array($list_menu_plugins)){
	$list_menu_plugins = array();
}
$form = new AWD_facebook_form('form_settings', 'POST', '', $this->plugin_option_pref);
?>
<div id="div_options_content">
	
	<?php echo $form->start(); ?>
		
		<div id="settings_plugins" class="tabbable tabs-left">
			
			<?php if(count($list_menu_plugins)){ ?>
				<ul id="plugins_menu" class="nav nav-tabs">  	
					<?php 
					foreach($list_menu_plugins as $item_id => $label){
						echo '<li><a href="#'.$item_id.'" data-toggle="tab">'.$label.'</a></li>';
					}
					?>
				</ul>
			<?php } ?>
			
			
			<div class="tab-content">
			
				<div id="like_button_settings" class="tab-pane">
					<?php 					
					if(isset($fields['like_button']) && is_array($fields['like_button'])){
						echo $form->proccessFields('like_button',$fields['like_button']);
					}
					?>
				</div>
				
				<div id="like_box_settings" class="tab-pane">
					<?php 					
					if(isset($fields['like_box']) && is_array($fields['like_box'])){						
						echo $form->proccessFields('like_box',$fields['like_box']); 
					}
					?>
				</div>
				
				<div id="activity_settings" class="tab-pane">				
					<?php 
					if(isset($fields['activity_box']) && is_array($fields['activity_box'])){
						echo $form->proccessFields('activity_box',$fields['activity_box']);
					}
					?>
				</div>

				<div id="shared_activity_settings" class="tab-pane">				
					<?php 
					if($this->options['connect_enable'] == 1){
						if(isset($fields['shared_activity_box']) && is_array($fields['shared_activity_box'])){
							echo $form->proccessFields('shared_activity_box',$fields['shared_activity_box']);
						}
					}else{
						$this->display_messages(__('You must enable FB Connect and set parameters in settings of the plugins',$this->ptd), 'error');
					}
					?>
				</div>
				
				
				<div id="login_button_settings" class="tab-pane">
					<?php 
					if($this->options['connect_enable'] == 1){
						if(isset($fields['login_button']) && is_array($fields['login_button'])){
							echo $form->proccessFields('login_button',$fields['login_button']);
						}
					}else{
						$this->display_messages(__('You must enable FB Connect and set parameters in settings of the plugins',$this->ptd), 'error');
					}
					?>
				</div>
				
				<div id="comments_settings" class="tab-pane">
					<?php
					$message = '<i class="icon-warning-sign"></i> '.__('The form place depend on how your form theme is coded. Maybe some places will not work with your theme.',$this->ptd);
					$this->display_messages($message, 'info');
					
					if(isset($fields['comments_box']) && is_array($fields['comments_box'])){
						echo $form->proccessFields('comments_box',$fields['comments_box']);
					}
					?>
				</div>
				
				<?php
				//Plugins sections
				$plugin_fields = $fields;
				unset($plugin_fields['comments_box']);
				unset($plugin_fields['login_button']);
				unset($plugin_fields['like_box']);
				unset($plugin_fields['like_button']);
				unset($plugin_fields['activity_box']);
				foreach($plugin_fields as $plugin=>$fields)
				{
					?>
					<div id="<?php echo $plugin ?>_settings" class="tab-pane">
						<?php
						if(isset($fields) && is_array($fields)){
							echo $form->proccessFields($plugin,$fields);
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		
		<?php wp_nonce_field($this->plugin_slug.'_update_options',$this->plugin_option_pref.'_nonce_options_update_field'); ?>
		
		<div class="form-actions">
			<a href="#" id="submit_settings" class="btn btn-primary" data-loading-text="<i class='icon-time icon-white'></i> <?php _e('Saving settings...',$this->ptd); ?>"><i class="icon-cog icon-white"></i> <?php _e('Save all settings',$this->ptd); ?></a>
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZQ2VL33YXHJLC" class="awd_tooltip_donate btn pull-right" id="help_donate" target="_blank" class="btn pull-right"><i class="icon-heart"></i> <?php _e('Donate!',$this->ptd); ?></a>
		</div>

	<?php echo $form->end(); ?>

</div>