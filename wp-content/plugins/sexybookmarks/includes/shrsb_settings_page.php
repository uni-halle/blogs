<?php

/*
 * @desc Like button Set Settings
 */

function shrsb_likeButtonSetHTML($settings,$pos = 'Bottom') {   // $pos = Bottom/Top

    ?>

    <table><tbody style ="display:none" class="likeButtonsAvailable<?php echo $pos;?>">
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Facebook Like Button', 'shrsb'); ?></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($settings["fbLikeButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="fbLikeButton<?php echo $pos;?>" id="fbLikeButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($settings["fbLikeButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="fbLikeButton<?php echo $pos;?>" id="fbLikeButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Facebook Send Button', 'shrsb'); ?></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($settings["fbSendButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="fbSendButton<?php echo $pos;?>" id="fbSendButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($settings["fbSendButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="fbSendButton<?php echo $pos;?>" id="fbSendButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Google +1 Button', 'shrsb'); ?></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($settings["googlePlusOneButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="googlePlusOneButton<?php echo $pos;?>" id="googlePlusOneButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($settings["googlePlusOneButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="googlePlusOneButton<?php echo $pos;?>" id="googlePlusOneButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Tweet Button', 'shrsb'); ?></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($settings["tweetButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="tweetButton<?php echo $pos;?>" id="tweetButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($settings["tweetButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="tweetButton<?php echo $pos;?>" id="tweetButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>

            <tr class="tabForTr likeButtonSetOptions<?php echo $pos;?>" id="likeButtonSetAlignment<?php echo $pos;?>" style="display:none">
                <td>
                    <span class="tab shrsb_option" style="display:block"><?php _e('Button Alignment (w.r.t post)', 'shrsb'); ?></span>
                </td>
                <td colspan="2">
                    <select name="likeButtonSetAlignment<?php echo $pos;?>">
                        <?php
                            print shrsb_select_option_group(
                                'likeButtonSetAlignment'.$pos, 
                                array(
                                    '0'=>__('Left Aligned', 'shrsb'),
                                    '1'=>__('Right Aligned', 'shrsb')
                                ),
                                $settings
                            );
                        ?>
                    </select>
                </td>
            </tr>
            <tr class ="tabForTr likeButtonSetOptions<?php echo $pos;?>" style="display:none">
                <td>
                    <span class="tab shrsb_option" style="display:block"><?php _e('Button Style', 'shrsb'); ?></span>
                </td>
                <td style="width:125px">
                    <select name="likeButtonSetSize<?php echo $pos;?>">
                        <?php
                            print shrsb_select_option_group(
                                "likeButtonSetSize$pos", array(
                                    '0'=>__('Standard', 'shrsb'),
                                    '1'=>__('Buttons', 'shrsb'),
                                    '2'=>__('Box', 'shrsb'),
                                ),
                                $settings
                            );
                        ?>
                    </select>
                </td>

            </tr>

            <tr class ="tabForTr likeButtonSetOptions<?php echo $pos;?>" style="display:none">
                <td>
                    <span class="tab shrsb_option" style="display:block"><?php _e('Show share counters:', 'shrsb'); ?></span>
                </td>
                <td style="width:125px">
                    <select name="likeButtonSetCount<?php echo $pos;?>">
                        <?php
                            print shrsb_select_option_group(
                                "likeButtonSetCount$pos", array(
                                    'true'=>__('Yes', 'shrsb'),
                                    'false'=>__('No', 'shrsb'),
                                ),
                                $settings
                            );
                        ?>
                    </select>
                </td>

            </tr>

            <tr class ="tabForTr likeButtonSetOptions<?php echo $pos;?>" style="display:none">
                <td rowspan="4" colspan="3" >
                    <small><?php _e('Drag to reorder.', 'shrsb'); ?></small>

                    <div style="clear: both; min-height: 1px; height: 5px; width: 100%;"></div>
                    <div id="buttonPreviews<?php echo $pos;?>" style="clear: both; max-height: 100px !important; max-width: 600px !important;"><ul>
                        <?php
                            $fbLikeHTML = '<li ><div style="display:none; cursor:move;" class="likebuttonpreview'.$pos.'">
                                        <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-fb-like"/>
                                    </div></li>';
                            $plusOneHTML = '<li><div style=" display:none; cursor:move;" class="plusonepreview'.$pos.'">
                                            <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-plus-one"/>
                                    </div></li>';

                            $fbSendHTML = '<li><div style = "display:none; cursor:move;" class="sendbuttonpreview'.$pos.' shr-fb-send">
                                        <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-fb-send"/>
                                    </div></li>';
                            $tweetButtonHTML = '<li><div style = "display:none; cursor:move;" class="tweetbuttonpreview'.$pos.' shr-tw-button">
                                        <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-tw-button"/>
                                    </div></li>';

                            foreach($settings['likeButtonOrder'.$pos] as $likeOption) {
                                switch($likeOption) {
                                    case "shr-fb-like":
                                        echo $fbLikeHTML;
                                        break;
                                    case "shr-plus-one":
                                        echo $plusOneHTML;
                                        break;
                                    case "shr-fb-send":
                                        echo $fbSendHTML;
                                        break;
                                    case "shr-tw-button":
                                        echo $tweetButtonHTML;
                                        break;
                                }
                            }
                        ?>
                    </ul></div>
                </td>
            </tr>
            <tr height="60px">
                <script>
                (function ($) {
                    var renderPlusOnes = function () {
                            var size = $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').val();
                            switch(size) {
                                case '1':
                                    size = "button";
                                    break;
                                case '2':
                                    size = "box";
                                    break;
                                default:
                                    size = "standard";
                                    break;
                            }
                            var count = $('select[name$="likeButtonSetCount<?php echo $pos;?>"]').val();
                            switch(count) {
                                case 'false':
                                    count = '';
                                    break;
                                default:
                                    count = '-count';
                                    break;
                            }
                            var classN = 'shr-plus-one-' + size + count;
                            classN = "plusonepreview<?php echo $pos;?> "  + classN;
                            $('.plusonepreview<?php echo $pos;?>').removeClass().addClass(classN);

                    };
                    $('select[name$="likeButtonSetCount<?php echo $pos;?>"],select[name$="likeButtonSetSize<?php echo $pos;?>"]').change(function () {
                        renderPlusOnes();
                    });

                    renderPlusOnes();
                    
                    var renderTweetButton = function () {
                        var layout = $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').val();
                        switch(layout) {
                            case '1':
                                layout = "button";
                                break;
                            case '2':
                                layout = "box";
                                break;
                            default:
                                layout = "standard";
                                break;
                        }
                        var count = $('select[name$="likeButtonSetCount<?php echo $pos;?>"]').val();
                        switch(count) {
                                case 'false':
                                    count = '';
                                    break;
                                default:
                                    count = '-count';
                                    break;
                            }
                        var classN = 'shr-tw-button-' + layout + count;
                        classN = "tweetbuttonpreview<?php echo $pos;?> "  + classN;
                        $('.tweetbuttonpreview<?php echo $pos;?>').removeClass().addClass(classN);
                    };

                    $('select[name$="likeButtonSetCount<?php echo $pos;?>"],select[name$="likeButtonSetSize<?php echo $pos;?>"]').change(function () {
                        renderTweetButton();
                    });
                    renderTweetButton();


                    var renderLikeButtonPreview = function () {
                        var layout = $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').val();
                        switch(layout) {
                            case '1':
                                layout = "button";
                                break;
                            case '2':
                                layout = "box";
                                break;
                            default:
                                layout = "standard";
                                break;
                        }
                        var classN = 'shr-fb-like-' + layout;
                        classN = "likebuttonpreview<?php echo $pos;?> "  + classN;
                        $('.likebuttonpreview<?php echo $pos;?>').removeClass().addClass(classN);
                    };

                    $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').change(function () {
                        renderLikeButtonPreview();
                    });
                    renderLikeButtonPreview();
                })(jQuery);
            </script>
            </tr>
            <tr></tr>
            <tr></tr>


<?php 

}

function shrsb_right_side_menu(){
    ?>

    <div id="shrsb-col-right">

        <h2 class="sh-logo"></h2>

        <div class="box-right">
            <div class="box-right-head">
                <h3 class="fugue f-info-frame"><?php _e('Helpful Plugin Links', 'shrsb'); ?></h3>
            </div>
            <div class="box-right-body">
                <div class="padding">
                    <ul class="infolinks">
                        <li><a href="https://shareaholic.com/tools/wordpress/usage-installation" target="_blank"><?php _e('Installation &amp; Usage Guide', 'shrsb'); ?></a></li>
                        <li><a href="https://shareaholic.com/tools/wordpress/faq" target="_blank"><?php _e('Frequently Asked Questions', 'shrsb'); ?></a></li>
                        <li><a href="http://support.shareaholic.com/" target="_blank"><?php _e('Bug Submission Form', 'shrsb'); ?></a></li>
                        <li><a href="http://support.shareaholic.com/" target="_blank"><?php _e('Feature Request Form', 'shrsb'); ?></a></li>
                        <li><a href="https://shareaholic.com/tools/wordpress/translations" target="_blank"><?php _e('Submit a Translation', 'shrsb'); ?></a></li>
                        <li><a href="https://shareaholic.com/tools/browser/" target="_blank"><?php _e('Shareaholic Browsers Add-ons', 'shrsb'); ?></a></li>
                        <li><a href="https://shareaholic.com/tools/wordpress/credits" target="_blank"><?php _e('Thanks &amp; Credits', 'shrsb'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
		
		<div style="clear:both;"></div>
		
        <div style="padding:15px; margin-bottom: 20px;">
			<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FShareaholic&amp;layout=standard&amp;show_faces=true&amp;width=240&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:240px; height:80px;" allowTransparency="true"></iframe>
		</div>

        <div id="shrsb-updates">
            <div id="shrsb-updates-container"></div>
             <script async="true" type="text/javascript" src="//dtym7iokkjlif.cloudfront.net/media/js/platforms/wordpress/wordpress-admin.js"></script>
        </div>

    </div>

    <?php
}



/**
 * Return SnapEngage Help Tab
 *
 * @return string
 * @author Jay Meattle
 **/
 
function get_snapengage() {
	$snapengage = <<<EOD
<!-- SnapEngage -->
<script type="text/javascript">
document.write(unescape("%3Cscript src='" + ((document.location.protocol=="https:")?"https://snapabug.appspot.com":"http://www.snapengage.com") + "/snapabug.js' type='text/javascript'%3E%3C/script%3E"));</script><script type="text/javascript">
SnapABug.setDomain('shareaholic.com');
SnapABug.addButton("62fa2e8b-38a9-4304-ba5c-86503444d30c","1","85%");
</script>
<!-- SnapEngage End -->
EOD;
	return $snapengage;
}

function shrsb_getfooter(){

?>
<div style="clear:both;"></div>
<ul id="shrsb-sortables" style="width:96%;">
	<li style="margin:0px;">
	<div class="footer">
		<a href="https://shareaholic.com/?src=wp_admin" target="_blank">Shareaholic for WordPress <?php echo SHRSB_vNum; ?></a> <span class="grey_light">|</span> <a href="https://shareaholic.com/privacy/?src=wp_admin" target="_blank">Privacy Policy</a> <span class="grey_light">|</span> <a href="https://shareaholic.com/terms/?src=wp_admin" target="_blank">Terms of Service</a> <span class="grey_light">|</span> <a href="http://support.shareaholic.com/" target="_blank">Support</a> <span class="grey_light">|</span> <a href="https://shareaholic.com/api/?src=wp_admin" target="_blank">API</a> <span class="grey_light">|</span> <a href="https://shareaholic.com/publishers/analytics/<?php $parse = parse_url(get_bloginfo('url')); echo $parse['host']; ?>/" target="_blank">Social Analytics</a> <br /> If you like this plugin and find it useful, please consider showing your support by <a href="http://wordpress.org/support/view/plugin-reviews/shareaholic" target="_blank" style="font-weight:bold;">giving us a good rating</a> on WordPress.org.  Thank you for using <a href="">Shareaholic</a>.
	</div>
	<br />
	<div style="display:block; font-size: 11px; color: #777777;">
		<?php _e("Shareaholic is trusted by over 200 thousand publishers and touches almost 300 million people each month.  Designed and built with all the love in the world in Boston, Massachusetts."); ?>
	</div>
	</li>
</ul>
<?php
}

/**
 * Gets the contents of a url on www.shareaholic.com.  We use shrbase as the
 * URL base path.  The caller is responsible for keeping track of whether the
 * cache is up-to-date or not.  If the cache is stale (because some argument
 * has changed), then the caller should pass true as the second argument.
 *
 * @url        - the partial url without base.  ex. /publishers
 * @path       - path to cache result to, under spritegen.
 *               ex. /publishers.html
 *               pass null to use the path part of url
 * @clearcache - force call and overwrite cache.
 */
function _shrsb_fetch_content($url, $path, $clearcache=false) {
  global $shrsb_plugopts;

  $shrbase = $shrsb_plugopts['shrbase']?$shrsb_plugopts['shrbase']:'http://www.shareaholic.com';

  if (!preg_match('|^/|', $url)) {
    @error_log("url must start with '/' in _shrsb_fetch_content");
    return FALSE;
  }

  // default path
  if (null === $path) {
    $url_parts = explode('?', $url);
    $path = rtrim($url_parts[0], '/');
  }

  $base_path = path_join(SHRSB_UPLOADDIR, 'spritegen');
  $abs_path = $base_path.$path;

  if ($clearcache || !($retval = _shrsb_read_file($abs_path))) {
    $response = wp_remote_get($shrbase.$url);
    if (is_wp_error($response)) {
      @error_log("Failed to fetch ".$shrbase.$url);
      $retval = FALSE;
    } else {
      $retval = $response['body'];
    }

   $write_succeed = _shrsb_write_file($abs_path, $retval);
   if(!$write_succeed) {
       $retval = FALSE;
   }
  }

  return $retval;
}


//Copy the file in to the requested folder
function _shrsb_copy_file($des , $src){
    if(!$des || !$src )
        return false;
    return _shrsb_write_file($des ,_shrsb_read_file($src));
}

function _shrsb_write_file($path, $content) {
  $dir = dirname($path);
  $return = false;
  if(!wp_mkdir_p(dirname($path))) {
    @error_log("Failed to create path ".dirname($path));
  }
  $fh = fopen($path, 'w+');
  if (!$fh) {
    @error_log("Failed to open ".$path);
  } 
  else {
    if (!fwrite($fh, $content)) {
      @error_log("Failed to write to ".$path);
    } else {
        $return = true;
    }
    @fclose($fh);
  }
  return $return;
}

function _shrsb_read_file($path) {
  $content = FALSE;

  $fh = @fopen($path, 'r');
  if (!$fh) {
    @error_log("Failed to open ".$path);
  } 
  else {
    if (!$content = fread($fh, filesize($path))) {
      @error_log("Failed to read from ".$path);
    }
    @fclose($fh);
  }

  return $content;
}


function get_sprite_file($opts, $type) {
  global $shrsb_plugopts;
  $shrbase = $shrsb_plugopts['shrbase']?$shrsb_plugopts['shrbase']:'http://www.shareaholic.com';
  $spritegen = $shrbase.'/api/sprite/?v=1&apikey=8afa39428933be41f8afdb8ea21a495c&imageset=60'.$opts.'&apitype='.$type;
  $filename = SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.'.$type;
  $content = FALSE;

  if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
        // the spritegen folder isn't writable. Try changing it to writable
      @chmod(SHRSB_UPLOADDIR.'spritegen', 0775);
      // may or may not work
  }
  if ( $type == 'png' ) {
    $fp_opt = 'rb';
  }
  else {
    $fp_opt = 'r';
  }

  if(function_exists('wp_remote_retrieve_body') && function_exists('wp_remote_get') && function_exists('wp_remote_retrieve_response_code')) {
    $request = wp_remote_get(
      $spritegen,
      array(
        'user-agent' => "shr-wpspritebot-fopen/v" . SHRSB_vNum,
        'headers' => array(
          'Referer' => get_bloginfo('url')
        )
      )
    );
    $response = wp_remote_retrieve_response_code($request);
    if($response == 200 || $response == '200') {
      $content = wp_remote_retrieve_body($request);
    }
    else {
      $content = FALSE;
    }
  }

  if ( $content === FALSE && function_exists('curl_init') && function_exists('curl_exec') ) {
	  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $spritegen);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_USERAGENT, "shr-wpspritebot-cURL/v" . SHRSB_vNum);
    curl_setopt($ch, CURLOPT_REFERER, get_bloginfo('url'));
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);

    $content = curl_exec($ch);

    if ( curl_errno($ch) != 0 ) {
      $content = FALSE;
    }
    curl_close($ch);
  }

  if ( $content !== FALSE ) {
    if ( $type == 'png' ) {
      $fp_opt = 'w+b';
    }
    else {
      $fp_opt = 'w+';
    }

    
    $fp = @fopen($filename, $fp_opt);

    if ( $fp !== FALSE ) {
      $ret = @fwrite($fp, $content);
      @fclose($fp);
    }
    else {
      $ret = @file_put_contents($filename, $content);
    }

    if ( $ret !== FALSE ) {
      @chmod($filename, 0666);
      return 0;
    }
    else {
      return 1;
    }
  }
  else {
    return 2;
  }
}


function shrsb_preFlight_Checks() {
	global $shrsb_plugopts;

    //Check for the directory exists or not
    if(!wp_mkdir_p(SHRSB_UPLOADDIR.'spritegen/')) {
        @error_log("Failed to create path ".dirname($path));
    }
    if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
        // the spritegen folder isn't writable. Try changing it to writable
      @chmod(SHRSB_UPLOADDIR.'spritegen/', 0775);
      // may or may not work
  }
  
	if( ((function_exists('curl_init') && function_exists('curl_exec')) || function_exists('file_get_contents'))
            && (is_dir(SHRSB_UPLOADDIR) && is_writable(SHRSB_UPLOADDIR))
            && ((isset($_POST['bookmark']) && is_array($_POST['bookmark']) && sizeof($_POST['bookmark']) > 0 ) || (isset($shrsb_plugopts['bookmark']) && is_array($shrsb_plugopts['bookmark']) && sizeof($shrsb_plugopts['bookmark']) > 0 ))
            && (!isset($shrsb_plugopts['custom-mods']) ||  isset($shrsb_plugopts['custom-mods']) && $shrsb_plugopts['custom-mods'] !== 'yes') ) {

		return true;
	}
	else {
		return false;
	}
}

/* Adds FB Namespace */
function shrsb_addFBNameSpace($attr) {
    $attr .= "\n xmlns:og=\"http://opengraphprotocol.org/schema/\"";
	$attr .= "\n xmlns:fb=\"http://www.facebook.com/2008/fbml\"";
    return $attr;
}

//list all bookmarks in the plugin options page
function shrsb_network_input_select($name, $id, $hint) {
	global $shrsb_plugopts;
	return sprintf('<li class="%s" title="%s"><input %sname="bookmark[]" type="checkbox" value="%s"  id="%s" /><div style="margin-top:-8px;"></div>%s</li>',
		"shr-".$id,
		$hint,
		@in_array($name, $shrsb_plugopts['bookmark'])?'checked="checked" ':"",
		$name,
		$name,
		shrsb_truncate_text(end(explode('-', $name)), 9)
	);
}

function shrsb_truncate_text($text, $nbrChar, $append='..') {
     if(strlen($text) > $nbrChar) {
          $text = substr($text, 0, $nbrChar);
          $text .= $append;
     }
     return $text;
}

// returns the option tag for a form select element
// $opts array expecting keys: field, value, text
function shrsb_form_select_option($opts,$settings = NULL) {
	global $shrsb_plugopts;
    
    if($settings == NULL) $settings = $shrsb_plugopts;
    
	$opts=array_merge(
		array(
			'field'=>'',
			'value'=>'',
			'text'=>'',
		),
		$opts
	);
	return sprintf('<option%s value="%s">%s</option>',
		($settings[$opts['field']]==$opts['value'])?' selected="selected"':"",
		$opts['value'],
		$opts['text']
	);
}

// given an array $options of data and $field to feed into shrsb_form_select_option
function shrsb_select_option_group($field, $options,$settings = NULL) {
	$h='';
	foreach ($options as $value=>$text) {
		$h.=shrsb_form_select_option(
            array(
                'field'=>$field,
                'value'=>$value,
                'text'=>$text,
            ),
            $settings
        );
	}
	return $h;
}

// returns the HTML of options for menu display in type 
function shrsb_options_menu_type($pageorpost){
	?>
	
	<span class="shrsb_option"><?php _e('Posts, pages, categories or the whole shebang?', 'shrsb'); ?></span>
	<input type="checkbox" id="type_post" name="content_type[]"  value="post" <?php echo (false!==strpos($pageorpost,"post"))? 'checked' : ""; ?>/><label for="type_post" class="padding"><?php _e('posts', 'shrsb'); ?></label>
	<input type="checkbox" id="type_page" name="content_type[]"  value="page" <?php echo (false!==strpos($pageorpost,"page"))? 'checked' : ""; ?>/><label for="type_page" class="padding"><?php _e('pages', 'shrsb'); ?></label>
	<input type="checkbox" id="type_index" name="content_type[]"  value="index" <?php echo (false!==strpos($pageorpost,"index"))? 'checked' : ""; ?>/><label for="type_index"  class="padding"><?php _e('main index', 'shrsb'); ?></label>
	<input type="checkbox" id="type_category" name="content_type[]"  value="category" <?php echo (false!==strpos($pageorpost,"category"))? 'checked' : ""; ?>/><label for="type_category" class="padding"><?php _e('category index', 'shrsb'); ?></label>
	
	<?php
}

/*
*   @desc For setting the content type which are enabled
*/
function shrsb_set_content_type() {
    $type  = "";
    $content = @$_POST['content_type'];
    if(empty ($content)){
        $type  = "postpageindexcategory";
    }else{
        $n = count($content);
        for($i = 0; $i < $n; $i++){
            $type .= $content[$i];
        }
    }
    return $type;
}                       
?>