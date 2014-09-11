<?php

/**
 * Warning : Please go through the code first before re-using the function
 * Append the character at the end of the string.
 * For Windows Servers, replace backward slashes to forward
 * 
 * @param <type> $string
 * @param <type> $char
 * @return <type> string
 */
function shrb_addTrailingChar($string, $char){
    // For window based servers
    if($char == '/'){
        $string = shrb_convertBackToForwardSlash($string);
    }
    
    //Appending the charachter at end if it already deoes not exist.
    if(substr($string, -1) != $char){
        $string .= $char;
    }
    return $string;
}

function shrb_convertBackToForwardSlash($string){

    $exp = array('\\','\\/', '\\\\','///');
    $string = str_replace($exp, '/', $string);

    return $string;
}

/**
 * Return Google Analytics for Admin Pages
 *
 * @return string
 * @author Jay Meattle
 **/
 
function get_googleanalytics() {
	$google_analytics = <<<EOD
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-12964573-5']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
EOD;
	return $google_analytics;
}

/**
 * @desc dump the sexybookmark settings from the database
 **/ 
function shrsb_dump_settings(){
    
    global $shrsb_debug;
    //data to dump
    $data = array(
		"siteurl"               => 	get_option('siteurl'),
		"version_database"      => 	get_option('SHRSBvNum'),
    "version_plugin"        => 	SHRSB_vNum,
		"apikey"                => 	get_option('SHRSB_apikey'),
		"custom_sprite"         => 	get_option('SHRSB_CustomSprite'),
		"default_spritegen" 	  => 	get_option('SHRSB_DefaultSprite'),
		"sb_plugopts"           =>	get_option('SexyBookmarks'),
    "tb_plugopts"           =>  get_option('ShareaholicTopbar')
	);
    
    if($shrsb_debug['dump_type'])
        switch($shrsb_debug['dump_type']){
            case "json":
                echo json_encode($data);	
                break;
            case "tree":
                echo shrsb_displayTree($data);
                break;
            default :
                var_export($data);    
        }
	$shrsb_debug['sb_die'] && die();
}

//Change the directory path to webpath
function shr_dir_to_path($dir){
    if(!$dir){
        return false;
    }
    //If its is a symlink, it will be resolved to origonal dir path
    $dir = shrb_addTrailingChar(realpath($dir), '/' );
    $path = get_option("siteurl");
    if(substr($path, -1) != '/'){
        $path .= '/';
    }
    $path .= substr($dir , strlen(ABSPATH));
    return $path;
}

/**
 * @desc check for the attributes in the get and post
 **/
function shrsb_get_value($method =NULL, $attr = NULL, $def=false){
    if(!$method && !$attr){
      return $def;
    }

    switch($method){
        case "get":
            if(isset($_GET) && isset($_GET[$attr]) ) 
                return $_GET[$attr];
            break;
        case "post":
            if(isset($_POST) && isset($_POST[$attr]) ) 
                return $_POST[$attr];
            break;
        default :
    }
    
    return $def;
}

/**
 * @desc log the message if logging is enabled
 **/
function shrsb_log($msg){
    global $shrsb_debug;
    if(isset($shrsb_debug) && isset($shrsb_debug['sb_log']) && $shrsb_debug['sb_log'] !== false){
            echo '<!-- log:start --><span style=color:red>'.$msg.'</span><br><!-- log:end -->';
    }
}