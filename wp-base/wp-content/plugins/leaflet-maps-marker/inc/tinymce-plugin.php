<?php
/**
Hook into WordPress
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'tinymce-plugin.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
add_action('admin_print_styles-post.php', 'marker_select_box_css');
add_action('admin_print_styles-post-new.php', 'marker_select_box_css');
function marker_select_box_css() {
	wp_register_style( 'lmm-tinymce-css', LEAFLET_PLUGIN_URL . 'inc/css/marker_select_box.css', array(), NULL );
	wp_enqueue_style( 'lmm-tinymce-css' );
}
add_action('init', 'mm_shortcode_button');
/**
Create Our Initialization Function
*/
function mm_shortcode_button() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
     return;
   }
   if ( get_user_option('rich_editing') == 'true' ) {
     add_filter( 'mce_external_plugins', 'lmm_add_plugin' );
     add_filter( 'mce_buttons', 'lmm_register_button' );
     add_filter( 'mce_external_plugins', 'mm_qt' );
   } else{
     add_action('admin_footer', 'mm_qt');
   }
}
function mm_qt($plugin_array) {
    $link = LEAFLET_PLUGIN_URL . 'inc/js/lmm_html_shortcode.php?leafletpluginurl='.base64_encode(LEAFLET_PLUGIN_URL);
    wp_register_script('html-dialog', $link);
    wp_enqueue_script('html-dialog');
    return $plugin_array;
}
/**
Register Button
*/
function lmm_register_button( $buttons ) {
	array_push( $buttons, "|", "mm_shortcode" );
	return $buttons;
}
/**
Register TinyMCE Plugin
*/
function lmm_add_plugin( $plugin_array ) {
	$plugin_array['mm_shortcode'] = LEAFLET_PLUGIN_URL . 'inc/js/lmm_tinymce_shortcode.php?leafletpluginurl='.base64_encode(LEAFLET_PLUGIN_URL);
	return $plugin_array;
}
add_action('wp_ajax_get_mm_list',  'get_mm_list');
function get_mm_list(){
    global $wpdb;
    $table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
    $table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';

    $l_condition = isset($_GET['q']) ? "AND l.name LIKE '%" . mysql_real_escape_string($_GET['q']) . "%'" : '';
    $m_condition = isset($_GET['q']) ? "AND m.markername LIKE '%" . mysql_real_escape_string($_GET['q']) . "%'" : '';

    $marklist = $wpdb->get_results("
            (SELECT l.id, l.name as 'name', l.createdon, 'layer' as 'type' FROM $table_name_layers as l WHERE l.id != '0' $l_condition)
            UNION
            (SELECT m.id, m.markername as 'name', m.createdon, 'marker' as 'type' FROM $table_name_markers as m WHERE  m.id != '0' $m_condition)
            order by createdon DESC LIMIT 15", ARRAY_A);
    if(isset($_GET['q']) ){
        buildMarkersList($marklist);
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php _e('Insert map','lmm') ?></title>
	<?php echo '<script type="text/javascript" src="' . site_url() . '/wp-includes/js/jquery/jquery.js"></script>'.PHP_EOL; ?>
	<?php if(!isset($_GET['mode'])): ?>
		<script type='text/javascript' src='<?php echo LEAFLET_PLUGIN_URL . 'inc/js/tinymce_popup.js' ?>'></script>
		<script type='text/javascript' src='<?php echo LEAFLET_PLUGIN_URL . 'inc/js/lmm_tinymce_shortcode.php' ?>'></script>
	<?php endif;?>
	<script type='text/javascript' src='<?php echo LEAFLET_PLUGIN_URL . 'inc/js/jquery_caret.js' ?>'></script>
	<link rel='stylesheet' href='<?php echo LEAFLET_PLUGIN_URL . 'inc/css/marker_select_box.css' ?>' type='text/css' media='all' />
</head>
<body>
<table style="width:100%;"><tr>
<td id="msb_header_description" colspan="3"><?php echo __('If no search term is entered, the latest 15 maps will be shown.','lmm'); ?></td></tr>
<tr>
<td style="width:50%;"><div id="msb_searchContainer" style="color:#666666;"><?php _e('Search','lmm'); ?> <input type="text" name="q" id="msb_serch"/></div></td>
<td><a style="font-size: 10px;Verdana,Arial,Helvetica,sans-serif; color:#666666;" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_marker" target="_blank" title="<?php esc_attr_e('create a new marker map','lmm'); ?>"><?php _e("Add new marker", "lmm") ?></a></td>
<td><a style="font-size: 10px;Verdana,Arial,Helvetica,sans-serif; color:#666666;" href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_layer" target="_blank" title="<?php esc_attr_e('create a new layer map','lmm'); ?>"><?php _e("Add new layer", "lmm") ?></a></td>
</tr>
</table>
<div id="msb_listContainer">
	<?php
	if ($marklist != NULL) {
		echo '<div id="msb_listHint">' . __('Please select the map you would like to add','lmm') . '</div>';
		buildMarkersList($marklist);
	} else {
		$no_marker_created_yet = sprintf(__('No map has been created yet. Please <a href="%s" target="_top">click here</a> to add your first one!','lmm'), LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker');
		echo '<table style="width:100%;border-top: 1px solid #CCCCCC;"><tr><td style="width:55px;">' . $no_marker_created_yet . '</td></tr></table>';
	} ?>
</div>
<table style="width:100%;"><tr>
<td style="padding-top:13px;"><div id="msb_attribution">powered by <a style="text-decoration:none;" href="http://www.mapsmarker.com" target"_blank">MapsMarker.com</a></div></td>
<td><input class="button-primary" type="button" href="#" id="msb_insertMarkerSC" value="<?php esc_attr_e('Add shortcode','lmm'); ?>" /></td></tr>
</table>
<script type="text/javascript">
(function($){
    var selectMarkerBox = {
        markerID : '',
        mapsmarkerType : '',

        init : function(){
            var self = selectMarkerBox;
	        $('#msb_insertMarkerSC').on('click', function(e){
                e.preventDefault();
                self.insert();
                self.close();
            })
            $('#msb_cancel').on('click', function(e){
                e.preventDefault();
                self.close();
            })
            $(document).on('click touchstart', '.list_item', function(e){
                e.preventDefault();
                var id = $(this).find('input[name="msb_id"]').val();
                var type = $(this).find('input[name="msb_type"]').val();
                $('.list_item.active').removeClass('active');
                $(this).addClass('active');
                self.setMarkerID(id)
                self.setMarkerType(type);
            })
            $('#msb_serch').on('keyup', function(){
                $.post('<?php if (!is_multisite()) { echo admin_url(); } else { echo get_admin_url(); } ?>admin-ajax.php?action=get_mm_list&q='+$(this).val(), function(data){
                        $('.list_item').remove();
                        $('#msb_listContainer').append(data);
                })
            })
        },
        setMarkerID : function(id) {
            selectMarkerBox.markerID = id;
        },
        setMarkerType : function(type) {
            switch (type)
            {
                case 'layer':
                    selectMarkerBox.mapsmarkerType = 'layer';
                    break;
                case 'marker':
                    selectMarkerBox.mapsmarkerType = 'marker';
                    break;
            }
        },
        getShortCode : function(){
          return '[mapsmarker '+ selectMarkerBox.mapsmarkerType +'="'+ selectMarkerBox.markerID +'"]';
        },
        insert : function() {
            if(typeof(tinyMCEPopup) !== 'undefined')
            tinyMCEPopup.editor.execCommand('mceInsertContent', false, selectMarkerBox.getShortCode());
            else
            $('#content', parent.document.body).insertAtCaret(selectMarkerBox.getShortCode());
        },
        insertMarker : function() {
            return;
        },
        insertList : function() {
            return;
        },
        close : function() {
            if(typeof(tinyMCEPopup) !== 'undefined')
            tinyMCEPopup.close();
            else
            window.parent.jQuery('#modal-content').wpdialog('close');
        }
    }
    selectMarkerBox.init();
})(jQuery)
</script>
</body>
</html>
<?php
exit;
}
function buildMarkersList($array){
?>
    <?php foreach($array as $one):
		$date_prepare = strtotime($one['createdon']);
		$date = date("Y/m/d", $date_prepare);
		if ($one['name'] == NULL) {
			$name = '(ID '. $one['id'].')';
		} else {
			$name = $one['name'] . ' (ID '. $one['id'].')';
		}

		if ($one['type'] == 'marker') {
			$maptype = __('Marker','lmm'). '<br/>ID '. $one['id'];
		} else {
			$maptype = __('Layer','lmm'). ' <br/>ID '. $one['id'];
		}
	?>
    <div class="list_item">
	<table style="width:100%;"><tr>
	<td style="width:55px;">
		<span class="name" title="<?php esc_attr_e('map type and ID','lmm');?>"><?php echo $maptype; ?></span>
	</td>
	<td valign="top">
        <span class="name" title="<?php esc_attr_e('name','lmm');?>"><strong><?php echo stripslashes(htmlspecialchars($one['name'])); ?></strong></span>
	</td>
	<td valign="top">
		<span class="date" title="<?php esc_attr_e('created on','lmm');?>"><?php echo $date; ?></span>
        <input type="hidden" value="<?php echo $one['type']?>" name="msb_type">
        <input type="hidden" value="<?php echo $one['id']?>" name="msb_id">
	</td></tr></table>
    </div>
    <?php endforeach; ?>
<?php
}
?>