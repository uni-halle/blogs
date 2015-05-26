<?php
/**
* Manages activation keys in admin
*
* @author Nicolas GUILLAUME
* @since 1.0
*/
class TC_activation_key {
	static $instance;
	public $theme_name;
  public $theme_version;
  public $theme_prefix;

	function __construct ( $args ) {

    	self::$instance =& $this;

    	//extract properties from args
      list( $this -> theme_name , $this -> theme_prefix , $this -> theme_version  ) = $args;

    	//this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
      if( ! defined( 'TC_THEME_URL' ) ) {
        //adds the menu if no other plugins has already defined it
        add_action('admin_menu'                     , array( $this , 'tc_licenses_menu') );

        define( 'TC_THEME_URL' , 'http://presscustomizr.com' );
      }

      // creates our settings in the options table
      add_action('admin_init'                         , array( $this ,'tc_theme_register_option') );
      add_action('admin_init'                         , array( $this ,'tc_theme_activate_license') );
      add_action('admin_init'                         , array( $this ,'tc_theme_desactivate_license') );

    }//end of construct


    function tc_licenses_menu() {
        add_theme_page(
          sprintf( __('%1$s Key') , $this -> theme_name ),
          sprintf( __('%1$s Key') , $this -> theme_name ),
          'manage_options',
          'tc-licenses',
          array( $this , 'tc_theme_license_page' )
        );
    }


    function tc_theme_license_page() {
        ?>
        <div class="wrap">
            <?php $this -> tc_theme_license_page_content();  ?>
        </div> <!-- .wrap -->
        <?php
    }


    function tc_theme_license_page_content() {
        $license    = get_option( 'tc_' . $this->theme_prefix . '_license_key' );
        $status     = get_option( 'tc_' . $this->theme_prefix . '_license_status' );
        ?>
        <form method="post" action="options.php">

            <?php wp_nonce_field( 'tc_theme_licenses_nonce', 'tc_theme_licenses_nonce' ); ?>

            <h2><?php printf( __('%1$s Key') , $this -> theme_name ) ; ?></h2>
            <?php settings_fields('tc_' . $this->theme_prefix . '_license'); ?>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Activation key'); ?>
                        </th>
                        <td>
                            <input id="tc_<?php echo $this->theme_prefix ?>_license_key" name="tc_<?php echo $this->theme_prefix ?>_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                            <label class="description" for="tc_<?php echo $this->theme_prefix ?>_license_key"><?php _e('Enter your activation key'); ?></label>
                        </td>
                    </tr>
                    <?php if( false !== $license ) { ?>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('Activate key'); ?>
                            </th>
                            <td>
                                <?php if( $status !== false && $status == 'valid' )  : ?>
                                    <span style="color:green;"><?php _e('active'); ?></span>
                                    <input type="submit" class="button-secondary" name="tc_<?php echo $this->theme_prefix ?>_license_desactivate" value="<?php _e('Deactivate Key'); ?>"/>
                                <?php else : ?>
                                    <input type="submit" class="button-secondary" name="tc_<?php echo $this->theme_prefix ?>_license_activate" value="<?php _e('Activate Key'); ?>"/>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php submit_button(); ?>
            </form>

        <?php
    }




    function tc_theme_register_option() {
        // creates our settings in the options table
        register_setting('tc_' . $this->theme_prefix . '_license', 'tc_' . $this->theme_prefix . '_license_key', array( $this , 'tc_sanitize_license' ) );
    }




    function tc_sanitize_license( $new ) {
        $old = get_option( 'tc_' . $this->theme_prefix . '_license_key' );
        if( $old && $old != $new ) {
            delete_option( 'tc_' . $this->theme_prefix . '_license_status' ); // new license has been entered, so must reactivate
        }
        return $new;
    }



    function tc_theme_activate_license() {

        // listen for our activate button to be clicked
        if( isset( $_POST['tc_' . $this->theme_prefix . '_license_activate'] ) ) {

            // run a quick security check
            if( ! check_admin_referer( 'tc_theme_licenses_nonce', 'tc_theme_licenses_nonce' ) )
                return; // get out if we didn't click the Activate button

            // retrieve the license from the database
            $license = trim( get_option( 'tc_' . $this->theme_prefix . '_license_key' ) );


            // data to send in our API request
            $api_params = array(
                'edd_action'=> 'activate_license',
                'license'   => $license,
                'item_name' => urlencode( $this -> theme_name ) // the name of our product in EDD
            );

            // Call the custom API.
            $response = wp_remote_get( add_query_arg( $api_params, TC_THEME_URL ), array( 'timeout' => 2000, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "active" or "inactive"

            update_option( 'tc_' . $this->theme_prefix . '_license_status', $license_data->license );

        }
    }


    function tc_theme_desactivate_license() {

        // listen for our activate button to be clicked
        if( isset( $_POST['tc_' . $this->theme_prefix . '_license_desactivate'] ) ) {

            // run a quick security check
            if( ! check_admin_referer( 'tc_theme_licenses_nonce', 'tc_theme_licenses_nonce' ) )
                return; // get out if we didn't click the Activate button

            // retrieve the license from the database
            $license = trim( get_option( 'tc_' . $this->theme_prefix . '_license_key' ) );


            // data to send in our API request
            $api_params = array(
                'edd_action'=> 'deactivate_license',
                'license'   => $license,
                'item_name' => urlencode( $this -> theme_name ) // the name of our product in EDD
            );

            // Call the custom API.
            $response = wp_remote_get( add_query_arg( $api_params, TC_THEME_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "deactivated" or "failed"
            if( $license_data->license == 'deactivated' )
                delete_option( 'tc_' . $this->theme_prefix . '_license_status' );

        }
    }

}//end of class