<?php
function wrp_configuration_settings(){
  global $wrp;
  add_meta_box( 
             'wrp-cron-meta-box'
            ,__( 'Unix Cron', 'wrp' )
            ,'wrp_cron_meta_box'
            ,$wrp->settings
            ,'cron'
            ,'high'
        ); 
}
function wrp_twitter_settings(){
  global $wrp;
  
  add_meta_box( 
             'wrp-twitter-meta-box'
            ,__( 'Account Info', 'wrp' )
            ,'wrp_twitter_meta_box'
            ,$wrp->twitter
            ,'twitter'
            ,'high'
        );	
}
function wrp_facebook_settings(){
  global $wrp;
  
  add_meta_box( 
             'wrp-facebook-meta-box'
            ,__( 'Account Info', 'wrp' )
            ,'wrp_facebook_meta_box'
            ,$wrp->facebook
            ,'facebook'
            ,'high'
        );	
}
function wrp_add_new_settings(){
   global $wrp;
  
  add_meta_box( 
             'wrp-add-new-meta-box'
            ,__( 'Cron Jobs', 'wrp' )
            ,'wrp_campagins_schedule_meta_box'
            ,$wrp->addnew
            ,'schedule'
            ,'high'
        );
  
  add_meta_box( 
             'wrp-category-meta-box'
            ,__( 'Post Categories', 'wrp' )
            ,'wrp_campagins_category_meta_box'
            ,$wrp->addnew
            ,'category'
            ,'high'
        );
   add_meta_box( 
             'wrp-category-new-meta-box'
            ,__( 'Add New Category', 'wrp' )
            ,'wrp_campagins_new_category_meta_box'
            ,$wrp->addnew
            ,'new_category'
            ,'high'
        );        
   add_meta_box( 
             'wrp-number-meta-box'
            ,__( 'Feed Number', 'wrp' )
            ,'wrp_campagins_number_meta_box'
            ,$wrp->addnew
            ,'number'
            ,'high'
        );        
  add_meta_box( 
             'wrp-content-meta-box'
            ,__( 'Settings', 'wrp' )
            ,'wrp_campagins_rss_meta_box'
            ,$wrp->addnew
            ,'rss'
            ,'high'
        ); 
  add_meta_box( 
             'wrp-template-meta-box'
            ,__( 'Post Template', 'wrp' )
            ,'wrp_campagins_template_meta_box'
            ,$wrp->addnew
            ,'template'
            ,'high'
        );       
  add_meta_box( 
             'wrp-rewrite-meta-box'
            ,__( 'Rewrite Options', 'wrp' )
            ,'wrp_campagins_rewrite_meta_box'
            ,$wrp->addnew
            ,'rewrite'
            ,'high'
        ); 
  add_meta_box( 
             'wrp-exclude-meta-box'
            ,__( 'Exclude Options', 'wrp' )
            ,'wrp_campagins_exclude_meta_box'
            ,$wrp->addnew
            ,'exclude'
            ,'high'
        );                  	
}

function wrp_campaigns_page(){
   //Create an instance of our package class...
    $campaignsListTable = new Campaigns_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $campaignsListTable->prepare_items();
?>
 <div class="wrap">
        
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        <h2><?php _e( 'Campaigns List', 'wrp' ); ?></h2>
        <?php if ( isset( $_GET['success'] ) && 'true' == esc_attr( $_GET['success'] ) ) wrp_success_message(); ?>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="campaigns-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $campaignsListTable->display() ?>
        </form>
        
    </div>
    <?php   
}

function wrp_add_new_page(){
   global $wrp;
   $plugin_data = get_plugin_data( WRP_DIR . 'wp_rss_poster.php' ); 
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);
     $content = $data->content;                    
   }else{
	 $content = '';   
   }
?>
   <div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Add New Campaign', 'wrp' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) && !empty($_GET['id']) ) wrp_update_message(); ?>
       		     
        <form id="addnew" method="post">
		<input name="action" value="<?php if(!empty($_GET['id'])){echo "update";}else{echo "create";}?>" type="hidden">
		<div id="poststuff" class="metabox-holder has-right-sidebar">			               
				<div id="side-info-column" class="inner-sidebar">
					<div id="side-sortables" class="meta-box-sortables">
				   	 <?php do_meta_boxes( $wrp->addnew, 'schedule', $plugin_data ); ?>
				   	 <?php do_meta_boxes( $wrp->addnew, 'category', $plugin_data ); ?>
			         <?php do_meta_boxes( $wrp->addnew, 'new_category', $plugin_data ); ?> 
				   	 <?php do_meta_boxes( $wrp->addnew, 'number', $plugin_data ); ?>  
				    </div>
				</div>	
				<div id="post-body">
				   <div id="post-body-content">
				       <div id="titlediv">
				         <div id="titlewrap">
				             <input id="title" tabindex="1" size="30" name="name" type="text" value="<?php if(!empty($_GET['id'])){echo $data->name;}else{echo "Name";}?>"/>
				         </div><!-- #titlewrap -->
				       </div><!-- #titlediv -->
				      <?php do_meta_boxes( $wrp->addnew, 'rss', $plugin_data ); ?>
				      <?php do_meta_boxes( $wrp->addnew, 'template', $plugin_data ); ?>	 
				      <?php do_meta_boxes( $wrp->addnew, 'exclude', $plugin_data ); ?>	  
				      <?php do_meta_boxes( $wrp->addnew, 'rewrite', $plugin_data ); ?> 
				   </div><!-- #post-body-content -->
				</div><!-- post-body -->
									
		</div><!-- #poststuff -->
		<br class="clear">
        <input class="button button-primary" type="submit" value="<?php _e('Save'); ?>" name="campaign" />
        </form>
	</div><!-- .wrap -->  
<?php       
}

function wrp_configuration_page(){
global $wrp;

	$plugin_data = get_plugin_data( WRP_DIR . 'wp_rss_poster.php' );  ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) wrp_update_message(); ?>
		<h2><?php _e( 'General Settings', 'wrp' ); ?></h2>
       
        <form id="settings" method="post">		
		<div id="poststuff">			               
				<div class="metabox-holder">
					
					<div class="post-box-container column-1 cron"><?php do_meta_boxes( $wrp->settings, 'cron', $plugin_data ); ?></div>
							            
		       	</div>						
		</div><!-- #poststuff -->
     <br class="clear">
        <input class="button button-primary" type="submit" value="<?php _e('Save'); ?>" name="settings" />
        </form>
	</div><!-- .wrap -->  	
<?php	
}

function wrp_twitter_page(){
global $wrp;

	$plugin_data = get_plugin_data( WRP_DIR . 'wp_rss_poster.php' );  ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Twitter Account', 'wrp' ); ?></h2>
       
        <?php // echo S2MEMBER_CURRENT_USER_ACCESS_LEVEL; ?> 
		<div id="poststuff">			               
				<div class="metabox-holder">
					
					<div class="post-box-container column-1 twitter"><?php do_meta_boxes( $wrp->twitter, 'twitter', $plugin_data ); ?></div>
							            
		       	</div>						
		</div><!-- #poststuff -->

	</div><!-- .wrap -->  	
<?php	
}

function wrp_facebook_page(){
global $wrp;

	$plugin_data = get_plugin_data( WRP_DIR . 'wp_rss_poster.php' );  ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Facebook Account', 'wrp' ); ?></h2>
       
        <?php // echo S2MEMBER_CURRENT_USER_ACCESS_LEVEL; ?> 
		<div id="poststuff">			               
				<div class="metabox-holder">
					
					<div class="post-box-container column-1 facebook"><?php do_meta_boxes( $wrp->facebook, 'facebook', $plugin_data ); ?></div>
							            
		       	</div>						
		</div><!-- #poststuff -->

	</div><!-- .wrap -->  	
<?php		
}
?>
