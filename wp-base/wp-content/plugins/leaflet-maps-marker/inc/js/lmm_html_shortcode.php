<?php
header('Content-Type: text/javascript; charset=UTF-8');
//info: construct path to wp-load.php and get $wp_path
while(!is_file('wp-load.php')) {
	if(is_dir('..' . DIRECTORY_SEPARATOR)) chdir('..' . DIRECTORY_SEPARATOR);
	else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );
$lmm_options = get_option( 'leafletmapsmarker_options' );
if (!is_multisite()) { $adminurl = admin_url(); } else { $adminurl = get_admin_url(); }
$LEAFLET_PLUGIN_URL = isset($_GET['leafletpluginurl']) ? base64_decode($_GET['leafletpluginurl']) : '';

if ( isset($lmm_options['misc_tinymce_button']) && ($lmm_options['misc_tinymce_button'] == 'enabled') ):?>

jQuery(function($) {
    $(document).ready(function(){
        $('#ed_insertMap, #globe').remove();
        var html_active = true;
        if($('#wp-content-wrap').is('.html-active')){
        $('#wp-content-media-buttons').append('<a title=\'<?php esc_attr_e('Insert map','lmm'); ?>\' id = globe href=#><img src="<?=$LEAFLET_PLUGIN_URL?>inc/img/icon-tinymce.png"></a>');
        $('#ed_toolbar').append('<input type=button value=\'<?php esc_attr_e('Insert map','lmm'); ?>\' id=ed_insertMap class=ed_button title=\'<?php esc_attr_e('Insert map','lmm'); ?>\' />');

        var info = $("<div id=modal-content style='overflow: hidden' />");
        info.html('<iframe width="450" height="480" scrolling="no" src = "<?=$adminurl?>admin-ajax.php?action=get_mm_list&mode=html" />')
            info.wpdialog({
                title : '<?php esc_attr_e('Insert map','lmm'); ?>',
                dialogClass: 'wp-dialog',
                width : 450,
                height : 480,
                modal : true,
                autoOpen : false,
                closeOnEscape : true,
            });
            $(document).on('click', '#globe, #ed_insertMap', function(event) {
                info.wpdialog('open');
                return false;
            });
        }});
});
<?php endif;?>