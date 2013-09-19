<?php

add_action( 'admin_init', 'drcms_theme_options_init' );
add_action( 'admin_menu', 'drcms_theme_options_add_page' );

/**
 * Add theme options page styles
 */
wp_register_style( 'drcms', get_template_directory_uri(). '/theme-options.css', '', '0.1' );
if ( isset( $_GET['page'] ) && $_GET['page'] == 'theme_options' ) {
	wp_enqueue_style( 'drcms' );
}

/**
 * Init plugin options to white list our options
 */
function drcms_theme_options_init(){
	register_setting( 'drcms_options', 'drcms_theme_options', 'drcms_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function drcms_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'drcms_theme_options_do_page' );
}

global $themename;
$shortname = "drcms";

$icons = drcms_list_images( TEMPLATEPATH. '/images/icons/');
array_unshift($icons, "");

$all_theme_options = array (
 
	array( 
		"name"	=> $themename ." Theme Options",
		"type"	=> "title"),
	
	array( 
		"name"	=> __( "General Options", 'atmosphere' ),
		"type"	=> "section"),

	array( "type"	=> "open"),

	array( 
		"name"	=> __( "Hide header titles", 'atmosphere' ),
		"desc"	=> __( "Hide the themes header title text", 'atmosphere' ),
		"id"	=> $shortname."_hide_titles",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide top menu", 'atmosphere' ),
		"desc"	=> __( "Hide the themes header top menu bar", 'atmosphere' ),
		"id"	=> $shortname."_hide_top_menu",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide main menu", 'atmosphere' ),
		"desc"	=> __( "Hide the themes main menu bar", 'atmosphere' ),
		"id"	=> $shortname."_hide_lower_menu",
		"type"	=> "checkbox",
		"default"	=> false),
	
	array( 
		"name"	=> __( "Hide Side Icons", 'atmosphere' ),
		"desc"	=> __( "Hide the right vertical social icons", 'atmosphere' ),
		"id"	=> $shortname."_hide_vertical_icons",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide Footer Icons", 'atmosphere' ),
		"desc"	=> __( "Hide the horizontal footer social icons", 'atmosphere' ),
		"id"	=> $shortname."_hide_footer_icons",
		"type"	=> "checkbox",
		"default"	=> false),

	array( "type" => "close"),

	array( 
		"name"	=> __( "Sidebar Widgets", 'atmosphere' ),
		"type"	=> "section"),
		
	array( "type"	=> "open"),
		
	array( 
		"name"	=> __( "Hide Page Menu", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar page menu section", 'atmosphere' ),
		"id"	=> $shortname."_hide_menu",
		"type"	=> "checkbox",
		"default"	=> false),
		
	array( 
		"name"	=> __( "Hide Categories", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar categories section", 'atmosphere' ),
		"id"	=> $shortname."_hide_categories",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide Archives", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar archive section", 'atmosphere' ),
		"id"	=> $shortname."_hide_archives",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide Calendar", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar calendar section", 'atmosphere' ),
		"id"	=> $shortname."_hide_calendar",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide Links", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar links section", 'atmosphere' ),
		"id"	=> $shortname."_hide_links",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide Meta", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar meta section", 'atmosphere' ),
		"id"	=> $shortname."_hide_meta",
		"type"	=> "checkbox",
		"default"	=> false),

	array( 
		"name"	=> __( "Hide Search", 'atmosphere' ),
		"desc"	=> __( "Hide the sidebar search section", 'atmosphere' ),
		"id"	=> $shortname."_hide_search",
		"type"	=> "checkbox",
		"default"	=> false),

	array( "type" => "close"),

	array( 
		"name"	=> __( "Footer", 'atmosphere' ),
		"type"	=> "section"),
		
	array( "type" => "open"),

	array( 
		"name"	=> __( "Footer Text", 'atmosphere' ),
		"desc"	=> __( "Company Footer Text", 'atmosphere' ),
		"id"	=> $shortname."_footer_text",
		"type"	=> "textarea",
		"default"	=> ""),

	array( "type" => "close"),

	array( 
		"name"	=> __( "Social Icons", 'atmosphere' ),
		"type"	=> "section"),
		
	array( "type" => "open"),
	
	array( 
		"name"	=> __( "RSS Icon", 'atmosphere' ),
		"desc"	=> __( "Option: select a different the rss icon", 'atmosphere' ),
		"id"	=> $shortname."_rss_icon",
		"type"	=> "select",
		"options" => $icons,
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Icon", 'atmosphere' ),
		"desc"	=> __( "Option: select a social media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_1",
		"type"	=> "select",
		"options" => $icons,
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Link", 'atmosphere' ),
		"desc"	=> __( "URL to your social media, starting http:// or mailto:", 'atmosphere' ),
		"id"	=> $shortname."_icon_1_url",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Alt Text", 'atmosphere' ),
		"desc"	=> __( "Enter alternate text for social the media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_1_alt",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Icon", 'atmosphere' ),
		"desc"	=> __( "Option: select a social media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_2",
		"type"	=> "select",
		"options" => $icons,
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Link", 'atmosphere' ),
		"desc"	=> __( "URL to your social media, starting http:// or mailto:", 'atmosphere' ),
		"id"	=> $shortname."_icon_2_url",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Alt Text", 'atmosphere' ),
		"desc"	=> __( "Enter alternate text for social the media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_2_alt",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Icon", 'atmosphere' ),
		"desc"	=> __( "Option: select a social media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_3",
		"type"	=> "select",
		"options" => $icons,
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Link", 'atmosphere' ),
		"desc"	=> __( "URL to your social media, starting http:// or mailto:", 'atmosphere' ),
		"id"	=> $shortname."_icon_3_url",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Alt Text", 'atmosphere' ),
		"desc"	=> __( "Enter alternate text for the media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_3_alt",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Icon", 'atmosphere' ),
		"desc"	=> __( "Option: select a social media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_4",
		"type"	=> "select",
		"options" => $icons,
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Link", 'atmosphere' ),
		"desc"	=> __( "URL to your social media, starting http:// or mailto:", 'atmosphere' ),
		"id"	=> $shortname."_icon_4_url",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Alt Text", 'atmosphere' ),
		"desc"	=> __( "Enter alternate text for the media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_4_alt",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Icon", 'atmosphere' ),
		"desc"	=> __( "Option: select a social media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_5",
		"type"	=> "select",
		"options" => $icons,
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Link", 'atmosphere' ),
		"desc"	=> __( "URL to your social media, starting http:// or mailto:", 'atmosphere' ),
		"id"	=> $shortname."_icon_5_url",
		"type"	=> "text",
		"default"	=> ""),

	array( 
		"name"	=> __( "Social Media Alt Text", 'atmosphere' ),
		"desc"	=> __( "Enter alternate text for the media icon", 'atmosphere' ),
		"id"	=> $shortname."_icon_5_alt",
		"type"	=> "text",
		"default"	=> ""),

	array( "type" => "close"),

);

function drcms_theme_options_do_page() {
 
	global $themename, $all_theme_options;
	$i=0;
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
	
	<div class="wrap rm_wrap">
	<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'atmosphere') . "</h2>"; ?>
	
	<div class="rm_opts">
	
	<?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; ?>
	
	<form method="post" action="options.php">
	<?php settings_fields( 'drcms_options' ); ?>
	<?php $stored_options = get_option('drcms_theme_options'); ?>

	<?php foreach ($all_theme_options as $value) {
		switch ( $value['type'] ) {
		 
		case "open":
		?>
		 
		<?php break;
		 
		case "close":
		?>
		 
		</div>
		</div>
		<br />
		 
		<?php break;
		 
		case "title":
		 
		break;
		 
		case 'text':
			$id = $value['id'];
			if( isset( $stored_options[$id] ) ) {
				if( $stored_options[$id] ) $current_value = $stored_options[$id];
					else $current_value = $value['default'];
			}
			else $current_value = $value['default'];
			?>
			<div class="rm_input rm_text">
				<label for="drcms_theme_options[<?php echo $id;?>]"><?php echo $value['name'];?></label> 
				<input type="text" name="drcms_theme_options[<?php echo $id;?>]" value="<?php echo $current_value;?>" />
				<small><?php echo $value['desc']; ?></small>
				<div class="clearfix"></div>
			</div>	
			<?php
		break;

		case 'textarea':		
			$id = $value['id'];	
			?>
			<div class="rm_input rm_textarea">
				<label for="drcms_theme_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label>
				<textarea name="drcms_theme_options[<?php echo $value['id']; ?>]" cols="1" rows="1"><?php if ( isset( $stored_options[$id] )) { echo $stored_options[$id] ; } else { echo $value['default']; } ?></textarea>
				<small><?php echo $value['desc']; ?></small>
				<div class="clearfix"></div>
			</div>
			<?php
		break;
		 
		case 'select':
			$id = $value['id'];
			if (isset( $stored_options[$id] ) ) : 
	           $selected = $stored_options[$id]; 		
			else : 
			   $selected = '';
			endif; 
			?>
			<div class="rm_input rm_select">
			<label for="drcms_theme_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label>	
			<select name="drcms_theme_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>">
			<?php foreach ($value['options'] as $option) { ?>
					<option <?php if ($selected && $selected == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option>
				<?php } ?>
				</select>
				<small><?php echo $value['desc']; ?></small>
				<div class="clearfix"></div>
			</div>
			<?php
		break;

		case "checkbox":
			$id = $value['id'];	
			?>
			<fieldset>
			<div class="rm_input rm_checkbox">
				<label for="drcms_theme_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label>
				<?php if(isset( $stored_options[$id]) && $stored_options[$id]){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
				<input type="checkbox" name="drcms_theme_options[<?php echo $value['id']; ?>]"value="true" <?php echo $checked; ?> />
				<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
			</div>
			</fieldset>
			<?php 
			break;
 
		case "section":

		$i++;

		?>

		<div class="rm_section">
		<div class="rm_title"><h3><?php echo $value['name']; ?></h3>
		<span class="submit">
				<input name="save<?php echo $i; ?>" type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
		</span>
		<div class="clearfix"></div></div>
		<div class="rm_options">

		<?php break;
		 
		}
	}
	?>

	</form>
	</div> 
	</div>
	<?php 
} 

// Validate the input 
function drcms_theme_options_validate( $input ) {
	global $all_theme_options;
	foreach($all_theme_options as $theme_option) {
		$option_type = $theme_option['type'];
		$id  = $theme_option['id'];
		if( !isset( $input[$id] ) ) $input[$id] = $theme_option['default'];
		switch ($option_type) {
			
		case 'text':
			   $input[$id] = trim( wp_filter_nohtml_kses( $input[$id] ) );
			break;
			
			case 'textarea':
				$input[$id] = trim( wp_filter_post_kses( $input[$id] ) );
			break;
			
			case 'select':
				$options = $theme_option['options'];
				if ( array_search( $input[$is], $options ) != 0 )
					$input[$id] = null;
			break;			
		}
		if( !isset( $input[$id] ) ) $input[$id] = $theme_option['default'];
	}
	return $input;	
}