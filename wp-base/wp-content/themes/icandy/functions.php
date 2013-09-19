<?php
$headerimgoptions = get_option('candy_options');
if(trim($headerimgoptions['candyheaderimage']) == "") $defaultimg = "header1"; else $defaultimg = $headerimgoptions['candyheaderimage'];
define('HEADER_TEXTCOLOR', 'eee');
define('HEADER_IMAGE', '%s/images/'.$defaultimg.'.png'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 900);
define('HEADER_IMAGE_HEIGHT', 180);

function candy_admin_header_style() {
?>
<style type="text/css">
  #headimg { background: url(<?php header_image() ?>) no-repeat; height: <?php echo HEADER_IMAGE_HEIGHT; ?>px; width: <?php echo HEADER_IMAGE_WIDTH; ?>px; }
  #headimg h1 { margin:0; padding: 52px 0 0 22px; font-size: 34px; font-weight: normal; text-shadow: #000 1px 1px 1px; font-family: Georgia; font-style: italic; text-transform: lowercase; }
  #headimg h1 a { text-decoration:none; border:0; }
  #headimg #desc { margin:0; padding:7px 0 0 22px; font-style:italic; text-shadow: #000 1px 1px 1px; }
  #headimg * { color:#<?php header_textcolor();?>; }
</style>
<?php }
function candy_header_style() {
?>
<style type="text/css">
  #banner { background: url(<?php header_image() ?>) bottom no-repeat; height: <?php echo HEADER_IMAGE_HEIGHT; ?>px; width: <?php echo HEADER_IMAGE_WIDTH; ?>px; }  
  #banner h1 { color:#<?php header_textcolor();?>; }
  #banner h1 a { color:#<?php header_textcolor();?>; }
  #banner #description { color:#<?php header_textcolor();?>; }
</style>
<?php
}
if ( function_exists('add_custom_image_header') ) {
    add_custom_image_header('candy_header_style', 'candy_admin_header_style');
} 
?>
<?php
function remove_title_attribute($subject) {
    $result = preg_replace('/title=\"(.*?)\"/','',$subject);
    return $result;
}
?>
<?php
if ( function_exists('register_sidebars') )
    register_sidebars(1,array('before_widget' => '<li>','after_widget' => '<div class="clear"></div></li>','before_title' => '<h2>','after_title' => '</h2>'));
?>
<?php
/* Theme Options */
/*****************/
class candyoptions {
    
    function includejsandstyle() {
        echo '<style type="text/css">#candy_options_form label { margin-right: 20px; }</style>';
        echo '<script type="text/javascript" src="'.get_bloginfo('template_url').'/js/admin.js"></script>';
        echo '<script type="text/javascript" src="'.get_bloginfo('template_url').'/js/jscolor/jscolor.js"></script>';
    }
    
    function getlistofpages() {
        $options = get_option('candy_options');
        $pages = get_pages();
        $excludelist = '';
        foreach($pages as $pageinfo) {
            $selected = '';
            if(in_array($pageinfo->ID,$options['candy_exclude'])) $selected = 'selected';
            if(isset($_POST['exclude_none'])) $selected = '';
            $excludelist .= '<option value="'.$pageinfo->ID.'" '.$selected.'>'.$pageinfo->post_title.'</option>';
        }
        return $excludelist;
    }
    
    function getoptions() {
        $options = get_option('candy_options');
        if (!is_array($options)) {
            $options['candyheaderimage'] = 'header1';
            $options['menu_items'] = 'pages';
            $options['candy_exclude'] = '';
            $options['backcolor'] = '#111111';
            $options['twocolcat'] = true;
            $options['sidebarloc'] = 'right';
            $options['sidebarwidth'] = 270;
            $options['metaenabled'] = false;
            $options['excerptfront'] = false;
            $options['excerptarchives'] = true;
            $options['excerptsearch'] = true;
            $options['templatetitleshow'] = true;
            $options['showcattag'] = true;
            $options['showaddtoany'] = true;
            $options['txtsizechange'] = true;
            update_option('candy_options', $options);
        }
        return $options;
    }
    
    function addoptions() {
        if(isset($_POST['candy_save'])) {
            $options = candyoptions::getoptions();
            $options['candyheaderimage'] = stripslashes($_POST['candyheaderimage']);
            if($_POST['menu_items'] == 'categories') {
                $options['menu_items'] = 'categories';
            }
            else {
                $options['menu_items'] = 'pages';
            }
            
            if(count($_POST['exclude_list']) > 0) {
                $options['candy_exclude'] = $_POST['exclude_list'];    
            }
            
            $options['backcolor'] = stripslashes($_POST['backcolor']);
            
            if($_POST['twocolcat']) {
                $options['twocolcat'] = (bool)true;
            } else {
                $options['twocolcat'] = (bool)false;
            }
            
            if($_POST['sidebarloc'] == 'right') {
                $options['sidebarloc'] = 'right';
            }
            else if($_POST['sidebarloc'] == 'left') {
                $options['sidebarloc'] = 'left';
            } else {
                $options['sidebarloc'] = 'none';
            }
            
            if($_POST['metaenabled']) {
                $options['metaenabled'] = (bool)true;
            } else {
                $options['metaenabled'] = (bool)false;
            }   
            if($_POST['excerptfront']) {
                $options['excerptfront'] = (bool)true;
            } else {
                $options['excerptfront'] = (bool)false;
            }
            if($_POST['excerptarchives']) {
                $options['excerptarchives'] = (bool)true;
            } else {
                $options['excerptarchives'] = (bool)false;
            }  
            if($_POST['excerptsearch']) {
                $options['excerptsearch'] = (bool)true;
            } else {
                $options['excerptsearch'] = (bool)false;
            }
            if($_POST['templatetitleshow']) {
                $options['templatetitleshow'] = (bool)true;
            } else {
                $options['templatetitleshow'] = (bool)false;
            }
            if($_POST['showcattag']) {
                $options['showcattag'] = (bool)true;
            } else {
                $options['showcattag'] = (bool)false;
            }
            if($_POST['showaddtoany']) {
                $options['showaddtoany'] = (bool)true;
            } else {
                $options['showaddtoany'] = (bool)false;
            }
            if($_POST['txtsizechange']) {
                $options['txtsizechange'] = (bool)true;
            } else {
                $options['txtsizechange'] = (bool)false;
            }
            $options['sidebarwidth'] = stripslashes($_POST['sidebarwidth']);
            update_option('candy_options', $options);
            
        } else if(isset($_POST['exclude_none'])) {
            $options = candyoptions::getoptions();
            $options['candy_exclude'] = '';
            update_option('candy_options',$options);
        } else if(isset($_POST['candy_reset'])) {
            delete_option('candy_options');
        } else {
            candyoptions::getoptions();
        }
        add_theme_page(__('Candy Theme Options', 'Candy'), __('Candy Theme Options', 'Candy'), 'edit_themes', basename(__FILE__), array('candyoptions', 'displayoptions'));
    }

    function displayoptions() {
        $options = candyoptions::getoptions();
    ?>
    <form action="#" method="post" enctype="multipart/form-data" name="candy_options_form" id="candy_options_form">
    <div class="wrap">
        <h2><?php _e('iCandy Theme Options'); ?></h2>
        <?php if(isset($_POST['candy_save'])) { ?>
            <div style="background: #FFF299; padding: 5px; border: 1px #FFDE99 solid;"><?php _e('Settings Saved'); ?></div>
        <?php } ?>
        <?php if(isset($_POST['candy_reset'])) { ?>
            <div style="background: #FFF299; padding: 5px; border: 1px #FFDE99 solid;"><?php _e('Default Settings Restored'); ?></div>
        <?php } ?>
        <hr>
        <h3><?php _e('Change the default header image'); ?></h3>
            <p><input type="radio" name="candyheaderimage" value="header1" <?php if($options['candyheaderimage'] == "header1") echo "checked" ?> />&nbsp;&nbsp;&nbsp;<img style="vertical-align: middle;" src="<?php bloginfo('template_url'); ?>/images/header1.png" width="300" height="60" /></p>
            <p><input type="radio" name="candyheaderimage" value="header2" <?php if($options['candyheaderimage'] == "header2") echo "checked" ?> />&nbsp;&nbsp;&nbsp;<img style="vertical-align: middle;" src="<?php bloginfo('template_url'); ?>/images/header2.png" width="300" height="60" /></p>
            <p><input type="radio" name="candyheaderimage" value="header3" <?php if($options['candyheaderimage'] == "header3") echo "checked" ?> />&nbsp;&nbsp;&nbsp;<img style="vertical-align: middle;" src="<?php bloginfo('template_url'); ?>/images/header3.png" width="300" height="60" /></p>
            <p><input type="radio" name="candyheaderimage" value="header_none" <?php if($options['candyheaderimage'] == "header_none") echo "checked" ?> />&nbsp;&nbsp;&nbsp;<?php _e('No Background Image'); ?></p>
            <br />
            <p><u><strong><?php _e('NOTE:'); ?></strong></u> <?php _e('To upload your own image as the header image, use the Custom Header settings.'); ?></p>
        <hr>
        <h3><?php _e('Page Menu or Category Menu', 'iCandy'); ?></h3>
        <p>
            <label><?php _e('Display pages or categories as dropdown menu: ', 'iCandy'); ?></label>
            <select name="menu_items">
                <option value="pages" <?php if($options['menu_items'] == "pages") { ?> selected="selected" <?php } ?> ><?php _e('Pages'); ?></option>
                <option value="categories"<?php if($options['menu_items'] == "categories") { ?> selected="selected" <?php } ?> ><?php _e('Categories'); ?></option>
            </select>
        </p>
        <hr>
        <h3><?php _e('Exclude pages from the menu', 'iCandy'); ?></h3>
        <p>
            <label><?php _e('Select the pages you want to exclude from the dropdown menu:', 'Candy'); ?></label>
            <select name="exclude_list[]" id="exclude_list" multiple="multiple" style="height: auto; vertical-align: middle;">
                <?php echo candyoptions::getlistofpages(); ?>
            </select>
            <input class="button-primary" type="submit" name="exclude_none" value="<?php _e('Exclude no pages', 'iCandy'); ?>" /><br />
            <p><?php _e('<i>For multiple selection, select while holding down the the ctrl key</i>'); ?>
        </p>
        <hr>
        <h3><?php _e('Select the background color for the theme', 'iCandy'); ?></h3>
        <p>
            <label><?php _e('Pick the background color: ', 'iCandy'); ?></label>
            <input class="color {pickerMode:'HVS'}" name="backcolor" value="<?php echo $options['backcolor']; ?>" />
        </p>
        <hr>
        <h3><?php _e('Make categories two-column display in sidebar','iCandy'); ?></h3>
        <p>
            <label style="width: 200px"><?php _e('Do you want to make the categories widget in sidebar display as two-columns instead of one ?'); ?></label>
            <input name="twocolcat" type="checkbox" value="checkbox" <?php if($options['twocolcat']) echo "checked='checked'"; ?> />
        </p>
        <hr>
        <h3><?php _e('Sidebar Options', 'iCandy'); ?></h3>
        <p>
            <label><?php _e('Select the location of sidebar: ', 'iCandy'); ?></label>
            <select name="sidebarloc">
                <option value="none" <?php if($options['sidebarloc'] == "none") { ?> selected="selected" <?php } ?>><?php _e('No Sidebar'); ?></option>
                <option value="left" <?php if($options['sidebarloc'] == "left") { ?> selected="selected" <?php } ?>><?php _e('Left Hand Side'); ?></option>
                <option value="right" <?php if($options['sidebarloc'] == "right" or trim($options['sidebarloc']) == "") { ?> selected="selected" <?php } ?>><?php _e('Right Hand Side'); ?></option>
            </select>
            <br />
            <label><?php _e('Change the width of sidebar (Minimum: 100px and Maximum: 500px)'); ?></label>
            <input type="button" name="widthDecr" value=" - " onclick="decrementWidth()" />
            <span><input style="background: none; border: none; font-weight: bold; width: 30px;" type="text" readonly="readonly" name="sidebarwidth" id="sidebarwidth" value="<?php if(trim($options['sidebarwidth'])=="") echo 270; else echo $options['sidebarwidth']; ?>" />px</span>
            <input type="button" name="widthIncr" value=" + " onclick="incrementWidth()" />
        </p>
        <hr>
        <h3><?php _e('Post Options', 'iCandy'); ?></h3>
        <p>
            <label><?php _e('Display Post Meta information on Pages (i.e Date, Author and Number of Comments) ?', 'iCandy'); ?></label>
            <input name="metaenabled" type="checkbox" value="checkbox" <?php if($options['metaenabled']) echo "checked='checked'"; ?> />
            <br /><br />
            <label><?php _e('Display Excerpts instead of full articles in following templates:', 'iCandy'); ?></label>
            <input name="excerptfront" type="checkbox" value="checkbox" <?php if($options['excerptfront']) echo "checked='checked'"; ?> /><label style="margin-left: 5px;"><?php _e('Front Page'); ?></label>
            <input name="excerptarchives" type="checkbox" value="checkbox" <?php if($options['excerptarchives']) echo "checked='checked'"; ?> /><label style="margin-left: 5px;"><?php _e('Archives Page'); ?></label>
            <input name="excerptsearch" type="checkbox" value="checkbox" <?php if($options['excerptsearch']) echo "checked='checked'"; ?> /><label style="margin-left: 5px;"><?php _e('Search results Page'); ?></label>
            <br /><br />
            <label><?php _e('Do you want to display Post Categories and Tags below every post ?'); ?></label>
            <input name="showcattag" type="checkbox" value="checkbox" <?php if($options['showcattag']) echo "checked='checked'"; ?> />
            <br /><br />
            <label><?php _e('Do you want to display Share This button below every post ?'); ?></label>
            <input name="showaddtoany" type="checkbox" value="checkbox" <?php if($options['showaddtoany']) echo "checked='checked'"; ?> />
            <br /><br />
            <label><?php _e('Show text-size increment/decrement feature ?'); ?></label>
            <input name="txtsizechange" type="checkbox" value="checkbox" <?php if($options['txtsizechange']) echo "checked='checked'"; ?> />

        </p>
        <hr>
        <h3><?php _e('Search and Archive Page Options', 'iCandy'); ?></h3>
        <p>
            <label><?php _e('Show template page title for Archives and Search Pages ?'); ?></label>
            <input name="templatetitleshow" type="checkbox" value="checkbox" <?php if($options['templatetitleshow']) echo "checked='checked'"; ?> />
        </p>
        <hr>
        <p class="submit">
            <input class="button-primary" type="submit" name="candy_save" value="<?php _e('Save Changes', 'iCandy'); ?>" />
            <input class="button-primary" type="submit" name="candy_reset" value="<?php _e('Reset to default settings', 'iCandy'); ?>" />
        </p>
    </div>
    </form>
    <?php
    }
}
add_action('admin_head', array('candyoptions', 'includejsandstyle'));
add_action('admin_menu', array('candyoptions', 'addoptions'));
?>
<?php
function mytheme_comment($comment, $args, $depth) { $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    <div id="comment-<?php comment_ID(); ?>">
        <div class="commentavatar"><?php if(function_exists('get_avatar')) { echo get_avatar($comment, '48', get_bloginfo('template_url').'/images/gravatar.png'); } ?></div>
        <div class="commentmeta">
            <p class="commentauthor"><?php comment_author_link() ?></p>
            <p><?php printf( __('%1$s at %2$s', 'default'), get_comment_time(__('F jS, Y', 'default')), get_comment_time(__('H:i', 'default')) ); ?> <?php edit_comment_link(__('Edit', 'default'),'&nbsp;&nbsp;',''); ?></p>
            <?php if ($comment->comment_approved == '0') { ?>
            <p style="font-style:italic;"><?php _e('Your comment is awaiting moderation', 'default'); ?></p>
            <?php } ?>
        </div>
        <div class="returntop">
            <p><a href="#wrapper"><?php _e("Return to top"); ?></a></p>
        </div>
        <div class="clear"></div>
        <div class="commenttxt"><?php comment_text() ?></div>
        <div class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
    </div>
<?php } ?>
<?php
function mytheme_ping($comment, $args, $depth) { $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    <div id="comment-<?php comment_ID(); ?>">
        <div class="commentmeta">
            <p class="commentauthor"><?php comment_author_link() ?></p>
            <p><?php printf( __('%1$s at %2$s', 'default'), get_comment_time(__('F jS, Y', 'default')), get_comment_time(__('H:i', 'default')) ); ?> <?php edit_comment_link(__('Edit', 'default'),'&nbsp;&nbsp;',''); ?></p>
            <?php if ($comment->comment_approved == '0') { ?>
            <p style="font-style:italic;"><?php _e('Your comment is awaiting moderation', 'default'); ?></p>
            <?php } ?>
        </div>
        <div class="returntop">
            <p><a href="#wrapper"><?php _e("Return to top"); ?></a></p>
        </div>
        <div class="clear"></div>
        <div class="commenttxt"><?php comment_text() ?></div>
        <div class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
    </div>
<?php } ?>