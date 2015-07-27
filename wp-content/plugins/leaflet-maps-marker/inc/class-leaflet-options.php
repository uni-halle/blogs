<?php
/**
 * Leaflet Maps Marker Plugin - settings class
 * based on class by Alison Barrett, http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'class-leaflet-options.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
class Class_leaflet_options {
	private $panes;
	private $sections;
	private $checkboxes;
	private $_settings = array();

	public function __get( $name ) {
		if ( 'settings' === $name ) {
			$this->get_settings();
			return $this->_settings;
		}
		return null;
	}

	public function __isset( $name ) {
		if ( 'settings' === $name ) {
			$this->get_settings();
			return ! empty( $this->_settings );
		}
		return false;
	}

	 /**
	 *
	 * Construct
	 *
	 */
	public function __construct() {
		//info:  This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();

		$this->panes['mapdefaults'] 	= esc_attr__('Map Defaults','lmm');
		$this->panes['basemaps'] 		= esc_attr__('Basemaps','lmm');
		$this->panes['overlays']		= esc_attr__('Overlays','lmm');
		$this->panes['wms']				= esc_attr__('WMS','lmm');
		$this->panes['google']			= esc_attr__('Google Maps','lmm');
		$this->panes['bing']			= esc_attr__('Bing Maps','lmm');
		$this->panes['directions']		= esc_attr__('Directions','lmm');
		$this->panes['ar']				= esc_attr__('Augmented-Reality','lmm');
		$this->panes['misc']			= esc_attr__('Misc','lmm');
		$this->panes['reset']			= esc_attr__('Reset','lmm');

		$this->sections['mapdefaults-section1']		= esc_attr__('Default basemap for new markers/layers','lmm');
		$this->sections['mapdefaults-section2']		= esc_attr__('Names for default basemaps','lmm');
		$this->sections['mapdefaults-section3']		= esc_attr__('Available basemaps in control box','lmm');
		$this->sections['mapdefaults-section4']		= esc_attr__('Default values for new marker maps','lmm');
		$this->sections['mapdefaults-section5']		= esc_attr__('Default values for marker icons','lmm');
		$this->sections['mapdefaults-section6']		= esc_attr__('Default values for marker popups','lmm');
		$this->sections['mapdefaults-section7']		= esc_attr__('Default values for markers added directly','lmm');
		$this->sections['mapdefaults-section8']		= esc_attr__('Default values for new layer maps','lmm');
		$this->sections['mapdefaults-section9']		= esc_attr__('List of markers settings','lmm');
		$this->sections['mapdefaults-section10']	= esc_attr__('Interaction options','lmm');
		$this->sections['mapdefaults-section11']	= esc_attr__('Keyboard navigation options','lmm');
		$this->sections['mapdefaults-section12']	= esc_attr__('Panning inertia options','lmm');
		$this->sections['mapdefaults-section13']	= esc_attr__('Control options','lmm');
		$this->sections['mapdefaults-section14']	= esc_attr__('Scale control','lmm');
		$this->sections['mapdefaults-section15']	= esc_attr__('Retina display detection','lmm');
		$this->sections['mapdefaults-section16']	= esc_attr__('Mobile web app settings','lmm');
		$this->sections['mapdefaults-section17']	= esc_attr__('Minimap settings','lmm');
		$this->sections['mapdefaults-section18']	= esc_attr__('Marker clustering settings','lmm');
		$this->sections['mapdefaults-section19']	= esc_attr__('GPX tracks settings','lmm');
		$this->sections['mapdefaults-section20']	= esc_attr__('Geolocate settings','lmm');

		$this->sections['basemaps-section1']		= esc_attr__('MapBox 1 settings','lmm');
		$this->sections['basemaps-section2']		= esc_attr__('MapBox 2 settings','lmm');
		$this->sections['basemaps-section3']		= esc_attr__('MapBox 3 settings','lmm');
		$this->sections['basemaps-section4']		= esc_attr__('Custom basemap 1 settings','lmm');
		$this->sections['basemaps-section5']		= esc_attr__('Custom basemap 2 settings','lmm');
		$this->sections['basemaps-section6']		= esc_attr__('Custom basemap 3 settings','lmm');

		$this->sections['overlays-section1']		= esc_attr__('Available overlays for new markers/layers','lmm');
		$this->sections['overlays-section2']		= esc_attr__('Custom overlay settings','lmm');
		$this->sections['overlays-section3']		= esc_attr__('Custom overlay 2 settings','lmm');
		$this->sections['overlays-section4']		= esc_attr__('Custom overlay 3 settings','lmm');
		$this->sections['overlays-section5']		= esc_attr__('Custom overlay 4 settings','lmm');

		$this->sections['wms-sections1']			= esc_attr__('Available WMS layers for new markers/layers','lmm');
		$this->sections['wms-sections2']			= esc_attr__('WMS layer 1 settings','lmm');
		$this->sections['wms-sections3']			= esc_attr__('WMS layer 2 settings','lmm');
		$this->sections['wms-sections4']			= esc_attr__('WMS layer 3 settings','lmm');
		$this->sections['wms-sections5']			= esc_attr__('WMS layer 4 settings','lmm');
		$this->sections['wms-sections6']			= esc_attr__('WMS layer 5 settings','lmm');
		$this->sections['wms-sections7']			= esc_attr__('WMS layer 6 settings','lmm');
		$this->sections['wms-sections8']			= esc_attr__('WMS layer 7 settings','lmm');
		$this->sections['wms-sections9']			= esc_attr__('WMS layer 8 settings','lmm');
		$this->sections['wms-sections10']			= esc_attr__('WMS layer 9 settings','lmm');
		$this->sections['wms-sections11']			= esc_attr__('WMS layer 10 settings','lmm');

		$this->sections['google-section1']			= esc_attr__('Google Maps API','lmm');
		$this->sections['google-section2']			= esc_attr__('Google language localization','lmm');
		$this->sections['google-section3']			= esc_attr__('Google Maps base domain','lmm');
		$this->sections['google-section4']			= esc_attr__('Google Places Autocomplete API','lmm');
		$this->sections['google-section5']			= esc_attr__('Google Adsense settings','lmm');
		$this->sections['google-section6']			= esc_attr__('Google Maps styling','lmm');

		$this->sections['bing-section1']			= esc_attr__('Bing Maps API Key','lmm');
		$this->sections['bing-section2']			= esc_attr__('Bing Culture Parameter','lmm');

		$this->sections['directions-section1']		= esc_attr__('General directions settings','lmm');
		$this->sections['directions-section2']		= esc_attr__('Google Maps directions','lmm');
		$this->sections['directions-section3']		= 'yournavigation.org';
		$this->sections['directions-section4']		= 'map.project-osrm.org';
		$this->sections['directions-section5']		= 'openrouteservice.org';

		$this->sections['ar-section1']				= esc_attr__('Wikitude settings','lmm');

		$this->sections['misc-section1']			= esc_attr__('General settings','lmm');
		$this->sections['misc-section2']			= esc_attr__('Language Settings','lmm');
		$this->sections['misc-section3']			= esc_attr__('KML settings','lmm');
		$this->sections['misc-section4']			= esc_attr__('Available columns for marker listing page','lmm');
		$this->sections['misc-section5']			= esc_attr__('Sort order for marker listing page','lmm');
		$this->sections['misc-section6']			= esc_attr__('Available columns for layer listing page','lmm');
		$this->sections['misc-section7']			= esc_attr__('Sort order for layer listing page','lmm');
		$this->sections['misc-section8']			= esc_attr__('QR code settings','lmm');
		$this->sections['misc-section9']			= esc_attr__('MapsMarker API settings','lmm');

		$this->sections['reset-section1']			= esc_attr__('Reset Settings','lmm');

		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		if ( ! get_option( 'leafletmapsmarker_options' ) )
			$this->initialize_settings();
	}
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'version' => '',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section1',
			'title'   => __( 'Default Field','lmm' ),
			'desc'    => __( 'This is a default description.','lmm' ),
			'std'     => '',
			'type'    => 'text',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;

		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'leafletmapsmarker_settings', $section, $field_args );
	}

	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {

		include(LEAFLET_PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin-header.php');
		if ( isset( $_GET['settings-updated'] ) )
			echo '<div style="margin:15px 0 0 0;" class="updated"><p>' . __( 'Plugin options updated.','lmm' ) . '</p></div>';
		
		echo '<h3 style="font-size:23px;margin:0.83em 0 0 0;float:left;">'.__('Settings','lmm').'</h3><div class="wrap lmmsettings" style="clear:both;">';
		echo '<form action="options.php" method="post">';
		settings_fields( 'leafletmapsmarker_options' );
		echo '<div class="lmm-ui-tabs tabs-top tabbable tabs-top">
			<ul class="lmm-ui-tabs-nav-top ul-outter tabs" id="lmm-admin-top-tabs">';

		$section_index = 0;
		foreach ( $this->panes as $pane_slug => $pane ) {
			$li_class = 0 === $section_index ? ' class="active"' : '';
			$section_index++;
			
			echo '<li' . $li_class . '><a href="#' . $pane_slug . '" data-toggle="tab">' . $pane . '</a></li>';
		}

		echo '</ul>';
		echo '<div class="tab-content">';
		
		$panel_index = 0;
        foreach($this->panes as $pane_slug => $pane){
	        $li_class = 0 === $panel_index ? ' in active' : '';
	        $panel_index++;
	        
            echo '<div id = '.$pane_slug.' class="lmm-ui-tabs tabs-left tab-pane lmm-fade' . $li_class . '">';
	        echo '<div class="tabbable tabs-left">';
            echo '<ul class="lmm-ui-tabs-nav lmm-ui-tabs-navleft tabs lmm-admin-navleft-tabs">';
            $sections = array();
	        $sub_panel_index = 0;
            foreach ( $this->sections as $key => $row ){
	            $k = explode("-",$key);
	            if ( $k[0] == $pane_slug ) {
		            $li_class = 0 === $sub_panel_index ? ' class="active"' : '';
		            $sub_panel_index ++;
		            echo '<li' . $li_class . '><a href="#' . $key . '" data-toggle="tab">' . $row . '</a></li>';
		            $sections[] = $key;
	            }
            }
            echo '</ul>';
	        echo '<div class="tab-content">';
	        
	        $sub_panel_index = 0;
                foreach($sections as $slug => $section){
	                $li_class = 0 === $sub_panel_index ? ' in active' : '';
	                $sub_panel_index++;
                    echo '<div class="section tab-pane lmm-fade' . $li_class . '" id="' . $section. '">';
                    echo "<h3 class='titl'>".$this->sections[$section]."</h3>";
                    if (function_exists('display_'.$pane_slug.'_section')) { //info: Phalanger fix
                    	@call_user_func(array(&$this, 'display_'.$pane_slug.'_section'), array());
                    }
                    echo '<table class="form-table">';
                        do_settings_fields( $_GET['page'], $section );
                    echo '</table>';
                    echo '</div>';
                }

            echo '</div>'; // tab-content
            echo '</div>'; // tabs-left
            echo '</div>'; // tab-pane
        }
		echo '</div>'; // tab-content
		echo '</div>'; // tabs-top
		echo '<p class="submit"><input id="submit" name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes','lmm' ) . '" /></p>

	</form>';

	include(LEAFLET_PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin-footer.php');

	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var panes = [];';

			foreach ( $this->sections as $pane_slug => $pane )
				echo "panes['$pane'] = '$pane_slug';";
			echo '
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});

			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					//$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});

			$(".lmmsettings h3, .lmmsettings table").show();

			//info:  This will make the "warning" checkbox class really stand out when checked.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});

			//info:  Browser compatibility
			if ($.browser.mozilla)
			         $("form").attr("autocomplete", "off");

			//info: warn on unsaved changes when leaving page
			var unsaved = false;
			$(":input").change(function(){
				unsaved = true;
			});
			$("#submit, #s2id_lmm-select-search-tabs").click(function() {
				unsaved = false;
			});
			function unloadPage(){ 
				if(unsaved){
					return "' . esc_attr__('You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?','lmm') . '";
				}
			}
			window.onbeforeunload = unloadPage;	
		});
	</script></div>';
		?>
	<?php
	}

	/**
	 * HTML output for text field
	 */
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( 'leafletmapsmarker_options' );

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;

		switch ( $type ) {

			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2" rowspan="2"><h4>' . $desc . '</h4>';
				break;

			case 'helptext':
				echo '</td></tr><tr valign="top"><td colspan="2">' . $desc . '';
				break;

			case 'helptext-twocolumn':
				echo $desc;
				break;

			case 'checkbox':
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				break;

			case 'checkbox-pro':
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' disabled="disabled" /> <label for="' . $id . '">' . $desc . '</label>';
				break;

			case 'checkbox-readonly':
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' disabled="disabled" /> <label for="' . $id . '">' . $desc . '</label>';
				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="leafletmapsmarker_options[' . $id . ']">';
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				echo '</select>';
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'select-pro':
				echo '<select class="select' . $field_class . '" name="leafletmapsmarker_options[' . $id . ']">';
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . ' disabled="disabled">' . $label . '</option>';
				echo '</select>';
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="leafletmapsmarker_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span>';
				break;

			case 'radio-reverse':
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span><br/>';
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="leafletmapsmarker_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				break;

			case 'radio-pro':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="leafletmapsmarker_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . ' disabled="disabled"> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span>';
				break;

			case 'radio-reverse-pro':
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span><br/>';
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="leafletmapsmarker_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . ' disabled="disabled"> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'textarea-pro':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30" disabled="disabled">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" style="width:30em;" type="text" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		break;

			case 'text-reverse':
		 		if ( $desc != '' )
		 			echo '<span class="description">' . $desc . '</span><br />';
				echo '<input class="regular-text' . $field_class . '" style="width:30em;" type="text" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		break;

			case 'text-pro':
		 		echo '<input class="regular-text' . $field_class . '" style="width:30em;" type="text" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" disabled="disabled" />';
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		break;

			case 'text-reverse-pro':
		 		if ( $desc != '' )
		 			echo '<span class="description">' . $desc . '</span><br/>';
		 		echo '<input class="regular-text' . $field_class . '" style="width:30em;" type="text" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" disabled="disabled" />';
		 		break;

			case 'text-readonly':
		 		echo '<input readonly="readonly" class="regular-text' . $field_class . '" style="width:60em;" type="text" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
	 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		break;

			case 'text-deletable':
		 		echo '<input class="regular-text' . $field_class . '" style="width:60em;" type="text" id="' . $id . '" name="leafletmapsmarker_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
	 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		break;
		}
	}

	/**
	 * Settings and defaults
	 */
	public function get_settings() {
		if ( ! empty( $this->_settings ) )
			return;
			
		$pro_button_link = '<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>';

		/*===========================================
		*
		*
		* pane basemaps
		*
		*
		===========================================*/
		/*
		* Default basemap for new markers/layers
		*/
		$this->_settings['default_basemap_helptext1'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section1',
			'title'   => '',
			'desc'    => __( 'Please select the basemap which should be pre-selected as default for new markers and layers. Can be changed afterwards on each marker/layer.', 'lmm').'<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap.jpg" width="386" height="290" />',
			'type'    => 'helptext'
		);
		$this->_settings['standard_basemap'] = array(
			'version' => '3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section1',
			'title'   => __('Default basemap','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'osm_mapnik',
			'choices' => array(
				'osm_mapnik' => __('OpenStreetMap (Mapnik, max zoom 18)','lmm'),
				'mapquest_osm' => __('MapQuest (OSM, max zoom 18)','lmm'),
				'mapquest_aerial' => __('MapQuest (Aerial, max zoom 12 globally, 12+ in the United States)','lmm'),
				'googleLayer_roadmap' => __('Google Maps (Roadmap)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'googleLayer_satellite' => __('Google Maps (Satellite)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'googleLayer_hybrid' => __('Google Maps (Hybrid)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'googleLayer_terrain' => __('Google Maps (Terrain)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'bingaerial' => __('Bing Maps (Aerial)','lmm') . ' - <strong>' . __('API key required!','lmm') . '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'bingaerialwithlabels' => __('Bing Maps (Aerial+Labels)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'bingroad' => __('Bing Maps (Road)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'ogdwien_basemap' => __('OGD Vienna basemap (max zoom 19)','lmm'),
				'ogdwien_satellite' => __('OGD Vienna satellite (max zoom 19)','lmm'),
				'mapbox' => 'MapBox 1',
				'mapbox2' => 'MapBox 2',
				'mapbox3' => 'MapBox 3',
				'custom_basemap' => __('Custom basemap','lmm'),
				'custom_basemap2' => __('Custom basemap 2','lmm'),
				'custom_basemap3' => __('Custom basemap 3','lmm'),
				'empty_basemap' => __('empty basemap','lmm')
			)
		);
		$this->_settings['global_maxzoom_level'] = array(
			'version' => 'p1.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section1',
			'title'   => __('Global maximum zoom level','lmm') . $pro_button_link,
			'desc'    => __('If the native maximum zoom level of a basemap is lower, tiles will be upscaled automatically.','lmm'),
			'std'     => '21',
			'type'    => 'text-pro'
		);
		/*
		* Names for default basemaps
		*/
		$this->_settings['default_basemap_helptext2'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Optionally you can also change the name of the predefined basemaps in the controlbox.', 'lmm').'<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-names.jpg" width="386" height="290" />',
			'type'    => 'helptext'
		);
		$this->_settings['default_basemap_name_osm_mapnik'] = array(
			'version' => '3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'OpenStreetMap (Mapnik)',
			'desc'    => '',
			'std'     => 'OpenStreetMap',
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_mapquest_osm'] = array(
			'version' => '3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'Mapquest',
			'desc'    => '',
			'std'     => 'Mapquest (OSM)',
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_mapquest_aerial'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'Mapquest (Aerial)',
			'desc'    => '',
			'std'     => 'Mapquest (Aerial)',
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_googleLayer_roadmap'] = array(
			'version' => '2.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Google Maps (Roadmap)','lmm'),
			'desc'    => '',
			'std'   => __('Google Maps (Roadmap)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_googleLayer_satellite'] = array(
			'version' => '2.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Google Maps (Satellite)','lmm'),
			'desc'    => '',
			'std'   => __('Google Maps (Satellite)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_googleLayer_hybrid'] = array(
			'version' => '2.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Google Maps (Hybrid)','lmm'),
			'desc'    => '',
			'std'   => __('Google Maps (Hybrid)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_googleLayer_terrain'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Google Maps (Terrain)','lmm'),
			'desc'    => '',
			'std'   => __('Google Maps (Terrain)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_bingaerial'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Bing Maps (Aerial)','lmm'),
			'desc'    => '',
			'std'   => __('Bing Maps (Aerial)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_bingaerialwithlabels'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Bing Maps (Aerial+Labels)','lmm'),
			'desc'    => '',
			'std'   => __('Bing Maps (Aerial+Labels)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_bingroad'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __('Bing Maps (Road)','lmm'),
			'desc'    => '',
			'std'   => __('Bing Maps (Road)','lmm'),
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_ogdwien_basemap'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'OGD Vienna basemap',
			'desc'    => '',
			'std'     => 'OGD Vienna basemap',
			'type'    => 'text'
		);
		$this->_settings['default_basemap_name_ogdwien_satellite'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'OGD Vienna satellite',
			'desc'    => '',
			'std'     => 'OGD Vienna satellite',
			'type'    => 'text'
		);
		$this->_settings['mapbox_name'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'MapBox',
			'desc'    => '',
			'std'     => 'Blue Marble Topography',
			'type'    => 'text'
		);
		$this->_settings['mapbox2_name'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'MapBox 2',
			'desc'    => '',
			'std'     => 'Geography Class',
			'type'    => 'text',
		);
		$this->_settings['mapbox3_name'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => 'MapBox 3',
			'desc'    => '',
			'std'     => 'Natural Earth I',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap_name'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __( 'Custom Basemap', 'lmm' ),
			'desc'    => '',
			'std'     => 'Open Cycle Map',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap2_name'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __( 'Custom Basemap 2', 'lmm' ),
			'desc'    => '',
			'std'     => 'Stamen Watercolor',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap3_name'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __( 'Custom Basemap 3', 'lmm' ),
			'desc'    => '',
			'std'     => 'Transport Map',
			'type'    => 'text'
		);
		$this->_settings['empty_basemap_name'] = array(
			'version' => '3.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section2',
			'title'   => __( 'empty basemap', 'lmm' ),
			'desc'    => '',
			'std'     => 'empty basemap',
			'type'    => 'text'
		);
		/*
		* Available basemaps in control box
		*/
		$this->_settings['default_basemap_helptext3'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'std'     => '',
			'title'    => '',
			'desc'    => __( 'Please select the basemaps which should be available in the control box.', 'lmm').'<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-available-basemaps.jpg" width="386" height="290" />',
			'type'    => 'helptext'
		);
		$this->_settings['controlbox_osm_mapnik'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => __( 'Basemaps available in control box', 'lmm' ),
			'desc'    => __( 'OpenStreetMap (Mapnik)', 'lmm' ),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_mapquest_osm'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('MapQuest (OSM)','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_mapquest_aerial'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('MapQuest (Aerial)','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_googleLayer_roadmap'] = array(
			'version' => '2.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Google Maps (Roadmap)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_googleLayer_satellite'] = array(
			'version' => '2.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Google Maps (Satellite)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_googleLayer_hybrid'] = array(
			'version' => '2.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Google Maps (Hybrid)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_googleLayer_terrain'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Google Maps (Terrain)','lmm')  . ' - <strong>' . __('API key required for commercial usage!','lmm') . '</strong> <a href="https://www.mapsmarker.com/google-maps-api-key" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_bingaerial'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Bing Maps (Aerial)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_bingaerialwithlabels'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Bing Maps (Aerial+Labels)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_bingroad'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Bing Maps (Road)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_ogdwien_basemap'] = array(
			'version' => '3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('OGD Vienna basemap','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_ogdwien_satellite'] = array(
			'version' => '3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('OGD Vienna satellite','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_mapbox'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => 'MapBox',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_mapbox2'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => 'MapBox 2',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_mapbox3'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => 'MapBox 3',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['controlbox_custom_basemap'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Custom basemap','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_custom_basemap2'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Custom basemap 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_custom_basemap3'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('Custom basemap 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['controlbox_empty_basemap'] = array(
			'version' => '3.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section3',
			'title'   => '',
			'desc'    => __('empty basemap','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* Default values for new marker maps
		*/
		$this->_settings['defaults_marker_helptext1'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Will be used when creating a new marker. All values can be changed afterwards on each marker.', 'lmm') . '<br/>' . __('The following screenshot was taken with the advanced editor enabled:','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-marker-defaults.jpg" width="650" height="593" />',
			'type'    => 'helptext'
		);
		$this->_settings['defaults_marker_lat'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Latitude', 'lmm' ),
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '48.216038',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_lon'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Longitude', 'lmm' ),
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '16.378984',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_zoom'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Zoom', 'lmm' ),
			'desc'    => '',
			'std'     => '11',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_mapwidth'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Map width', 'lmm' ),
			'desc'    => '',
			'std'     => '640',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_mapwidthunit'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __('Map width unit','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'px',
			'choices' => array(
				'px' => 'px',
				'%' => '%'
			)
		);
		$this->_settings['defaults_marker_mapheight'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Map height', 'lmm' ) . ' (px)',
			'desc'    => '',
			'std'     => '480',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_default_layer'] = array(
			'version' => '3.4',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Default layer ID', 'lmm' ),
			'desc'    => __('Set to 0 if you do not want to assign new markers to a layer','lmm'),
			'std'     => '0',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_openpopup'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __('Open popup by default','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array(
				'0' => __('disabled','lmm'),
				'1' => __('enabled','lmm')
			)
		);
		$this->_settings['defaults_marker_controlbox'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __('Controlbox for basemaps/overlays','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'0' => __('hidden','lmm'),
				'1' => __('collapsed','lmm'),
				'2' => __('expanded','lmm')
			)
		);
		// defaults_marker - which overlays are active by default?
		$this->_settings['defaults_marker_overlays_custom_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'    => __('Checked overlays in control box','lmm'),
			'desc'    => __('Custom overlay','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_overlays_custom2_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('Custom overlay 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->_settings['defaults_marker_overlays_custom3_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('Custom overlay 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_overlays_custom4_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('Custom overlay 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_panel'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __('Panel for displaying marker name and API URLs on top of map','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'1' => __('show','lmm'),
				'0' => __('hide','lmm'),
			)
		);
		// defaults_marker - active API links in panel
		$this->_settings['defaults_marker_panel_directions'] = array(
			'version' => '1.4',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'    => __('Visible API links in panel','lmm'),
			'desc'    => __('Directions','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-car.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_marker_panel_kml'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'    => '',
			'desc'    => 'KML <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-kml.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_marker_panel_fullscreen'] = array(
			'version' => '1.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'    => '',
			'desc'    => __('Fullscreen','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-fullscreen.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_marker_panel_qr_code'] = array(
			'version' => '1.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'    => '',
			'desc'    => __('QR code','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-qr-code.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_marker_panel_geojson'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => 'GeoJSON <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-json.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_marker_panel_georss'] = array(
			'version' => '1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => 'GeoRSS <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-georss.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_marker_panel_wikitude'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => 'Wikitude <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-wikitude.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_panel_background_color'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Panel background color', 'lmm' ),
			'desc'    => __('Please use hexadecimal color values','lmm'),
			'std'     => '#efefef',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_panel_paneltext_css'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => __( 'Panel text css', 'lmm' ),
			'desc'    => '',
			'std'     => 'font-weight:bold;color:#373737;',
			'type'    => 'text'
		);
		// defaults_marker - which WMS layers are active by default?
		$this->_settings['defaults_marker_wms_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'    => __('Checked WMS layers','lmm'),
			'desc'    => __('WMS 1','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms2_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms3_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms4_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms5_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 5','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms6_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 6','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms7_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 7','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms8_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 8','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms9_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 9','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_wms10_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section4',
			'title'   => '',
			'desc'    => __('WMS 10','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* Default values for marker icons
		*/
		$this->_settings['defaults_marker_icon_helptext1'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => '',
			'type'    => 'helptext'
		);
		$this->_settings['defaults_marker_custom_icon_url_dir'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __('Use custom icon URL and directory','lmm') . $pro_button_link,
			'desc'    => __('If set to yes, please be aware that the pro settings below have to be changed when you move your WordPress installation to another server for example!','lmm') . '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>',
			'type'    => 'radio-pro',
			'std'     => 'no',
			'choices' => array(
				'no' => __('no','lmm'),
				'yes' => __('yes','lmm'),
			)
		);
		$this->_settings['defaults_marker_icon_url'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Custom icons URL', 'lmm' ) . $pro_button_link,
			'desc'    => __( 'If the option above is set to yes, icons will automatically be loaded from this URL. If the option above is set to no, the following marker icons url will be used:','lmm') . '<br/><strong>' . LEAFLET_PLUGIN_ICONS_URL . '</strong>',
			'std'     => __('Custom directories can be set in the pro version only!','lmm'),
			'type'    => 'text-pro'
		);
		$this->_settings['defaults_marker_icon_dir'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Custom icons directory', 'lmm' ) . $pro_button_link,
			'desc'    => __( 'If option above is set to yes, the directory on server where icons are stored will be used (needed for backend only). If the option above is set to no, the following marker icons directory will be used:','lmm') . '<br/><strong>' . LEAFLET_PLUGIN_ICONS_DIR . '</strong>',
			'std'     => __('Custom directories can be set in the pro version only!','lmm'),
			'type'    => 'text-pro'
		);
		$this->_settings['defaults_marker_icon'] = array(
			'version' => '1.8',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Default icon', 'lmm' ),
			'desc'    => sprintf(__( 'If you want to use another icon than the blue pin (%s), please enter the file name of the icon in the form field - e.g. smiley_happy.png', 'lmm' ),'<img src="' . LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png" width="32" height="37" />'),
			'std'     => '',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_shadow_url_status'] = array(
			'version' => '3.5.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => '<br/>' . __('Marker shadow','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'default',
			'choices' => array(
				'default' => __('use default shadow','lmm') . ' (' . LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker-shadow.png, ' . __('preview','lmm') . ': <img src="' . LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker-shadow.png" width="51" height="37" />)',
				'custom' => __('use custom shadow (please enter URL below)','lmm')
			)
		);
		$this->_settings['defaults_marker_icon_shadow_url'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Custom marker shadow URL', 'lmm' ),
			'desc'    => __( 'The URL to the custom icons shadow image. If empty, no shadow image will be used (even if the option above is set to default).', 'lmm' ) . '',
			'std'     => LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker-shadow.png',
			'type'    => 'text-deletable'
		);
		$this->_settings['defaults_marker_icon_title'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __('Marker tooltip','lmm') . '<br/><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-marker-title.jpg" width="79" height="40" />',
			'desc'    => __('Show marker name for the browser tooltip that appear on marker hover (tooltip is always hidden if marker name is empty). Please be aware that this settings has to be changed when you move your WordPress installation to another server for example!','lmm'),
			'type'    => 'radio',
			'std'     => 'show',
			'choices' => array(
				'show' => __('show','lmm'),
				'hide' => __('hide','lmm')
			)
		);
		$this->_settings['defaults_marker_icon_opacity'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Opacity', 'lmm' ),
			'desc'    => __( 'The opacity of the markers.', 'lmm' ),
			'std'     => '1.0',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_helptext2'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => '<strong>' . sprintf(__('Only change the values below if you are not using marker or shadow icons from the <a href="%1$s" target="_blank">Map Icons Collection</a>!','lmm'), 'https://mapicons.mapsmarker.com') . '</strong>',
			'type'    => 'helptext'
		);
		$this->_settings['defaults_marker_icon_iconsize_x'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Icon size', 'lmm' ) . ' (x)',
			'desc'    => __( 'Width of the icons in pixel', 'lmm' ),
			'std'     => '32',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_iconsize_y'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Icon size', 'lmm' ) . ' (y)',
			'desc'    => __( 'Height of the icons in pixel', 'lmm' ),
			'std'     => '37',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_iconanchor_x'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Icon anchor', 'lmm' ) . ' (x)',
			'desc'    => __( 'The x-coordinates of the "tip" of the icons (relative to its top left corner).', 'lmm' ),
			'std'     => '17',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_iconanchor_y'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Icon anchor', 'lmm' ) . ' (y)',
			'desc'    => __( 'The y-coordinates of the "tip" of the icons (relative to its top left corner).', 'lmm' ),
			'std'     => '36',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_popupanchor_x'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Popup anchor', 'lmm' ) . ' (x)',
			'desc'    => __( 'The x-coordinates of the popup anchor (relative to its top left corner)', 'lmm' ),
			'std'     => '-1',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_popupanchor_y'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Popup anchor', 'lmm' ) . ' (y)',
			'desc'    => __( 'The y-coordinates of the popup anchor (relative to its top left corner)', 'lmm' ),
			'std'     => '-32',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_shadowsize_x'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Shadow size', 'lmm' ) . ' (x)',
			'desc'    => __( 'Width of the shadow icon in pixel', 'lmm' ),
			'std'     => '41',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_shadowsize_y'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Shadow size', 'lmm' ) . ' (y)',
			'desc'    => __( 'Height of the shadow icon in pixel', 'lmm' ),
			'std'     => '41',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_shadowanchor_x'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Shadow anchor', 'lmm' ) . ' (x)',
			'desc'    => __( 'The x-coordinates of the "tip" of the shadow icon (relative to its top left corner)', 'lmm' ),
			'std'     => '16',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_icon_shadowanchor_y'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section5',
			'title'   => __( 'Shadow anchor', 'lmm' ) . ' (y)',
			'desc'    => __( 'The y-coordinates of the "tip" of the shadow icon (relative to its top left corner)', 'lmm' ),
			'std'     => '43',
			'type'    => 'text'
		);
		/*
		* Default values for marker popups
		*/
		$this->_settings['defaults_marker_popups_helptext1'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'std'     => '',
			'title'   => '',
			'desc'    => '<img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-popup.jpg" width="342" height="168" />',
			'type'    => 'helptext'
		);
		$this->_settings['defaults_marker_popups_maxwidth'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'maxWidth (px)',
			'desc'    => __( 'Maximum width of popups in pixel', 'lmm' ),
			'std'     => '300',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_popups_minwidth'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'minWidth (px)',
			'desc'    => __( 'Minimum width of popups in pixel', 'lmm' ),
			'std'     => '250',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_popups_maxheight'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'maxHeight (px)',
			'desc'    => __( 'If set, creates a scrollable container of the given height in pixel inside popups if its content exceeds it.', 'lmm' ),
			'std'     => '160',
			'type'    => 'text-deletable'
		);
		$this->_settings['defaults_marker_popups_image_css'] = array(
			'version' => '3.8.7',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => __('CSS for images in popups','lmm'),
			'desc'    => __( 'Gets added to .leaflet-popup-content img {...} - use max-width to reduce the image width in popups automatically to the given value in pixel (only if it is wider). The height of the images gets reduced by the according ratio automatically.', 'lmm' ),
			'std'     => 'max-width:234px !important; height:auto; width:auto !important;',
			'type'    => 'text-deletable'
		);
		$this->_settings['defaults_marker_popups_autopan'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'autoPan',
			'desc'    => __('Set it to false if you do not want the map to do panning animation to fit the opened popup.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['defaults_marker_popups_closebutton'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'closeButton',
			'desc'    => __('Controls the presence of a close button in popups.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['defaults_marker_popups_add_markername'] = array(
			'version' => 'p1.5.8',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => __('add markername to popup','lmm') . $pro_button_link,
			'desc'    => __('If set to true, the marker name gets added to the top of the popup automatically','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['defaults_marker_popups_autopanpadding_x'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'autoPanPadding (x)',
			'desc'    => __( 'The x-coordinates of the margin between popups and the edges of the map view after autopanning was performed.', 'lmm' ),
			'std'     => '5',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_popups_autopanpadding_y'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section6',
			'title'   => 'autoPanPadding (y)',
			'desc'    => __( 'The y-coordinates of the margin between popups and the edges of the map view after autopanning was performed.', 'lmm' ),
			'std'     => '5',
			'type'    => 'text'
		);
		/*
		* Default values for markers added directly
		*/
		$this->_settings['defaults_marker_shortcode_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'You can also add markers directly to posts or pages without having to save them to your database previously. You just have to use the shortcode with the attributes mlat and mlon (e.g. <strong>[mapsmarker mlat="48.216038" mlon="16.378984"]</strong>).', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-marker-direct.jpg" width="408" height="80" /><br/><br/>' . __('Defaults values for markers added directly:','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['defaults_marker_shortcode_basemap'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => __('Default basemap','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'osm_mapnik',
			'choices' => array(
				'osm_mapnik' => __('OpenStreetMap (Mapnik, max zoom 18)','lmm'),
				'mapquest_osm' => __('MapQuest (OSM, max zoom 18)','lmm'),
				'mapquest_aerial' => __('MapQuest (Aerial, max zoom 12 globally, 12+ in the United States)','lmm'),
				'googleLayer_roadmap' => __('Google Maps (Roadmap)','lmm'),
				'googleLayer_satellite' => __('Google Maps (Satellite)','lmm'),
				'googleLayer_hybrid' => __('Google Maps (Hybrid)','lmm'),
				'googleLayer_terrain' => __('Google Maps (Terrain)','lmm'),
				'bingaerial' => __('Bing Maps (Aerial)','lmm') . ' - <strong>' . __('API key required!','lmm') . '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'bingaerialwithlabels' => __('Bing Maps (Aerial+Labels)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'bingroad' => __('Bing Maps (Road)','lmm') . ' - <strong>' . __('API key required!','lmm'). '</strong> <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
				'ogdwien_basemap' => __('OGD Vienna basemap (max zoom 19)','lmm'),
				'ogdwien_satellite' => __('OGD Vienna satellite (max zoom 19)','lmm'),
				'custom_basemap' => __('Custom basemap','lmm'),
				'custom_basemap2' => __('Custom basemap 2','lmm'),
				'custom_basemap3' => __('Custom basemap 3','lmm')
			)
		);
		$this->_settings['defaults_marker_shortcode_zoom'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => __( 'Zoom', 'lmm' ),
			'desc'    => '',
			'std'     => '11',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_shortcode_mapwidth'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => __( 'Map width', 'lmm' ),
			'desc'    => '',
			'std'     => '640',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_shortcode_mapwidthunit'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => __('Map width unit','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'px',
			'choices' => array(
				'px' => 'px',
				'%' => '%'
			)
		);
		$this->_settings['defaults_marker_shortcode_mapheight'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => __( 'Map height', 'lmm' ) . ' (px)',
			'desc'    => '',
			'std'     => '480',
			'type'    => 'text'
		);
		$this->_settings['defaults_marker_shortcode_controlbox'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => __('Controlbox for basemaps/overlays','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'0' => __('hidden','lmm'),
				'1' => __('collapsed','lmm'),
				'2' => __('expanded','lmm')
			)
		);
		// defaults_marker - which overlays are active by default?
		$this->_settings['defaults_marker_shortcode_overlays_custom_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'    => __('Checked overlays in control box','lmm'),
			'desc'    => __('Custom overlay','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_overlays_custom2_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('Custom overlay 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->_settings['defaults_marker_shortcode_overlays_custom3_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('Custom overlay 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->_settings['defaults_marker_shortcode_overlays_custom4_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('Custom overlay 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		// defaults_marker shortcode - which WMS layers are active by default?
		$this->_settings['defaults_marker_shortcode_wms_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'    => __('Checked WMS layers','lmm'),
			'desc'    => __('WMS 1','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms2_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms3_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms4_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms5_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 5','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms6_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 6','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms7_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 7','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms8_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 8','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms9_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 9','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_marker_shortcode_wms10_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section7',
			'title'   => '',
			'desc'    => __('WMS 10','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* Default values for new layer maps
		*/
		$this->_settings['defaults_layer_helptext1'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Will be used when creating a new layer. All values can be changed afterwards on each layer.', 'lmm') . '<br/>' . __('The following screenshot was taken with the advanced editor enabled:','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-layer-defaults.jpg" width="650" height="466" />',
			'type'    => 'helptext'
		);
		$this->_settings['defaults_layer_lat'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Latitude', 'lmm' ),
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '48.216038',
			'type'    => 'text'
		);
		$this->_settings['defaults_layer_lon'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Longitude', 'lmm' ),
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '16.378984',
			'type'    => 'text'
		);
		$this->_settings['defaults_layer_zoom'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Zoom', 'lmm' ),
			'desc'    => '',
			'std'     => '11',
			'type'    => 'text'
		);
		$this->_settings['defaults_layer_mapwidth'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Map width', 'lmm' ),
			'desc'    => '',
			'std'     => '640',
			'type'    => 'text'
		);
		$this->_settings['defaults_layer_mapwidthunit'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __('Map width unit','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'px',
			'choices' => array(
				'px' => 'px',
				'%' => '%'
			)
		);
		$this->_settings['defaults_layer_mapheight'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Map height', 'lmm' ) . ' (px)',
			'desc'    => '',
			'std'     => '480',
			'type'    => 'text'
		);
		$this->_settings['defaults_layer_controlbox'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __('Controlbox for basemaps/overlays','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'0' => __('hidden','lmm'),
				'1' => __('collapsed','lmm'),
				'2' => __('expanded','lmm')
			)
		);
		// defaults_layer - which overlays are active by default?
		$this->_settings['defaults_layer_overlays_custom_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'    => __('Checked overlays in control box','lmm'),
			'desc'    => __('Custom overlay','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_overlays_custom2_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('Custom overlay 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_overlays_custom3_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('Custom overlay 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_overlays_custom4_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('Custom overlay 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_panel'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __('Panel for displaying layer name and API URLs on top of map','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'1' => __('show','lmm'),
				'0' => __('hide','lmm'),
			)
		);
		// defaults_layer_clustering
		$this->_settings['defaults_layer_clustering'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __('Marker clustering','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm'),
			)
		);
		// defaults_layer - active API links in panel
		$this->_settings['defaults_layer_panel_kml'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'    => __('Visible API links in panel','lmm'),
			'desc'    => 'KML <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-kml.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_panel_fullscreen'] = array(
			'version' => '1.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'    => '',
			'desc'    => __('Fullscreen','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-fullscreen.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_panel_qr_code'] = array(
			'version' => '1.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'    => '',
			'desc'    => __('QR code','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-qr-code.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_panel_geojson'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => 'GeoJSON <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-json.png" width="14" height="14" /> (' . __('not available on multi layer maps','lmm') . ')',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_panel_georss'] = array(
			'version' => '1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => 'GeoRSS <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-georss.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_panel_wikitude'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => 'Wikitude <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-wikitude.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_panel_background_color'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Panel background color', 'lmm' ),
			'desc'    => __('Please use hexadecimal color values','lmm'),
			'std'     => '#efefef',
			'type'    => 'text'
		);
		$this->_settings['defaults_layer_panel_paneltext_css'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => __( 'Panel text css', 'lmm' ),
			'desc'    => '',
			'std'     => 'font-weight:bold;color:#373737;',
			'type'    => 'text'
		);
		// defaults_layer - which WMS layers are active by default?
		$this->_settings['defaults_layer_wms_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'    => __('Checked WMS layers','lmm'),
			'desc'    => __('WMS 1','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms2_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms3_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms4_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms5_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 5','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms6_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 6','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms7_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 7','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms8_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 8','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms9_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 9','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_wms10_active'] = array(
			'version' => '1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section8',
			'title'   => '',
			'desc'    => __('WMS 10','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* List of markers settings
		*/
		$this->_settings['defaults_layer_listmarkers_helptext'] = array(
			'version' => '3.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'std'     => '',
			'title'   => '',
			'desc'    => '<img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-list-markers.jpg" width="400" height="199" />',
			'type'    => 'helptext'
		);
		$this->_settings['defaults_layer_listmarkers'] = array(
			'version' => '1.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => __('Display a list of markers under layer maps','lmm'),
			'desc'    => __('Can be changed for each layer map','lmm'),
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'0' => __('no','lmm'),
				'1' => __('yes','lmm')
			)
		);
		$this->_settings['defaults_layer_listmarkers_show_icon'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => __('Marker attributes to display in list','lmm'),
			'desc'    => __('Icon','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_show_markername'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => '',
			'desc'    => __('Marker name','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_show_popuptext'] = array(
			'version' => '2.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => '',
			'desc'    => __('Popup text','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_show_address'] = array(
			'version' => '3.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => '',
			'desc'    => __('Address','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_order_by'] = array(
			'version' => '1.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => __('Order list of markers by','lmm'),
			'desc'    =>  '',
			'type'    => 'radio',
			'std'     => 'm.id',
			'choices' => array(
				'm.id' => 'ID',
				'm.markername' => __('marker name','lmm'),
				'm.createdon' => __('created on','lmm'),
				'm.updatedon' => __('updated on','lmm'),
				'm.layer' => __('layer ID','lmm')
			)
		);
		$this->_settings['defaults_layer_listmarkers_sort_order'] = array(
			'version' => '1.5',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => __('Sort order','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'ASC',
			'choices' => array(
				'ASC' => __('ascending','lmm'),
				'DESC' => __('descending','lmm')
			)
		);
		$this->_settings['defaults_layer_listmarkers_limit'] = array(
			'version' => '1.7',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => __( 'Limit', 'lmm' ),
			'desc'    => __( 'maximum number of markers to display in the list', 'lmm' ),
			'std'     => '100',
			'type'    => 'text'
		);
		// defaults_layer - active API links in markers list
		$this->_settings['defaults_layer_listmarkers_api_directions'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => __('Visible API links for each marker','lmm'),
			'desc'    => __('Directions','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-car.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_api_kml'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => '',
			'desc'    => 'KML <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-kml.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_api_fullscreen'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => '',
			'desc'    => __('Fullscreen','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-fullscreen.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['defaults_layer_listmarkers_api_qr_code'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'    => '',
			'desc'    => __('QR code','lmm') .  ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-qr-code.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_listmarkers_api_geojson'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => '',
			'desc'    => 'GeoJSON <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-json.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_listmarkers_api_georss'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => '',
			'desc'    => 'GeoRSS <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-georss.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_listmarkers_api_wikitude'] = array(
			'version' => '2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => '',
			'desc'    => 'Wikitude <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-wikitude.png" width="14" height="14" />',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['defaults_layer_listmarkers_link_action'] = array(
			'version' => 'p1.8',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section9',
			'title'   => __('Default action for clicking on icons or marker names','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'setview-open' => __('set map center on marker position and open popup','lmm'),
				'setview-only' => __('set map center on marker position only','lmm'),
				'disabled' => __('no action (and hide links)','lmm')
			)
		);
		/*
		* Interaction options
		* formerly "General map settings" and moved to "Basemaps" from "Misc" tab
		*/
		$this->_settings['map_interaction_options_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The following settings will be used for all marker and layer maps', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['misc_map_dragging'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'dragging',
			'desc'    => __('Whether the map be draggable with mouse/touch or not.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['misc_map_touchzoom'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'touchZoom',
			'desc'    => __('Whether the map can be zoomed by touch-dragging with two fingers.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['misc_map_scrollwheelzoom'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'scrollWheelZoom',
			'desc'    => __('Whether the map can be zoomed by using the mouse wheel.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['misc_map_doubleclickzoom'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'doubleClickZoom',
			'desc'    => __('Whether the map can be zoomed in by double clicking on it.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_interaction_options_boxzoom'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'boxzoom',
			'desc'    => __('Whether the map can be zoomed to a rectangular area specified by dragging the mouse while pressing shift.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['misc_map_trackresize'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'trackResize',
			'desc'    => __('Whether the map automatically handles browser window resize to update itself.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_interaction_options_worldcopyjump'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'worldCopyJump',
			'desc'    => __('With this option enabled, the map tracks when you pan to another "copy" of the world and moves all overlays like markers and vector layers there.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['misc_map_closepopuponclick'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => 'closePopupOnClick',
			'desc'    => __('Set it to false if you do not want popups to close when user clicks the map.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['misc_map_osm_editlink'] = array(
			'version' => '3.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => __('OpenStreetMap edit link','lmm'),
			'desc'    => __('Appends an edit link to the OpenStreetMap and Mapquest (OSM) attribution text which allows direct edits on www.openstreetmap.org (free account required)','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-osmeditlink.jpg" width="521" height="78" />',
			'type'    => 'radio',
			'std'     => 'show',
			'choices' => array(
				'show' => __('show','lmm'),
				'hide' => __('hide','lmm')
			)
		);
		$this->_settings['misc_map_osm_editlink_editor'] = array(
			'version' => '3.6',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section10',
			'title'   => __('OpenStreetMap edit link editor','lmm'),
			'desc'    => __('Editor used to edit maps','lmm'),
			'type'    => 'radio',
			'std'     => 'id',
			'choices' => array(
				'id' => '<a href="http://ideditor.com/" target="_blank">iD</a>',
				'potlatch2' => '<a href="http://wiki.openstreetmap.org/wiki/Potlatch_2" target="_blank">Potlatch 2</a>',
				'remote' => __('remote editor','lmm') . ' (<a href="http://wiki.openstreetmap.org/wiki/JOSM" target="_blank">JOSM</a> / <a href="http://wiki.openstreetmap.org/wiki/Merkaartor" target="_blank">Merkaartor</a>)'
			)
		);
		/*
		* Keyboard navigation options
		*/
		$this->_settings['map_keyboard_navigation_options_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section11',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The following settings will be used for all marker and layer maps', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['map_keyboard_navigation_options_keyboard'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section11',
			'title'   => 'keyboard',
			'desc'    => __('Makes the map focusable and allows users to navigate the map with keyboard arrows and +/- keys','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_keyboard_navigation_options_keyboardpanoffset'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section11',
			'title'   => 'keyboardPanOffset',
			'desc'    => __('Amount of pixels to pan when pressing an arrow key','lmm'),
			'std'     => '80',
			'type'    => 'text'
		);
		$this->_settings['map_keyboard_navigation_options_keyboardzoomoffset'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section11',
			'title'   => 'keyboardZoomOffset',
			'desc'    => __( 'Number of zoom levels to change when pressing + or - key.', 'lmm' ),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['map_keyboard_navigation_options_helptext2'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section11',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:400px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* Panning inertia options
		*/
		$this->_settings['map_panning_inertia_options_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section12',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The following settings will be used for all marker and layer maps', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['map_panning_inertia_options_inertia'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section12',
			'title'   => 'inertia',
			'desc'    => __('If enabled, panning of the map will have an inertia effect where the map builds momentum while dragging and continues moving in the same direction for some time. Feels especially nice on touch devices.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_panning_inertia_options_inertiadeceleration'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section12',
			'title'   => 'inertiaDeceleration',
			'desc'    => __('The rate with which the inertial movement slows down, in pixels/second','lmm'),
			'std'     => '3000',
			'type'    => 'text'
		);
		$this->_settings['map_panning_inertia_options_inertiamaxspeed'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section12',
			'title'   => 'inertiaMaxSpeed',
			'desc'    => __('Max speed of the inertial movement, in pixels/second.','lmm'),
			'std'     => '1500',
			'type'    => 'text'
		);
		$this->_settings['map_panning_inertia_options_helptext2'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section12',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:400px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* Control options
		*/
		$this->_settings['map_control_options_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section13',
			'title'   => '',
			'desc'    => __( 'The following settings will be used for all marker and layer maps', 'lmm'),
			'std'     => '',
			'type'    => 'helptext'
		);
		$this->_settings['misc_map_zoomcontrol'] = array(
			'version' => '2.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section13',
			'title'   => 'zoomControl',
			'desc'    => __('Whether the zoom control is added to the map by default.','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-zoomcontrol.png" width="124" height="77" />',
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_fullscreen_button'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section13',
			'title'   => __('Fullscreen button','lmm') . $pro_button_link,
			'desc'    => __('Whether to add a button for displaying maps in fullscreen via HTML5','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-fullscreen.png" width="83" height="115" /><a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>',
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm'),
			)
		);
		$this->_settings['map_fullscreen_button_position'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section13',
			'title'   => __('Fullscreen button position','lmm') . $pro_button_link,
			'desc'    => __('The position of the fullscreen button (one of the map corners).','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'topleft',
			'choices' => array(
				'bottomleft' => __('Bottom left of the map','lmm'),
				'bottomright' => __('Bottom right of the map','lmm'),
				'topright' => __('Top right of the map','lmm'),
				'topleft' => __('Top left of the map','lmm')
			)
		);
		$this->_settings['map_control_options_helptext2'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section13',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:130px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* Scale control options
		*/
		$this->_settings['map_scale_control_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'A simple scale control that shows the scale of the current center of screen in metric (m/km) and/or imperial (mi/ft) systems. The following settings will be used for all marker and layer maps.', 'lmm').'<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-basemap-scale-control.jpg" width="645" height="43" />',
			'type'    => 'helptext'
		);
		$this->_settings['map_scale_control'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'title'   => __('Scale Control','lmm'),
			'desc'    => __('Whether the scale control is added to the map by default.','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['map_scale_control_position'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'title'   => __('Position','lmm'),
			'desc'    => __('The position of the control (one of the map corners).','lmm'),
			'type'    => 'radio',
			'std'     => 'bottomleft',
			'choices' => array(
				'bottomleft' => __('Bottom left of the map','lmm'),
				'bottomright' => __('Bottom right of the map','lmm'),
				'topright' => __('Top right of the map','lmm'),
				'topleft' => __('Top left of the map','lmm')
			)
		);
		$this->_settings['map_scale_control_maxwidth'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'title'   => 'maxWidth',
			'desc'    => __('Maximum width of the control in pixels. The width is set dynamically to show round values (e.g. 100, 200, 500).','lmm'),
			'std'     => '100',
			'type'    => 'text'
		);
		$this->_settings['map_scale_control_metric'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'title'   => 'metric',
			'desc'    => __('Whether to show the metric scale line (m/km).','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_scale_control_imperial'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'title'   => 'imperial',
			'desc'    => __('Whether to show the imperial scale line (mi/ft).','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_scale_control_updatewhenidle'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'title'   => 'updateWhenIdle',
			'desc'    => __('If true, the control is updated on moveend, otherwise it is always up-to-date (updated on move).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_scale_control_helptext2'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section14',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:100px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* Retina display detection
		*/
		$this->_settings['map_retina_detection_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section15',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The following settings will be used for all marker and layer maps', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['map_retina_detection'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section15',
			'title'   => 'detectRetina',
			'desc'    => __('If true and user is on a retina display (= iPhone 4/4S/5, iPad 3, MacBook Pro 3rd Generation), it will request four tiles of half the specified size and a bigger zoom level in place of one to utilize the high resolution.','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['map_retina_detection_helptext2'] = array(
			'version' => '2.7.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section15',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:500px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* Mobile web app settings
		*/
		$this->_settings['map_webapp_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Fullscreen maps are optimized for mobile devices in the pro version. This means that the viewport of the map automatically adjusts to the width of the device used and native javascript is used instead of jQuery which results in higher performance when loading maps.<br/><br/>For iOS devices (iphone, ipad, ipod) it is also possible to add maps with a custom icon to the homescreen and open them as web apps with a custom launch image and without the address bar of the browser.', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-webapp.jpg" width="551" height="213" />'  . '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>',
			'type'    => 'helptext'
		);
		$this->_settings['map_webapp_images'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Images to use','lmm') . $pro_button_link,
			'desc'    => __('If you want to use custom images, please enter the URL to app icons and launch images below:','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'default',
			'choices' => array(
				'none' => __('disable images','lmm'),
				'default' => __('use default images','lmm'),
				'custom' => __('use custom images','lmm')
			)
		);
		$this->_settings['map_webapp_icon57'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('App icon URL','lmm') . $pro_button_link,
			'desc'    => __('Size for iPhone and iPod touch','lmm') . ' (57x57px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_icon114'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('App icon URL','lmm') . $pro_button_link,
			'desc'    => __('Size for high-resolution iPhone and iPod touch','lmm') . ' (114x114px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_icon72'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('App icon URL','lmm') . $pro_button_link,
			'desc'    => __('Size for iPad','lmm') . ' (72x72px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_icon144'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('App icon URL','lmm') . $pro_button_link,
			'desc'    => __('Size for high-resolution iPad','lmm') . ' (144x144px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch1024'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for iPad','lmm') . ' (1024x748px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch2048'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for high-resolution iPad','lmm') . ' (2048x1496px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch768'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for iPad','lmm') . ' (768x1004px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch1536'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for high-resolution iPad','lmm') . ' (1536x2008px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch320'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for iPhone and iPod touch','lmm') . ' (320x460px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch640'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for high-resolution iPhone and iPod touch','lmm') . ' (640x920px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['map_webapp_launch640_1096'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section16',
			'title'   => __('Launch image URL','lmm') . $pro_button_link,
			'desc'    => __('Size for iPhone 5 and iPod 5','lmm') . ' (640x1096px)',
			'std'     => '',
			'type'    => 'text-pro'
		);
		/*
		* Minimap settings
		*/
		$this->_settings['minimap_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'std'     => '',
			'title'   => '',
			'desc'    => '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>' . __( 'Add an expandable minimap to your maps which shows the same as the main map with a set zoom offset', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-minimap.jpg" width="400" height="183" />',
			'type'    => 'helptext'
		);
		$this->_settings['minimap_status'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => __('Status','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'hidden',
			'choices' => array(
				'collapsed' => __('collapsed','lmm'),
				'expanded' => __('expanded','lmm'),
				'hidden' => __('hidden','lmm')
			)
		);
		$this->_settings['minimap_basemap'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => __('Basemap','lmm') . $pro_button_link,
			'desc'    => __('Please select basemap which should be used for minimaps','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'automatic',
			'choices' => array(
				'automatic' => __('automatic (use basemap from main map and OpenStreetMap as fallback if unsupported)','lmm'),
				'osm_mapnik_minimap' => __('OpenStreetMap (Mapnik, max zoom 18)','lmm'),
				'mapquest_osm_minimap' => __('MapQuest (OSM, max zoom 18)','lmm'),
				'mapquest_aerial_minimap' => __('MapQuest (Aerial, max zoom 12 globally, 12+ in the United States)','lmm'),
				'googleLayer_roadmap_minimap' => __('Google Maps (Roadmap)','lmm'),
				'googleLayer_satellite_minimap' => __('Google Maps (Satellite)','lmm'),
				'googleLayer_hybrid_minimap' => __('Google Maps (Hybrid)','lmm'),
				'googleLayer_terrain_minimap' => __('Google Maps (Terrain)','lmm'),
				'bingaerial_minimap' => __('Bing Maps (Aerial)','lmm') . ' - <strong>' . __('API key required!','lmm') . '</strong>',
				'bingaerialwithlabels_minimap' => __('Bing Maps (Aerial+Labels)','lmm') . ' - <strong>' . __('API key required!','lmm') . '</strong>',
				'bingroad_minimap' => __('Bing Maps (Road)','lmm') . ' - <strong>' . __('API key required!','lmm') . '</strong>'
				)
		);
		$this->_settings['minimap_position'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => __('Position','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'bottomright',
			'choices' => array(
				'bottomleft' => __('Bottom left of the map','lmm'),
				'bottomright' => __('Bottom right of the map','lmm'),
				'topright' => __('Top right of the map','lmm'),
				'topleft' => __('Top left of the map','lmm')
			)
		);
		$this->_settings['minimap_width'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => __('Width','lmm') . $pro_button_link,
			'desc'    => __('The width of the minimap in pixels.','lmm'),
			'std'     => '150',
			'type'    => 'text-pro'
		);
		$this->_settings['minimap_height'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => __('Height','lmm') . $pro_button_link,
			'desc'    => __(' The height of the minimap in pixels.','lmm'),
			'std'     => '150',
			'type'    => 'text-pro'
		);
		$this->_settings['minimap_collapsedWidth'] = array(
			'version' => 'p2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => 'collapsedWidth' . $pro_button_link,
			'desc'    => __('The width of the toggle marker and the minimap when collapsed, in pixels.','lmm'),
			'std'     => '19',
			'type'    => 'text-pro'
		);
		$this->_settings['minimap_collapsedHeight'] = array(
			'version' => 'p2.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => 'collapsedHeight' . $pro_button_link,
			'desc'    => __('The height of the toggle marker and the minimap when collapsed, in pixels.','lmm'),
			'std'     => '19',
			'type'    => 'text-pro'
		);
		$this->_settings['minimap_zoomLevelOffset'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => 'zoomLevelOffset<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('The offset applied to the zoom in the minimap compared to the zoom of the main map. Can be positive or negative.','lmm'),
			'std'     => '-5',
			'type'    => 'text-pro'
		);
		$this->_settings['minimap_zoomLevelFixed'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   =>'zoomLevelFixed<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Overrides the offset to apply a fixed zoom level to the minimap regardless of the main map zoom. Set it to any valid zoom level, if unset zoomLevelOffset is used instead.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['minimap_zoomAnimation'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => 'zoomAnimation<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Sets whether the minimap should have an animated zoom. (Will cause it to lag a bit after the movement of the main map.)','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['minimap_toggleDisplay'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => 'toggleDisplay<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Sets whether the minimap should have a button to minimise it','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['minimap_autoToggleDisplay'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section17',
			'title'   => 'autoToggleDisplay<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Sets whether the minimap should hide automatically if the parent map bounds does not fit within the minimap bounds. Especially useful when zoomLevelFixed is set.','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		/*
		* Marker clustering settings
		*/
		$this->_settings['clustering_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'std'     => '',
			'title'   => '',
			'desc'    => '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>' . __( 'Clustering can be enabled/disabled for each layer separately on the layer edit page. Below you will find the global settings which are valid for all layer maps with clustering enabled.', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering.jpg" width="500" height="204" />',
			'type'    => 'helptext'
		);
		$this->_settings['clustering_zoomToBoundsOnClick'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => 'zoomToBoundsOnClick<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('When you click a cluster it zooms to its bounds','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_showCoverageOnHover'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => 'showCoverageOnHover<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('When you mouse over a cluster it shows the bounds of its markers:','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering-coverage.jpg" width="135" height="94" />',
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_spiderfyOnMaxZoom'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => 'spiderfyOnMaxZoom<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('When you click a cluster at the bottom zoom level it spiderfies it so you can see all of its markers:','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering-spiderify.jpg" width="140" height="100" />',
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_disableClusteringAtZoom'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'disableClusteringAtZoom<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('If set (1 to 18 repectively 19 if supported), at this zoom level and below markers will not be clustered.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['clustering_maxClusterRadius'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'maxClusterRadius<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more smaller clusters.','lmm'),
			'std'     => '80',
			'type'    => 'text-pro'
		);
$this->_settings['clustering_helptext2'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'std'     => '',
			'title'   =>'polygonOptions<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => sprintf(__('Options to pass when creating the L.Polygon for styling (<a href="%1s" target="_blank">more details</a>)','lmm'), 'http://leafletjs.com/reference.html#path-options') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering-polygon-options.jpg" width="141" height="91" />',
			'type'    => 'helptext-twocolumn'
		);
		$this->_settings['clustering_polygonOptions_stroke'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => '',
			'desc'    => __('stroke (whether to draw stroke along the path. Set it to false to disable borders on polygons or circles)','lmm'),
			'type'    => 'radio-reverse-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_polygonOptions_color'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('color (stroke color)','lmm') . ' - ' . __('example','lmm') . ': ff0000',
			'std'     => '03f',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_polygonOptions_weight'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('weight (stroke width in pixel)','lmm') . ' - ' . __('example','lmm') . ': 5',
			'std'     => '5',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_polygonOptions_opacity'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('opacity (stroke opacity)','lmm') . ' - ' . __('example','lmm') . ': 0.5',
			'std'     => '0.5',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_polygonOptions_fill'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => '',
			'desc'    => __('fill (whether to fill the path with color. Set it to false to disable filling on polygons or circles)','lmm'),
			'type'    => 'radio-reverse-pro',
			'std'     => 'auto',
			'choices' => array(
				'auto' => __('automatic','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_polygonOptions_fillColor'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('fillColor (fill color)','lmm') . ' - ' . __('example','lmm') . ': ff0000',
			'std'     => '03f',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_polygonOptions_fillopacity'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => '',
			'desc'    => __('fillOpacity (fill opacity)','lmm') . ' - ' . __('example','lmm') . ': 0.2',
			'std'     => '0.2',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_polygonOptions_clickable'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => '',
			'desc'    => __('clickable (if false, the vector will not emit mouse events and will act as a part of the underlying map)','lmm'),
			'type'    => 'radio-reverse-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_helptext3'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'std'     => '',
			'title'   => __('Cluster colors','lmm') . '<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Options to set the colors of the cluster circles','lmm') . ' - <a href="https://www.mapsmarker.com/colorpicker" target="_blank">https://www.mapsmarker.com/colorpicker</a><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering-colors.jpg" width="400" height="98" />',
			'type'    => 'helptext-twocolumn'
		);
		$this->_settings['clustering_color_small_text'] = array(
			'version' => 'p1.3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster small (text color)','lmm'),
			'std'     => '#000000',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_small'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster small (outer)','lmm'),
			'std'     => 'rgba(181, 226, 140, 0.6)',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_small_inner'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster small (inner)','lmm'),
			'std'     => 'rgba(110, 204, 57, 0.6)',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_medium'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster medium (outer)','lmm'),
			'std'     => 'rgba(241, 211, 87, 0.6)',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_medium_inner'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster medium (inner)','lmm'),
			'std'     => 'rgba(240, 194, 12, 0.6)',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_medium_text'] = array(
			'version' => 'p1.3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster medium (text color)','lmm'),
			'std'     => '#000000',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_large'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster large (outer)','lmm'),
			'std'     => 'rgba(253, 156, 115, 0.6)',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_large_inner'] = array(
			'version' => 'p1.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster large (inner)','lmm'),
			'std'     => 'rgba(241, 128, 23, 0.6)',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_color_large_text'] = array(
			'version' => 'p1.3.1',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'',
			'desc'    => __('Cluster large (text color)','lmm'),
			'std'     => '#000000',
			'type'    => 'text-reverse-pro'
		);
		$this->_settings['clustering_singleMarkerMode'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => 'singleMarkerMode<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('If set to true, overrides the icon for all added markers to make them appear as a 1 size cluster:','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering-singlemarkermode.jpg" width="123" height="100" />',
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['clustering_spiderfyDistanceMultiplier'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   =>'spiderfyDistanceMultiplier<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Increase from 1 to increase the distance away from the center that spiderfied markers are placed. Use if you are using big marker icons:','lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-clustering-spiderify-distance.jpg" width="117" height="100" />',
			'std'     => '1',
			'type'    => 'text-pro'
		);
		$this->_settings['clustering_animateAddingMarkers'] = array(
			'version' => 'p1.0',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section18',
			'title'   => 'animateAddingMarkers<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('If set to true then adding individual markers to the MarkerClusterGroup after it has been added to the map will add the marker and animate it in to the cluster. Defaults to false as this gives better performance when bulk adding markers.','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);

		/*
		* GPX tracks settings
		*/
		$this->_settings['gpx_helptext'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'std'     => '',
			'title'   => '',
			'desc'    => '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-gpx.jpg" width="449" height="263" /><br/><br/>' . __( 'Settings below will be applied to all GPX tracks added to marker or layer maps.', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['gpx_track_color'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => __('Polygon options','lmm'). $pro_button_link,
			'desc'    => __('Track color','lmm') . ' - ' . sprintf(__('Please enter the hex value of the color you would like to use. For help please visit <a href="%1s" target="_blank">%2s</a>.','lmm'), 'https://www.mapsmarker.com/colorpicker', 'mapsmarker.com/colorpicker'),
			'std'     => '#0000FF',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_track_weight'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __('Track weight','lmm') . ' - ' . __('Stroke width in pixels','lmm'),
			'std'     => '5',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_track_opacity'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __('Stroke opacity','lmm'),
			'std'     => '0.5',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_track_clickable'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __('Clickable','lmm') . ' - ' . __('If true, the vector will emit mouse events and will act as a part of the underlying map.','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['gpx_track_noClip'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => 'noClip' . ' - ' . __('polyline clipping','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['gpx_track_smoothFactor'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __('Track smoothFactor','lmm') . ' - ' . __('How much to simplify the polyline on each zoom level. More means better performance and smoother look, and less means more accurate representation.','lmm'),
			'std'     => '1.0',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_metadata_units'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => __('GPX metadata settings','lmm'). $pro_button_link,
			'desc'    => __('Distance units','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'metric',
			'choices' => array(
				'metric' => __('metric (km)','lmm'),
				'imperial' => __('imperial (miles)','lmm')
			)
		);		
		$this->_settings['gpx_metadata_name'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Track name', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_start'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Starting time', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_end'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'End time', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 0
		);
		$this->_settings['gpx_metadata_distance'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Total track distance', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_duration_total'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Duration', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_duration_moving'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Moving time', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 0
		);
		$this->_settings['gpx_metadata_avpace'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Average moving pace', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_avhr'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Average heart rate', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 0
		);
		$this->_settings['gpx_metadata_hr_full'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Full heart rate data', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 0
		);		
		$this->_settings['gpx_metadata_elev_gain'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Elevation gain', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_elev_loss'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Elevation loss', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_elev_net'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Elevation net', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_metadata_elev_full'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Full elevation data', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 0
		);
		$this->_settings['gpx_metadata_gpx_download'] = array(
			'version' => 'p2.4',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'download GPX file', 'lmm' ),
			'type'    => 'checkbox-pro',
			'std'     => 1
		);
		$this->_settings['gpx_max_point_interval'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => __('Maximum interval between points','lmm'). $pro_button_link,
			'desc'    => __('GPX parsing will automatically handle pauses in the track with a default tolerance interval of 15 seconds between points.','lmm'),
			'std'     => '15000',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_startIconUrl'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => __('GPX icon settings','lmm'). $pro_button_link,
			'desc'    => __('Start icon','lmm') . ' - ' . sprintf(__('Leave empty to use the <a href="%1s" target="_blank">default icon</a>. To use a custom icon, please enter the file name of the icon within your marker icon directory (%2s)','lmm'), LEAFLET_PLUGIN_URL . 'leaflet-dist/images/gpx-icon-start.png', LEAFLET_PLUGIN_ICONS_URL),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_endIconUrl'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __('End icon','lmm') . ' - ' . sprintf(__('Leave empty to use the <a href="%1s" target="_blank">default icon</a>. To use a custom icon, please enter the file name of the icon within your marker icon directory (%2s)','lmm'), LEAFLET_PLUGIN_URL . 'leaflet-dist/images/gpx-icon-end.png', LEAFLET_PLUGIN_ICONS_URL),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_shadowUrl'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __('Shadow icon','lmm') . ' - ' . sprintf(__('Leave empty to use the <a href="%1s" target="_blank">default icon</a>. To use a custom icon, please enter the file name of the icon within your marker icon directory (%2s)','lmm'), LEAFLET_PLUGIN_URL . 'leaflet-dist/images/gpx-icon-shadow.png', LEAFLET_PLUGIN_ICONS_URL),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_iconSize_x'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Icon size', 'lmm' ) . ' (x) - ' . __( 'Width of the icons in pixel', 'lmm' ),
			'std'     => '33',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_iconSize_y'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Icon size', 'lmm' ) . ' (y) - ' . __( 'Width of the icons in pixel', 'lmm' ),
			'std'     => '50',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_shadowSize_x'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Shadow size', 'lmm' ) . ' (x) - ' . __( 'Width of the shadow in pixel', 'lmm' ),
			'std'     => '50',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_shadowSize_y'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Shadow size', 'lmm' ) . ' (y) - ' . __( 'Height of the shadow in pixel', 'lmm' ),
			'std'     => '50',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_iconAnchor_x'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Icon anchor', 'lmm' ) . ' (x) - ' . __( 'The x-coordinates of the icons in pixel', 'lmm' ),
			'std'     => '16',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_iconAnchor_y'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Icon anchor', 'lmm' ) . ' (y) - ' . __( 'The y-coordinates of the icons in pixel', 'lmm' ),
			'std'     => '45',
			'type'    => 'text-pro'
		);		
		$this->_settings['gpx_shadowAnchor_x'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Shadow anchor', 'lmm' ) . ' (x) - ' . __( 'The x-coordinates of the shadow in pixel', 'lmm' ),
			'std'     => '16',
			'type'    => 'text-pro'
		);
		$this->_settings['gpx_shadowAnchor_y'] = array(
			'version' => 'p1.2',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section19',
			'title'   => '',
			'desc'    => __( 'Shadow anchor', 'lmm' ) . ' (y) - ' . __( 'The x-coordinates of the shadow in pixel', 'lmm' ),
			'std'     => '47',
			'type'    => 'text-pro'
		);		
		/*
		* Geolocate settings
		*/
		$this->_settings['geolocate_helptext'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'std'     => '',
			'title'   => '',
			'desc'    => '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>' . __('Add a geolocate button to all maps which allows to show and follow your current location:','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-geolocation.jpg" width="399" height="186" />',
			'type'    => 'helptext'
		);
		$this->_settings['geolocate_status'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => __('Add a geolocate button to each map','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_autostart'] = array(
			'version' => 'p2.3',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => __('Autostart','lmm') . $pro_button_link,
			'desc'    => sprintf(__('If set to true, geolocation will start automatically on each map (backend and frontend).<br/>For infos on how to start geolocation automatically for selected maps only, please see <a href="%1$S" target="_blank">this changelog</a>.','lmm'), 'https://www.mapsmarker.com/v2.3p'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_icon'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => __('icon','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'icon-cross-hairs',
			'choices' => array(
				'icon-cross-hairs' => 'cross hairs <img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-icon-cross-hairs.png" width="20" height="20" />',
				'icon-pin' => 'pin <img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-icon-pin.png" width="16" height="17" />',
				'icon-arrow' => 'arrow <img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-icon-arrow.png" width="17" height="16" />'
			)
		);
		$this->_settings['geolocate_position'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => __('Geolocate button position','lmm') . $pro_button_link,
			'desc'    => __('The position of the geolocate button (one of the map corners).','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'topleft',
			'choices' => array(
				'topleft' => __('Top left of the map','lmm'),
				'topright' => __('Top right of the map','lmm'),
				'bottomleft' => __('Bottom left of the map','lmm'),
				'bottomright' => __('Bottom right of the map','lmm')
			)
		);
		$this->_settings['geolocate_drawCircle'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'drawCircle' . $pro_button_link,
			'desc'    => __('controls whether a circle is drawn that shows the uncertainty about the location','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_follow'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'follow' . $pro_button_link,
			'desc'    => esc_attr__('follow the location of the user','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_setView'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'setView' . $pro_button_link,
			'desc'    => esc_attr__('automatically sets the map view to the location of the user, enabled if setting "follow" is true','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_keepCurrentZoomLevel'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'keepCurrentZoomLevel' . $pro_button_link,
			'desc'    => esc_attr__('keep the current map zoom level when displaying the location of the user','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false (use maximum zoom level)','lmm')
			)
		);
		$this->_settings['geolocate_remainActive'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'remainActive' . $pro_button_link,
			'desc'    => esc_attr__('if true locate control remains active on click even if the location of the user is in view','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'false',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_showPopup'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'showPopup' . $pro_button_link,
			'desc'    => esc_attr__('display a popup when the user click on the inner marker','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		$this->_settings['geolocate_units'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => __('Distance units','lmm') . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'true',
			'choices' => array(
				'true' => __('metric (km)','lmm'),
				'false' => __('imperial (miles)','lmm')
			)
		);
		$this->_settings['geolocate_circlePadding'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'circlePadding' . $pro_button_link,
			'desc'    => __('padding around accuracy circle, value is passed to setBounds','lmm'),
			'std'     => '[0,0]',
			'type'    => 'text-pro'
		);
		$this->_settings['geolocate_circleStyle'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'circleStyle' . $pro_button_link,
			'desc'    => sprintf(esc_attr__('change the style of the circle around the location of the user, example: %1$s','lmm'), "<br/>'color':'#136AEC','fillColor':'#136AEC','fillOpacity':'0.15','weight':'2','opacity':'0.5'"),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['geolocate_markerStyle'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'markerStyle' . $pro_button_link,
			'desc'    => sprintf(esc_attr__('change the style of the marker of the location of the user, example: %1$s','lmm'), "<br/>'color':'#136AEC','fillColor':'#136AEC','fillOpacity':'0.15','weight':'2','opacity':'0.5','radius':'5'"),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['geolocate_followCircleStyle'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'followCircleStyle' . $pro_button_link,
			'desc'    => sprintf(esc_attr__('change the style of the circle around the location of the user while following, example: %1$s','lmm'), "<br/>'color':'#136AEC','fillColor':'#136AEC','fillOpacity':'0.15','weight':'2','opacity':'0.5'"),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['geolocate_followMarkerStyle'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'followMarkerStyle' . $pro_button_link,
			'desc'    => sprintf(esc_attr__('change the style of the marker of the location of the user while following, example: %1$s','lmm'), "<br/>'color':'#136AEC','fillColor':'#136AEC','fillOpacity':'0.15','weight':'4','opacity':'0.5','radius':'5'"),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['geolocate_locateOptions'] = array(
			'version' => 'p1.9',
			'pane'    => 'mapdefaults',
			'section' => 'mapdefaults-section20',
			'title'   => 'locateOptions' . $pro_button_link,
			'desc'    => sprintf(__('define additional location options e.g enableHighAccuracy: true, maxZoom: 10<br/>reference: %1$s','lmm'), '<a href="http://leafletjs.com/reference.html#map-locate-options" target="_blank">http://leafletjs.com/reference.html#map-locate-options</a>'),
			'std'     => 'watch: true',
			'type'    => 'text-pro'
		);

		/*===========================================
		*
		*
		* pane basemaps
		*
		*
		===========================================*/
		/*
		* MapBox settings
		*/
		$this->_settings['mapbox_helptext'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => '<span style="background:#f99755;display:block;padding:3px;text-decoration:none;width:635px;">' . sprintf(__('Warning: as Mapbox now requires to use a custom API access token, custom Mapbox basemaps will not work anymore if you registered your Mapbox account before January 2015.<br/>In case your Mapbox maps are broken, please switch to another basemap like OpenStreetMap or <a href="%1$s">upgrade to Maps Marker Pro</a>, which enables you to continue using custom Mapbox basemaps - even with accounts created after January 2015 (please also note that Mapbox might discontinue the usage of their old API for existing users in the long run too!).','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade') . '</span><br/>' . sprintf(__('A tutorial on how to configure Mapbox basemaps can be found at %1$s','lmm'), '<a href="https://www.mapsmarker.com/mapbox" target="_blank">https://www.mapsmarker.com/mapbox</a>') . '<br/><br/>' . __('If you want to change the name of the custom Mapbox basemap shown in the layer controlbox, please change the according settings at "Map Defaults" / "Names for default basemaps"','lmm') . '<br/><br/>' . __('If you want the custom Mapbox basemap not to be available in the layer controlbox for selection, please change the according settings at "Map Defaults" / "Available basemaps in control box"','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-mapbox.jpg" width="400" height="230" />',
			'type'    => 'helptext'
		);
		$this->_settings['mapbox_user'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'title'   => __( 'User', 'lmm' ),
			'desc'    => __('extracted from Map ID within mapbox editor, e.g.','lmm') . '<strong>YourUsername</strong>.kpf3blj3',
			'std'     => 'mapbox',
			'type'    => 'text'
		);
		$this->_settings['mapbox_map'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'title'   => __('Style','lmm'),
			'desc'    => __('extracted from Map ID within mapbox editor, e.g.','lmm') . 'YourUsername.<strong>kpf3blj3</strong>',
			'std'     => 'blue-marble-topo-jul',
			'type'    => 'text'
		);
		$this->_settings['mapbox_access_token'] = array(
			'version' => 'p2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'title'   => __('API access token','lmm') . $pro_button_link,
			'desc'    => __('Access to Mapbox web services requires an access token. The access token is used to associate requests to API resources with your account.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['mapbox_minzoom'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'title'   => __('Minimum zoom level','lmm'),
			'desc'    => '',
			'std'     => '0',
			'type'    => 'text'
		);
		$this->_settings['mapbox_maxzoom'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'title'   => __('Maximum zoom level','lmm'),
			'desc'    => '',
			'std'     => '8',
			'type'    => 'text'
		);
		$this->_settings['mapbox_attribution'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section1',
			'title'   => __('Attribution','lmm'),
			'desc'    => __("For example","lmm"). ": Copyright ".date('Y')." &lt;a href=&quot;http://xy.com&quot;&gt;Provider X&lt;/a&gt;",
			'std'     => "MapBox/NASA, <a href=&quot;http://www.mapbox.com&quot; target=&quot;_blank&quot;>http://www.mapbox.com</a>",
			'type'    => 'text'
		);
		/*
		* MapBox 2 settings
		*/
		$this->_settings['mapbox2_helptext'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'std'     => '',
			'title'   => '',
			'desc'    => '<span style="background:#f99755;display:block;padding:3px;text-decoration:none;width:635px;">' . sprintf(__('Warning: as Mapbox now requires to use a custom API access token, custom Mapbox basemaps will not work anymore if you registered your Mapbox account before January 2015.<br/>In case your Mapbox maps are broken, please switch to another basemap like OpenStreetMap or <a href="%1$s">upgrade to Maps Marker Pro</a>, which enables you to continue using custom Mapbox basemaps - even with accounts created after January 2015 (please also note that Mapbox might discontinue the usage of their old API for existing users in the long run too!).','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade') . '</span><br/>' . sprintf(__('A tutorial on how to configure Mapbox basemaps can be found at %1$s','lmm'), '<a href="https://www.mapsmarker.com/mapbox" target="_blank">https://www.mapsmarker.com/mapbox</a>') . '<br/><br/>' . __('If you want to change the name of the custom Mapbox basemap shown in the layer controlbox, please change the according settings at "Map Defaults" / "Names for default basemaps"','lmm') . '<br/><br/>' . __('If you want the custom Mapbox basemap not to be available in the layer controlbox for selection, please change the according settings at "Map Defaults" / "Available basemaps in control box"','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-mapbox.jpg" width="400" height="230" />',
			'type'    => 'helptext'
		);
		$this->_settings['mapbox2_user'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'title'   => __( 'User', 'lmm' ),
			'desc'    => __('extracted from Map ID within mapbox editor, e.g.','lmm') . '<strong>YourUsername</strong>.kpf3blj3',
			'std'     => 'mapbox',
			'type'    => 'text'
		);
		$this->_settings['mapbox2_map'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'title'   => __('Style','lmm'),
			'desc'    => __('extracted from Map ID within mapbox editor, e.g.','lmm') . 'YourUsername.<strong>kpf3blj3</strong>',
			'std'     => 'geography-class',
			'type'    => 'text'
		);
		$this->_settings['mapbox2_access_token'] = array(
			'version' => 'p2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'title'   => __('API access token','lmm') . $pro_button_link,
			'desc'    => __('Access to Mapbox web services requires an access token. The access token is used to associate requests to API resources with your account.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['mapbox2_minzoom'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'title'   => __('Minimum zoom level','lmm'),
			'desc'    => '',
			'std'     => '0',
			'type'    => 'text'
		);
		$this->_settings['mapbox2_maxzoom'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'title'   => __('Maximum zoom level','lmm'),
			'desc'    => '',
			'std'     => '8',
			'type'    => 'text'
		);
		$this->_settings['mapbox2_attribution'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section2',
			'title'   => __('Attribution','lmm'),
			'desc'    => __("For example","lmm"). ": Copyright ".date('Y')." &lt;a href=&quot;http://xy.com&quot;&gt;Provider X&lt;/a&gt;",
			'std'     => "MapBox, <a href=&quot;http://www.mapbox.com&quot; target=&quot;_blank&quot;>http://www.mapbox.com</a>",
			'type'    => 'text'
		);
		/*
		* MapBox 3 settings
		*/
		$this->_settings['mapbox3_helptext'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'std'     => '',
			'title'   => '',
			'desc'    => '<span style="background:#f99755;display:block;padding:3px;text-decoration:none;width:635px;">' . sprintf(__('Warning: as Mapbox now requires to use a custom API access token, custom Mapbox basemaps will not work anymore if you registered your Mapbox account before January 2015.<br/>In case your Mapbox maps are broken, please switch to another basemap like OpenStreetMap or <a href="%1$s">upgrade to Maps Marker Pro</a>, which enables you to continue using custom Mapbox basemaps - even with accounts created after January 2015 (please also note that Mapbox might discontinue the usage of their old API for existing users in the long run too!).','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade') . '</span><br/>' . sprintf(__('A tutorial on how to configure Mapbox basemaps can be found at %1$s','lmm'), '<a href="https://www.mapsmarker.com/mapbox" target="_blank">https://www.mapsmarker.com/mapbox</a>') . '<br/><br/>' . __('If you want to change the name of the custom Mapbox basemap shown in the layer controlbox, please change the according settings at "Map Defaults" / "Names for default basemaps"','lmm') . '<br/><br/>' . __('If you want the custom Mapbox basemap not to be available in the layer controlbox for selection, please change the according settings at "Map Defaults" / "Available basemaps in control box"','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-mapbox.jpg" width="400" height="230" />',
			'type'    => 'helptext'
		);
		$this->_settings['mapbox3_user'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'title'   => __( 'User', 'lmm' ),
			'desc'    => __('extracted from Map ID within mapbox editor, e.g.','lmm') . '<strong>YourUsername</strong>.kpf3blj3',
			'std'     => 'mapbox',
			'type'    => 'text'
		);
		$this->_settings['mapbox3_map'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'title'   => __('Style','lmm'),
			'desc'    => __('extracted from Map ID within mapbox editor, e.g.','lmm') . 'YourUsername.<strong>kpf3blj3</strong>',
			'std'     => 'natural-earth-1',
			'type'    => 'text'
		);
		$this->_settings['mapbox3_access_token'] = array(
			'version' => 'p2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'title'   => __('API access token','lmm') . $pro_button_link,
			'desc'    => __('Access to Mapbox web services requires an access token. The access token is used to associate requests to API resources with your account.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['mapbox3_minzoom'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'title'   => __('Minimum zoom level','lmm'),
			'desc'    => '',
			'std'     => '0',
			'type'    => 'text'
		);
		$this->_settings['mapbox3_maxzoom'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'title'   => __('Maximum zoom level','lmm'),
			'desc'    => '',
			'std'     => '6',
			'type'    => 'text'
		);
		$this->_settings['mapbox3_attribution'] = array(
			'version' => '2.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section3',
			'title'   => __('Attribution','lmm'),
			'desc'    => __("For example","lmm"). ": Copyright ".date('Y')." &lt;a href=&quot;http://xy.com&quot;&gt;Provider X&lt;/a&gt;",
			'std'     => "MapBox, <a href=&quot;http://www.mapbox.com&quot; target=&quot;_blank&quot;>http://www.mapbox.com</a>",
			'type'    => 'text'
		);
		/*
		* Custom basemap 1 settings
		*/
		$this->_settings['custom_basemap_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom basemap', 'lmm').' (custom 1). ' . __('After finishing the basemap configuration, please navigate to Settings / "Maps Defaults" / "Available basemaps in control box" and check the corresponding checkbox to make this basemap available in the layer control box!','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-custom-basemaps.jpg" width="411" height="261" />',
			'type'    => 'helptext'
		);
		$this->_settings['custom_basemap_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __("For example","lmm"). ": http://tile.opencyclemap.org/cycle/{z}/{x}/{y}.png",
			'std'     => 'http://tile.opencyclemap.org/cycle/{z}/{x}/{y}.png',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => __("For example","lmm"). ": &copy; &lt;a href=&quot;http://openstreetmap.org/&quot;&gt;OpenStreetMap contributors&lt;/a&gt; CC-BY-SA",
			'std'	  => "&copy; <a href=&quot;http://openstreetmap.org/&quot; target=&quot;_blank&quot;>OpenStreetMap contributors</a>, <a href=&quot;http://creativecommons.org/licenses/by-sa/2.0/&quot; target=&quot;_blank&quot;>CC-BY-SA</a>",
			'type'    => 'text'
		);
		$this->_settings['custom_basemap_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '17',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap_tms'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['custom_basemap_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;a&quot;, &quot;b&quot;, &quot;c&quot;",
			'std'     => '&quot;a&quot;, &quot;b&quot;, &quot;c&quot;',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap_continuousworld_enabled'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __('Enable continuousWorld?','lmm'),
			'desc'    => __('If set to true, the tile coordinates will not be wrapped by world width (-180 to 180 longitude) or clamped to lie within world height (-90 to 90). Use this if you use Leaflet for maps that do not reflect the real world (e.g. game, indoor or photo maps).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap_nowrap_enabled'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __('Enable nowrap?','lmm'),
			'desc'    => __('If set to true, the tiles just will not load outside the world width (-180 to 180 longitude) instead of repeating.','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap_errortileurl'] = array(
			'version' => '3.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section4',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use basemaps produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		/*
		* Custom basemap 2 settings
		*/
		$this->_settings['custom_basemap2_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom basemap', 'lmm').' (custom 2). ' . __('After finishing the basemap configuration, please navigate to Settings / "Maps Defaults" / "Available basemaps in control box" and check the corresponding checkbox to make this basemap available in the layer control box!','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-custom-basemaps.jpg" width="411" height="261" />',
			'type'    => 'helptext'
		);
		$this->_settings['custom_basemap2_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __("For example","lmm"). ": http://tile.stamen.com/watercolor/{z}/{x}/{y}.jpg",
			'std'     => 'http://tile.stamen.com/watercolor/{z}/{x}/{y}.jpg',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap2_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => __("For example","lmm"). ":  Map tiles: &lt;a href=&quot;http://stamen.com&quot;&gt;Stamen Design&lt;/a&gt;, &lt;a href=&quot;http://creativecommons.org/licenses/by/3.0&quot;&gt;CC BY 3.0&lt;/a&gt;. Data: &lt;a href=&quot;http://openstreetmap.org&quot;&gt;OpenStreetMap&lt;/a&gt;, &lt;a href=&quot;http://creativecommons.org/licenses/by-sa/3.0&quot;&gt;CC BY SA&lt;/a&gt;",
			'std'     => "Map tiles: <a href=&quot;http://stamen.com&quot; target=&quot;_blank&quot;>Stamen Design</a>, <a href=&quot;http://creativecommons.org/licenses/by/3.0&quot; target=&quot;_blank&quot;>CC BY 3.0</a>. Data: <a href=&quot;http://openstreetmap.org&quot; target=&quot;_blank&quot;>OpenStreetMap</a>, <a href=&quot;http://creativecommons.org/licenses/by-sa/3.0&quot; target=&quot;_blank&quot;>CC BY SA</a>",
			'type'    => 'text'
		);
		$this->_settings['custom_basemap2_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap2_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '17',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap2_tms'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap2_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['custom_basemap2_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;a&quot;, &quot;b&quot;, &quot;c&quot;",
			'std'     => '&quot;a&quot;, &quot;b&quot;, &quot;c&quot;',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap2_continuousworld_enabled'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __('Enable continuousWorld?','lmm'),
			'desc'    => __('If set to true, the tile coordinates will not be wrapped by world width (-180 to 180 longitude) or clamped to lie within world height (-90 to 90). Use this if you use Leaflet for maps that do not reflect the real world (e.g. game, indoor or photo maps).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap2_nowrap_enabled'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __('Enable nowrap?','lmm'),
			'desc'    => __('If set to true, the tiles just will not load outside the world width (-180 to 180 longitude) instead of repeating.','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap2_errortileurl'] = array(
			'version' => '3.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section5',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use basemaps produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		/*
		* Custom basemap 3 settings
		*/
		$this->_settings['custom_basemap3_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom basemap', 'lmm').' (custom 3). ' . __('After finishing the basemap configuration, please navigate to Settings / "Maps Defaults" / "Available basemaps in control box" and check the corresponding checkbox to make this basemap available in the layer control box!','lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-default-basemap-custom-basemaps.jpg" width="411" height="261" />',
			'type'    => 'helptext'
		);
		$this->_settings['custom_basemap3_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __("For example","lmm"). ": http://{s}.tile2.opencyclemap.org/transport/{z}/{x}/{y}.png",
			'std'     => 'http://{s}.tile2.opencyclemap.org/transport/{z}/{x}/{y}.png',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap3_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => __("For example","lmm"). ": &copy Gravitystorm Ltd. &lt;a href=&quot;http://www.thunderforest.com&quot;&gt;Thunderforest&lt;/a&gt;",
			'std'     => "&copy; Gravitystorm Ltd. <a href=&quot;http://www.thunderforest.com&quot; target=&quot;_blank&quot;>Thunderforest</a>",
			'type'    => 'text'
		);
		$this->_settings['custom_basemap3_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap3_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '18',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap3_tms'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap3_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['custom_basemap3_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;a&quot;, &quot;b&quot;, &quot;c&quot;",
			'std'     => '&quot;a&quot;, &quot;b&quot;, &quot;c&quot;',
			'type'    => 'text'
		);
		$this->_settings['custom_basemap3_continuousworld_enabled'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __('Enable continuousWorld?','lmm'),
			'desc'    => __('If set to true, the tile coordinates will not be wrapped by world width (-180 to 180 longitude) or clamped to lie within world height (-90 to 90). Use this if you use Leaflet for maps that do not reflect the real world (e.g. game, indoor or photo maps).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap3_nowrap_enabled'] = array(
			'version' => '2.7.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __('Enable nowrap?','lmm'),
			'desc'    => __('If set to true, the tiles just will not load outside the world width (-180 to 180 longitude) instead of repeating.','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['custom_basemap3_errortileurl'] = array(
			'version' => '3.1',
			'pane'    => 'basemaps',
			'section' => 'basemaps-section6',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use basemaps produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);

		/*===========================================
		*
		*
		* pane overlays
		*
		*
		===========================================*/
		/*
		* Available overlays for new markers/layers
		*/
		$this->_settings['overlays_available_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select the overlays which should be available in the control box.', 'lmm').'<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-overlays-custom.jpg" width="500" height="162" />',
			'type'    => 'helptext'
		);
		$this->_settings['overlays_custom'] = array(
			'version' => '3.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section1',
			'title'    => __('Available overlays in control box','lmm'),
			'desc'    => __('Custom overlay','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['overlays_custom2'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section1',
			'title'   => '',
			'desc'    => __('Custom overlay 2','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->_settings['overlays_custom3'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section1',
			'title'   => '',
			'desc'    => __('Custom overlay 3','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->_settings['overlays_custom4'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section1',
			'title'   => '',
			'desc'    => __('Custom overlay 4','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* Custom overlay settings
		*/
		$this->_settings['overlays_custom_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom overlay', 'lmm').':<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-overlays-custom.jpg" width="500" height="162" />',
			'type'    => 'helptext'
		);
		$this->_settings['overlays_custom_name'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Name', 'lmm' ),
			'desc'   => __( 'Will be displayed in controlbox if selected', 'lmm' ),
			'std'     => __('OGD Vienna addresses','lmm'),
			'type'    => 'text'
		);

		$this->_settings['overlays_custom_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __('For example','lmm'). ": http://{s}.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png",
			'std'     => 'http://{s}.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom_attribution'] = array(
			'version' => '1.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => '',
			'std'     => 'Addresses: City of Vienna (<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>open.wien.at</a>)',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '19',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom_opacity'] = array(
			'version' => '2.7.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Opacity', 'lmm' ),
			'desc'    => __('The opacity of the tile layer.','lmm'),
			'std'     => '1.0',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom_tms'] = array(
			'version' => '3.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['overlays_custom_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['overlays_custom_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;",
			'std'     => '&quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom_errortileurl'] = array(
			'version' => '3.2',
			'pane'    => 'overlays',
			'section' => 'overlays-section2',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use overlays produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		/*
		* Custom overlay 2 settings
		*/
		$this->_settings['overlays_custom2_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom overlay', 'lmm').' 2:<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-overlays-custom.jpg" width="500" height="162" />',
			'type'    => 'helptext'
		);
		$this->_settings['overlays_custom2_name'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Name', 'lmm' ),
			'desc'   => __( 'Will be displayed in controlbox if selected', 'lmm' ),
			'std'     => 'Custom2',
			'type'    => 'text'
		);

		$this->_settings['overlays_custom2_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __('For example','lmm'). ": http://{s}.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png",
			'std'     => 'http://{s}.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom2_attribution'] = array(
			'version' => '1.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => '',
			'std'     => 'Addresses: City of Vienna (<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>open.wien.at</a>)',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom2_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom2_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '17',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom2_opacity'] = array(
			'version' => '2.7.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Opacity', 'lmm' ),
			'desc'    => __('The opacity of the tile layer.','lmm'),
			'std'     => '1.0',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom2_tms'] = array(
			'version' => '3.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['overlays_custom2_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['overlays_custom2_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;",
			'std'     => '&quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom2_errortileurl'] = array(
			'version' => '3.2',
			'pane'    => 'overlays',
			'section' => 'overlays-section3',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use overlays produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		/*
		* Custom overlay 3 settings
		*/
		$this->_settings['overlays_custom3_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom overlay', 'lmm').' 3:<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-overlays-custom.jpg" width="500" height="162" />',
			'type'    => 'helptext'
		);
		$this->_settings['overlays_custom3_name'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Name', 'lmm' ),
			'desc'   => __( 'Will be displayed in controlbox if selected', 'lmm' ),
			'std'     => 'Custom3',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __("For example","lmm"). ": http://maps.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png",
			'std'     => 'http://{s}.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_attribution'] = array(
			'version' => '1.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => '',
			'std'     => 'Addresses: City of Vienna (<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>open.wien.at</a>)',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '17',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_opacity'] = array(
			'version' => '2.7.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Opacity', 'lmm' ),
			'desc'    => __('The opacity of the tile layer.','lmm'),
			'std'     => '1.0',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_tms'] = array(
			'version' => '3.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['overlays_custom3_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['overlays_custom3_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;",
			'std'     => '&quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom3_errortileurl'] = array(
			'version' => '3.2',
			'pane'    => 'overlays',
			'section' => 'overlays-section4',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use overlays produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);
		/*
		* Custom overlay 4 settings
		*/
		$this->_settings['overlays_custom4_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please enter settings for custom overlay', 'lmm').' 4:<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-overlays-custom.jpg" width="500" height="162" />',
			'type'    => 'helptext'
		);
		$this->_settings['overlays_custom4_name'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Name', 'lmm' ),
			'desc'   => __( 'Will be displayed in controlbox if selected', 'lmm' ),
			'std'     => 'Custom4',
			'type'    => 'text'
		);

		$this->_settings['overlays_custom4_tileurl'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Tiles URL', 'lmm' ),
			'desc'    => __("For example","lmm"). ": http://maps.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png",
			'std'     => 'http://{s}.wien.gv.at/wmts/beschriftung/normal/google3857/{z}/{y}/{x}.png',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom4_attribution'] = array(
			'version' => '1.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Attribution', 'lmm' ),
			'desc'    => '',
			'std'     => 'Addresses: City of Vienna (<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>open.wien.at</a>)',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom4_minzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Minimum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '1',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom4_maxzoom'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Maximum zoom level', 'lmm' ),
			'desc'    => __('Note: maximum zoom level may vary on your basemap','lmm'),
			'std'     => '17',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom4_opacity'] = array(
			'version' => '2.7.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Opacity', 'lmm' ),
			'desc'    => __('The opacity of the tile layer.','lmm'),
			'std'     => '1.0',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom4_tms'] = array(
			'version' => '3.1',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => 'tms',
			'desc'    => __('If true, inverses Y axis numbering for tiles (turn this on for TMS services).','lmm'),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['overlays_custom4_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from tiles url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes (please enter subdomains in next form field)','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['overlays_custom4_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;",
			'std'     => '&quot;maps&quot;, &quot;maps1&quot;, &quot;maps2&quot;, &quot;maps3&quot;',
			'type'    => 'text'
		);
		$this->_settings['overlays_custom4_errortileurl'] = array(
			'version' => '3.2',
			'pane'    => 'overlays',
			'section' => 'overlays-section5',
			'title'   => __('Show errorTile-images if map could not be loaded?','lmm'),
			'desc'    => __('Set to false if you want to use overlays produced with maptiler for example','lmm'),
			'type'    => 'radio',
			'std'     => 'true',
			'choices' => array(
				'true' => __('true','lmm'),
				'false' => __('false','lmm')
			)
		);

		/*===========================================
		*
		*
		* pane wms
		*
		*
		===========================================*/
		/*
		* Available WMS layers for new markers/layers
		*/
		$this->_settings['wms_available_helptext2'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'std'     => '',
			'title'   => '',
			'desc'    => __('WMS stands for <a href="http://www.opengeospatial.org/standards/wms" target="_blank">Web Map Service</a> and is a standard protocol for serving georeferenced map images over the Internet that are generated by a map server using data from a GIS database.<br/>With Leaflet Maps Marker you can configure up to 10 WMS layers which can be enabled for each map. As default, 10 WMS layers from <a href="http://data.wien.gv.at" target="_blank">OGD Vienna</a> and from the <a href="http://www.eea.europa.eu/code/gis" target="_blank">European Environment Agency</a> have been predefined for you.<br/>A selection of further possible WMS layers can be found at <a href="http://www.mapsmarker.com/wms" target="_blank">http://www.mapsmarker.com/wms</a>', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_available_heading'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'   => '',
			'desc'    => '<a name="wms1" class="lmm-index-links"></a>' . __( 'Available WMS layers for new markers/layers', 'lmm'),
			'type'    => 'heading'
		);
		$this->_settings['wms_available_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select the WMS layers which should be available when creating new markers/layers', 'lmm').'<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-wms-available-wms-layers.jpg" width="724" height="245" />',
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => __('Available WMS layers','lmm'),
			'desc'    => 'WMS 1',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms2_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 2',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms3_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 3',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms4_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 4',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms5_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 5',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms6_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 6',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms7_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 7',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms8_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 8',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms9_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 9',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['wms_wms10_available'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections1',
			'title'    => '',
			'desc'    => 'WMS 10',
			'type'    => 'checkbox',
			'std'     => 1
		);
		/*
		* WMS layer settings
		*/
		$this->_settings['wms_wms_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>OGD Vienna - Public Toilets</a>'
		);
		$this->_settings['wms_wms_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'https://data.wien.gv.at/daten/wms'
		);
		$this->_settings['wms_wms_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => 'WCANLAGEOGD'
		);
		$this->_settings['wms_wms_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/gif'
		);
		$this->_settings['wms_wms_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.1.1'
		);
		$this->_settings['wms_wms_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: City of Vienna (<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>open.wien.gv.at</a>)'
		);
		$this->_settings['wms_wms_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'https://data.wien.gv.at/daten/geo?version=1.3.0&service=WMS&request=GetMap&crs=EPSG:4326&bbox=48.10,16.16,48.34,16.59&width=1&height=1&layers=ogdwien:WCANLAGEOGD&styles=&format=application/vnd.google-earth.kml+xml'
		);
		$this->_settings['wms_wms_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections2',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);

		/*
		* WMS layer 2 settings
		*/
		$this->_settings['wms_wms2_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms2_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>OGD Vienna - Elevators at stations</a>'
		);
		$this->_settings['wms_wms2_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'https://data.wien.gv.at/daten/wms'
		);
		$this->_settings['wms_wms2_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => 'AUFZUGOGD'
		);
		$this->_settings['wms_wms2_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms2_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/gif'
		);
		$this->_settings['wms_wms2_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms2_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.1.1'
		);
		$this->_settings['wms_wms2_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: City of Vienna (<a href=&quot;https://open.wien.at&quot; target=&quot;_blank&quot;>http://open.wien.at</a>)'
		);
		$this->_settings['wms_wms2_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms2_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms2_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms2_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms2_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms2_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms2_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'https://data.wien.gv.at/daten/geo?version=1.3.0&service=WMS&request=GetMap&crs=EPSG:4326&bbox=48.10,16.16,48.34,16.59&width=1&height=1&layers=ogdwien:AUFZUGOGD&styles=&format=application/vnd.google-earth.kml+xml'
		);
		$this->_settings['wms_wms2_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms2_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms2_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms2_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections3',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 3 settings
		*/
		$this->_settings['wms_wms3_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms3_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/ArcGIS/rest/services/Air/EPRTRDiffuseAir_Dyna_WGS84/MapServer/7&quot; target=&quot;_blank&quot;>EEA - CO emissions from road transport</a>'
		);
		$this->_settings['wms_wms3_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Air/EPRTRDiffuseAir_Dyna_WGS84/MapServer/WMSServer'
		);
		$this->_settings['wms_wms3_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '24'
		);
		$this->_settings['wms_wms3_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms3_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms3_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms3_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms3_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms3_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Air/EPRTRDiffuseAir_Dyna_WGS84/MapServer/WMSServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=1'
		);
		$this->_settings['wms_wms3_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms3_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms3_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms3_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms3_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms3_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/rest/services/Air/EPRTRDiffuseAir_Dyna_WGS84/MapServer/generatekml?docName=&l%3A7=on&layers=7&layerOptions=nonComposite'
		);
		$this->_settings['wms_wms3_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms3_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms3_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms3_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections4',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 4 settings
		*/
		$this->_settings['wms_wms4_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms4_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/ArcGIS/rest/services/Land/CLC2006_Dyna_WM/MapServer&quot; target=&quot;_blank&quot;>EEA - Agricultural areas</a>'
		);
		$this->_settings['wms_wms4_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Land/CLC2006_Dyna_WM/MapServer/WMSServer'
		);
		$this->_settings['wms_wms4_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '10'
		);
		$this->_settings['wms_wms4_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms4_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms4_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms4_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms4_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms4_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms4_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Land/CLC2000_Cach_WM/MapServer/WMSServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=11'
		);
		$this->_settings['wms_wms4_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms4_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms4_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms4_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms4_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/rest/services/Land/CLC2006_Dyna_WM/MapServer/generatekml?docName=&l%3A5=on&layers=5&layerOptions=nonComposite'
		);
		$this->_settings['wms_wms4_kml_refreshMode'] = array(
			'version' => '1.4.3',


			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms4_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms4_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms4_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections5',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 5 settings
		*/
		$this->_settings['wms_wms5_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms5_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/arcgis/rest/services/Land/LandscapeFragmentation_LAEA/MapServer&quot; target=&quot;_blank&quot;>EEA - Landscape fragmentation</a>'
		);
		$this->_settings['wms_wms5_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/arcgis/services/Land/LandscapeFragmentation_LAEA/MapServer/WmsServer?'
		);
		$this->_settings['wms_wms5_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '0'
		);
		$this->_settings['wms_wms5_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms5_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms5_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms5_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms5_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms5_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms5_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/arcgis/services/Land/LandscapeFragmentation_LAEA/MapServer/WmsServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=0'
		);
		$this->_settings['wms_wms5_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms5_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms5_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms5_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',

			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms5_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/rest/services/Noise/Noise_Dyna_LAEA/MapServer/generatekml?docName=&l%3A460=on&layers=460&layerOptions=nonComposite'
		);
		$this->_settings['wms_wms5_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms5_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms5_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms5_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections6',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 6 settings
		*/
		$this->_settings['wms_wms6_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms6_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/ArcGIS/rest/services/Land/CLC2006_Dyna_WM/MapServer&quot; target=&quot;_blank&quot;>EEA - WaterBodies</a>'
		);
		$this->_settings['wms_wms6_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Land/CLC2006_Dyna_WM/MapServer/WMSServer'
		);
		$this->_settings['wms_wms6_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '2'
		);
		$this->_settings['wms_wms6_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms6_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms6_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms6_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms6_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms6_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms6_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Land/CLC2006_Dyna_WM/MapServer/WMSServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=2'
		);
		$this->_settings['wms_wms6_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms6_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms6_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms6_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms6_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/rest/services/Land/CLC2006_Dyna_WM/MapServer/generatekml?docName=&l%3A14=on&layers=14&layerOptions=nonComposite'
		);
		$this->_settings['wms_wms6_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms6_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms6_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms6_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections7',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 7 settings
		*/
		$this->_settings['wms_wms7_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms7_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/ArcGIS/rest/services/Water/RiverAndLakes_Dyna_WM/MapServer&quot; target=&quot;_blank&quot;>EEA - Mean annual nitrates in rivers 2008</a>'
		);
		$this->_settings['wms_wms7_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Water/RiverAndLakes_Dyna_WM/MapServer/WMSServer'
		);
		$this->_settings['wms_wms7_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '14'
		);
		$this->_settings['wms_wms7_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms7_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms7_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms7_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms7_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms7_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms7_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Water/RiverAndLakes_Dyna_WM/MapServer/WMSServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=14'
		);
		$this->_settings['wms_wms7_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms7_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms7_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms7_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms7_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/rest/services/Water/RiverAndLakes_Dyna_WM/MapServer/generatekml?docName=&l%3A9=on&layers=9&layerOptions=nonComposite'
		);
		$this->_settings['wms_wms7_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms7_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms7_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms7_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections8',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 8 settings
		*/
		$this->_settings['wms_wms8_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms8_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/ArcGIS/rest/services/Reports2010/Reports2008_Dyna_WGS84/MapServer&quot; target=&quot;_blank&quot;>EEA - Temperature Change</a>'
		);
		$this->_settings['wms_wms8_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/services/Reports2010/Reports2008_Dyna_WGS84/MapServer/WMSServer'
		);
		$this->_settings['wms_wms8_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '5'
		);
		$this->_settings['wms_wms8_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms8_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms8_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms8_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms8_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms8_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms8_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://cow6/ArcGIS/services/Reports2010/Reports2008_Dyna_WGS84/MapServer/WMSServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=5'
		);
		$this->_settings['wms_wms8_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms8_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms8_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms8_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',

			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms8_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/ArcGIS/rest/services/Reports2010/Reports2008_Dyna_WGS84/MapServer/generatekml?docName=&l%3A26=on&layers=26&layerOptions=nonComposite'
		);
		$this->_settings['wms_wms8_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms8_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms8_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms8_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections9',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 9 settings
		*/
		$this->_settings['wms_wms9_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms9_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/arcgis/rest/services/Land/UrbanAtlas_LAEA/MapServer&quot; target=&quot;_blank&quot;>EEA - Urban Outlines</a>'
		);
		$this->_settings['wms_wms9_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/arcgis/services/Land/UrbanAtlas_LAEA/MapServer/WmsServer?'
		);
		$this->_settings['wms_wms9_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '4'
		);
		$this->_settings['wms_wms9_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms9_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms9_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms9_version'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms9_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms9_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms9_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/arcgis/services/Land/UrbanAtlas_LAEA/MapServer/WmsServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=4'
		);
		$this->_settings['wms_wms9_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms9_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms9_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms9_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms9_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms9_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms9_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms9_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms9_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections10',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		/*
		* WMS layer 10 settings
		*/
		$this->_settings['wms_wms10_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms10_name'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Name','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => '<a href=&quot;http://discomap.eea.europa.eu/arcgis/rest/services/Bio/Article17_Dyna_WM/MapServer&quot; target=&quot;_blank&quot;>EEA - Bieogeographical Regions</a>'
		);
		$this->_settings['wms_wms10_baseurl'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('baseURL','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/arcgis/services/Bio/Article17_Dyna_WM/MapServer/WmsServer?'
		);
		$this->_settings['wms_wms10_layers'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Layers','lmm'),
			'desc'    => __('(required) Comma-separated list of WMS layers to show','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);
		$this->_settings['wms_wms10_styles'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Styles','lmm'),
			'desc'    => __('Comma-separated list of WMS styles','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms10_format'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Format','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'image/png'
		);
		$this->_settings['wms_wms10_transparent'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => __('Transparent','lmm'),
			'desc'    => __('If yes, the WMS service will return images with transparency','lmm'),
			'type'    => 'radio',
			'std'     => 'TRUE',
			'choices' => array(
				'TRUE' => __('true','lmm'),
				'FALSE' => __('false','lmm')
			)
		);
		$this->_settings['wms_wms10_version'] = array(

			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Version','lmm'),
			'desc'    => __('Version of the WMS service to use','lmm'),
			'type'    => 'text',
			'std'     => '1.3.0'
		);
		$this->_settings['wms_wms10_attribution'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Attribution','lmm'),
			'desc'    => '',
			'type'    => 'text',
			'std'     => 'WMS: <a href=&quot;http://www.eea.europa.eu/code/gis&quot; target=&quot;_blank&quot;>European Environment Agency</a>'
		);
		$this->_settings['wms_wms10_legend_enabled'] = array(
			'version' => '1.1',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => __('Display legend?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('Yes','lmm'),
				'no' => __('No','lmm')
			)
		);
		$this->_settings['wms_wms10_legend'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'    => __('Legend','lmm'),
			'desc'    => __('URL of image which gets show when hovering the text "(Legend)" next to WMS attribution text','lmm'),
			'type'    => 'text',
			'std'     => 'http://discomap.eea.europa.eu/arcgis/services/Bio/Article17_Dyna_WM/MapServer/WmsServer?request=GetLegendGraphic%26version=1.3.0%26format=image/png%26layer=2'
		);
		$this->_settings['wms_wms10_subdomains_enabled'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => __('Support for subdomains?','lmm'),
			'desc'    => __('Will replace {s} from base url if available','lmm'),
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'no' => __('No','lmm'),
				'yes' => __('Yes (please enter subdomains in next form field)','lmm')
			)
		);
		$this->_settings['wms_wms10_subdomains_names'] = array(
			'version' => '1.0',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => __( 'Subdomain names', 'lmm' ),
			'desc'    => __('For example','lmm'). ": &quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;",
			'std'     => '&quot;subdomain1&quot;, &quot;subdomain2&quot;, &quot;subdomain3&quot;',
			'type'    => 'text'
		);
		$this->_settings['wms_wms10_kml_helptext'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'std'     => '',
			'title'   => '<strong>' . __('KML settings','lmm') . '</strong>',
			'desc'    => __('If the WMS server supports KML output of the WMS layer, the settings below will be used when a marker or layer map with this active WMS layer is exported as KML.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['wms_wms10_kml_support'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => __('Does the WMS server support KML output?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'no',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')
			)
		);
		$this->_settings['wms_wms10_kml_href'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#href" target="_blank">href</a>',
			'desc'    => __('http-address of the KML-webservice of the WMS layer','lmm'),
			'type'    => 'text',
			'std'     => ''
		);
		$this->_settings['wms_wms10_kml_refreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshmode" target="_blank">refreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'onChange',
			'choices' => array(
				'onChange' => __('onChange (refresh when the file is loaded and whenever the Link parameters change)','lmm'),
				'onInterval' => __('onInterval (refresh every n seconds (specified in refreshInterval)','lmm'),
				'onExpire' => __('onExpire (refresh the file when the expiration time is reached)','lmm'),
				'onStop' => __('onStop (after camera movement stops)','lmm')
			)
		);
		$this->_settings['wms_wms10_kml_refreshInterval'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#refreshinterval" target="_blank">refreshInterval</a>',
			'desc'    => __('Indicates to refresh the file every n seconds','lmm'),
			'type'    => 'text',
			'std'     => '30'
		);
		$this->_settings['wms_wms10_kml_viewRefreshMode'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshmode" target="_blank">viewrefreshMode</a>',
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'never',
			'choices' => array(
				'never' => __('never (ignore changes in the view)','lmm'),
				'onStop' => __('onStop (refresh the file n seconds after movement stops, where n is specified in viewRefreshTime)','lmm'),
				'onRequest' => __('onRequest (refresh the file only when the user explicitly requests it)','lmm')
			)
		);
		$this->_settings['wms_wms10_kml_viewRefreshTime'] = array(
			'version' => '1.4.3',
			'pane'    => 'wms',
			'section' => 'wms-sections11',
			'title'   => '<a href="http://code.google.com/apis/kml/documentation/kmlreference.html#viewrefreshtime" target="_blank">viewRefreshTime</a>',
			'desc'    => __('After camera movement stops, specifies the number of seconds to wait before refreshing the view (is used when viewRefreshMode is set to onStop)','lmm'),
			'type'    => 'text',
			'std'     => '1'
		);

		/*===========================================
		*
		*
		* pane Google
		*
		*
		===========================================*/
		/*
		* Google Maps API Key
		*/
		$this->_settings['google_maps_api_key_helptext'] = array(
			'version' => '2.6',
			'pane'    => 'google',
			'section' => 'google-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The usage of Google Maps is free for non-commercial users. Since 01/2012, commercial users have a current usage limit of 25.000 free requests a day - with additional usage cost of 0.5$/1000 requests. In order to comply with the <a href="https://developers.google.com/maps/faq" target="_blank">Google Maps terms of services</a>, commercial users have to <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key">register for a free API key</a>. This API key can also be used by non-commercial users in order to monitor their Google Maps API usage.', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['google_maps_api_status'] = array(
			'version' => 'p1.6',
			'pane'    => 'google',
			'section' => 'google-section1',
			'title'   => __('Google Maps API status','lmm') . $pro_button_link,
			'desc'    => __('Disabling the Google Maps API will prevent loading scripts from google.com on frontend and will result in higher performance if alternative basemaps are going to be used only. Existing maps using Google basemaps will switch to OpenStreetMap automatically if this setting is disabled!','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['google_maps_api_key'] = array(
			'version' => '2.6',
			'pane'    => 'google',
			'section' => 'google-section1',
			'title'   => __( 'Google Maps API key', 'lmm'),
			'desc'    => __( 'Please enter your Google Maps API key here', 'lmm' ),
			'std'     => '',
			'type'    => 'text'
		);
		/*
		* Google language localization
		* https://spreadsheets.google.com/spreadsheet/pub?key=0Ah0xU81penP1cDlwZHdzYWkyaERNc0xrWHNvTTA1S1E&gid=1
		*/
		$this->_settings['google_maps_language_localization_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'google',
			'section' => 'google-section2',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Language used when displaying textual information such as the names for controls, copyright notices, driving directions and labels on Google maps, direction links and autocomplete for address search.', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['google_maps_language_localization'] = array(
			'version' => '2.7.1',
			'pane'    => 'google',
			'section' => 'google-section2',
			'title'   => __('Default language','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'browser_setting',
			'choices' => array(
				'browser_setting' => __('automatic 1 (distinct language for each user - detects the users browser language setting, preferred method by Google)','lmm'),
				'wordpress_setting' => sprintf(__('automatic 2 (same language for each user - using the global variable $locale = %s)','lmm'), '<strong>' . substr(get_locale(),0,2) . '</strong>'),
				'ar' => __('Arabic','lmm') . ' (' . __('language code','lmm') . ': ar)',
				'bg' => __('Bulgarian','lmm') . ' (' . __('language code','lmm') . ': bg)',
				'ca' => __('Catalan','lmm') . ' (' . __('language code','lmm') . ': ca)',
				'cs' => __('Czech','lmm') . ' (' . __('language code','lmm') . ': cs)',
				'da' => __('Danish','lmm') . ' (' . __('language code','lmm') . ': da)',
				'de' => __('German','lmm') . ' (' . __('language code','lmm') . ': de)',
				'el' => __('Greek','lmm') . ' (' . __('language code','lmm') . ': el)',
				'en' => __('English','lmm') . ' (' . __('language code','lmm') . ': en)',
				'en-AU' => __('English (Australian)','lmm') . ' (' . __('language code','lmm') . ': en-AU)',
				'en-GB' => __('English (Great Britain)','lmm') . ' (' . __('language code','lmm') . ': en-GB)',
				'es' => __('Spanish','lmm') . ' (' . __('language code','lmm') . ': es)',
				'eu' => __('Basque','lmm') . ' (' . __('language code','lmm') . ': eu)',
				'fa' => __('Farsi','lmm') . ' (' . __('language code','lmm') . ': fa)',
				'fi' => __('Finnish','lmm') . ' (' . __('language code','lmm') . ': fi)',
				'fil' => __('Filipino','lmm') . ' (' . __('language code','lmm') . ': fil)',
				'fr' => __('French','lmm') . ' (' . __('language code','lmm') . ': fr)',
				'gl' => __('Galician','lmm') . ' (' . __('language code','lmm') . ': gl)',
				'gu' => __('Gujarati','lmm') . ' (' . __('language code','lmm') . ': gu)',
				'hi' => __('Hindi','lmm') . ' (' . __('language code','lmm') . ': hi)',
				'hr' => __('Croatian','lmm') . ' (' . __('language code','lmm') . ': hr)',
				'hu' => __('Hungarian','lmm') . ' (' . __('language code','lmm') . ': hu)',
				'id' => __('Indonesian','lmm') . ' (' . __('language code','lmm') . ': id)',
				'it' => __('Italian','lmm') . ' (' . __('language code','lmm') . ': it)',
				'iw' => __('Hebrew','lmm') . ' (' . __('language code','lmm') . ': iw)',
				'ja' => __('Japanese','lmm') . ' (' . __('language code','lmm') . ': ja)',
				'kn' => __('Kannada','lmm') . ' (' . __('language code','lmm') . ': kn)',
				'ko' => __('Korean','lmm') . ' (' . __('language code','lmm') . ': ko)',
				'lt' => __('Lithuanian','lmm') . ' (' . __('language code','lmm') . ': lt)',
				'lv' => __('Latvian','lmm') . ' (' . __('language code','lmm') . ': lv)',
				'ml' => __('Malayalam','lmm') . ' (' . __('language code','lmm') . ': ml)',
				'mr' => __('Marathi','lmm') . ' (' . __('language code','lmm') . ': mr)',
				'nl' => __('Dutch','lmm') . ' (' . __('language code','lmm') . ': nl)',
				'no' => __('Norwegian','lmm') . ' (' . __('language code','lmm') . ': no)',
				'pl' => __('Polish','lmm') . ' (' . __('language code','lmm') . ': pl)',
				'pt' => __('Portuguese','lmm') . ' (' . __('language code','lmm') . ': pt)',
				'pt-BR' => __('Portuguese (Brazil)','lmm') . ' (' . __('language code','lmm') . ': pt-BR)',
				'pt-PT' => __('Portuguese (Portugal)','lmm') . ' (' . __('language code','lmm') . ': pt-PT)',
				'ro' => __('Romanian','lmm') . ' (' . __('language code','lmm') . ': ro)',
				'ru' => __('Russian','lmm') . ' (' . __('language code','lmm') . ': ru)',
				'sk' => __('Slovak','lmm') . ' (' . __('language code','lmm') . ': sk)',
				'sl' => __('Slovenian','lmm') . ' (' . __('language code','lmm') . ': sl)',
				'sr' => __('Serbian','lmm') . ' (' . __('language code','lmm') . ': sr)',
				'sv' => __('Swedish','lmm') . ' (' . __('language code','lmm') . ': sv)',
				'tl' => __('Tagalog','lmm') . ' (' . __('language code','lmm') . ': tl)',
				'ta' => __('Tamil','lmm') . ' (' . __('language code','lmm') . ': ta)',
				'te' => __('Telugu','lmm') . ' (' . __('language code','lmm') . ': te)',
				'th' => __('Thai','lmm') . ' (' . __('language code','lmm') . ': th)',
				'uk' => __('Ukrainian','lmm') . ' (' . __('language code','lmm') . ': uk)',
				'vi' => __('Vietnamese','lmm') . ' (' . __('language code','lmm') . ': vi)',
				'zh-CN' => __('Chinese (simplified)','lmm') . ' (' . __('language code','lmm') . ': zh-CN)',
				'zh-TW' => __('Chinese (traditional)','lmm') . ' (' . __('language code','lmm') . ': zh-TW)',
			)
		);
		/*
		* Google Maps base domain
		*/
		$this->_settings['google_maps_base_domain_helptext'] = array(
			'version' => '2.7.1',
			'pane'    => 'google',
			'section' => 'google-section3',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The base domain from which to load the Google Maps API (used for geocoding for example). If you want to change the language of the Google Maps interface (buttons etc) only, please change the option "Google language localization" above.', 'lmm') . ' ' . __('This base domain is also used for Google Maps Directions.','lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['google_maps_base_domain'] = array(
			'version' => '2.7.1',
			'pane'    => 'google',
			'section' => 'google-section3',
			'title'   => __('Google Maps base domain','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'maps.google.com',
			'choices' => array(
				'maps.google.com' => 'maps.google.com',
				'maps.google.at' => 'maps.google.at',
				'maps.google.com.au' => 'maps.google.com.au',
				'maps.google.com.ba' => 'maps.google.com.ba',
				'maps.google.be' => 'maps.google.be',
				'maps.google.bg' => 'maps.google.bg',
				'maps.google.com.br' => 'maps.google.com.br',
				'maps.google.ca' => 'maps.google.ca',
				'maps.google.ch' => 'maps.google.ch',
				'maps.google.cm' => 'maps.google.cm',
				'ditu.google.cn' => 'ditu.google.cn',
				'maps.google.cz' => 'maps.google.cz',
				'maps.google.de' => 'maps.google.de',
				'maps.google.dk' => 'maps.google.dk',
				'maps.google.es' => 'maps.google.es',
				'maps.google.fi' => 'maps.google.fi',
				'maps.google.fr' => 'maps.google.fr',
				'maps.google.it' => 'maps.google.it',
				'maps.google.lk' => 'maps.google.lk',
				'maps.google.jp' => 'maps.google.jp',
				'maps.google.nl' => 'maps.google.nl',
				'maps.google.no' => 'maps.google.no',
				'maps.google.co.nz' => 'maps.google.co.nz',
				'maps.google.pl' => 'maps.google.pl',
				'maps.google.ru' => 'maps.google.ru',
				'maps.google.se' => 'maps.google.se',
				'maps.google.tw' => 'maps.google.tw',
				'maps.google.co.uk' => 'maps.google.co.uk',
				'maps.google.co.ve' => 'maps.google.co.ve'
			)
		);
		$this->_settings['google_maps_base_domain_custom'] = array(
			'version' => '2.7.1',
			'pane'    => 'google',
			'section' => 'google-section3',
			'title'   => __( 'Custom base domain', 'lmm'),
			'desc'    => __( 'If your localized Google Maps basedomain is not available in the list above, please enter the domain name here (without http://, for example maps.google.com). If a domain name is entered, the setting "Google Maps base domain" from above gets overwritten.', 'lmm' ),
			'std'     => '',
			'type'    => 'text'
		);
		/*
		* Google Places Bounds
		*/
		$this->_settings['google_places_bounds_helptext2'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The integration of the <a href="https://developers.google.com/places/documentation/autocomplete" target="_blank">Google Places Autocomplete API</a> on backend allows you to easily find coordinates for places or addresses:', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-google-places-preview.png" width="640" height="126" style="border:1px solid #ccc;" />',
			'type'    => 'helptext'
		);
		$this->_settings['google_places_status'] = array(
			'version' => 'p1.8',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __('Google Places status','lmm') . $pro_button_link,
			'desc'    => __('Disabling Google Places will also stop all Google API calls on backend and is only recommended if e.g. access to Google API services is blocked in your country!','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);		
		$this->_settings['google_places_bounds_status'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __('Google Places bounds','lmm'),
			'desc'    => __( 'You can get better search results if you enable the bounds feature. This allows you to specify the area in which to primarily search for places or addresses. Please note: the results are biased towards, but not restricted to places or addresses contained within these bounds.', 'lmm') . __( 'If enabled, please enter longitude and latitude values below for the corner points of the prefered search area. Below you find an example for Vienna/Austria:', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-google-places-bounds.jpg" width="425" height="334" />',
			'type'    => 'radio',
			'std'     => 'disabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['google_places_bounds_lat1'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __( 'Latitude', 'lmm' ) . ' 1',
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '48.326583',
			'type'    => 'text'
		);
		$this->_settings['google_places_bounds_lon1'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __( 'Longitude', 'lmm' ) . ' 1',
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '16.55056',
			'type'    => 'text'
		);
		$this->_settings['google_places_bounds_lat2'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __( 'Latitude', 'lmm' ) . ' 2',
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '48.114308',
			'type'    => 'text'
		);
		$this->_settings['google_places_bounds_lon2'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __( 'Longitude', 'lmm' ) . ' 2',
			'desc'    => __( 'Please use a dot instead of a coma as decimal delimiter!', 'lmm' ),
			'std'     => '16.187325',
			'type'    => 'text'
		);
		/*
		* Google Places Search Prefix
		*/
		$this->_settings['google_places_search_prefix_helptext1'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'You can also select a search prefix, which automatically gets added to search form when creating a new marker or layer.', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-google-places-prefix.png" width="630" height="55" style="border:1px solid #ccc;" />',
			'type'    => 'helptext'
		);
		$this->_settings['google_places_search_prefix_status'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __('Google Places search prefix','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'disabled',
			'choices' => array(
				'disabled' => __('disabled','lmm'),
				'enabled' => __('enabled','lmm')
			)
		);
		$this->_settings['google_places_search_prefix'] = array(
			'version' => '1.0',
			'pane'    => 'google',
			'section' => 'google-section4',
			'title'   => __( 'Prefix to use', 'lmm' ),
			'desc'    => '',
			'std'     => 'Wien, ',
			'type'    => 'text'
		);
		/*
		* Google Adsense Settings
		*/
		$this->_settings['google_adsense_helptext1'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Use the settings below to customize the display of ads on Google basemaps.', 'lmm') . '<br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-adsense.jpg" width="625" height="67" /><br/><br/><a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a><br/><span style="font-weight:bold;color:red;">' . sprintf(__( 'Attention: please be aware that although the plugin has been designed to meet the <a href="%1s" target="_blank">Google AdSense programme policies</a>, finally it is your responsibility to verify that your maps meet the Adsense requirements (as this is heavily depended on how you have configured your maps). For example it is advised to double check the position of the ads in order that they are not being overlayed by map controls (which is not allowed by Google and could result in sanctions like the cancellation of your Adsense publisher account)!', 'lmm'), 'https://support.google.com/adsense/answer/48182') . '</span>',
			'type'    => 'helptext'
		);
		$this->_settings['google_adsense_status'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'Google Adsense<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Please set to disabled if you do not want to display ads on Google basemaps','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['google_adsense_publisherId'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'publisherId<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => sprintf(__('Adding display ads to your map requires that you have an AdSense account enabled for AdSense for Content. If you do not yet have an AdSense account, <a href="%1s" target="_blank">sign up for one</a>. Once you have done so (or if you already have an account) make sure you have also enabled the account with <a href="%2s" target="_blank">AdSense for Content</a>. Once you have an Adsense for Content account, you will have received an AdSense for Content (AFC) publisher ID. This publisher ID is used within your code to link any advertising shown to your AdSense account, allowing you to share in advertising revenue when a user clicks on one of the ads shown on your map.','lmm'), 'https://www.google.com/adsense/support/bin/answer.py?answer=10162', 'https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=17470'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['google_adsense_format'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => __('Format','lmm') . $pro_button_link,
			'desc'    => sprintf(__('Display formats of type google.maps.adsense.AdFormat, both text ads and link units are supported. Please see %1s for more details and examples','lmm'),'<a href="https://support.google.com/adsense/bin/answer.py?hl=de&utm_medium=link&utm_campaign=ww-ww-et-asfe_&utm_source=aso&answer=185665" target="_blank">https://support.google.com/adsense/...</a>'),
			'type'    => 'radio-pro',
			'std'     => 'HALF_BANNER',
			'choices' => array(
				'LEADERBOARD' => 'LEADERBOARD',
				'BANNER' => 'BANNER',
				'HALF_BANNER' => 'HALF_BANNER',
				'SKYSCRAPER' => 'SKYSCRAPER',
				'WIDE_SKYSCRAPER' => 'WIDE_SKYSCRAPER',
				'VERTICAL_BANNER' => 'VERTICAL_BANNER',
				'BUTTON' => 'BUTTON',
				'SMALL_SQUARE' => 'SMALL_SQUARE',
				'SQUARE' => 'SQUARE',
				'SMALL_RECTANGLE' => 'SMALL_RECTANGLE',
				'MEDIUM_RECTANGLE' => 'MEDIUM_RECTANGLE',
				'LARGE_RECTANGLE' => 'LARGE_RECTANGLE',
				'SMALL_VERTICAL_LINK_UNIT' => 'SMALL_VERTICAL_LINK_UNIT',
				'MEDIUM_VERTICAL_LINK_UNIT' => 'MEDIUM_VERTICAL_LINK_UNIT',
				'LARGE_VERTICAL_LINK_UNIT' => 'LARGE_VERTICAL_LINK_UNIT',
				'X_LARGE_VERTICAL_LINK_UNIT' => 'X_LARGE_VERTICAL_LINK_UNIT',
				'SMALL_HORIZONTAL_LINK_UNIT' => 'SMALL_HORIZONTAL_LINK_UNIT',
				'LARGE_HORIZONTAL_LINK_UNIT' => 'LARGE_HORIZONTAL_LINK_UNIT'
			)
		);
		$this->_settings['google_adsense_position'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => __('Position','lmm') . $pro_button_link,
			'desc'    => sprintf(__('<a href="%1s" target="_blank">click here for more information on ad positions which might cause conflicts with Google AdSense programme policies</a>','lmm'), 'https://www.mapsmarker.com/docs/pro-version-docs/how-to-configure-google-adsense-in-order-not-to-violate-google-adsense-programme-policies/') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-google-adsense-positions.jpg" width="640" height="480" />',
			'type'    => 'radio-pro',
			'std'     => 'TOP_CENTER',
			'choices' => array(
				'TOP_CENTER' => 'TOP_CENTER',
				'TOP_LEFT' => 'TOP_LEFT' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>',
				'TOP_RIGHT' => 'TOP_RIGHT' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>',
				'LEFT_TOP' => 'LEFT_TOP' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>',
				'RIGHT_TOP' => 'RIGHT_TOP' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>',
				'LEFT_CENTER' => 'LEFT_CENTER',
				'RIGHT_CENTER' => 'RIGHT_CENTER',
				'LEFT_BOTTOM' => 'LEFT_BOTTOM',
				'RIGHT_BOTTOM' => 'RIGHT_BOTTOM' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>',
				'BOTTOM_LEFT' => 'BOTTOM_LEFT' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>',
				'BOTTOM_CENTER' => 'BOTTOM_CENTER',
				'BOTTOM_RIGHT' => 'BOTTOM_RIGHT' . ' <span style="font-weight:bold;color:red;">(' . __('likely to violate Google AdSense programme policies with default configuration!','lmm') . ')</span>'
			)
		);
		$this->_settings['google_adsense_backgroundColor'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'backgroundColor<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Ad unit background color','lmm'),
			'std'     => '#c4d4f3',
			'type'    => 'text-pro'
		);
		$this->_settings['google_adsense_borderColor'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'borderColor<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Ad unit border color','lmm'),
			'std'     => '#e5ecf9',
			'type'    => 'text-pro'
		);
		$this->_settings['google_adsense_titleColor'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'titleColor<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Ad title link color','lmm'),
			'std'     => '#0000cc',
			'type'    => 'text-pro'
		);
		$this->_settings['google_adsense_textColor'] = array(
			'version' => 'p1.0',

			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'textColor<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Ad creative text color','lmm'),
			'std'     => '#000000',
			'type'    => 'text-pro'
		);
		$this->_settings['google_adsense_urlColor'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'urlColor<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('Ad attribution URL link color','lmm'),
			'std'     => '#009900',
			'type'    => 'text-pro'
		);
		$this->_settings['google_adsense_channelNumber'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section5',
			'title'   => 'channelNumber<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('The AdSense For Content channel number for tracking the performance of this AdUnit. It must be stored as a string as it will typically be a large UINT64.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		/*
		* Google Maps styling
		*/
		$this->_settings['google_styling_helptext1'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section6',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas.', 'lmm') . '<br/><a href="http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/examplestyles.html" target="_blank" title="' . esc_attr__('show examples','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-google-styling-preview.jpg" width="650" height="401" /></a><a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>',
			'type'    => 'helptext'
		);
		$this->_settings['google_styling_json'] = array(
			'version' => 'p1.0',
			'pane'    => 'google',
			'section' => 'google-section6',
			'title'   => 'JSON<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => sprintf(__('Please enter the custom JSON array to style your Google maps (you can use the <a href="%1s" target="_blank">Google Styled Maps Wizard</a> to create custom styles easily). Example for hiding roads:','lmm'), 'http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html') . ' <br/><strong>[ { &#39;featureType&#39;: &#39;road.highway&#39;, &#39;elementType&#39;: &#39;geometry&#39;, &#39;stylers&#39;: [ { &#39;visibility&#39;: &#39;off&#39; } ] },{ &#39;featureType&#39;: &#39;road.arterial&#39;, &#39;stylers&#39;: [ { &#39;visibility&#39;: &#39;off&#39; } ] },{ &#39;featureType&#39;: &#39;road.local&#39;, &#39;stylers&#39;: [ { &#39;visibility&#39;: &#39;off&#39; } ] } ]</strong>',
			'std'     => '',
			'type'    => 'text-pro'
		);
		/*===========================================
		*
		*
		* pane Bing
		*
		*
		===========================================*/
		/*
		* Bing Maps API Key
		*/
		$this->_settings['bingmaps_api_key_helptext'] = array(
			'version' => '2.6',
			'pane'    => 'bing',
			'section' => 'bing-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'An API key is required if you want to use Bing Maps as basemap for marker or layer maps. Please click on the question mark for more info on how to get your API key.', 'lmm') . ' <a href="https://www.mapsmarker.com/bing-maps" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'helptext'
		);
		$this->_settings['bingmaps_api_key'] = array(
			'version' => '2.6',
			'pane'    => 'bing',
			'section' => 'bing-section1',
			'title'   => __( 'Bing Maps API key', 'lmm' ),
			'desc'    => '',
			'std'     => '',
			'type'    => 'text'
		);
		/*
		* Bing culture parameter
		* http://msdn.microsoft.com/en-us/library/hh441729.aspx
		*/
		$this->_settings['bingmaps_culture_helptext'] = array(
			'version' => '2.9',
			'pane'    => 'bing',
			'section' => 'bing-section2',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'The culture parameter allows you to select the language of the culture for geographic entities, place names and map labels on bing map images. For supported cultures, street names are localized to the local culture. For example, if you request a location in France, the street names are localized in French. For other localized data such as country names, the level of localization will vary for each culture. For example, there may not be a localized name for the "United States" for every culture code. See <a href="http://msdn.microsoft.com/en-us/library/hh441729.aspx" target="_blank">this page</a> for more details.', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['bingmaps_culture'] = array(
			'version' => '2.9',
			'pane'    => 'bing',
			'section' => 'bing-section2',
			'title'   => __('Default culture','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'automatic',
			'choices' => array(
				'automatic' => sprintf(__('automatic (using the global variable $locale = %s - fallback to en-US if not supported by bing)','lmm'),'<strong>' . str_replace("_","-", get_locale()) . '</strong>'),
				'af' => __('Afrikaans','lmm') . ' (' . __('culture code','lmm') . ': af)',
				'am' => __('Amharic','lmm') . ' (' . __('culture code','lmm') . ': am)',
				'ar-sa' => __('Arabic (Saudi Arabia)','lmm') . ' (' . __('culture code','lmm') . ': ar-sa)',
				'as' => __('Assamese','lmm') . ' (' . __('culture code','lmm') . ': as)',
				'az-Latn' => __('Azerbaijani (Latin)','lmm') . ' (' . __('culture code','lmm') . ': az-Latn)',
				'be' => __('Belarusian','lmm') . ' (' . __('culture code','lmm') . ': be)',
				'bg' => __('Bulgarian','lmm') . ' (' . __('culture code','lmm') . ': bg)',
				'bn-BD' => __('Bangla (Bangladesh)','lmm') . ' (' . __('culture code','lmm') . ': bn-BD)',
				'bn-IN' => __('Bangla (India)','lmm') . ' (' . __('culture code','lmm') . ': bn-IN)',
				'bs' => __('Bosnian (Latin)','lmm') . ' (' . __('culture code','lmm') . ': bs)',
				'ca' => __('Catalan Spanish','lmm') . ' (' . __('culture code','lmm') . ': ca)',
				'ca-ES-valencia' => __('Valencian','lmm') . ' (' . __('culture code','lmm') . ': ca-ES-valencia)',
				'cs' => __('Czech','lmm') . ' (' . __('culture code','lmm') . ': cs)',
				'cy' => __('Welsh','lmm') . ' (' . __('culture code','lmm') . ': cy)',
				'da' => __('Danish','lmm') . ' (' . __('culture code','lmm') . ': da)',
				'de' => __('German (Germany)','lmm') . ' (' . __('culture code','lmm') . ': de)',
				'de-de' => __('German (Germany)','lmm') . ' (' . __('culture code','lmm') . ': de-de)',
				'el' => __('Greek','lmm') . ' (' . __('culture code','lmm') . ': el)',
				'en-GB' => __('English (United Kingdom)','lmm') . ' (' . __('culture code','lmm') . ': en-GB)',
				'en-US' => __('English (United States)','lmm') . ' (' . __('culture code','lmm') . ': en-US)',
				'es' => __('Spanish (Spain)','lmm') . ' (' . __('culture code','lmm') . ': es)',
				'es-ES' => __('Spanish (Spain)','lmm') . ' (' . __('culture code','lmm') . ': es-ES)',
				'es-US' => __('Spanish (United States)','lmm') . ' (' . __('culture code','lmm') . ': es-US)',
				'es-MX' => __('Spanish (Mexico)','lmm') . ' (' . __('culture code','lmm') . ': es-MX)',
				'et' => __('Estonian','lmm') . ' (' . __('culture code','lmm') . ': et)',
				'eu' => __('Basque','lmm') . ' (' . __('culture code','lmm') . ': eu)',
				'fa' => __('Persian','lmm') . ' (' . __('culture code','lmm') . ': fa)',
				'fi' => __('Finnish','lmm') . ' (' . __('culture code','lmm') . ': fi)',
				'fil-Latn' => __('Filipino','lmm') . ' (' . __('culture code','lmm') . ': fil-Latn)',
				'fr' => __('French (France)','lmm') . ' (' . __('culture code','lmm') . ': fr)',
				'fr-FR' => __('French (France)','lmm') . ' (' . __('culture code','lmm') . ': fr-FR)',
				'fr-CA' => __('French (Canada)','lmm') . ' (' . __('culture code','lmm') . ': fr-CA)',
				'ga' => __('Irish','lmm') . ' (' . __('culture code','lmm') . ': ga)',
				'gd-Latn' => __('Scottish Gaelic','lmm') . ' (' . __('culture code','lmm') . ': gd-Latn)',
				'gl' => __('Galician','lmm') . ' (' . __('culture code','lmm') . ': gl)',
				'gu' => __('Gujarati','lmm') . ' (' . __('culture code','lmm') . ': gu)',
				'ha-Latn' => __('Hausa (Latin)','lmm') . ' (' . __('culture code','lmm') . ': ha-Latn)',
				'he' => __('Hebrew','lmm') . ' (' . __('culture code','lmm') . ': he)',
				'hi' => __('Hindi','lmm') . ' (' . __('culture code','lmm') . ': hi)',
				'hr' => __('Croatian','lmm') . ' (' . __('culture code','lmm') . ': hr)',
				'hu' => __('Hungarian','lmm') . ' (' . __('culture code','lmm') . ': hu)',
				'hy' => __('Armenian','lmm') . ' (' . __('culture code','lmm') . ': hy)',
				'id' => __('Indonesian','lmm') . ' (' . __('culture code','lmm') . ': id)',
				'ig-Latn' => __('Igbo','lmm') . ' (' . __('culture code','lmm') . ': ig-Latn)',
				'is' => __('Icelandic','lmm') . ' (' . __('culture code','lmm') . ': )',
				'it' => __('Italian (Italy)','lmm') . ' (' . __('culture code','lmm') . ': it)',
				'it-it' => __('Italian (Italy)','lmm') . ' (' . __('culture code','lmm') . ': it-it)',
				'ja' => __('Japanese','lmm') . ' (' . __('culture code','lmm') . ': ja)',
				'ka' => __('Georgian','lmm') . ' (' . __('culture code','lmm') . ': ka)',
				'kk' => __('Kazakh','lmm') . ' (' . __('culture code','lmm') . ': kk)',
				'km' => __('Khmer','lmm') . ' (' . __('culture code','lmm') . ': km)',
				'kn' => __('Kannada','lmm') . ' (' . __('culture code','lmm') . ': kn)',
				'ko' => __('Korean','lmm') . ' (' . __('culture code','lmm') . ': ko)',
				'kok' => __('Konkani','lmm') . ' (' . __('culture code','lmm') . ': kok)',
				'ku-Arab' => __('Central Curdish','lmm') . ' (' . __('culture code','lmm') . ': ku-Arab)',
				'ky-Cyrl' => __('Kyrgyz','lmm') . ' (' . __('culture code','lmm') . ': ky-Cyrl)',
				'lb' => __('Luxembourgish','lmm') . ' (' . __('culture code','lmm') . ': lb)',
				'lt' => __('Lithuanian','lmm') . ' (' . __('culture code','lmm') . ': lt)',
				'lv' => __('Latvian','lmm') . ' (' . __('culture code','lmm') . ': lv)',
				'mi-Latn' => __('Maori','lmm') . ' (' . __('culture code','lmm') . ': mi-Latn)',
				'mk' => __('Macedonian','lmm') . ' (' . __('culture code','lmm') . ': mk)',
				'ml' => __('Malayalam','lmm') . ' (' . __('culture code','lmm') . ': ml)',
				'mn-Cyrl' => __('Mongolian (Cyrillic)','lmm') . ' (' . __('culture code','lmm') . ': mn-Cyrl)',
				'mr' => __('Marathi','lmm') . ' (' . __('culture code','lmm') . ': mr)',
				'ms' => __('Malay (Malaysia)','lmm') . ' (' . __('culture code','lmm') . ': ms)',
				'mt' => __('Maltese','lmm') . ' (' . __('culture code','lmm') . ': mt)',
				'nb' => __('Norwegian (Bokmal)','lmm') . ' (' . __('culture code','lmm') . ': nb)',
				'ne' => __('Nepali (Nepal)','lmm') . ' (' . __('culture code','lmm') . ': ne)',
				'nl' => __('Dutch (Netherlands)','lmm') . ' (' . __('culture code','lmm') . ': nl)',
				'nl-BE' => __('Dutch (Netherlands)','lmm') . ' (' . __('culture code','lmm') . ': nl-BE)',
				'nn' => __('Norwegian (Nynorsk)','lmm') . ' (' . __('culture code','lmm') . ': nn)',
				'nso' => __('Sesotho sa Leboa','lmm') . ' (' . __('culture code','lmm') . ': nso)',
				'or' => __('Odia','lmm') . ' (' . __('culture code','lmm') . ': or)',
				'pa' => __('Punjabi (Gurmukhi)','lmm') . ' (' . __('culture code','lmm') . ': pa)',
				'pa-Arab' => __('Punjabi (Arabic)','lmm') . ' (' . __('culture code','lmm') . ': pa-Arab)',
				'pl' => __('Polish','lmm') . ' (' . __('culture code','lmm') . ': pl)',
				'prs-Arab' => __('Dari','lmm') . ' (' . __('culture code','lmm') . ': prs-Arab)',
				'pt-BR' => __('Portuguese (Brazil)','lmm') . ' (' . __('culture code','lmm') . ': pt-BR)',
				'pt-PT' => __('Portuguese (Portugal)','lmm') . ' (' . __('culture code','lmm') . ': pt-PT)',
				'qut-Latn' => __('Kiche','lmm') . ' (' . __('culture code','lmm') . ': qut-Latn)',
				'quz' => __('Quechua (Peru)','lmm') . ' (' . __('culture code','lmm') . ': quz)',
				'ro' => __('Romanian (Romania)','lmm') . ' (' . __('culture code','lmm') . ': ro)',
				'ru' => __('Russian','lmm') . ' (' . __('culture code','lmm') . ': ru)',
				'rw' => __('Kinyarwanda','lmm') . ' (' . __('culture code','lmm') . ': rw)',
				'sd-Arab' => __('Sindhi (Arabic)','lmm') . ' (' . __('culture code','lmm') . ': sd-Arab)',
				'si' => __('Sinhala','lmm') . ' (' . __('culture code','lmm') . ': si)',
				'sk' => __('Slovak','lmm') . ' (' . __('culture code','lmm') . ': sk)',
				'sl' => __('Slovenian','lmm') . ' (' . __('culture code','lmm') . ': sl)',
				'sq' => __('Albanian','lmm') . ' (' . __('culture code','lmm') . ': sq)',
				'sr-Cyrl-BA' => __('Serbian (Cyrillic, Bosnia and Herzegovina)','lmm') . ' (' . __('culture code','lmm') . ': sr-Cyrl-BA)',
				'sr-Cyrl-RS' => __('Serbian (Cyrillic, Serbia)','lmm') . ' (' . __('culture code','lmm') . ': sr-Cyrl-RS)',
				'sr-Latn-RS' => __('Serbian (Latin, Serbia)','lmm') . ' (' . __('culture code','lmm') . ': sr-Latn-RS)',
				'sv' => __('Swedish (Sweden)','lmm') . ' (' . __('culture code','lmm') . ': sv)',
				'sw' => __('Kiswahili','lmm') . ' (' . __('culture code','lmm') . ': sw)',
				'ta' => __('Tamil','lmm') . ' (' . __('culture code','lmm') . ': ta)',
				'te' => __('Telugu','lmm') . ' (' . __('culture code','lmm') . ': te)',
				'tg-Cyrl' => __('Tajik (Cyrillic)','lmm') . ' (' . __('culture code','lmm') . ': tg-Cyrl)',
				'th' => __('Thai','lmm') . ' (' . __('culture code','lmm') . ': th)',
				'ti' => __('Tigrinya','lmm') . ' (' . __('culture code','lmm') . ': ti)',
				'tk-Latn' => __('Turkmen (Latin)','lmm') . ' (' . __('culture code','lmm') . ': tk-Latn)',
				'tn' => __('Setswana','lmm') . ' (' . __('culture code','lmm') . ': tn)',
				'tr' => __('Turkish','lmm') . ' (' . __('culture code','lmm') . ': tr)',
				'tt-Cyrl' => __('Tatar (Cyrillic)','lmm') . ' (' . __('culture code','lmm') . ': tt-Cyrl)',
				'ug-Arab' => __('Uyghur','lmm') . ' (' . __('culture code','lmm') . ': ug-Arab)',
				'uk' => __('Ukrainian','lmm') . ' (' . __('culture code','lmm') . ': uk)',
				'ur' => __('Urdu','lmm') . ' (' . __('culture code','lmm') . ': ur)',
				'uz-Latn' => __('Uzbek (Latin)','lmm') . ' (' . __('culture code','lmm') . ': uz-Latn)',
				'vi' => __('Vietnamese','lmm') . ' (' . __('culture code','lmm') . ': vi)',
				'wo' => __('Wolof','lmm') . ' (' . __('culture code','lmm') . ': wo)',
				'xh' => __('isiXhosa','lmm') . ' (' . __('culture code','lmm') . ': xh)',
				'yo-Latn' => __('Yoruba','lmm') . ' (' . __('culture code','lmm') . ': yo-Latn)',
				'zh-Hans' => __('Chinese (Simplified)','lmm') . ' (' . __('culture code','lmm') . ': zh-Hans)',
				'zh-Hant' => __('Chinese (Traditional)','lmm') . ' (' . __('culture code','lmm') . ': zh-Hant)',
				'zu' => __('isiZulu','lmm') . ' (' . __('culture code','lmm') . ': zu)'
			)
		);
		/*===========================================
		*
		*
		* pane Directions
		*
		*
		===========================================*/
		/*
		* Directions General
		*/
		$this->_settings['directions_general_helptext1'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select your prefered directions provider. This setting will be used for the directions link in the panel on top of marker maps and for the action panel which gets attached to the popup text on each marker if enabled.', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['directions_provider'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section1',
			'title'   => __('Use the following directions provider','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'googlemaps',
			'choices' => array(
				'googlemaps' => __('Google Maps (worldwide)','lmm') . ' - <a href="http://maps.google.com/maps?saddr=Vienna&daddr=Linz&hl=de&sll=37.0625,-95.677068&sspn=59.986788,135.263672&geocode=FS6Z3wIdO9j5ACmfyjZRngdtRzFGW6JRiuXC_Q%3BFfwa4QIdBvzZAClNhZn6lZVzRzHEdXlXLClTfA&vpsrc=0&mra=ls&t=m&z=9&layer=t" style="text-decoration:none;" target="_blank">Demo</a>',
				'yours' => __('yournavigation.org (based on OpenStreetMap, worldwide)','lmm') . ' - <a href="http://www.yournavigation.org/?flat=52.215636&flon=6.963946&tlat=52.2573&tlon=6.1799&v=motorcar&fast=1&layer=mapnik" style="text-decoration:none;" target="_blank">Demo</a>',
				'osrm' => __('map.project-osrm.org (based on OpenStreetMap, worldwide)','lmm') . ' - <a href="http://map.project-osrm.org/?hl=en&loc=48.242330,16.433030&loc=48.219069,16.380959" style="text-decoration:none;" target="_blank">Demo</a>',
				'ors' => __('openrouteservice.org (based on OpenStreetMap, Europe only)','lmm') . ' - <a href="http://openrouteservice.org/index.php?start=7.0892567,50.7265543&end=7.0986258,50.7323634&lat=50.72905&lon=7.09574&zoom=15&pref=Fastest&lang=de" style="text-decoration:none;" target="_blank">Demo</a>',
				'bingmaps' => __('Bing Maps (worldwide)','lmm') . ' - <a href="http://www.bing.com/maps/default.aspx?v=2&rtp=pos.48.208614_16.370541___e_~pos.48.207321_16.330513" style="text-decoration:none;" target="_blank">Demo</a>'
			)
		);
		$this->_settings['directions_popuptext_panel'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section1',
			'title'   => __('Attach directions panel with address to popup text on each marker','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'yes',
			'choices' => array(
				'yes' => __('yes','lmm'),
				'no' => __('no','lmm')			)
		);
		$this->_settings['directions_general_helptext2'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => '<img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-directions-popuptext-panel.jpg" width="450" height="218" />',
			'type'    => 'helptext'
		);
		/*
		* Google Maps
		*/
		$this->_settings['directions_googlemaps_helptext1'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'   => '',
			'desc'    => '',
			'type'    => 'helptext',
			'std'     => ''
		);
		$this->_settings['directions_googlemaps_map_type'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'   => __('Map type','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'm',
			'choices' => array(
				'm' => __('Map','lmm'),
				'k' => __('Satellite','lmm'),
				'h' => __('Hybrid','lmm'),
				'p' => __('Terrain','lmm')
			)
		);
		$this->_settings['directions_googlemaps_traffic'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'   => __('Show traffic layer?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'1' => __('yes','lmm'),
				'0' => __('no','lmm')
			)
		);
		$this->_settings['directions_googlemaps_distance_units'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'   => __('Distance units','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'ptk',
			'choices' => array(
				'ptk' => __('metric (km)','lmm'),
				'ptm' => __('imperial (miles)','lmm')
			)
		);
		$this->_settings['directions_googlemaps_route_type_highways'] = array(
			'version' => '1.0',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'    => __('Route type','lmm'),
			'desc'    => __('Avoid highways','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['directions_googlemaps_route_type_tolls'] = array(
			'version' => '1.0',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'    => '',
			'desc'    => __('Avoid tolls','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['directions_googlemaps_route_type_public_transport'] = array(
			'version' => '1.0',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'    => '',
			'desc'    => __('Public transport (works only in some areas)','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['directions_googlemaps_route_type_walking'] = array(
			'version' => '1.0',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'    => '',
			'desc'    => __('Walking directions','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['directions_googlemaps_overview_map'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section2',
			'title'   => __('Overview map','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array(
				'0' => __('hidden','lmm'),
				'1' => __('visible','lmm')
			)
		);

		/*
		* yournavigation.org
		*/
		$this->_settings['directions_yours_helptext1'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section3',
			'std'     => '',
			'title'   => '',
			'desc'    => '',
			'type'    => 'helptext'
		);
		$this->_settings['directions_yours_type_of_transport'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section3',
			'title'   => __('Type of transport','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'motorcar',
			'choices' => array(
				'motorcar' => __('Motorcar','lmm'),
				'bicycle' => __('Bicycle','lmm'),
				'foot' => __('Foot','lmm')
			)
		);
		$this->_settings['directions_yours_route_type'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section3',
			'title'   => __('Route type','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'0' => __('fastest route','lmm'),
				'1' => __('shortest route','lmm')
			)
		);
		$this->_settings['directions_yours_layer'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section3',
			'title'   => __('Gosmore instance to calculate the route','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'mapnik',
			'choices' => array(
				'mapnik' => __('mapnik (for normal routing using car, bicycle or foot)','lmm'),
				'cn' => __('cn (for using bicycle routing using cycle route networks only)','lmm')
			)
		);
		/*
		* map.project-osrm.org
		*/
		$this->_settings['directions_osrm_helptext1'] = array(
			'version' => '2.7.1',
			'pane'    => 'directions',
			'section' => 'directions-section4',
			'title'   => '',
			'desc'    => '',
			'type'    => 'helptext',
			'std'     => ''
		);
		$this->_settings['directions_osrm_language'] = array(
			'version' => '2.7.1',
			'pane'    => 'directions',
			'section' => 'directions-section4',
			'title'   => __('Language of route instructions','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'en',
			'choices' => array(
				'en' => __('English','lmm'),
				'de' => __('German','lmm'),
				'dk' => __('Danish','lmm'),
				'es' => __('Spanish','lmm'),
				'fi' => __('Finnish','lmm'),
				'fr' => __('French','lmm'),
				'it' => __('Italian','lmm'),
				'lv' => __('Latvian','lmm'),
				'pl' => __('Polish','lmm'),
				'ru' => __('Russian','lmm')
			)
		);
		$this->_settings['directions_osrm_units'] = array(
			'version' => '2.7.1',
			'pane'    => 'directions',
			'section' => 'directions-section4',
			'title'   => __('Units','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array(
				'0' => __('metric (kilometer)','lmm'),
				'1' => __('imperial (miles)','lmm')
			)
		);
		/*
		* openrouteservice.org
		*/
		$this->_settings['directions_ors_helptext1'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => '',
			'type'    => 'helptext'
		);
		$this->_settings['directions_ors_route_preferences'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section5',
			'title'   => __('Route preferences','lmm'),
			'desc'    => '',
			'type'    => 'radio',

			'std'     => 'Shortest',
			'choices' => array(
				'Fastest' => __('fastest route','lmm'),
				'Shortest' => __('shortest route','lmm'),
				'Pedestrian' => __('route for pedestrians','lmm'),
				'Bicycle' => __('route for bicycles','lmm')
			)
		);
		$this->_settings['directions_ors_language'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section5',
			'title'   => __('Language of route instructions','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'en',
			'choices' => array(
				'en' => __('English','lmm'),
				'de' => __('German','lmm'),
				'it' => __('Italian','lmm'),
				'fr' => __('French','lmm'),
				'es' => __('Spanish','lmm')
			)
		);
		$this->_settings['directions_ors_no_motorways'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section5',
			'title'   => __('No motorways?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		$this->_settings['directions_ors_no_tollways'] = array(
			'version' => '1.4',
			'pane'    => 'directions',
			'section' => 'directions-section5',
			'title'   => __('No tollways?','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false','lmm'),
				'true' => __('true','lmm')
			)
		);
		/*===========================================
		*
		*
		* pane Augmented-Reality
		*
		*
		===========================================*/
		/*
		* AR General
		*/
		$this->_settings['ar_general_helptext1'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Markers created with Leaflet Maps Marker can also be displayed via <a href="http://en.wikipedia.org/wiki/Augmented_reality" target="_blank">Augmented-Reality technology</a> on mobile devices. As a first steps, an API to <a href="http://www.wikitude.com" target="_blank">Wikitude</a> has been implemented. APIs to other Augmented-Reality-Providers (like <a href="http://www.layar.com" target="_blank">Layar</a> or <a href="http://www.junaio.de" target="_blank">Junaio</a>) will probably follow in one of the next versions. Sample screenshots:', 'lmm') . '<br/><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-wikitude.jpg" width="500" height="699" />',
			'type'    => 'helptext'
		);
		/*
		* AR Wikitude
		*/
		$this->_settings['ar_wikitude_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please visit <a href="http://www.mapsmarker.com/wikitude" target="_blank">http://www.mapsmarker.com/wikitude</a> for instructions how to submit your marker or layer maps to Wikitude.', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['ar_wikitude_provider_name'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Provider name', 'lmm' ),
			'desc'    => '<strong>' . __( 'Identifies the content provider or content channel, no spaces/special characters', 'lmm' ) . '</strong>',
			'std'     => 'www_mapsmarker_com',
			'type'    => 'text'
		);
		$this->_settings['ar_wikitude_shortname'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Short name', 'lmm' ),
			'desc'    => __( 'A short name for your World, should only be up to 20 characters, to be used when there is not enough space.', 'lmm' ),
			'std'     => 'www.mapsmarker.com',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_description'] = array(
			'version' => 'p1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Description', 'lmm' ) . $pro_button_link,
			'desc'    => __( 'Description of the content provider that provides additional information about the content displayed.', 'lmm' ) . '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>',
			'std'     => __('Wikitude API powered by www.mapsmarker.com','lmm'),
			'type'    => 'text-pro'
		);
		$this->_settings['ar_wikitude_promotiontext'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Promotion text', 'lmm' ),
			'desc'    => __( 'A promotion text describing your World in more details.', 'lmm' ),
			'std'     => 'Wikitude API powered by www.mapsmarker.com',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_promotiongraphic'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Promotion graphic', 'lmm' ),
			'desc'    => __( 'A graphic advertising your World. Format: 180x120 pixel, transparent PNG', 'lmm' ),
			'std'     => LEAFLET_PLUGIN_URL . 'inc/img/wikitude-promotiongraphic-180x200.png',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_optout'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'No promotion', 'lmm' ),
			'desc'    => __( 'Opt-out from being promoted by Wikitude.', 'lmm' ),
			'type'    => 'radio',
			'std'     => 'false',
			'choices' => array(
				'false' => __('false', 'lmm'),
				'true' => __('true', 'lmm')
			)
		);
		$this->_settings['ar_wikitude_featuregraphic'] = array(
			'version' => '3.4',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Feature graphic', 'lmm' ),
			'desc'    => __( 'A graphic spotlighting your World in promotions. Format: 1024x500 pixel, transparent PNG', 'lmm' ),
			'std'     => LEAFLET_PLUGIN_URL . 'inc/img/wikitude-featuregraphic-1024x500.png',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_provider_url'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Provider URL', 'lmm' ),
			'desc'    => __( 'Link to content provider', 'lmm' ),
			'std'     => 'http://www.mapsmarker.com',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_logo'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Logo', 'lmm' ),
			'desc'    => __( 'The logo is displayed on the left bottom corner on Wikitude when an icon is selected - 96x96 pixel, transparent PNG', 'lmm' ),
			'std'     => LEAFLET_PLUGIN_URL . 'inc/img/wikitude-logo-96x96.png',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_icon'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Icon', 'lmm' ),
			'desc'    => __( 'The icon is displayed in the cam view of Wikitude to indicate a marker - 32x32 pixel, transparent PNG', 'lmm' ),
			'std'     => LEAFLET_PLUGIN_URL . 'inc/img/wikitude-icon-32x32.png',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_hiresicon'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'High Resolution Icon', 'lmm' ),
			'desc'    => __( 'A high resolution icon for your World which can be included in features. Format: 512x512 pixel, transparent PNG', 'lmm' ),
			'std'     => '',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_tags'] = array(
			'version' => '3.3',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Tags', 'lmm' ),
			'desc'    => __( 'Comma separated list of keywords that characterize the content provider. When users search for content in Wikitude the tags will be searched as well. A match in the tags is higher ranked than in the description.', 'lmm' ),
			'std'     => 'mapsmarker',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_email'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'E-Mail', 'lmm' ),
			'desc'    => __( 'Optional: displayed on each marker; used for sending an email directly from Wikitude', 'lmm' ),
			'std'     => '',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_phone'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Phone', 'lmm' ),
			'desc'    => __( 'Optional: example: +4312345 - when a phone number is given, Wikitude displays a "call me" button in the bubble; used for every marker.', 'lmm' ),
			'std'     => '',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_attachment'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Attachment', 'lmm' ),
			'desc'    => __( 'Optional: displayed on each marker; can be a link to a resource (image, PDF file...). You could use this to issue coupons or vouchers for potential clients that found you via Wikitude.', 'lmm' ),
			'std'     => '',
			'type'    => 'text-deletable'
		);
		$this->_settings['ar_wikitude_radius'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Search radius (in meter)', 'lmm' ),
			'desc'    => __( 'Retrieve POIs (Points of Interests) from database within this search radius in meters from the current location of the Wikitude user', 'lmm' ),
			'std'     => '100000',
			'type'    => 'text'
		);
		$this->_settings['ar_wikitude_maxnumberpois'] = array(
			'version' => '1.0',
			'pane'    => 'ar',
			'section' => 'ar-section1',
			'title'   => __( 'Maximum number of POIs', 'lmm' ),
			'desc'    => __( 'Used if Wikitude does not pass the variable maxNumberofPois - 50 is the maximum recommended', 'lmm' ),
			'std'     => '50',
			'type'    => 'text'
		);

		/*===========================================
		*
		*
		* pane miscellaneous
		*
		*
		===========================================*/
		$this->_settings['misc_general_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => '', //empty for not breaking settings layout
			'type'    => 'helptext'
		);
		$this->_settings['affiliate_id'] = array(
			'version' => '3.6.4',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'Affiliate ID', 'lmm' ),
			'desc'    => __( 'Enter your affiliate ID to replace the default MapsMarker.com-backlink on all maps with your personal affiliate link - enabling you to receive commissions up to 50% from sales of the pro version.', 'lmm' ) . '<br/><a href="https://www.mapsmarker.com/affiliateid" target="_blank">' . __('Click here for more infos on the Maps Marker affiliate program and how to get your affiliate ID','lmm') . '</a>',
			'std'     => '',
			'type'    => 'text'
		);
		$this->_settings['misc_backlink'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('MapsMarker.com backlinks','lmm') . $pro_button_link,
			'desc'    => __('Option to hide backlinks to Mapsmarker.com on maps and screen overlays in KML files.','lmm') . '<a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-backlink.jpg" width="642" height="45" /><br/><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-backlink-kml.jpg" width="471" height="71" />',
			'type'    => 'radio-pro',
			'std'     => 'show',
			'choices' => array(
				'show' => __('show','lmm'),
				'hide' => __('hide','lmm')
			)
		);
		$this->_settings['capabilities_edit'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'User role needed for adding and editing markers/layers', 'lmm' ),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'edit_posts',
			'choices' => array(
				'activate_plugins' => __('Administrator (Capability activate_plugins)', 'lmm'),
				'moderate_comments' => __('Editor (Capability moderate_comments)', 'lmm'),
				'edit_published_posts' => __('Author (Capability edit_published_posts)', 'lmm'),
				'edit_posts' => __('Contributor (Capability edit_posts)', 'lmm'),
				'read' => __('Subscriber (Capability read)', 'lmm')
			)
		);
		$this->_settings['capabilities_edit_others'] = array(
			'version' => 'p1.2',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'User role needed for editing markers/layers from other users', 'lmm' ) . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'edit_posts',
			'choices' => array(
				'activate_plugins' => __('Administrator (Capability activate_plugins)', 'lmm'),
				'moderate_comments' => __('Editor (Capability moderate_comments)', 'lmm'),
				'edit_published_posts' => __('Author (Capability edit_published_posts)', 'lmm'),
				'edit_posts' => __('Contributor (Capability edit_posts)', 'lmm'),
				'read' => __('Subscriber (Capability read)', 'lmm')
			)
		);
		$this->_settings['capabilities_delete'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'User role needed for deleting markers/layers', 'lmm' ),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'edit_posts',
			'choices' => array(
				'activate_plugins' => __('Administrator (Capability activate_plugins)', 'lmm'),
				'moderate_comments' => __('Editor (Capability moderate_comments)', 'lmm'),
				'edit_published_posts' => __('Author (Capability edit_published_posts)', 'lmm'),
				'edit_posts' => __('Contributor (Capability edit_posts)', 'lmm'),
				'read' => __('Subscriber (Capability read)', 'lmm')
			)
		);
		$this->_settings['capabilities_delete_others'] = array(
			'version' => 'p1.2',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'User role needed for deleting markers/layers from other users', 'lmm' ) . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => 'edit_posts',
			'choices' => array(
				'activate_plugins' => __('Administrator (Capability activate_plugins)', 'lmm'),
				'moderate_comments' => __('Editor (Capability moderate_comments)', 'lmm'),
				'edit_published_posts' => __('Author (Capability edit_published_posts)', 'lmm'),
				'edit_posts' => __('Contributor (Capability edit_posts)', 'lmm'),
				'read' => __('Subscriber (Capability read)', 'lmm')
			)
		);
		$this->_settings['markers_per_page'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'Markers per page', 'lmm' ),
			'desc'    => __( 'How many markers should be listed on one page at the page "list all markers"?', 'lmm' ),
			'std'     => '30',
			'type'    => 'text'
		);
		$this->_settings['shortcode'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'Shortcode', 'lmm' ),
			'desc'    => __( 'Shortcode to add markers or layers into articles or pages  - Example: [mapsmarker marker="1"].<br/> Attention: if you change the shortcode after having embedded shortcodes into posts/Pages, the shortcode on these specific articles/pages has to be changed also manually - otherwise these markers/layers will not be show on frontend!', 'lmm' ),
			'std'     => 'mapsmarker',
			'type'    => 'text'
		);
		$this->_settings['misc_tinymce_button'] = array(
			'version' => '1.9',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('TinyMCE button','lmm'),
			'desc'    => __('if enabled, an "Insert map" button gets added to the TinyMCE toolbar and the media bar on post and page edit screens for easily searching and inserting maps','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_add_georss_to_head'] = array(
			'version' => '1.5',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Add GeoRSS feed to &lt;head&gt;','lmm'),
			'desc'    => __('if enabled, a GeoRSS feed for all markers will be added to the &lt;head&gt;-section of the website, allowing users to subscribe to your markers','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['admin_bar_integration'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('WordPress Admin Bar integration','lmm'),
			'desc'    => __('show or hide drop down menu for Leaflet Maps Marker in Wordpress Admin Bar','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_admin_dashboard_widget'] = array(
			'version' => '2.5',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('WordPress admin dashboard widget','lmm'),
			'desc'    => __('shows a widget on the admin dashboard which displays latest markers and blog posts from mapsmarker.com','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_pointers'] = array(
			'version' => '2.8',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('WordPress Pointers','lmm'),
			'desc'    => __('display WordPress pointers on plugin updates','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_projections'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __( 'Coordinate Reference System', 'lmm' ),
			'desc'    => __( 'Used for created maps - do not change this if you are not sure what it means!', 'lmm'),
			'type'    => 'radio',
			'std'     => 'L.CRS.EPSG3857',
			'choices' => array(
				'L.CRS.EPSG3857' => __('EPSG:3857 (Spherical Mercator), used by most of commercial map providers (CloudMade, Google, Yahoo, Bing, etc.)', 'lmm'),
				'L.CRS.EPSG4326' => __('EPSG:4326 (Plate Carree), very popular among GIS enthusiasts', 'lmm'),
				'L.CRS.EPSG3395' => __('EPSG:4326 (Mercator), used by some map providers.', 'lmm')
			)
		);
		$this->_settings['misc_javascript_header_footer'] = array(
			'version' => '3.1',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Where to insert Javascript files on frontend?','lmm'),
			'desc'    => __('Footer is recommended for better performance. If you are using WordPress lesser than v3.3, Javascript files automatically get inserted into the header of your site and the javascript needed for each maps inline within the content.','lmm') . ' ' . __('If you choose footer, javascripts will also only be loaded when a shortcode is used and not on all pages.','lmm') . ' ' . __('Setting this option to header+inline-javascript is required, if maps should be displayed withing a jQuery mobile framework.','lmm'),
			'type'    => 'radio',
			'std'     => 'footer',
			'choices' => array(
				'header' => __('header (+ inline javascript)','lmm'),
				'footer' => __('footer','lmm')
			)
		);
		$this->_settings['misc_conditional_css_loading'] = array(
			'version' => '3.2.2',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Support for conditional css loading','lmm'),
			'desc'    => __('If enabled, css files will only be loaded if a shortcode is used.','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_responsive_support'] = array(
			'version' => '3.2',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Support for responsive designs','lmm'),
			'desc'    => __('If enabled, maps will automatically be resized to width=100% if map width unit is set to px and the div parent element is smaller than the width of the map.','lmm'),
			'type'    => 'radio',
			'std'     => 'enabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_global_admin_notices'] = array(
			'version' => '3.5',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Admin notices','lmm'),
			'desc'    => __('Option for global admin notices in backend (showing infos about plugin incompatibilities or invalid marker icon directory settings for example). Please be aware that hiding them results in not being informed about plugin incompatibilites discovered in future releases too!','lmm'),
			'type'    => 'radio',
			'std'     => 'show',
			'choices' => array(
				'show' => __('show','lmm'),
				'hide' => __('hide','lmm')
			)
		);
		$this->_settings['async_geojson_loading'] = array(
			'version' => 'p1.6',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Async GeoJSON loading for layer maps','lmm') . $pro_button_link,
			'desc'    => __('Disabling async GeoJSON loading will increase page loadtime and is only needed if multiple instances of a layer map should be displayed on one page.','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['misc_betatest'] = array(
			'version' => 'p1.1',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Beta testing','lmm'). $pro_button_link,
			'desc'    => __('Set to enabled if you want to easily upgrade to beta releases.','lmm') . ' <span style="font-weight:bold;color:red;">' . __('Warning: not recommended on production sites - use on your own risk!','lmm') . '</span>',
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'disabled' => __('disabled','lmm'),
				'enabled' => __('enabled','lmm')
			)
		);
		$this->_settings['misc_whitelabel_backend'] = array(
			'version' => 'p1.2',
			'pane'    => 'misc',
			'section' => 'misc-section1',
			'title'   => __('Whitelabel backend','lmm'). $pro_button_link,
			'desc'    => __('Set to enabled if you want to remove all backlinks and logos on backend as well as making the pages and menu entries for Tools, Settings, Support, License visible to admins only (user capability activate_plugins).','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'disabled' => __('disabled','lmm'),
				'enabled' => __('enabled','lmm')
			)
		);
		/*
		* Language Settings
		*/
		$this->_settings['misc_language_helptext'] = array(
			'version' => '2.4',
			'pane'    => 'misc',
			'section' => 'misc-section2',
			'std'     => '',
			'title'   => '',
			'desc'    => __('The language used on plugin pages on backend and/or on maps on frontend. Please note that the language for Google and Bing Services can be set seperately via Settings / tab "Google Maps" / "Google language localization" respectively tab "Bing Maps" / "Cultures"','lmm') . '<br/><br/>' . sprintf(__('If your language is missing or not fully translated yet, you are invited to help on the <a href="%1s" target="_blank">web-based translation plattform</a>.','lmm'), 'https://translate.mapsmarker.com/projects/lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['misc_plugin_language'] = array(
			'version' => '2.4',
			'pane'    => 'misc',
			'section' => 'misc-section2',
			'title'   => __('Default language','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'automatic',
			'choices' => array(
				'automatic' => __('automatic (use WordPress default)','lmm'),
				'ar' => __('Arabic','lmm') . ' (ar)',
				'af' => __('Afrikaans','lmm') . ' (af)',
				'bn_BD' => __('Bengali','lmm') . ' (bn_BD)',
				'bs_BA' => __('Bosnian','lmm') . ' (bs_BA)',
				'bg_BG' => __('Bulgarian','lmm') . ' (bg_BG)',
				'ca' => __('Catalan','lmm') . ' (ca)',
				'zh_CN' => __('Chinese','lmm') . ' (zh_CN)',
				'zh_TW' => __('Chinese','lmm') . ' (zh_TW)',
				'hr' => __('Croatian','lmm') . ' (hr)',
				'cs_CZ' => __('Czech','lmm') . ' (cs_CZ)',
				'da_DK' => __('Danish','lmm') . ' (da_DK)',
				'nl_NL' => __('Dutch','lmm') . ' (nl_NL)',
				'en_US' => __('English','lmm') . ' (en_US)',
				'fi_FI' => __('Finnish','lmm') . ' (fi_FI)',
				'fr_FR' => __('French','lmm') . ' (fr_FR)',
				'gl_ES' => __('Galician','lmm') . ' (gl_ES)',
				'de_DE' => __('German','lmm') . ' (de_DE)',
				'el' => __('Greek','lmm') . ' (el)',
				'he_IL' => __('Hebrew','lmm') . ' (he_IL)',
				'hi_IN' => __('Hindi','lmm') . ' (hi_IN)',
				'hu_HU' => __('Hungarian','lmm') . ' (hu_HU)',
				'id_ID' => __('Indonesian','lmm') . ' (id_ID)',
				'it_IT' => __('Italian','lmm') . ' (it_IT)',
				'ja' => __('Japanese','lmm') . ' (ja)',
				'ko_KR' => __('Korean','lmm') . ' (ko_KR)',
				'lv' => __('Latvian','lmm') . ' (lv)',
				'lt_LT' => __('Lithuanian','lmm') . ' (lt_LT)',
				'nb_NO' => __('Norwegian (Bokmål)','lmm') . ' (nb_NO)',
				'pl_PL' => __('Polish','lmm') . ' (pl_PL)',
				'pt_BR' => __('Portuguese','lmm') . ' - ' . __('Brazil','lmm') . ' (pt_BR)',
				'pt_PT' => __('Portuguese','lmm') . ' - ' . __('Portugal','lmm') . ' (pt_PT)',
				'ro_RO' => __('Romanian','lmm') . ' (ro_RO)',
				'ru_RU' => __('Russian','lmm') . ' (ru_RU)',
				'sk_SK' => __('Slovak','lmm') . ' (sk_SK)',
				'sl_SL' => __('Slovenian','lmm') . ' (sl_SL)',
				'sv_SE' => __('Swedish','lmm') . ' (sv_SE)',
				'es_ES' => __('Spanish','lmm') . ' (es_ES)',
				'es_MX' => __('Spanish','lmm') . ' (es_MX)',
				'th' => __('Thai','lmm') . ' (th)',
				'tr_TR' => __('Turkish','lmm') . ' (tr_TR)',
				'ug' => __('Uighur','lmm') . ' (ug)',
				'uk_UK' => __('Ukrainian','lmm') . ' (uk_UK)',
				'vi' => __('Vietnamese','lmm') . ' (vi)',
				'yi' => __('Yiddish','lmm') . ' (yi)'
			)
		);
		$this->_settings['misc_plugin_language_area'] = array(
			'version' => '2.4',
			'pane'    => 'misc',
			'section' => 'misc-section2',
			'title'   => __('Where to change the default language','lmm'),
			'desc'    => __('This setting will only be used when the plugin language is not selected automatically','lmm'),
			'type'    => 'radio',
			'std'     => 'backend',
			'choices' => array(
				'backend' => __('WordPress admin area only','lmm'),
				'frontend' => __('WordPress frontend only','lmm'),
				'both' => __('WordPress admin area and frontend','lmm')
			)
		);
		/*
		* KML Settings
		*/
		$this->_settings['misc_kml_helptext'] = array(
			'version' => '1.8',
			'pane'    => 'misc',
			'section' => 'misc-section3',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Choose how marker names should be displayed in KML files', 'lmm') . ' <a href="https://www.mapsmarker.com/kml-names" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>',
			'type'    => 'helptext'
		);
		$this->_settings['misc_kml'] = array(
			'version' => '1.8',
			'pane'    => 'misc',
			'section' => 'misc-section3',
			'title'   => __( 'Marker names in KML', 'lmm' ),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'show',
			'choices' => array(
				'show' => __('show', 'lmm'),
				'hide' => __('hide', 'lmm'),
				'popup' => __('put in front of popup-text', 'lmm')
			)
		);
		$this->_settings['misc_kml_helptext2'] = array(
			'version' => '1.8',
			'pane'    => 'misc',
			'section' => 'misc-section3',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:150px;"></div>',
			'type'    => 'helptext'
		);

		/*
		* Available columns for marker listing page
		*/
		$this->_settings['misc_marker_listing_columns_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select the columns which should be available on the page "List all markers"', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['misc_marker_listing_columns_id'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => __('Columns to show','lmm'),
			'desc'    => 'ID',
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_icon'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Icon','lmm'),
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_markername'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Marker name','lmm'),
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_address'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Address','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_popuptext'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Popup text','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_layername'] = array(
			'version' => '2.7.1',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Layer name','lmm') . ' ' . __('(for marker listings below multi-layer maps only)','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_basemap'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Basemap','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_layer'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Layer','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_coordinates'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Coordinates','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_zoom'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Zoom','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_openpopup'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Popup status','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_panelstatus'] = array(
			'version' => '1.4',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Panel status','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_mapsize'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Map size','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_createdby'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Created by','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_createdon'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Created on','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_updatedby'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Updated by','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_updatedon'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Updated on','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_controlbox'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Controlbox status','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_shortcode'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Shortcode','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_marker_listing_columns_kml'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => 'KML',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_fullscreen'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('Fullscreen','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_qr_code'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => __('QR code','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_geojson'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => 'GeoJSON',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_georss'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => 'GeoRSS',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_marker_listing_columns_wikitude'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section4',
			'title'    => '',
			'desc'    => 'Wikitude',
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* Sort order for marker listing page
		*/
		$this->_settings['misc_marker_listing_sort_helptext'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select order by and sort order for "List all markers" page', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['misc_marker_listing_sort_order_by'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section5',
			'title'   => __('Order list of markers by','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'm.id',
			'choices' => array(
				'm.id' => 'ID',
				'm.markername' => __('marker name','lmm'),
				'm.layer' => __('assigned layer','lmm') . '(ID)',
				'm.createdon' => __('created on','lmm'),
				'm.createdby' => __('created by','lmm'),
				'm.updatedon' => __('updated on','lmm'),
				'm.updatedby' => __('updated by','lmm')
			)
		);
		$this->_settings['misc_marker_listing_sort_sort_order'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section5',
			'title'   => __('Sort order','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'ASC',
			'choices' => array(
				'ASC' => __('ascending','lmm'),
				'DESC' => __('descending','lmm')
			)
		);
		$this->_settings['misc_marker_listing_sort_helptext2'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section5',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:0px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* Available columns for layer listing page
		*/
		$this->_settings['misc_layer_listing_columns_helptext'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select the columns which should be available on the page "List all layers"', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['misc_layer_listing_columns_id'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => __('Columns to show','lmm'),
			'desc'    => 'ID',
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_layer_listing_columns_type'] = array(
			'version' => '1.7',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Type','lmm'),
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_layer_listing_columns_layername'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Layer name','lmm'),
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_layer_listing_columns_address'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Address','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_layer_listing_columns_markercount'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Number of markers','lmm'),
			'type'    => 'checkbox-readonly',
			'std'     => 1
		);
		$this->_settings['misc_layer_listing_columns_basemap'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Basemap','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_layercenter'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Layer center','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_zoom'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Zoom','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_mapsize'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Map size','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_panelstatus'] = array(
			'version' => '1.4',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Panel status','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_createdby'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Created by','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_createdon'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Created on','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_updatedby'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Updated by','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_updatedon'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Updated on','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_controlbox'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Controlbox status','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_shortcode'] = array(
			'version' => '1.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Shortcode','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['misc_layer_listing_columns_kml'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => 'KML',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_fullscreen'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('Fullscreen','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_qr_code'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => __('QR code','lmm'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_geojson'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => 'GeoJSON',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_georss'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => 'GeoRSS',
			'type'    => 'checkbox',
			'std'     => 0
		);
		$this->_settings['misc_layer_listing_columns_wikitude'] = array(
			'version' => '3.0',
			'pane'    => 'misc',
			'section' => 'misc-section6',
			'title'    => '',
			'desc'    => 'Wikitude',
			'type'    => 'checkbox',
			'std'     => 0
		);
		/*
		* Sort order for layer listing page
		*/
		$this->_settings['misc_layer_listing_sort_helptext'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section7',
			'std'     => '',
			'title'   => '',
			'desc'    => __( 'Please select order by and sort order for "List all layers" page', 'lmm'),
			'type'    => 'helptext'
		);
		$this->_settings['misc_layer_listing_sort_order_by'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section7',
			'title'   => __('Order list of markers by','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'id',
			'choices' => array(
				'id' => 'ID',
				'name' => __('layer name','lmm'),
				'createdon' => __('created on','lmm'),
				'createdby' => __('created by','lmm'),
				'updatedon' => __('updated on','lmm'),
				'updatedby' => __('updated by','lmm')
			)
		);
		$this->_settings['misc_layer_listing_sort_sort_order'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section7',
			'title'   => __('Sort order','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'ASC',
			'choices' => array(
				'ASC' => __('ascending','lmm'),
				'DESC' => __('descending','lmm')
			)
		);
		$this->_settings['misc_layer_listing_sort_helptext2'] = array(
			'version' => '2.3',
			'pane'    => 'misc',
			'section' => 'misc-section7',
			'std'     => '',
			'title'   => '',
			'desc'    => '<div style="height:40px;"></div>',
			'type'    => 'helptext'
		);
		/*
		* QR Code settings
		*/
		$this->_settings['qrcode_provider_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'std'     => '',
			'title'   => '',
			'desc'    => '',
			'type'    => 'helptext'
		);
		$this->_settings['qrcode_provider'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __('QR code provider','lmm'),
			'desc'    => __('Service provider used for creating QR codes for links to full screen maps','lmm'),
			'type'    => 'radio',
			'std'     => 'visualead',
			'choices' => array(
				'visualead' => 'Visualead',
				'google' => 'Google'
			)
		);
		$this->_settings['qrcode_visualead_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'std'     => '',
			'title'   => '<strong>' . __('Visualead settings','lmm') . '</strong>',
			'desc'    =>  '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-visualead.png" width="200" height="200" /><a style="background:#f99755;display:block;padding:3px;text-decoration:none;color:#2702c6;width:635px;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>',
			'type'    => 'helptext'
		);
		$this->_settings['qrcode_visualead_caching'] = array(
			'version' => 'p1.5',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __('QR code caching','lmm') . $pro_button_link,
			'desc'    => __('Save generated QR codes in the following directory for higher performance and for saving API calls:','lmm') . ' n/a<br/>' . __('Cached QR images will automatically be deleted if the according marker or layer map gets deleted!','lmm'),
			'type'    => 'radio-pro',
			'std'     => 'disabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);		
		$this->_settings['qrcode_visualead_api_key'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'API key', 'lmm' ) . $pro_button_link,
			'desc'    => __('If empty, the default API key from MapsMarker.com will be used','lmm') . ' (' . __('valid for default image only!','lmm') . ')',
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_image_url'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'Image URL', 'lmm' ) . $pro_button_link,
			'desc'    => sprintf(__('A custom image can only be used if you sign up for a custom visualead API key! Please visit %s for more information.','lmm'), '<a href="https://www.mapsmarker.com/pro-feature-qrcode" target="_blank">mapsmarker.com/visualead</a>'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_project_id'] = array(
			'version' => 'p1.5',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => 'project_id' . $pro_button_link,
			'desc'    => sprintf(__('If a visualead project_id is given, the generate_from_project method will be used instead of the generate method for creating QR codes with custom images (please note that the image url from above will also be ignored if a project_id is set - the image from the project_id is used instead). To create a new project, please log into the <a href="%s" target="_blank">visualead admin dashboard</a>.','lmm'), 'http://api.visualead.com/v3'),
			'std'     => '',
			'type'    => 'text-pro'
		);		
		$this->_settings['qrcode_visualead_qr_size'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'QR size', 'lmm' ) . $pro_button_link,
			'desc'    => __('The width/height of the Visual QR Code (minimum: 124 pixel)','lmm'),
			'std'     => '124',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_qr_cell_size'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'QR cell size', 'lmm' ) . $pro_button_link,
			'desc'    => esc_attr__('Force a specific size of the QR Code cell (Measured in pixels). Once this parameter is used then the "QR size" parameter is ignored. If the resulting QR Code size is bigger than the image, then the image is extended.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_qr_x'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => 'QR x<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('The top left x position of the QR Code. (Measured in pixels)','lmm'),
			'std'     => '4',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_qr_y'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => 'QR y<br/><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-option.png" width="65" height="15" /></a>',
			'desc'    => __('The top left y position of the QR Code. (Measured in pixels)','lmm'),
			'std'     => '5',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_qr_gravity'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'QR gravity', 'lmm' ) . $pro_button_link,
			'desc'    => esc_attr__('center/N/S/W/E (or common combinations such as NW or Scenter) - will position the QR Code accordingly. Once this parameter is used then the "qr_x and "qr_y" parameters are ignored.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_qr_rotation'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'QR rotation', 'lmm' ) . $pro_button_link,
			'desc'    => __('The angle of the QR Code rotation. Allowed angles: 0/90/180/270.','lmm'),
			'std'     => '0',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_output_image_width'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'Output image width', 'lmm' ) . $pro_button_link,
			'desc'    => __('The desired image width returned in the response. If empty, the width of the image from image url will be used.','lmm'),
			'std'     => '',
			'type'    => 'text-pro'
		);
		$this->_settings['qrcode_visualead_cells_type'] = array(
			'version' => 'p1.5',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => 'cells_type' . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => '1',
			'choices' => array(
				'1' => __('squared','lmm'),
				'2' => __('rounded','lmm')
			)
		);
		$this->_settings['qrcode_visualead_markers_type'] = array(
			'version' => 'p1.5',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => 'markers_type' . $pro_button_link,
			'desc'    => '',
			'type'    => 'radio-pro',
			'std'     => '1',
			'choices' => array(
				'1' => __('squared','lmm'),
				'2' => __('rounded','lmm')
			)
		);
		$this->_settings['qrcode_google_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'std'     => '',
			'title'   => '<strong>' . __('Google QR settings','lmm') . '</strong><br/><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-google-qr.png" width="122" height="126" />',
			'desc'    => '',
			'type'    => 'helptext'
		);
		$this->_settings['misc_qrcode_size'] = array(
			'version' => '1.1',
			'pane'    => 'misc',
			'section' => 'misc-section8',
			'title'   => __( 'QR code image size', 'lmm' ),
			'desc'    => __( 'Width and height in pixel of QR code image for marker/layer standalone fullscreen map links', 'lmm' ),
			'std'     => '150',
			'type'    => 'text'
		);
		/*
		* MapsMarker API settings
		*/
		$this->_settings['api_helptext'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'std'     => '',
			'title'   => '',
			'desc'    => sprintf(__('For more information on how to use the MapsMarker API, <a href="%1s" target="_blank">please visit the API docs on mapsmarker.com</a>','lmm'), 'https://www.mapsmarker.com/mapsmarker-api') . '<br/><br/><br/><strong>' . __('API endpoint','lmm') . ':</strong> ' . LEAFLET_PLUGIN_URL . 'leaflet-api.php<br/><br/>',
			'type'    => 'helptext'
		);
		$this->_settings['api_status'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('API status','lmm'),
			'desc'    => '',
			'type'    => 'radio',
			'std'     => 'disabled',
			'choices' => array(
				'enabled' => __('enabled','lmm'),
				'disabled' => __('disabled','lmm')
			)
		);
		$this->_settings['api_default_format'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('API default format','lmm'),
			'desc'    => __('Default output format (can be overwritten by parameter format on each API request)','lmm'),
			'type'    => 'radio',
			'std'     => 'json',
			'choices' => array(
				'json' => 'JSON',
				'xml' => 'XML'
			)
		);
		$this->_settings['api_json_callback'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('JSONP callback function name', 'lmm'),
			'desc'    => sprintf(__('Used for JSON format only, allows to overcome the <a href="%1s" target="_blank">same origin policy</a> (can be overwritten by parameter callback on each API request)','lmm'), 'http://en.wikipedia.org/wiki/JSONP'),
			'std'     => 'jsonp',
			'type'    => 'text'
		);
		$this->_settings['api_helptext4'] = array(
			'version' => '3.8.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'std'     => '',
			'title'   => '<strong>' . __('Authentication','lmm') . '</strong>',
			'desc'    =>  sprintf(__('You will find a <a href="%1$s">API URL generator</a> and <a href="%2$s">API URL tester</a> in the tools section. Please see the API docs for more information on authentication.','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools#api-url-generator', LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools#api-url-tester'),
			'type'    => 'helptext'
		);
		$this->_settings['api_key'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('Public API key', 'lmm'),
			'desc'    => __('Both public and private API keys are needed for API calls!','lmm'),
			'std'     => '',
			'type'    => 'text'
		);
		$this->_settings['api_key_private'] = array(
			'version' => '3.8.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('Private API key', 'lmm'),
			'desc'    => __('Both public and private API keys are needed for API calls!','lmm'),
			'std'     => '',
			'type'    => 'text'
		);
		$this->_settings['api_helptext3'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'std'     => '',
			'title'   => '<strong>' . __('Security options','lmm') . '</strong>',
			'desc'    =>  '',
			'type'    => 'helptext'
		);
		$this->_settings['api_permissions_view'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('Allowed API actions','lmm'),
			'desc'    => __('view existing markers/layers','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['api_permissions_add'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => '',
			'desc'    => __('add new markers/layers','lmm'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['api_permissions_update'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => '',
			'desc'    => __('update existing markers/layers','lmm') . ' <a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-feature.png" width="70" height="15" /></a>',
			'type'    => 'checkbox-pro',
			'std'     => 0
		);
		$this->_settings['api_permissions_delete'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => '',
			'desc'    => __('delete existing markers/layers','lmm') . ' <a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-feature.png" width="70" height="15" /></a>',
			'type'    => 'checkbox-pro',
			'std'     => 0
		);
		$this->_settings['api_permissions_search'] = array(
			'version' => 'p1.5.7',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => '',
			'desc'    => __('search existing markers/layers','lmm') . ' <a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-feature.png" width="70" height="15" /></a>',
			'type'    => 'checkbox-pro',
			'std'     => 0
		);		
		$this->_settings['api_allowed_ip'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('IP access restriction', 'lmm'),
			'desc'    => __('If an IP address or range is entered above (like 12.34.56.*), only API calls from this IP address or range are allowed.','lmm'),
			'std'     => '',
			'type'    => 'text'
		);
		$this->_settings['api_allowed_referer'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('Allowed referer', 'lmm'),
			'desc'    => __('If set (like http://www.your-domain.com/form.php), only API calls with this referer are allowed.','lmm'),
			'std'     => '',
			'type'    => 'text'
		);
		$this->_settings['api_request_type_get'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => __('Allowed API request methods','lmm'),
			'desc'    => 'GET',
			'type'    => 'checkbox',
			'std'     => 1
		);
		$this->_settings['api_request_type_post'] = array(
			'version' => '3.6',
			'pane'    => 'misc',
			'section' => 'misc-section9',
			'title'   => '',
			'desc'    => 'POST',
			'type'    => 'checkbox',
			'std'     => 1
		);

		/*===========================================
		*
		*
		* pane reset
		*
		*
		===========================================*/
		$this->_settings['reset_helptext'] = array(
			'version' => 'p1.0',
			'pane'    => 'misc',
			'section' => 'reset-section1',
			'std'     => '',
			'title'   => '',
			'desc'    => '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-feature.png" width="70" height="15" /></a> ' . sprintf(__('You can backup your current settings on the <a href="%1$s">tools page</a> before resetting all options to their default values.','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools'),
			'type'    => 'helptext'
		);
		$this->_settings['reset_settings'] = array(
			'version' => '1.0',
			'pane'    => 'reset',
			'section' => 'reset-section1',
			'title'   => __('Warning - cannot be undone!','lmm'),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset plugin options to their defaults.','lmm' )
		);
	}

	/**
	 * Initialize settings to their default values
	 */
	public function initialize_settings() {
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' ) {
				$default_settings[$id] = $setting['std'];
				}
		}
		update_option( 'leafletmapsmarker_options', $default_settings );
	}
	/**
	* Register settings
	*/

	public function register_settings() {
		global $pagenow;
		$current_page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : '';
		if ( ('options.php' !== $pagenow) && ! in_array( $current_page, array( 'leafletmapsmarker_settings' ) ) )
			return;

		register_setting( 'leafletmapsmarker_options', 'leafletmapsmarker_options', array ( &$this, 'validate_settings' ) );

		$this->get_settings();

		    foreach ( $this->settings as $id => $setting ) {
			    $setting['id'] = $id;
			    $this->create_setting( $setting );  // ----setttings
        }
	}
	/**
	 * save defaults for new options after plugin updates but keep values of old settings
	 */
	public function save_defaults_for_new_options() {
		//info:  set defaults for options introduced in v1.1
		if (get_option('leafletmapsmarker_version') == '1.0' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.1')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.2
		if (get_option('leafletmapsmarker_version') == '1.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.2')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.4
		if (get_option('leafletmapsmarker_version') == '1.3' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.4')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.4.3
		if (get_option('leafletmapsmarker_version') == '1.4.2' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.4.3')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.5
		if (get_option('leafletmapsmarker_version') == '1.4.3' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.5')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.6
		if (get_option('leafletmapsmarker_version') == '1.5.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.6')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.7
		if (get_option('leafletmapsmarker_version') == '1.6' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.7')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.8
		if (get_option('leafletmapsmarker_version') == '1.7' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.8')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v1.9
		if (get_option('leafletmapsmarker_version') == '1.8' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '1.9')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.1
		if (get_option('leafletmapsmarker_version') == '2.0' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.1')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.2
		if (get_option('leafletmapsmarker_version') == '2.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.2')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.3
		if (get_option('leafletmapsmarker_version') == '2.2' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.3')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.4
		if (get_option('leafletmapsmarker_version') == '2.3' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.4')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.5
		if (get_option('leafletmapsmarker_version') == '2.4' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.5')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.6
		if (get_option('leafletmapsmarker_version') == '2.5' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.6')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.7.1
		if (get_option('leafletmapsmarker_version') == '2.7' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.7.1')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.8
		if (get_option('leafletmapsmarker_version') == '2.7.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.8')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v2.9
		if (get_option('leafletmapsmarker_version') == '2.8.2' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '2.9')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.0
		if (get_option('leafletmapsmarker_version') == '2.9.2' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.0')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.1
		if (get_option('leafletmapsmarker_version') == '3.0' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.1')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.2
		if (get_option('leafletmapsmarker_version') == '3.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.2')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.2.2
		if (get_option('leafletmapsmarker_version') == '3.2.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.2.2')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.3
		if (get_option('leafletmapsmarker_version') == '3.2.5' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.3')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.4
		if (get_option('leafletmapsmarker_version') == '3.3' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.4')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.5
		if (get_option('leafletmapsmarker_version') == '3.4.3' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.5')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.5.2
		if (get_option('leafletmapsmarker_version') == '3.5.1' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.5.2')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.6
		if (get_option('leafletmapsmarker_version') == '3.5.4' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.6')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.4
		if (get_option('leafletmapsmarker_version') == '3.6.3' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.6.4')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.8.6
		if (get_option('leafletmapsmarker_version') == '3.8.5' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.8.6')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		//info:  set defaults for options introduced in v3.8.7
		if (get_option('leafletmapsmarker_version') == '3.8.6' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.8.7')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		/* template for plugin updates
		//info:  set defaults for options introduced in v3.10
		if (get_option('leafletmapsmarker_version') == '3.9.10' )
		{
			$new_options_defaults = array();
			foreach ( $this->settings as $id => $setting )
			{
				if ( $setting['type'] != 'heading' && $setting['type'] != 'helptext' && $setting['type'] != 'helptext-twocolumn' && $setting['type'] != 'checkbox-pro' && $setting['type'] != 'select-pro' && $setting['type'] != 'radio-pro' && $setting['type'] != 'radio-reverse-pro' && $setting['type'] != 'textarea-pro' && $setting['type'] != 'text-pro' && $setting['type'] != 'text-reverse-pro' && $setting['version'] == '3.10')
				{
				$new_options_defaults[$id] = $setting['std'];
				}
			}
		$options_current = get_option( 'leafletmapsmarker_options' );
		$options_new = array_merge($options_current, $new_options_defaults);
		update_option( 'leafletmapsmarker_options', $options_new );
		}
		*/
	}

	/**
	* Validate settings
	*/
	public function validate_settings( $input ) {

		if ( ! isset( $input['reset_settings'] ) ) {
			$options = get_option( 'leafletmapsmarker_options' );

			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			return $input;
		}
		return false;
	}
}
?>