<?php
/**
 * Add the custom controls to the Customizer
 */
function graphene_add_customizer_controls( $wp_customize ) {
	/**
	 * Multiple select
	 */
	class Graphene_Multiple_Select_Control extends WP_Customize_Control {
		public $type = 'select';
		public $multiple = false;
		
		public function render_content() {
			if ( ! array_key_exists( 'class', $this->input_attrs ) ) $this->input_attrs['class'] = '';
			if ( $this->multiple ) {
				$this->input_attrs['class'] .= ' chzn-select select-multiple';
				$this->input_attrs['class'] = trim( $this->input_attrs['class'] );
				$this->input_attrs['multiple'] = 'multiple';
			}
			?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>

                <select <?php $this->link(); ?> <?php $this->input_attrs(); ?>>
                    <?php
                    foreach ( $this->choices as $value => $label ){
						$selected = ( in_array( $value, (array) $this->value() ) ) ? 'selected="selected"' : '';
                        echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					}
                    ?>
                </select>
            </label>
			<?php
		}
	}


	/**
	 * Radio with HTML allowed in label
	 */
	class Graphene_Radio_HTML_Control extends WP_Customize_Control {
		public $type = 'radio';
		
		public function render_content() {
			if ( empty( $this->choices ) ) return;
            $name = '_customize-radio-' . $this->id;
            ?>
            	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php foreach ( $this->choices as $value => $label ) : ?>
                <label>
                    <input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                    <?php echo $label; ?><br/>
                </label>
            <?php endforeach;
		}
	}
	
	
	/**
	 * Custom text field control
	 */
	class Graphene_Enhanced_Text_Control extends WP_Customize_Control {
		public $unit = '';
	 
		public function render_content() {
			?>
			<label class="graphene-text">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
				<input type="<?php echo esc_attr( $this->type ); ?>" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
                <?php if ( ! empty( $this->unit ) ) echo $this->unit; ?>
			</label>
			<?php
		}
	}
	
	
	/**
	 * Code textarea control
	 */
	class Graphene_Code_Control extends WP_Customize_Control {
		public $mode = 'htmlmixed';
	 
		public function render_content() {
			if ( ! array_key_exists( 'class', $this->input_attrs ) ) $this->input_attrs['class'] = '';
			$this->input_attrs['class'] .= ' widefat code';
			$this->input_attrs['class'] .= trim( $this->input_attrs['class'] );
			
			$matches = array();
			preg_match( '/graphene_settings\[(.*)\]/i', $this->id, $matches );
			$setting_name = ( isset( $matches[1] ) ) ? $matches[1] : $this->id;
			?>
			<label class="graphene-code">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
                
                <textarea id="<?php echo $setting_name; ?>" <?php $this->link(); ?> <?php $this->input_attrs(); ?>><?php echo htmlentities( stripslashes( $this->value() ) ); ?></textarea>
			</label>
            
            <script type="text/javascript">
            	if ( typeof CodeMirror.fromTextArea === "undefined" ) CodeMirror = wp.CodeMirror;
				var <?php echo $setting_name; ?>CM = CodeMirror.fromTextArea(document.getElementById( "<?php echo $setting_name; ?>"), {
					mode			: '<?php echo $this->mode; ?>',
					lineNumbers		: true,
					lineWrapping	: true,
					indentUnit		: 4,
					styleActiveLine	: true,
					autoRefresh		: true
				});
				<?php echo $setting_name; ?>CM.on( 'blur', function(){
					wp.customize( '<?php echo $this->id; ?>', function ( obj ) {
						obj.set( <?php echo $setting_name; ?>CM.getValue() );
					} );
				});
			</script>
			<?php
		}
	}


	/**
	 * Action Hooks Widget Areas
	 */
	class Graphene_Widget_Hooks_Control extends WP_Customize_Control {
		public function render_content() {
            ?>
            <p><?php _e( "This option enables you to place virtually any content anywhere in the theme, by attaching widget areas to the theme's action hooks.", 'graphene' ); ?></p>
            <p><?php _e( "All action hooks available in the Graphene Theme are listed below. Click on the filename to display all the action hooks available in that file. Then, tick the checkbox next to an action hook to make a widget area available for that action hook.", 'graphene' ); ?></p>
            <p><?php printf( __( 'To see a visual map showing where each action hook in Graphene is located, visit this page: %s', 'graphene' ), '<a href="http://demo.graphene-theme.com/graphene-hooks-map/" target="_blank">' . __( 'Graphene Action Hooks Map', 'graphene' ) . '</a>' ); ?></p>

            <ul class="graphene-action-hooks">    
                <?php                
                global $graphene_settings;
                $actionhooks = graphene_get_action_hooks();
                foreach ( $actionhooks as $actionhook ) : 
                    $file = $actionhook['file']; 
                ?>
                    <li>
                        <p class="hooks-file"><a href="#" class="toggle-widget-hooks" title="<?php _e( 'Click to show/hide the action hooks for this file', 'graphene' ); ?>"><?php echo $file; ?></a></p>
                        <ul class="hooks-list">
                            <li class="widget-hooks<?php if(count(array_intersect( $actionhook['hooks'], $graphene_settings['widget_hooks'] ) ) == 0) echo ' hide'; ?>">
								<?php foreach ( $actionhook['hooks'] as $hook) : ?>
                                    <input type="checkbox" value="<?php echo $hook; ?>" id="hook_<?php echo $hook; ?>" <?php if ( in_array( $hook, $graphene_settings['widget_hooks'] ) ) echo 'checked="checked"'; ?> /> <label for="hook_<?php echo $hook; ?>"><?php echo $hook; ?></label><br />
                                <?php endforeach; ?>
                            </li>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $graphene_settings['widget_hooks'] ) ); ?>" />
            <?php
		}
	}


	/**
	 * Custom HTML
	 */
	class Graphene_HTML_Control extends WP_Customize_Control {
		public $content = '';
		public function render_content() {
			if ( isset( $this->label ) ) echo '<span class="customize-control-title">' . $this->label . '</span>';
			if ( isset( $this->content ) ) echo $this->content;
			if ( isset( $this->description ) ) echo '<span class="description customize-control-description">' . $this->description . '</span>';
		}
	}


	/**
	 * Columns Width
	 */
	class Graphene_Columns_Width_Control extends WP_Customize_Control {
		public function render_content() {
			global $graphene_settings;

			if ( isset( $this->description ) ) echo '<span class="description customize-control-description">' . $this->description . '</span>';
            ?>
            	<?php if ( $this->id == 'graphene_settings[container_width]' ) : ?>
	            	<span class="customize-control-title"><?php _e( 'Container width', 'graphene' ); ?></span>
	            	<input type="text" class="code" size="8" <?php $this->link(); ?> id="container_width" value="<?php echo $graphene_settings['container_width']; ?>" /> px
	            	<div class="input-slider">
	                	<div id="container_width-slider"></div>
	                    <span class="alignleft slider-legend">800 px</span>
	                    <span class="alignright slider-legend">1400 px</span>
	                </div>
	            <?php else : ?>

	                <span class="customize-control-title"><?php _e( 'Columns width (two-column mode)', 'graphene' ); ?></span>
	            	<div class="width-wrap width-wrap-left">
	                	<?php _e( 'Content', 'graphene' ); ?><br />
	                	<input type="text" class="code" size="3" id="column_width_2col_content" value="<?php echo $graphene_settings['column_width']['two_col']['content']; ?>" /> cols
	                </div>
	            	<div class="width-wrap width-wrap-right">
	                	<?php _e( 'Sidebar', 'graphene' ); ?><br />
	            		<input type="text" class="code" size="3" id="column_width_2col_sidebar" value="<?php echo $graphene_settings['column_width']['two_col']['sidebar']; ?>" /> cols
	                </div>
	                <div class="input-slider">
	                    <div id="column_width_2col-slider"></div>
	                    <div class="alignleft slider-legend">0 col</div>
	                    <div class="column_width-max-legend alignright slider-legend">12 cols</div>
	                </div>


	                <span class="customize-control-title"><?php _e( 'Columns width (three-column mode)', 'graphene' ); ?></span>
	            	<div class="width-wrap width-wrap-left">
	                	<?php _e( 'Left sidebar', 'graphene' ); ?><br />
	            		<input type="text" class="code" size="3" id="column_width_sidebar_left" value="<?php echo $graphene_settings['column_width']['three_col']['sidebar_left']; ?>" /> cols
	                </div>
	                <div class="width-wrap width-wrap-center">
	                	<?php _e( 'Content', 'graphene' ); ?><br />
	                	<input type="text" class="code" size="3" id="column_width_content" value="<?php echo $graphene_settings['column_width']['three_col']['content']; ?>" /> cols
	                </div>
	            	<div class="width-wrap width-wrap-right">
	                	<?php _e( 'Right sidebar', 'graphene' ); ?><br />
	                	<input type="text" class="code" size="3" name="graphene_settings[column_width][three_col][sidebar_right]" id="column_width_sidebar_right" value="<?php echo $graphene_settings['column_width']['three_col']['sidebar_right']; ?>" /> cols
	                </div>
	                <div class="input-slider">
	                    <div id="column_width-slider"></div>
	                    <div class="alignleft slider-legend">0 col</div>
	                    <div class="column_width-max-legend alignright slider-legend">12 cols</div>
	                </div>

	                <input type="hidden" <?php $this->link(); ?> value="<?php echo json_encode( $graphene_settings['column_width'] ); ?>" />
	            <?php endif; ?>
            <?php
		}
	}


	/**
	 * Social Profiles
	 */
	class Graphene_Social_Profiles_Control extends WP_Customize_Control {
		public function render_content() {
			global $graphene_settings;

			if ( isset( $this->label ) ) echo '<span class="customize-control-title">' . $this->label . '</span>';

            /*
			 * Available profiles according to the icons available in Font Awesome
			 */
            $available_profiles = array (  '-', 'Custom', '-', 'Twitter', 'Facebook', 'LinkedIn', 'YouTube', 'RSS', 'Instagram', 'Foursquare', 
                                'Delicious', 'DeviantArt', 'Digg', 'Etsy', 'Flickr', 'Github', 'Google', 'Google Plus', 'Houzz', 'LastFM', 'Pinterest', 
								'Reddit', 'Skype', 'Slack', 'Snapchat', 'Spotify', 'Soundcloud', 'Steam', 'StumbleUpon', 'Telegram', 'TripAdvisor', 'Tumblr', 
								'Twitch', 'Vimeo', 'Vine', 'vk', 'WeChat', 'WhatsApp', 'WordPress', 'Yahoo!', 'Yelp' );
            $social_profiles = ( ! empty( $graphene_settings['social_profiles'] ) ) ? $graphene_settings['social_profiles'] : array();
        	?>
        	<ul class="graphene-social-profiles">
	        	<?php
					if ( ! in_array( false, $social_profiles) ) : foreach ($social_profiles as $profile_key => $profile_data) :
						$profile_data['url'] = esc_url( $profile_data['url'] );
						if ( $profile_data['type'] == 'custom' ) {
							$profile_data['icon_url'] = esc_url( $profile_data['icon_url'] );
							$profile_data['icon_name'] = trim( strtolower( $profile_data['icon_name'] ) );
							$profile_data['name'] = __( 'Custom', 'graphene' );
						} else {
							$profile_data['icon_url'] = '';
							$profile_data['icon_name'] = '';
						}
				?>
                    <li class="graphene-social-profile">
                        <span class="customize-control-title">
                        	<?php if ( $profile_data['type'] == 'custom' ) : ?>
                        		<?php if ( $profile_data['icon_name'] ) : ?>
                        			<i class="fa fa-<?php echo $profile_data['icon_name']; ?>"></i>
                        		<?php else : ?>
	                            	<img class="mysocial-icon" src="<?php echo $profile_data['icon_url']; ?>" alt="" />
	                            <?php endif; ?>
	                        <?php else : ?>
	                            <i class="fa fa-<?php echo $profile_data['type']; ?>"></i>
	                        <?php endif; ?>

                        	<?php echo $profile_data['name']; ?>
                    	</span>

                    	<input type="hidden" name="social-profile-data" data-type="<?php echo $profile_data['type']; ?>" data-name="<?php echo $profile_data['name']; ?>" data-title="<?php echo $profile_data['title']; ?>" data-url="<?php echo $profile_data['url']; ?>" data-icon-url="<?php echo $profile_data['icon_url']; ?>" data-icon-name="<?php echo $profile_data['icon_name']; ?>" />

                        <div class="inline-field">
                        	<label><?php _e( 'Description', 'graphene' ); ?></label>
                        	<input type="text" data-key="title" name="social-profile-title" value="<?php echo esc_attr( $profile_data['title'] ); ?>" />
                        </div>

                        <div class="inline-field">
                        	<label><?php _e( 'URL', 'graphene' ); ?></label>
                        	<input type="text" data-key="url" value="<?php echo esc_attr( $profile_data['url'] ); ?>" />

                        	<?php if ( $profile_data['type'] == 'rss' ) : ?>
                                <span class="description"><?php _e('Leave the URL empty to use the default RSS URL.', 'graphene'); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ( $profile_data['type'] == 'custom' ) : ?>
                        	<div class="inline-field">
	                        	<label><?php _e( 'Icon URL', 'graphene' ); ?></label>
	                        	<input type="text" data-key="icon-url" value="<?php echo esc_attr( $profile_data['icon_url'] ); ?>" />
	                        </div>
	                        <div class="inline-field">
	                        	<label><?php _e( 'Icon name', 'graphene' ); ?></label>
	                        	<input type="text" data-key="icon-name" value="<?php echo esc_attr( $profile_data['icon_name'] ); ?>" />
	                        </div>
                        <?php endif; ?>

                        <span class="delete"><a href="#" class="socialprofile-del"><i class="fa fa-times" title="<?php _e( 'Delete', 'graphene' ); ?>"></i></a></span>
                        <span class="move"><i class="fa fa-arrows" title="<?php _e( 'Drag and drop to reorder', 'graphene' ); ?>"></i></span>
                    </li>
				<?php endforeach; endif; ?>
			</ul>

			<div class="add-social-profile">
				<span class="customize-control-title"><?php _e( 'Add social profile', 'graphene' ); ?></span>

				<div class="inline-field">
					<label>Type</label>
					<select id="new-socialprofile-type" placeholder="<?php _e( 'Choose type', 'graphene' ); ?>">
		                <option value=""></option>
		                <?php foreach ( $available_profiles as $profile_type) : ?>                                
		                    <?php if ($profile_type == '-') : ?>
		                    	<option disabled="disabled" value="-">-----------------------</option>
		                    <?php elseif ($profile_type == 'Custom') : ?>
		                    	<option value="custom"><?php _e( 'Custom', 'graphene' ); ?></option>
		                    <?php else : ?>
		                    	<option value="<?php echo sanitize_title( $profile_type ); ?>"><?php echo $profile_type; ?></option>
		                    <?php endif; ?>
		                <?php endforeach; ?>
		            </select>
		        </div>

		        <div class="inline-field">
                	<label><?php _e( 'Description', 'graphene' ); ?></label>
                	<input type="text" id="new-socialprofile-title" value="" />
                </div>

                <div class="inline-field">
                	<label><?php _e( 'URL', 'graphene' ); ?></label>
                	<input type="text" id="new-socialprofile-url" value="" />
                </div>

                <div class="inline-field icon-url hide">
                	<label><?php _e( 'Icon URL', 'graphene' ); ?></label>
                	<input type="text" id="new-socialprofile-icon-url" value="" />
                </div>

                <div class="inline-field icon-name hide">
                	<label><a href="https://fontawesome.io/icons/"><?php _e( 'Icon name', 'graphene' ); ?></a></label>
                	<input type="text" id="new-socialprofile-icon-name" value="" />
                </div>

                <a class="button" href="#"><?php _e( 'Add social profile', 'graphene' ); ?></a>
			</div>

			<input type="hidden" <?php $this->link(); ?> id="graphene_settings_social_profiles" value="" />
			<?php
		}
	}


	/**
	 * Reset settings
	 */
	class Graphene_Reset_Settings extends WP_Customize_Control {
		public function render_content() {
			?>
			<p><?php _e( 'Reset all of the theme\'s settings to their default values. Custom Header, Custom Menus, and other WordPress settings won\'t be affected.', 'graphene' ); ?></p>
            <p><?php _e( '<strong>WARNING:</strong> This action is not reversible.', 'graphene' ); ?></p>
            <p>
            	<a class="button-primary graphene-reset-settings confirm-click" href="#" data-nonce="<?php wp_create_nonce( 'graphene-reset-settings' ); ?>" data-message="<?php esc_attr_e( 'Confirm to reset settings? This action is not reversible.', 'graphene' ); ?>"><?php _e( 'Reset settings', 'graphene' ); ?></a>
            	<i class="status-icon hide fa fa-spin fa-refresh"></i>
            </p>
            <div class="graphene-status-message"></div>
            <?php
		}
	}


	/**
	 * Export settings
	 */
	class Graphene_Export_Settings extends WP_Customize_Control {
		public $type = '';
		public function render_content() {
			?>
			<span class="customize-control-title"><?php echo $this->label; ?></span>
			<?php if ( isset( $this->description ) ) echo '<span class="description customize-control-description">' . $this->description . '</span>'; ?>

            <p>
            	<a class="button" href="<?php echo add_query_arg( array( 'graphene-export' => 1, 'nonce' => wp_create_nonce( 'graphene-export' ), 'type' => $this->type ), admin_url() ); ?>"><?php _e( 'Export settings', 'graphene' ); ?></a>
            </p>
            <?php
		}
	}


	/**
	 * Import settings
	 */
	class Graphene_Import_Settings extends WP_Customize_Control {
		public function render_content() {
			?>
			<span class="customize-control-title"><?php echo $this->label; ?></span>
			<?php if ( isset( $this->description ) ) echo '<span class="description customize-control-description">' . $this->description . '</span>'; ?>

            <form class="graphene-import" action="" method="POST" enctype="multipart/form-data">
				<input type="file" name="graphene-import-file" class="graphene-import-file" accept=".txt" />
				<input type="hidden" name="graphene-import" value="1" />
				<?php wp_nonce_field( 'graphene-import', 'graphene-nonce' ); ?>

				<p>
					<input type="submit" class="button-primary" name="graphene-import-button" value="<?php esc_attr_e( 'Import settings', 'graphene' ); ?>" />
					<i class="status-icon hide fa fa-spin fa-refresh"></i>
				</p>
			</form>
			
			<div class="graphene-status-message"></div>
            <?php
		}
	}


	/**
	 * Alpha colour picker
	 */
	require_once( GRAPHENE_ROOTDIR . '/admin/customizer/alpha-color-picker/alpha-color-picker.php' );
}