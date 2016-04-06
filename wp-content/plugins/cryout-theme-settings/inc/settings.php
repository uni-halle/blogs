	<div class="wrap">
		<h2><?php _e('Cryout Serious Theme Settings Status', 'cryout-theme-settings') ?></h2>
		
			<?php if ($this->status == 1): ?>
			<div class="updated"><p>Current active (or parent) theme is: <strong><?php echo ucwords($this->current_theme['slug']); ?></strong>. 
				The plugin is <strong style="color: #008000;">active</strong>.<br>
				<br>
				Navigate <a href="themes.php?page=<?php echo $this->current_theme['slug'] ?>-page"><strong>here</strong></a> to configure <?php echo ucwords($this->current_theme['slug']) ?>.
				</p></div>
			<?php elseif ($this->status == 4): ?>
			<div class="updated"><p>Current active (or parent) theme is: <strong><?php echo ucwords($this->current_theme['slug']); ?></strong> and you are running WordPress 4.4 or newer. 
				The plugin is <strong style="color: #008000;">active</strong> in compatibility mode.<br>
				<br>
				Navigate <a href="themes.php?page=<?php echo $this->current_theme['slug'] ?>-page"><strong>here</strong></a> to configure <?php echo ucwords($this->current_theme['slug']) ?>.
				</p></div>
			<?php else: ?>
			<div class="error"><p>
			<?php
				switch ($this->status):
					case 3: 
					// unsupported theme ?>
						Current active (or parent) theme is: <strong><?php echo ucwords($this->current_theme['slug']); ?></strong>.<br>
						This plugin is designed to work only with the supported themes. <br>
						The plugin is <strong style="color: #800000;">INACTIVE</strong>. <?php
					break;
					case 2:
					// unsupported version ?>
						Current active (or parent) theme is: <strong><?php echo ucwords($this->current_theme['slug']) ?></strong>, however the plugin is designed to work with version <b><?php echo $this->supported_themes[$this->current_theme['slug']] ?></b> or newer of <em><?php echo ucwords($this->current_theme['slug']) ?></em>.<br>
						You are running <em><?php echo ucwords($this->current_theme['slug']) ?> version <?php echo $this->current_theme['version'] ?></em> which does not need this plugin.</br>
						The plugin is <strong style="color: #800000;">INACTIVE</strong>. <?php					
					break;
					case 0:
					default: 
					// inactive/undefined ?>
						Current active (or parent) theme is: <strong><?php echo ucwords($this->current_theme['slug']); ?></strong>.<br>
						This plugin is designed to work only with the supported themes. <br>
						The plugin is <strong style="color: #800000;">INACTIVE</strong>. <?php
					break; ?>
			<?php endswitch; ?>
			</p></div>
			<?php endif; ?>
			<?php if ($this->renamed_theme) :?>
			<div class="error">
				<p>The plugin has detected that you have renamed the theme folder - this will limit your ability to update the theme.<br>
				If you need to customize the theme code, we strongly recommend creating a <a href="http://www.cryoutcreations.eu/wordpress-themes/wordpress-tutorials/wordpress-child-themes" target="_blank">child theme</a>.</p>
			</div>
			<?php endif; ?>
			
		<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			
			<div id="post-body-content">
						
					<div class="postbox">

						<div class="handlediv"><br></div>
						<h3 class="hndle"><span>About</span></h3>

						<div class="inside">
							<p>
							This plugin is designed to inter-operate with our supported themes:
							<ul><li><a href="http://wordpress.org/themes/mantra" target="_blank">Mantra</a> version 2.5 and newer</li>
							<li><a href="http://wordpress.org/themes/nirvana" target="_blank">Nirvana</a> version 1.2 and newer</li>
							<li><a href="http://wordpress.org/themes/parabola" target="_blank">Parabola</a> version 1.6 and newer</li>
							<li><a href="http://wordpress.org/themes/tempera" target="_blank">Tempera</a> version 1.4 and newer</li>
							</ul>
							To restore their theme settings pages which we had to remove due to the Customize-based settings enforcement.<br>
							</p>
							<hr>
							<p>
							Additionally, the plugin will restore the settings page to working condition on WordPress 4.4-RC1 and newer with:
							<ul><li><u>Tempera</u> versions 0.9 - 1.3.3</li>
							<li><u>Parabola</u> versions 0.9 - 1.5.1</li>
							<li><a href="http://wordpress.org/themes/mantra" target="_blank">Mantra</a> versions 2.0 - 2.4.1.1</li>
							</ul>
							</p>
							<p>If you are using a different theme or a theme version not listed here this plugin will not activate.</p>
						</div>
					</div>
					
			</div> <!-- post-body-content-->

			<div class="postbox-container" id="postbox-container-1">

						<div class="meta-box-sortables">

							<div class="postbox">
								<div class="handlediv"><br></div>
								<h3 style="text-align: center;" class="hndle">
									<span><strong>Cryout Serious Theme Settings</strong></span>
								</h3>

								<div class="inside">
									<div style="text-align: center; margin: auto">
										<strong>version: <?php echo $this->version ?></strong><br>
										by Cryout Creations<br>
										<a target="_blank" href="http://www.cryoutcreations.eu/cryout-theme-settings/">www.cryoutcreations.eu</a>
									</div>
								</div>
							</div>

							<div class="postbox">
								<div class="handlediv"><br></div>
								<h3 style="text-align: center;" class="hndle">
									<span>Support</span>
								</h3><div class="inside">
									<div style="text-align: center; margin: auto">
										For support questions,<br>
										please use <a target="_blank" href="http://www.cryoutcreations.eu/forum">our forum</a>. 
									</div>
								</div>
							</div>
						</div>
			</div> <!-- postbox-container -->

		</div> <!-- post-body -->
		<br class="clear">
		</div> <!-- poststuff -->

	</div><!--end wrap-->
