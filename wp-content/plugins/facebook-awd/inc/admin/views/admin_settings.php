<?php
/**
 *
 * @author alexhermann
 *
 */
$form = new AWD_facebook_form('form_settings', 'POST', '', $this->plugin_option_pref);
?>
<div id="div_options_content">
	<?php echo $form->start(); ?>
		<div id="settings" class="tabbable tabs-left">
			<ul id="settings_menu" class="nav nav-tabs">
				<?php if(current_user_can('manage_facebook_awd_settings')){ ?>
					<li><a href="#general" data-toggle="tab">General</a></li>
					<li><a href="#fbconnect" data-toggle="tab">Facebook Connect</a></li>
					<li><a href="#opengraph" data-toggle="tab">OpenGraph</a></li>
				<?php } ?>
				<?php if(current_user_can('manage_facebook_awd_publish_to_pages')){ ?>
					<li><a href="#managepages" data-toggle="tab">Manage Pages</a></li>
				<?php } ?>
			</ul>
			<div class="tab-content">
				<?php if(current_user_can('manage_facebook_awd_settings')){ ?>
					<div id="general" class="tab-pane">		
						<div class="row">
							<?php 
							echo $form->addInputText(__('App ID (facebook)',$this->ptd).' '.$this->get_the_help('app_id'), 'app_id', $this->options['app_id'], 'span4', array('class'=>'span3'), 'icon-barcode');
							echo $form->addInputText(__('App SECRET KEY',$this->ptd).' '.$this->get_the_help('app_secret_key'), 'app_secret_key', $this->options['app_secret_key'], 'span4', array('class'=>'span3'), 'icon-barcode'); 
							echo $form->addInputText(__('Admin IDs',$this->ptd).' '.$this->get_the_help('admins'), 'admins', $this->options['admins'], 'span4', array('class'=>'span3'), 'icon-barcode'); 
							?>
							<div class="span4">
								<div class="row">
									<?php 						
									echo $form->addInputText(__('Locale',$this->ptd).' '.$this->get_the_help('locale'), 'locale', $this->options['locale'], 'span2', array('class'=>'span1'), 'icon-flag'); 
									?>
								</div>
							</div>
							<?php
								echo $form->addSelect(__('Mode Debug ?',$this->ptd).' '.$this->get_the_help('debug_enable'), 'debug_enable', array(
									array('value'=>0, 'label'=>__('No',$this->ptd)),
									array('value'=>1, 'label'=>__('Yes',$this->ptd))									
								), $this->options['debug_enable'], 'span4', array('class'=>'span1'));
							?>
						</div>
					</div>
					<div id="opengraph" class="tab-pane">							
						<div class="row">
							<?php 
							echo $form->addSelect(__('Activate Open Graph ?',$this->ptd).' '.$this->get_the_help('open_graph_enable'), 'open_graph_enable', array(
								array('value'=>0, 'label'=>__('No',$this->ptd)),
								array('value'=>1, 'label'=>__('Yes',$this->ptd).' '.__('(recommended)',$this->ptd))									
							), $this->options['open_graph_enable'], 'span3', array('class'=>'span3'));
							?>
						</div>
					</div>
			
					<div id="fbconnect" class="tab-pane">	
						<h1><?php _e("Facebook Connect",$this->ptd); ?></h1>						
						<div class="row">
							<?php 
							echo $form->addSelect(__('Activate FB Connect ?', $this->ptd).' '.$this->get_the_help('connect_enable'),'connect_enable',array(
								array('value'=>0, 'label'=>__('No',$this->ptd)),
								array('value'=>1, 'label'=>__('Yes',$this->ptd))									
							),$this->options['connect_enable'],'span3',array('class'=>'span3'));
							
							echo $form->addSelect(__('Add FB avatar choice ?',$this->ptd).' '.$this->get_the_help('connect_fbavatar'), 'connect_fbavatar', array(
								array('value'=>0, 'label'=>__('No',$this->ptd)),
								array('value'=>1, 'label'=>__('Yes',$this->ptd))									
							), $this->options['connect_fbavatar'], 'span3', array('class'=>'span3 depend_fb_connect', 'disabled'=> $this->options['connect_enable']== '0' ? 'disabled':''));
							?>
						</div>
						<div class="row">
							<?php
							echo $form->addInputText(__('Facebook Connect permissions',$this->ptd).' '.$this->get_the_help('perms'), 'perms', $this->options['perms'], 'span3', array('class'=>'span3 depend_fb_connect', 'disabled'=> $this->options['connect_enable']== '0' ? 'disabled':'')); 
							
							echo $form->addInputText(__('Timeout Facebook connect API',$this->ptd).' '.$this->get_the_help('timeout'), 'timeout', $this->options['timeout'], 'span3', array('class'=>'span3 depend_fb_connect', 'disabled'=> $this->options['connect_enable']== '0' ? 'disabled':''));
							?>
						</div>
						<br />
						<h1><?php _e("Facebook Realtime API",$this->ptd); ?></h1>						
						
						<div class="alert alert-info">
							<?php _e("This plugin can keep user data sync in realtime when they change on the facebook side. Normally the plugin wait the user loggin action to fetch new data. If you want to use this feature, you must configure the realtime api in your facebook application settings, on the facebook developer website.", $this->ptd); ?>
							<a target="_blank" href="https://developers.facebook.com/docs/reference/api/realtime/" class="btn btn-mini btn-info"><?php _e("Learn more",$this->ptd); ?></a>	
						</div>
						
						<label><?php _e("Configure the realtime API",$this->ptd); ?></label>
						<p><small><i class="icon-warning-sign"></i> <?php _e("The plugin only supports User fields and permissions subscriptions.",$this->ptd); ?></small></p>
						<ol>
							<li>
								<a href="https://developers.facebook.com/apps/<?php echo $this->options['app_id']; ?>/realtime" class="btn btn btn-mini" target="_blank">
									<i class="icon-cog"></i> <?php _e("Go on the Subscriptions page",$this->ptd); ?>
								</a>
							</li>
							<li><?php _e("Click on the Add button and Select the subscription to create",$this->ptd); ?></li>
							<li><?php _e("Add fields name you want to follow",$this->ptd); ?></li>
							<li><?php _e("Set the callback url, you should use the callback url provided on this page.",$this->ptd); ?></li>
							<li><?php _e("Set the verify token, you should use the token provided on this page.",$this->ptd); ?></li>
							<li><?php _e("Click on Test to check the configuration, if success it's done Validate the form. If no, check your configuration again.",$this->ptd); ?></li>
							<li><?php _e("Come back here, reload the page and check subscriptions list.",$this->ptd); ?></li>
						</ol>
						
						<label><?php _e("Your Callback url",$this->ptd); ?></label>
						<i class="icon-share"></i> <a href="<?php echo $this->_realtime_api_url; ?>"><?php echo $this->_realtime_api_url; ?></a></p>
						
						<label><?php _e("Verify Token",$this->ptd); ?></label>
						<i class="icon-share"></i> <code><?php echo md5($this->options['app_id']); ?></code></p>
						<h4><?php _e("Subscriptions detected:",$this->ptd); ?></h4>
						<?php
						$subscriptions = $this->get_realtime_subscriptions(); 
						if(!is_wp_error($subscriptions)){ ?>
							<?php if(count($subscriptions)){ ?>							
								<div class="row">
								<?php foreach($subscriptions as $sub){
									$class="success";
									if(!$sub['active'])
										$class="error";
									if($this->_realtime_api_url != $sub['callback_url'])
										$class="error";
									echo '
									<div class="span3 list_item">
										<h2>'.ucfirst($sub['object']).'
										'.($this->_realtime_api_url != $sub['callback_url'] ? '<span class="label label-'.$class.' pull-right">'.__("Callback url not match",$this->ptd).'</span>' :'').'
										<span class="pull-right label label-'.($sub['active'] ? 'success' : 'error').'">'.($sub['active'] ? __("Active",$this->ptd) : __("Disabled",$this->ptd)).'</span>
										</h2>
										<div class="thumbnail">
											<label>'.__("Fields",$this->ptd).' <i class="icon-tags"></i>  </label><span class="badge badge-info">'.rtrim(implode('</span> <span class="badge badge-info">', $sub['fields']),', ').'</span>
											<p><label>'.__("Callback Url",$this->ptd).' <i class="icon-share"></i> </label><small><a href="'.$sub['callback_url'].'">'.$sub['callback_url'].'"</a></small></p>
										</div>
									</div>';
								} ?>
								</div>
							<?php 
							}else{
								$this->display_messages(__("No Realtime subscription",$this->ptd), "info");
							}
						}else{
							$this->display_messages($subscriptions->get_error_message(), "warning");
						}
						?>
					</div>
				<?php } ?>
				
				<?php if(current_user_can('manage_facebook_awd_publish_to_pages')){ ?>
					<div id="managepages" class="tab-pane">
					<?php 
					if($this->options['connect_enable'] == 0){ 
						$this->display_messages(__('Facebook connect is required to manage pages',$this->ptd), 'error');
					}else{	
						if($this->current_facebook_user_can('publish_stream')){
							$this->display_messages(__('Publish Stream is enabled',$this->ptd), 'success');
						}else{
							echo '<a href="#" data-scope="email,publish_stream" class="get_permissions btn btn-info"><i class="icon-ok-sign icon-white"></i> '.__('Authorize App to publish on your pages',$this->ptd).'</a>';
						}
					
						if($this->current_facebook_user_can('manage_pages')){
							$message = __('Pages can be managed',$this->ptd).' <a href="#" id="toogle_list_pages" class="btn btn-info"><i class="icon-check icon-white"></i> '.__('Select pages to link with Wordpress',$this->ptd).'</a>';
							$this->display_messages($message, 'success');
						}else{
							echo '<a href="#" data-scope="email,manage_pages" class="get_permissions btn btn-info"><i class="icon-ok-sign icon-white"></i> '.__('Authorize App to access your pages',$this->ptd).'</a>';
						}
						
						if($this->current_facebook_user_can('manage_pages')){ ?>
							<div class="toogle_fb_pages hidden well">
								<h2><?php _e('Check the page you want to sync with your posts.',$this->ptd); ?></h2>
								<?php
								$fb_pages = $this->me['pages'];
								if(is_array($fb_pages) AND count($fb_pages)){
									echo '
									<table class="table table-striped">
										<thead>
											<tr>
												<th>'.__('Publish on ?', $this->ptd).'</th>
												<th>'.__('Picture', $this->ptd).'</th>
												<th>'.__('Name', $this->ptd).'</th>
												<th>'.__('ID', $this->ptd).'</th>
												<th>'.__('Category', $this->ptd).'</th>
											</tr>
										</thead>
										<tbody>';
										foreach($fb_pages as $fb_page){
											if($fb_page['category'] == 'Application')
												continue;
											$value = isset($this->options['fb_publish_to_pages'][$fb_page['id']]) ? $this->options['fb_publish_to_pages'][$fb_page['id']] : 0;
											$select = $form->addSelect('', 'fb_publish_to_pages['.$fb_page['id'].']', array(
												array('value'=>0, 'label'=>__('No',$this->ptd)),
												array('value'=>1, 'label'=>__('Yes',$this->ptd))									
											), $value, 'span2', array('class'=>'span2'));
											echo '
											<tr>
												<td>'.$select.'</td>
												<td><a href="#" class="thumbnail"><img class="fb_pages_picto" src="http://graph.facebook.com/'.$fb_page['id'].'/picture" width="30" height="30" /></a></td>
												<td>'.$fb_page['name'].'</td>
												<td>'.$fb_page['id'].'</td>
												<td>'.$fb_page['category'].'</td>
											</tr>';
										}
									echo '</tbody>
									</table>';
								}
								?>
							</div>
						<?php } ?>
						<?php if($this->current_facebook_user_can('publish_stream') && current_user_can('manage_facebook_awd_settings')){ ?>
							<div class="row">
								<?php 
								if($this->current_facebook_user_can('manage_pages')){ 
									echo $form->addSelect(__('Auto publish post on Facebook pages ?',$this->ptd).' '.$this->get_the_help('publish_to_pages'), 'publish_to_pages', array(
										array('value'=>0, 'label'=>__('No',$this->ptd)),
										array('value'=>1, 'label'=>__('Yes',$this->ptd))									
									), $this->options['publish_to_pages'], 'span3', array('class'=>'span3'));
								}
								
								echo $form->addSelect(__('Auto publish post on Facebook profile ?',$this->ptd).' '.$this->get_the_help('publish_to_profile'), 'publish_to_profile', array(
									array('value'=>0, 'label'=>__('No',$this->ptd)),
									array('value'=>1, 'label'=>__('Yes',$this->ptd))									
								), $this->options['publish_to_profile'], 'span3', array('class'=>'span3'));
								?>
							</div>
							<div class="row">
								<?php
								echo $form->addInputText(__('Default link action Text',$this->ptd).' '.$this->get_the_help('publish_read_more_text'), 'publish_read_more_text', $this->options['publish_read_more_text'], 'span3', array('class' => 'span3', 'maxlengh'=>'25')); 
								?>
							</div>
						<?php } ?>
					<?php } ?>
					</div>
				<?php } ?>
				
				
			</div>
		</div>
		<?php wp_nonce_field($this->plugin_slug.'_update_options',$this->plugin_option_pref.'_nonce_options_update_field'); ?>
		<div class="form-actions">
			<a href="#" id="submit_settings" class="btn btn-primary" data-loading-text="<i class='icon-time icon-white'></i> <?php _e('Saving settings...',$this->ptd); ?>"><i class="icon-cog icon-white"></i> <?php _e('Save all settings',$this->ptd); ?></a>
			<?php if(current_user_can('manage_facebook_awd_settings')){ ?>
				<a href="#" id="reset_settings" class="btn btn-danger" data-loading-text="<i class='icon-time icon-white'></i> <?php _e('Waiting for your anwser',$this->ptd); ?>"><i class="icon-trash icon-white"></i> <?php _e('Restore defaults settings',$this->ptd); ?></a>
			<?php } ?>
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZQ2VL33YXHJLC" class="awd_tooltip_donate btn pull-right" id="help_donate" target="_blank" class="btn pull-right"><i class="icon-heart"></i> <?php _e('Donate!',$this->ptd); ?></a>
		</div>
	<?php 
	echo $form->end(); 
	
	//reset form
	if(current_user_can('manage_facebook_awd_settings')){
		$form_reset = new AWD_facebook_form('reset_settings', 'POST', '', $this->plugin_option_pref);
		echo $form_reset->start();
		wp_nonce_field($this->plugin_slug.'_reset_options',$this->plugin_option_pref.'_nonce_reset_options');
		echo $form_reset->end();
	}
	?>
</div>
<?php if(current_user_can('manage_facebook_awd_settings')){ ?>
	<div class="alert alert-error alert_reset_settings alert-block dn">
		<a href="#" class="close reset_settings_dismiss">&times;</a>      
		<?php _e("Do you really want to reset all settings (AWD plugins and openGraph settings will be reset) ?",$this->ptd); ?>
		<a href="#" class="btn btn-danger reset_settings_confirm"><?php _e("Restore", $this->ptd); ?></a>  
	</div>
<?php } ?>