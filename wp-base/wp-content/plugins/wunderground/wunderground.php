<?php
/*
Plugin Name: WP Wunderground
Plugin URI: http://www.seodenver.com/wunderground/
Description: Get accurate and beautiful weather forecasts powered by Wunderground.com for your content or your sidebar.
Version: 1.2.5.1
Author: Katz Web Services, Inc.
Author URI: http://www.katzwebservices.com/
*/

class wp_wunderground {
	var $url = 'http://api.wunderground.com/auto/wui/geo/ForecastXML/index.xml?query=';
	var $location = '90210';
	var $icon_set = 'Incredible';
	var $measurement = 'fahrenheit';
	var $type = 'table';
	var $align = 'center';
	var $highlow = '%%high%%&deg;/%%low%%&deg;';
	var $caption = 'Weather for Beverly Hills';
	var $numdays = 6;
	var $datelabel = '%%weekday%%';
	var $todaylabel = 'Today';
	var $showlink = '';
	var $cache = true;
	var $width = '100%';

	function wp_wunderground() {

		// PHP5 only
		if(!version_compare(PHP_VERSION, '5.0.0', '>=')) {
			add_action('admin_notices', 'wpwundergroundphp5error');
			function wpwundergroundphp5error() {
				$out = '<div class="error" id="messages"><p>';
				$out .= 'The WP Wunderground plugin requires PHP5. Your server is running PHP4. Please ask your hosting company to upgrade your server to PHP5. It should be free.';
				$out .= '</p></div>';
				echo $out;
			}
			return;
		}

		// Some hosts don't support it...
		if(!function_exists('simplexml_load_string')) {
			add_action('admin_notices', 'wpwundergroundsimplexmlerror');
			function wpwundergroundsimplexmlerror() {
				$out = '<div class="error" id="messages"><p>';
				$out .= 'The WP Wunderground plugin requires the PHP function <code>simplexml_load_string()</code>. Your server has this disabled. Please ask your hosting company to enable <code>simplexml_load_string</code>.';
				$out .= '</p></div>';
				echo $out;
			}
			return;
		}


		add_action('admin_menu', array(&$this, 'admin'));
	    add_filter('plugin_action_links', array(&$this, 'settings_link'), 10, 2 );
        add_action('admin_init', array(&$this, 'settings_init') );
    	$this->options = get_option('wp_wunderground', array());
        add_shortcode('forecast', array(&$this, 'build_forecast'));

        // Set each setting...
        foreach($this->options as $key=> $value) {
        	$this->{$key} = $value;
        }

		if(!is_admin()) {
			add_action('wp_footer', array(&$this,'showlink'));
		}
	}

	function settings_init() {
        register_setting( 'wp_wunderground_options', 'wp_wunderground', array(&$this, 'sanitize_settings') );
    }

    function sanitize_settings($input) {
        return $input;
    }

    function settings_link( $links, $file ) {
        static $this_plugin;
        if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
        if ( $file == $this_plugin ) {
            $settings_link = '<a href="' . admin_url( 'options-general.php?page=wp_wunderground' ) . '">' . __('Settings', 'wp_wunderground') . '</a>';
            array_unshift( $links, $settings_link ); // before other links
        }
        return $links;
    }

    function admin() {
        add_options_page('WP Wunderground', 'WP Wunderground', 'administrator', 'wp_wunderground', array(&$this, 'admin_page'));
    }

    function admin_page() {
        ?>
        <div class="wrap">
        <h2>WP Wunderground: Weather Forecasts for WordPress</h2>
        <div class="postbox-container" style="width:65%;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <form action="options.php" method="post">
                   <?php
                    	wp_nonce_field('update-options');
                        settings_fields('wp_wunderground_options');


                       	$rows[] = array(
                                'id' => 'wp_wunderground_location',
                                'label' => __('Location', 'wp_wunderground'),
                                'content' => "<input type='text' name='wp_wunderground[location]' id='wp_wunderground_location' value='".esc_attr__($this->location)."' size='40' style='width:95%!important;' /><br /><small>The location can be entered as: ZIP code (US or Canadian); city state; city, state; city; state; country; airport code (3-letter or 4-letter); lat, long.</small>",
                                'desc' => 'The location for the forcast.'
                            );
                        $rows[] = array(
                        		'id' => 'wp_wunderground_numdays',
                                'label' => __('Days of Forecast', 'wp_wunderground'),
                                'desc' => 'How many days would you like to display in the forecast? Supports up to 6.',
                        		'content' => $this->buildDays()
                        );

                        $rows[] = array(
                        		'id' => 'wp_wunderground_measurement',
                                'label' => __('Degree (&deg;) Measurement', 'wp_wunderground'),
                                'desc' => 'Are you metric or U.S., baby?',
                        		'content' => $this->buildMeasurement()
                        );

                        $rows[] = array(
                                'id' => 'wp_wunderground_caption',
                                'label' => __('Forecast Caption', 'wp_wunderground'),
                                'content' => "<input type='text' name='wp_wunderground[caption]' id='wp_wunderground_caption' value='".esc_attr__($this->caption)."' size='40' style='width:95%!important;' />",
                                'desc' => 'This will display above the forecast. Think of it like a forecast title.'
                            );

                        $rows[] = array(
                                'id' => 'wp_wunderground_datelabel',
                                'label' => __('"All Dates" Label', 'wp_wunderground'),
                                'content' => "<input type='text' name='wp_wunderground[datelabel]' id='wp_wunderground_datelabel' value='".esc_attr__($this->datelabel)."' size='40' style='width:95%!important;' />",
                                'desc' => 'How all dates appear by default. See instructions in the "Date Formatting" section of the box on the right &rarr;'
                            );

                        $rows[] = array(
                                'id' => 'wp_wunderground_todaylabel',
                                'label' => __('"Today\'s Date" Label', 'wp_wunderground'),
                                'content' => "<input type='text' name='wp_wunderground[todaylabel]' id='wp_wunderground_todaylabel' value='".esc_attr__($this->todaylabel)."' size='40' style='width:95%!important;' />",
                                'desc' => 'How today\'s date appears (overrides All Dates format). See instructions in the "Date Formatting" section of the box on the right &rarr;'
                        );

                        $rows[] = array(
                                'id' => 'wp_wunderground_highlow',
                                'label' => __('"High/Low" Formatting', 'wp_wunderground'),
                                'desc' => 'See instructions in the "Highs &amp; Lows Formatting" section of the box on the right &rarr;',
                                'content' => "<input type='text' name='wp_wunderground[highlow]' id='wp_wunderground_highlow' value='".htmlspecialchars($this->highlow)."' size='40' style='width:95%!important;' />"
                        );


                        $rows[] = array(
                        		'id' => 'wp_wunderground_icon_set',
                                'label' => __('Icon Set', 'wp_wunderground'),
                                'desc' => 'How do you want your weather icons to look?',
                        		'content' => $this->buildIconSet()
                        );

                        $checked = (empty($this->cache) || $this->cache == 'yes') ? ' checked="checked"' : '';
                        $rows[] = array(
                                'id' => 'wp_wunderground_cache',
                                'label' => __('Use Cache', 'wp_wunderground'),
                                'desc' => 'Cache the results to prevent fetching the forecast on each page load. <strong>Highly encouraged.</strong>',
                                'content' => "<p><label for='wp_wunderground_cache'><input type='hidden' name='wp_wunderground[cache]' value='no' /><input type='checkbox' name='wp_wunderground[cache]' value='yes' id='wp_wunderground_cache' $checked /> Cache forecast results</label></p>"
                        );

                        $checked = (empty($this->showlink) || $this->showlink == 'yes') ? ' checked="checked"' : '';

                        $rows[] = array(
                                'id' => 'wp_wunderground_showlink',
                                'label' => __('Give Thanks', 'wp_wunderground'),
                                'desc' => 'Checking the box tells the world you use this free plugin by adding a link to your footer. If you don\'t like it, you can turn it off, so please enable.',
                                'content' => "<p><label for='wp_wunderground_showlink'><input type='hidden' name='wp_wunderground[showlink]' value='no' /><input type='checkbox' name='wp_wunderground[showlink]' value='yes' id='wp_wunderground_showlink' $checked /> Help show the love.</label></p>"
                        );

                        $this->postbox('wp_wundergroundsettings',__('Store Settings', 'wp_wunderground'), $this->form_table($rows), false);

                    ?>


                        <input type="hidden" name="page_options" value="<?php foreach($rows as $row) { $output .= $row['id'].','; } echo substr($output, 0, -1);?>" />
                        <input type="hidden" name="action" value="update" />
                        <p class="submit">
                        <input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes', 'wp_wunderground') ?>" />
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <div class="postbox-container" style="width:34%;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                <?php $this->postbox('wp_wundergroundhelp',__('Configuring This Plugin', 'wp_wunderground'), $this->configuration(), true);  ?>
                </div>
            </div>
        </div>

    </div>
    <?php
    }

    function showLink() {
    	if($this->showlink == 'yes') {
			mt_srand(crc32($_SERVER['REQUEST_URI'])); // Keep links the same on the same page

			$urls = array('http://seodenver.com/wunderground/?ref=foot', 'http://wordpress.org/extend/plugins/wunderground/', 'http://www.denversnow.co');
			$url = $urls[mt_rand(0, count($urls)-1)];
			$names = array('WP Wunderground', 'Wordpress Weather', 'Wunderground for WordPress');
			$name = $names[mt_rand(0, count($names)-1)];
			$kws = array('Denver Snow Removal', 'Denver Snow Plowing', 'Denver Snow Service');
			$kw = $kws[mt_rand(0, count($kws)-1)];
			$links = array(
				'The forecast for '.$this->location.' by <a href="'.$url.'">'.$name.'</a>',
				'Weather forecast by WP Wunderground &amp; <a href="'.$url.'">'.$kw.'</a>',
				'Our weather forecast is from <a href="'.$url.'">'.$name.'</a>'
			);
			$link = '<p class="wp_wunderground" style="text-align:center;">'.trim($links[mt_rand(0, count($links)-1)]).'</p>';

			echo apply_filters('wp_wunderground_showlink', $link);

			mt_srand(); // Make it random again.
    	}
    }

    function buildDays() {
    	$c = ' selected="selected"';
    	$output = '<select id="wp_wunderground_numdays" name="wp_wunderground[numdays]">';
		$output .= '	<option value="1"'; if($this->numdays == '1') { $output .= $c; } $output .= '>1 Day</option>';
		$output .= '	<option value="2"'; if($this->numdays == '2') { $output .= $c; } $output .= '>2 Days</option>';
		$output .= '	<option value="3"'; if($this->numdays == '3') { $output .= $c; } $output .= '>3 Days</option>';
		$output .= '	<option value="4"'; if($this->numdays == '4') { $output .= $c; } $output .= '>4 Days</option>';
		$output .= '	<option value="5"'; if($this->numdays == '5') { $output .= $c; } $output .= '>5 Days</option>';
		$output .= '	<option value="6"'; if($this->numdays == '6') { $output .= $c; } $output .= '>6 Days</option>';
		$output .= '</select>';
		$output .= '<label for="wp_wunderground_numdays" style="padding-left:10px;"># of Days in Forecast:</label>';
		return $output;
	}

    function buildMeasurement() {
    	$c = ' selected="selected"';
    	$output = '<select id="wp_wunderground_measurement" name="wp_wunderground[measurement]">';
		$output .= '	<option value="fahrenheit"'; if($this->measurement == 'fahrenheit') { $output .= $c; } $output .= '>U.S. (&deg;F)</option>';
		$output .= '	<option value="celsius"'; if($this->measurement == 'celsius') { $output .= $c; } $output .= '>Metric (&deg;C)</option>';
		$output .= '</select>';
		$output .= '<label for="wp_wunderground_measurement" style="padding-left:10px;">Fahrenheit or Celsius:</label>';
		return $output;
	}

    function buildIconSet() {
    	$c = ' selected="selected"';
    	$output = '<label for="wp_wunderground_icon_set" style="padding-right:10px;">Icon Set:</label>';
    	$output .= '<select id="wp_wunderground_icon_set" name="wp_wunderground[icon_set]">';
		$output .= '	<option value="Default"'; if($this->icon_set == 'Default') { $output .= $c; } $output .= '>Default</option>';
		$output .= '	<option value="Smiley"'; if($this->icon_set == 'Smiley') { $output .= $c; } $output .= '>Smiley</option>';
		$output .= '	<option value="Generic"'; if($this->icon_set == 'Generic') { $output .= $c; } $output .= '>Generic</option>';
		$output .= '	<option value="Old School"'; if($this->icon_set == 'Old School') { $output .= $c; } $output .= '>Old School</option>';
		$output .= '	<option value="Cartoon"'; if($this->icon_set == 'Cartoon') { $output .= $c; } $output .= '>Cartoon</option>';
		$output .= '	<option value="Mobile"'; if($this->icon_set == 'Mobile') { $output .= $c; } $output .= '>Mobile</option>';
		$output .= '	<option value="Simple"'; if($this->icon_set == 'Simple') { $output .= $c; } $output .= '>Simple</option>';
		$output .= '	<option value="Contemporary"'; if($this->icon_set == 'Contemporary') { $output .= $c; } $output .= '>Contemporary</option>';
		$output .= '	<option value="Helen"'; if($this->icon_set == 'Helen') { $output .= $c; } $output .= '>Helen</option>';
		$output .= '	<option value="Incredible"'; if($this->icon_set == 'Incredible') { $output .= $c; } $output .= '>Incredible</option>';
		$output .= '</select>';

		$output .= '
			<div style="margin-top:1em; text-align:center;">
			<div style="padding-right:10px; width:100px; height:75px; float:left;"><img src="http://icons-ak.wxug.com/i/c/a/clear.gif" width="50" height="50" /><img src="http://icons-ak.wxug.com/i/c/a/rain.gif" width="50" height="50" /><br />Default</div>
			<div style="padding-right:10px; width:100px; height:67px; float:left; padding-top:8px;"><img src="http://icons-ak.wxug.com/i/c/b/clear.gif" width="42" height="42" /><img src="http://icons-ak.wxug.com/i/c/b/rain.gif" width="42" height="42" /><br />Smiley</div>
			<div style="padding-right:10px; width:100px; height:75px; float:left;"><img src="http://icons-ak.wxug.com/i/c/c/clear.gif" width="50" height="50" /><img src="http://icons-ak.wxug.com/i/c/c/rain.gif" width="50" height="50" /><br />Generic</div>
			<div style="padding-right:10px; width:100px; height:67px; float:left; padding-top:8px;"><img src="http://icons-ak.wxug.com/i/c/d/clear.gif" width="42" height="42" /><img src="http://icons-ak.wxug.com/i/c/d/rain.gif" width="42" height="42" /><br />Old School</div>
			<div style="padding-right:10px; width:100px; height:67px; float:left; padding-top:8px;"><img src="http://icons-ak.wxug.com/i/c/e/clear.gif" width="42" height="42" /><img src="http://icons-ak.wxug.com/i/c/e/rain.gif" width="42" height="42" /><br />Cartoon</div>
			<div style="padding-right:10px; width:100px; height:67px; float:left; padding-top:8px;"><img src="http://icons-ak.wxug.com/i/c/f/clear.gif" width="42" height="42" /><img src="http://icons-ak.wxug.com/i/c/f/rain.gif" width="42" height="42" /><br />Mobile</div>
			<div style="padding-right:10px; width:100px; height:75px; float:left;"><img src="http://icons-ak.wxug.com/i/c/g/clear.gif" width="50" height="50" /><img src="http://icons-ak.wxug.com/i/c/g/rain.gif" width="50" height="50" /><br />Simple</div>
			<div style="padding-right:10px; width:100px; height:75px; float:left;"><img src="http://icons-ak.wxug.com/i/c/h/clear.gif" width="50" height="50" /><img src="http://icons-ak.wxug.com/i/c/h/rain.gif" width="50" height="50" /><br />Contemporary</div>
			<div style="padding-right:10px; width:100px; height:75px; float:left;"><img src="http://icons-ak.wxug.com/i/c/i/clear.gif" width="50" height="50" /><img src="http://icons-ak.wxug.com/i/c/i/rain.gif" width="50" height="50" /><br />Helen</div>
			<div style="padding-right:10px; width:100px; height:75px; float:left;"><img src="http://icons-ak.wxug.com/i/c/k/clear.gif" width="50" height="50" /><img src="http://icons-ak.wxug.com/i/c/k/rain.gif" width="50" height="50" /><br />Incredible</div>
			</div>
		';

		return $output;
    }

    function configuration() {
    $date2 = date('m-d-y');
    $date = date('m/d/Y');
    $weekday = date('l');
	$html = <<<EOD
	<h4>Adding the Forecast to your Content</h4>
	<p class="howto updated" style="padding:1em;">If you configure the settings to the left, all you will need to do is add <code>[forecast]</code> to your post or page content or text widget to add the forecast table.</p>
	<h4>Date Formatting</h4>
	<p>You can use the following tags: <code>%%weekday%%</code>, <code>%%day%%</code>, <code>%%month%%</code>, <code>%%year%%</code>, as well as using <a href="http://www.php.net/manual/en/function.date.php" target="_blank">PHP date formatting</a>.</p>
	<h4>Example date options:</h4>
	<ul>
		<li><code>Today's Weather (%%weekday%%)</code> <em>outputs as:&nbsp;</em> <strong>Today's Weather ($weekday)</strong></li>
		<li><code>%%day%%<strong>/</strong>%%month%%<strong>/</strong>%%year%% Weather</code> <em>outputs as:&nbsp;</em> <strong>$date Weather</strong></li>
		<li><code>Weather on date("m/d/Y")</code> <em>outputs as:&nbsp;</em> <strong>Weather on $date</strong></li>
		<li><code>date("\W\e\a\\t\h\e\\r\ \\f\o\\r\ m-d-y")</code> <em>outputs as:&nbsp;</em> <strong>Weather for $date2</strong></li>
	</ul>
	<hr style="padding-top:1em; outline:none; border:none; border-bottom:1px solid #ccc;"/>
	<h4>Highs &amp; Lows Formatting</h4>
	<p>Use the following tags to modify how low and high temps display: <code>%%high%%</code> and <code>%%low%%</code>.</p>
	<ul>
		<li><strong>HTML is fully supported. This allows you to color-code:</strong><br /><code>&lt;span style=&quot;color:red;&quot;&gt;%%high%%&lt;/span&gt;/&lt;span style=&quot;color:blue;&quot;&gt;%%low%%&lt;/span&gt;</code> <em>outputs as:&nbsp;</em> <span style="color:red;">85</span>/<span style="color:blue;">55</span></li>
		<li><strong>Choose to only show high or low:</strong><br /><code>High of %%high%%.</code> <em>outputs as:&nbsp;</em> High of 85.</li>
		<li><strong>Use CSS classes too:</strong><br /><code>&lt;div class=&quot;temp&quot;&gt;High of &lt;span class=&quot;temphigh&quot;&gt;%%high%%&lt;/span&gt;&lt;br /&gt;Low of &lt;span class=&quot;templow&quot;&gt;%%low%%&lt;/span&gt;&lt;/div&gt;</code> <em>outputs as:&nbsp;</em> <div class="temp">High of <span class="temphigh">85</span><br />Low of <span class="templow">55</span></div></li>
	</ul>
	<hr style="padding-top:1em; outline:none; border:none; border-bottom:1px solid #ccc;"/>

	<h4>Using the <code>[forecast]</code> Shortcode</h4>

	<p>If you're a maniac for shortcodes, and you want all control all the time, this is a good way to use it.</p>

	<p><code>[forecast location="Tokyo, Japan" caption="Weather for Tokyo" measurement='F' todaylabel="Today" datelabel="date('m/d/Y')" highlow='%%high%%&deg;/%%low%%&deg;' numdays="3" iconset="Cartoon" class="css_table_class" cache="true" width="100%"]</code></p>

	<p><strong>The shortcode supports the following settings:</strong></p>
	<ul>
		<li><code>location="Tokyo, Japan"</code> - Use any city/state combo or US/Canada ZIP code
		</li><li><code>caption="Weather for Tokyo"</code> - Add a caption to your table (it's like a title)
		</li><li><code>measurement='F'</code> - Choose Fahrenheit or Celsius by using "F" or "C"
		</li><li><code>datelabel="date('m/d/Y')"</code> - Format the way the days display ("9/30/2012" in this example)
		</li><li><code>todaylabel="Today"</code> - Format how today's date appears ("Today" in this example)
		</li><li><code>highlow='%%high%%&deg;/%%low%%&deg;'</code> - Format how the highs & low temps display ("85&deg;/35&deg;" in this example)
		</li><li><code>numdays=3</code> - Change the number of days displayed in the forecast. Up to 6 day forecast.
		</li><li><code>iconset="Cartoon"</code> - Choose your iconset from one of 10 great options
		</li><li><code>cache="true"</code> - Whether to cache forecast results. Use <code>0</code> to disable (not recommended).
		</li><li><code>width="100%"</code> - Change the width of the forecast table
	</ul>

EOD;
	return $html;
    }

    // THANKS JOOST!
    function form_table($rows) {
        $content = '<table class="form-table" width="100%">';
        foreach ($rows as $row) {
            $content .= '<tr><th valign="top" scope="row" style="width:50%">';
            if (isset($row['id']) && $row['id'] != '')
                $content .= '<label for="'.$row['id'].'" style="font-weight:bold;">'.$row['label'].':</label>';
            else
                $content .= $row['label'];
            if (isset($row['desc']) && $row['desc'] != '')
                $content .= '<br/><small>'.$row['desc'].'</small>';
            $content .= '</th><td valign="top">';
            $content .= $row['content'];
            $content .= '</td></tr>';
        }
        $content .= '</table>';
        return $content;
    }

    function postbox($id, $title, $content, $padding=false) {
        ?>
            <div id="<?php echo $id; ?>" class="postbox">
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class="hndle"><span><?php echo $title; ?></span></h3>
                <div class="inside" <?php if($padding) { echo 'style="padding:10px; padding-top:0;"'; } ?>>
                    <?php echo $content; ?>
                </div>
            </div>
        <?php
    }

	function r($content, $kill = false) {
		echo '<pre>'.print_r($content,true).'</pre>';
		if($kill) { die(); }
	}

	function true_false($value) {
		$value = trim($value);
		if(
			empty($value) ||
			$value === '' ||
			$value == 'false' ||
			$value == 'no' ||
			$value == 'off'
		) { return false; }
		return true;
	}

	function build_forecast($atts, $content=null) {
		$settings = shortcode_atts( array(
	      'location'	=>	$this->location,
	      'measurement' => 	$this->measurement,
	      'highlow' 	=> 	$this->highlow,
	      'iconset' 	=> 	$this->icon_set,
	      'align'		=>	$this->align,
	      'numdays'		=>	$this->numdays,
	      'caption'		=>	$this->caption,
	      'datelabel'	=>	$this->datelabel,
	      'todaylabel'	=>	$this->todaylabel,
	      'class'		=>	'wp_wunderground',
	      'cache'		=>	$this->cache,
	      'width'		=>	$this->width,
	      'table'		=>	false,
	      'type'		=>	'table'
	      ), $atts );
		extract( $settings );

	    // Set custom hard-coded width. Added in 1.2
	    if($this->true_false($width)) { $width = ' width="'.$width.'"';}

	    // Added in 1.2
	    $cache = $this->true_false($cache);

	    // They're hard to spell and long, man!
	    $measurement = strtolower($measurement);
	    if($measurement == 'c') { $measurement = 'celsius'; }
	    if($measurement == 'f') { $measurement = 'fahrenheit'; }

	    if($cache) {
	    	// Shorten the settings into an encrypted 40-byte string so that
		    // it's never longer than the 64-byte database column
		    foreach($settings as $k => $v) { $settings[$k] = esc_attr__($v); }
		    $transient_title = implode('_', $settings);
			$transient_title = 'wund_'.sha1($transient_title);

		 	// See if it exists already.
		 	$table = get_transient($transient_title);
		}

	    if(!$table || !$cache || isset($_REQUEST['cache'])) {
			$xmlStr = @wp_remote_fopen(trim($this->url.urlencode($location)));
			if(is_wp_error($xmlStr) || !$xml=simplexml_load_string($xmlStr)){
				#trigger_error('Error reading XML file',E_USER_ERROR);
				return '<!-- WP Wunderground Error : Error reading XML file at '.$this->url.$this->location.' -->'.$content;
			} elseif(empty($xml->simpleforecast->forecastday)) {
				return '<!-- WP Wunderground Error : Weather feed was empty from '.$this->url.$this->location.' -->'.$content;
			}

			$tablehead = $tablebody = ''; $i = 0;
			foreach($xml->simpleforecast->forecastday as $day) {
				#$this->r($day); // For debug...
				if($i < $numdays) {
					$day = simpleXMLToArray($day);
					extract($day);
					$icon_url = $this->get_icon_path($icons, $iconset).$icon.'.gif';
					$icon_size = $this->get_icon_size($iconset);
					$icon_size = " width=\"$icon_size\" height=\"$icon_size\"";
					$high = $high[$measurement];
					$low = $low[$measurement];
					$icon = '<img src="'.$icon_url.'"'.$icon_size.' alt="It is forcast to be '.$conditions.' at '.$date['pretty'].'" style="display:block;" />';
					$colwidth = round(100/$numdays, 2);

					$temp = str_replace('%%high%%', $high, $highlow);
					$temp = str_replace('%%low%%', $low, $temp);
					$temp = htmlspecialchars_decode($temp);

					$label = $this->format_date($date, $todaylabel, $datelabel);

					$tablehead .= "\n\t\t\t\t\t\t\t".'<th scope="col" width="'.$colwidth.'%" align="'.$align.'">'.$label.'</th>';

					$tablebody .=
					"\n\t\t\t\t\t\t\t".'<td align="'.$align.'" class="'.esc_attr__($class).'_'.sanitize_title($conditions).'">'.apply_filters('wp_wunderground_forecast_icon',$icon).'<div class="wp_wund_conditions">'.apply_filters('wp_wunderground_forecast_conditions',$conditions).'</div>'.apply_filters('wp_wunderground_forecast_temp',$temp).'</td>';
				}
				$i++;
			}
			if($this->true_false($caption)) {
				$caption = "\n\t\t\t\t\t<caption>{$caption}</caption>";
			}
			$table = '
				<table cellpadding="0" cellspacing="0" border="0"'.$width.' class="'.esc_attr__($class).'">'.$caption.'
					<thead>
						<tr>'.$tablehead.'
						</tr>
					</thead>
					<tbody>
						<tr>'.$tablebody.'
						</tr>
					</tbody>
				</table>';
			$table = preg_replace('/\s+/ism', ' ', $table);
			set_transient($transient_title, $table, apply_filters('wp_wunderground_forecast_cache', 60*60*6));
		}

		return apply_filters('wp_wunderground_forecast', $table);
	}

	function format_date($date, $todaylabel = false, $datelabel = false) {
		if(!$todaylabel) { $todaylabel = $this->todaylabel; }
		if(!$datelabel) { $datelabel = $this->datelabel; }
		extract($date);

		try {
			$dt = new DateTime("$year-$month-$day", new DateTimeZone($tz_long));
			$dt->setTime($hour,$min,$sec);
			$tt = new DateTime('', new DateTimeZone($tz_long));
			if($tt->format('Y-m-d') == $dt->format('Y-m-d')) { $label = $todaylabel; } else { $label = $datelabel; }
		} catch(Exception $e) {

		}

		// First we do these easy date replacements
		$label = str_replace('%%weekday%%', $weekday, $label);
		$label = str_replace('%%day%%', $day, $label);
		$label = str_replace('%%month%%', $month, $label);
		$label = str_replace('%%year%%', $year, $label);

		// Then we look for the php date() function
		preg_match('/(.*?)date\([\'"]{0,1}(.*?)[\'"]{0,1}\)(.*?)/xism', $label, $matches);
		if(!empty($matches)) {
			try {
				// If we find date(), we format the date
				// and add the before text and after text back in
				$label = $matches[1].$dt->format($matches[2]).$matches[3];
			} catch(Exception $e) {

			}
		}
		return $label;
	}

	function get_icon_path($icons, $icon_set = false) {
		if(!$icon_set) { $icon_set = $this->icon_set; }
		// This may be slightly faster; let's try this first.
		switch($icon_set) {
			case 'Default':			return 'http://icons-ak.wxug.com/i/c/a/'; break;
			case 'Smiley':			return 'http://icons-ak.wxug.com/i/c/b/'; break;
			case 'Generic':			return 'http://icons-ak.wxug.com/i/c/c/'; break;
			case 'Old School':		return 'http://icons-ak.wxug.com/i/c/d/'; break;
			case 'Cartoon':			return 'http://icons-ak.wxug.com/i/c/e/'; break;
			case 'Mobile':			return 'http://icons-ak.wxug.com/i/c/f/'; break;
			case 'Simple':			return 'http://icons-ak.wxug.com/i/c/g/'; break;
			case 'Contemporary':	return 'http://icons-ak.wxug.com/i/c/h/'; break;
			case 'Helen': 			return 'http://icons-ak.wxug.com/i/c/i/'; break;
			case 'Incredible':		return 'http://icons-ak.wxug.com/i/c/k/'; break;
		}
		// If this doesn't work, use the other method
		$this->get_icon_url($icons, $icon_set);
	}

	function get_icon_size($icon_set = false) {
		if(!$icon_set) { $icon_set = $this->icon_set; }
		switch($this->icon_set) {

			case 'Default':
			case 'Helen':
			case 'Generic':
			case 'Simple':
			case 'Contemporary':
			case 'Incredible':
								return 50;
								break;
			case 'Smiley':
			case 'Old School':
			case 'Cartoon':
			case 'Mobile':
			default:
								return 42;
								break;

		}
	}

	function get_icon_url($icons) {
		foreach($icons['icon_set'] as $icon) {
			if(strtolower(trim($icon['name'])) == strtolower(trim($this->icon_set))) {
				return $icon['icon_url'];
			}
		}
		return false;
	}

}


function simpleXMLToArray($xml,
                    $flattenValues=true,
                    $flattenAttributes = true,
                    $flattenChildren=true,
                    $valueKey='@value',
                    $attributesKey='@attributes',
                    $childrenKey='@children'){

        $return = array();
        if(!($xml instanceof SimpleXMLElement)){return $return;}
        $name = $xml->getName();
        $_value = trim((string)$xml);
        if(strlen($_value)==0){$_value = null;};

        if($_value!==null){
            if(!$flattenValues){$return[$valueKey] = $_value;}
            else{$return = $_value;}
        }

        $children = array();
        $first = true;
        foreach($xml->children() as $elementName => $child){
            $value = simpleXMLToArray($child, $flattenValues, $flattenAttributes, $flattenChildren, $valueKey, $attributesKey, $childrenKey);
            if(isset($children[$elementName])){
                if($first){
                    $temp = $children[$elementName];
                    unset($children[$elementName]);
                    $children[$elementName][] = $temp;
                    $first=false;
                }
                $children[$elementName][] = $value;
            }
            else{
                $children[$elementName] = $value;
            }
        }
        if(count($children)>0){
            if(!$flattenChildren){$return[$childrenKey] = $children;}
            else{$return = array_merge($return,$children);}
        }

        $attributes = array();
        foreach($xml->attributes() as $name=>$value){
            $attributes[$name] = trim($value);
        }
        if(count($attributes)>0){
            if(!$flattenAttributes){$return[$attributesKey] = $attributes;}
            else{$return = array_merge($return, $attributes);}
        }

        return $return;
    }

function init_wp_wunderground() {
	if(method_exists('wp_wunderground', 'wp_wunderground')) {
		$wunder = new wp_wunderground;
	}
}

add_action('plugins_loaded', 'init_wp_wunderground');

// If you want to use shortcodes in your widgets, you should!
add_filter('widget_text', 'do_shortcode');
add_filter('wp_footer', 'do_shortcode');


?>