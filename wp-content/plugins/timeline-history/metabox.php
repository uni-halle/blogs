<?php
if ( ! defined('ABSPATH') ) {
    die('Please do not load this file directly!');
}
/*Meta Box Creation*/

function kt_meta_msg_box_add()
{
    add_meta_box( 'history-message', 'History Note:', 'msg_fuc', 'history_post', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'kt_meta_msg_box_add' );

function msg_fuc(){
	$msg = "<h4 style='color:#008ec2'>If update button <span style='font-weight:bold'>Disbled</span>, please add unique date below <i>'History Top Title & Date'</i> section.</h4>";
	echo $msg;
}

//Register Meta Box
function kt_meta_box_add()
{
    add_meta_box( 'history-date', 'History Top Title & Date', 'kt_history_meta_box', 'history_post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'kt_meta_box_add' );

//Add field
function kt_history_meta_box( $meta_id ) {
    echo "<script src='//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js'></script>
<script>webshims.setOptions('forms-ext', {types: 'date'});
webshims.polyfill('forms forms-ext')</script>";
 //Title field
    $outline = '<label for="history_top_title" style="width:150px; display:inline-block;">'. esc_html('History Top Title (Numeric):', 'text-domain') .'</label>';
    $title_field = esc_html(get_post_meta( $meta_id->ID, 'history_top_title', true ));
    $outline .= '<input type="text" name="history_top_title" id="history_top_title" class="history_top_title" value="'. esc_attr($title_field) .'" style="width:300px;"/>';

//calender field
	global $wpdb;
    $tbl_prefix = $wpdb->prefix;	
	$result_query = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'history-date' AND (meta_value != 'NULL' OR meta_value != '')");
	if ($result_query) {	
		$outline .= "<h3 style='color:red; display:inline-block;'><span style='color:#000;'><strong>Note:</strong></span>&nbsp;Select diffrent history date below other than present in the dropdown:&nbsp;</h3>";
		$outline .= '<Select>';
		foreach ($result_query as $res) {
			$hdate = date_create($res->meta_value);
			$hdate_formate = date_format($hdate,"Y/m/d");
			$hhistory_date_value = date("d-m-Y", strtotime($hdate_formate));
	        $outline .= "<option>".$hhistory_date_value."</option>";
	    }
		$outline .= '</Select></br>';
	}
	else {
		$outline .= "<h3 style='color:red; display:inline-block;'><span style='color:#000;'><strong>Note:</strong></span>&nbsp;Select diffrent history date below.</h3></br>";
	}
    $date_field = get_post_meta( $meta_id->ID, 'history-date', true );
    $date = date_create($date_field);
    $date_formate = date_format($date,"Y/m/d");
    $history_date_value = date("Y-m-d", strtotime($date_formate));
    $outline .= '<label for="history-date" style="width:150px; display:inline-block;">History Date (MM/DD/YYYY):</label><input type="date" name="history-date" id="history-date" class="history-date" value="'. esc_attr($history_date_value) .'" style="width:300px;"><span class="require_field" style="color:red;font-size: 20px;">*</span>';
	
	
    echo $outline;
}

//Save meta Data
function save_kt_custom_meta_box_top_History($post_id, $post, $update)
{   
    if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) // KT - protect from custom fields clears while quick edit
      return;
	  
	if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "history_post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_top_history_title_val = "";
    $meta_box_history_date_val = "";
    
    if(isset($_POST["history_top_title"]))
    {
        $meta_box_top_history_title_val = sanitize_text_field($_POST["history_top_title"]); //sanitize for server level validation
    }   
    update_post_meta($post_id, "history_top_title", $meta_box_top_history_title_val);

    if(isset($_POST["history-date"])) 
    {
        $meta_box_history_date_val = date('Y-m-d H:i:s', strtotime($_POST["history-date"]));
        //for server level validation
        $meta_box_history_date_final_val = preg_replace("([^0-9/] | [^0-9-])","",$meta_box_history_date_val);
    }

    global $wpdb;
    $tbl_prefix = $wpdb->prefix;
    $cur_post_id = get_the_ID();
     $result = $wpdb->get_results( "SELECT post_id as history_dt_num FROM ".$tbl_prefix."postmeta WHERE post_id <> ".$cur_post_id." AND meta_key = 'history-date' AND meta_value = '".$meta_box_history_date_final_val."'");
    if(sizeof($result)<1){
        update_post_meta($post_id, "history-date", $meta_box_history_date_final_val);
    } else
    {
        wp_die(
            'Error, History date can not be same with other history post dates please, choose other date.', 
            'Error',  
            array( 
                'response' => 500, 
                'back_link' => true 
            )
        );
    }
}

add_action("save_post", "save_kt_custom_meta_box_top_History", 10, 3);
/* End Meta Box Creation*/ 

/*Ajax Function to detect more than one date */
function kt_post_restrict_val(){
    $ajax_nonce = wp_create_nonce( 'my-special-string' );
?>
  <script>
 jQuery( document ).ready( function( $ ) {
    $('.post-new-php.post-type-history_post input[type="submit"]#publish').prop('disabled', true);
    $(".post-type-history_post #publish").attr("title", "Please Select History Date");
    $('input.history-date').on('blur', function(){
        var history_date = $('input.history-date').val();
        var data = {
            action: 'my_action',
            security: '<?php echo $ajax_nonce; ?>',
            posting_date: history_date
        };
        
        $.post( ajaxurl, data, function( data)  {
           var go = 'ktgo';
          if(data.trim() == go) {
                $('.post-new-php.post-type-history_post input[type="submit"]#publish').prop('disabled', false);
          } else if(data.trim() == 'ktback') {
            alert('History date can not be same with other history post dates please, choose other date');
                $('.post-new-php.post-type-history_post input[type="submit"]#publish').prop('disabled', true);
          }
        });
     });
  });
  </script>
 
 
  <?php
}
add_action('admin_footer', 'kt_post_restrict_val');

//The function that handles the AJAX request
function kt_action_callback() {
    global $wpdb;
    check_ajax_referer( 'my-special-string', 'security' );
    $tbl_prefix = $wpdb->prefix;
    $cur_post_id = get_the_ID();
    $meta_box_history_date_val = date('Y-m-d H:i:s', strtotime($_POST["posting_date"]));
    $meta_box_history_date_final_val = preg_replace("([^0-9/] | [^0-9-])","",$meta_box_history_date_val);
        
    $result = $wpdb->get_results( "SELECT post_id as history_dt_num FROM ".$tbl_prefix."postmeta WHERE meta_key = 'history-date' AND meta_value = '".$meta_box_history_date_final_val."'");
    if(sizeof($result)<1){
        echo $gohead = "ktgo";
        
    } else {
        echo $gohead = "ktback";
    }  
  die();
}
add_action( 'wp_ajax_my_action', 'kt_action_callback' );