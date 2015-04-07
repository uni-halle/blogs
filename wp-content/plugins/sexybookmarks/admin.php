<?php
/**
 * This file holds the ShareaholicAdmin class.
 *
 * @package shareaholic
 */

/**
 * This class takes care of all of the admin interface.
 *
 * @package shareaholic
 */
class ShareaholicAdmin {
  
  /**
   * Loads before all else
   */
  public static function admin_init() {        
    ShareaholicUtilities::check_for_other_plugin();
    // workaround: http://codex.wordpress.org/Function_Reference/register_activation_hook
    if (is_admin() && get_option( 'Activated_Plugin_Shareaholic') == 'shareaholic') {
      delete_option('Activated_Plugin_Shareaholic');
       /* do stuff once right after activation */
       if (has_action('wp_ajax_nopriv_shareaholic_share_counts_api') && has_action('wp_ajax_shareaholic_share_counts_api')) {
         ShareaholicUtilities::share_counts_api_connectivity_check();
       }
    }
  }
  
  /**
   * The function called during the admin_head action.
   *
  */
  public static function admin_header() {
    ShareaholicUtilities::draw_meta_xua();
  }
  
  /**
   * Load the terms of service notice that shows up
   * at the top of the admin pages.
   */
  public static function show_terms_of_service() {
    ShareaholicUtilities::load_template('terms_of_service_notice');
  }

  /**
   * Renders footer
   */
  public static function show_footer() {
    ShareaholicUtilities::load_template('footer');
  }

  /**
   * Renders footer
   */
  public static function show_header() {
    $settings = ShareaholicUtilities::get_settings();
    $settings['base_link'] = Shareaholic::URL . '/publisher_tools/' . $settings['api_key'] . '/';
    $settings['website_settings_link'] = $settings['base_link'] . 'websites/edit?verification_key=' . $settings['verification_key'];
    ShareaholicUtilities::load_template('header', array(
      'settings' => $settings
    ));
  }

  /**
   * Renders SnapEngage
   */
  public static function include_snapengage() {
    ShareaholicUtilities::load_template('script_snapengage');
  }

  /**
   * Adds meta boxes for post and page options
   */
  public static function add_meta_boxes() {
    $post_types = get_post_types();
    // $post_types = array( 'post', 'page', 'product' );
    foreach ($post_types as $post_type) {
      add_meta_box(
        'shareaholic',
        'Shareaholic',
        array('ShareaholicAdmin', 'meta_box'),
        $post_type,
        'side',
        'low'
      );
    }
  }

  /**
   * This is the wp ajax callback for when a user
   * checks a checkbox for a location that doesn't
   * already have a location_id. After it has been
   * successfully created the id needs to be stored,
   * which is what this method does.
   */
  public static function add_location() {
    $location = $_POST['location'];
    $app_name = $location['app_name'];

    // if location id is not numeric throw bad request
    // or user lacks permissions
    // or does not have the nonce token
    // otherwise forcibly change it to a number
    if (!wp_verify_nonce( $_REQUEST['nonce'], 'shareaholic_add_location') ||
        !current_user_can('publish_posts') || !is_numeric($location['id'])) {
      header('HTTP/1.1 400 Bad Request', true, 400);
      die();
    } else {
      $location['id'] = intval($location['id']);
    }

    ShareaholicUtilities::update_options(array(
      'location_name_ids' => array(
        $app_name => array(
          $location['name'] => $location['id']
        ),
      ),
      $app_name => array(
        $location['name'] => 'on'
      )
    ));

    echo json_encode(array(
      'status' => "successfully created a new {$location['app_name']} location",
      'id' => $location['id']
    ));

    die();
  }

  /**
   * Shows the message about failing to create an api key
   */
  public static function failed_to_create_api_key() {
    ShareaholicUtilities::load_template('failed_to_create_api_key');
    if (isset($_GET['page']) && preg_match('/shareaholic-settings/', $_GET['page'])) {
      ShareaholicUtilities::load_template('failed_to_create_api_key_modal');
    }
  }


  /**
   * The actual function in charge of drawing the meta boxes.
   */
  public static function meta_box() {
    global $post;
    $settings = ShareaholicUtilities::get_settings();
    ShareaholicUtilities::load_template('meta_boxes', array(
      'settings' => $settings,
      'post' => $post
    ));
  }

  /**
   * This function fires when a post is saved
   *
   * @param int $post_id
   */
  public static function save_post($post_id) {
    // wordpress does something silly where save_post is fired twice,
    // once with the id of a revision and once with the actual id. This
    // filters out revision ids (which we don't want)
    if (!wp_is_post_revision($post_id)) {
      self::disable_post_attributes($post_id);
    }
  }

  /**
   * For each of the things that a user can disable or exclude per post,
   * we iterate through and turn add the post meta, or make it false
   * if it *used* to be true, but did not come through in $_POST
   * (because unchecked boxes are not submitted).
   *
   * @param int $post_id
   */
  private static function disable_post_attributes($post_id) {
    foreach (array(
      'disable_share_buttons',
      'disable_open_graph_tags',
      'exclude_recommendations',
      'disable_recommendations'
    ) as $attribute) {
      $key = 'shareaholic_' . $attribute;
      if (isset($_POST['shareaholic'][$attribute]) &&
          $_POST['shareaholic'][$attribute] == 'on') {
        update_post_meta($post_id, $key, true);
      } elseif (get_post_meta($post_id, $key, true)) {
        update_post_meta($post_id, $key, false);
      }
    }
  }
  

  /**
   * Enqueing styles and scripts for the admin panel
   *
   * @since 7.0.2.0
   */
  public static function enqueue_scripts() {
    if (isset($_GET['page']) && preg_match('/shareaholic/', $_GET['page'])) {
      wp_enqueue_style('shareaholic_application_css', ShareaholicUtilities::asset_url_admin('assets/application.css'), false,  ShareaholicUtilities::get_version());
      wp_enqueue_style('shareaholic_bootstrap_css', plugins_url('assets/css/bootstrap.css', __FILE__), false,  ShareaholicUtilities::get_version());
      wp_enqueue_style('shareaholic_main_css', plugins_url('assets/css/main.css', __FILE__), false,  ShareaholicUtilities::get_version());
      wp_enqueue_style('shareaholic_open_sans_css', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700');

      wp_enqueue_script('shareholic_utilities_js', ShareaholicUtilities::asset_url_admin('assets/pub/utilities.js'), false, ShareaholicUtilities::get_version());
      wp_enqueue_script('shareholic_bootstrap_js', plugins_url('assets/js/bootstrap.min.js', __FILE__), false,  ShareaholicUtilities::get_version());
      wp_enqueue_script('shareholic_jquery_custom_js', plugins_url('assets/js/jquery_custom.js', __FILE__), false,  ShareaholicUtilities::get_version());
      wp_enqueue_script('shareholic_jquery_ui_custom_js', plugins_url('assets/js/jquery_ui_custom.js', __FILE__), array('shareholic_jquery_custom_js'),  ShareaholicUtilities::get_version());
      wp_enqueue_script('shareholic_modified_reveal_js', plugins_url('assets/js/jquery.reveal.modified.js', __FILE__), array('shareholic_jquery_custom_js', 'shareholic_jquery_ui_custom_js'),  ShareaholicUtilities::get_version());
      wp_enqueue_script('shareholic_main_js', plugins_url('assets/js/main.js', __FILE__), false,  ShareaholicUtilities::get_version());
      wp_enqueue_script('shareholic_admin_js', ShareaholicUtilities::asset_url_admin('media/js/platforms/wordpress/wordpress-admin.js'), false,  ShareaholicUtilities::get_version(), true);
    }
  }

  /**
   * Puts a new menu item under Settings.
   */
  public static function admin_menu() {
    add_menu_page(
      __('Shareaholic Settings', 'shareaholic'),
      __('Shareaholic', 'shareaholic'),
      'manage_options',
      'shareaholic-settings',
      array('ShareaholicAdmin', 'admin'),
      SHAREAHOLIC_ASSET_DIR . 'img/shareaholic_16x16_2.png'
    );
    add_submenu_page(
      'shareaholic-settings',
      __('App Manager', 'shareaholic'),
      __('App Manager', 'shareaholic'),
      'manage_options',
      'shareaholic-settings',
      array('ShareaholicAdmin', 'admin')
    );
    add_submenu_page(
      'shareaholic-settings',
      __('Advanced Settings', 'shareaholic'),
      __('Advanced Settings', 'shareaholic'),
      'manage_options',
      'shareaholic-advanced',
      array('ShareaholicAdmin', 'advanced_admin')
    );
  }

  /**
   * Updates the information if passed in and sets save message.
   */
  public static function admin() {
    $settings = ShareaholicUtilities::get_settings();
    $action = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
    if(isset($_POST['already_submitted']) && $_POST['already_submitted'] == 'Y' &&
        check_admin_referer($action, 'nonce_field')) {
      echo "<div class='updated settings_updated'><p><strong>". sprintf(__('Settings successfully saved', 'shareaholic')) . "</strong></p></div>";

      /*
       * only checked check boxes are submitted, so we have to iterate
       * through the existing app locations and if they exist in the settings
       * but not in $_POST, it must have been unchecked, and it
       * should be set to 'off'
       */
      foreach (array('share_buttons', 'recommendations') as $app) {
        if (isset($settings[$app])) {
          foreach ($settings[$app] as $location => $on) {
            if (!isset($_POST[$app][$location]) && $on == 'on') {
              $_POST[$app][$location] = 'off';
            }
          }
        }
        if (!isset($_POST[$app])) {
          $_POST[$app] = array();
        }
      }

      ShareaholicUtilities::update_options(array(
        'share_buttons' => $_POST['share_buttons'],
        'recommendations' => $_POST['recommendations'],
      ));

      ShareaholicUtilities::log_event("UpdatedSettings");
      // clear cache after settings update
      ShareaholicUtilities::clear_cache();
    }

    /*
     * Just in case they've added new settings on shareaholic.com
     */
    if (ShareaholicUtilities::has_accepted_terms_of_service()) {
      $api_key = ShareaholicUtilities::get_or_create_api_key();
      ShareaholicUtilities::get_new_location_name_ids($api_key);
    }

    self::draw_deprecation_warnings();
    self::draw_admin_form();
    self::draw_verify_api_key();
  }

  /**
   * The function for the advanced admin section
   */
  public static function advanced_admin() {
    $settings = ShareaholicUtilities::get_settings();
    $api_key = ShareaholicUtilities::get_or_create_api_key();
    $action = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);

    if (!ShareaholicUtilities::has_accepted_terms_of_service()) {
      ShareaholicUtilities::load_template('terms_of_service_modal', array(
        'image_url' => SHAREAHOLIC_ASSET_DIR . 'img'
      ));
    }

    if(isset($_POST['reset_settings'])
      && $_POST['reset_settings'] == 'Y'
      && check_admin_referer($action, 'nonce_field')) {
      ShareaholicUtilities::destroy_settings();
      echo "<div class='updated settings_updated'><p><strong>"
        . sprintf(__('Settings successfully reset. Refresh this page to complete the reset.', 'shareaholic'))
        . "</strong></p></div>";
    }

    if(isset($_POST['already_submitted']) && $_POST['already_submitted'] == 'Y' &&
        check_admin_referer($action, 'nonce_field')) {
      echo "<div class='updated settings_updated'><p><strong>". sprintf(__('Settings successfully saved', 'shareaholic')) . "</strong></p></div>";
      foreach (array('disable_tracking', 'disable_og_tags', 'disable_admin_bar_menu', 'disable_debug_info', 'disable_internal_share_counts_api') as $setting) {
        if (isset($settings[$setting]) &&
            !isset($_POST['shareaholic'][$setting]) &&
            $settings[$setting] == 'on') {
          $_POST['shareaholic'][$setting] = 'off';
        } elseif (!isset($_POST['shareaholic'][$setting])) {
          $_POST['shareaholic'][$setting] = array();
        }
      }

      if (isset($_POST['shareaholic']['api_key']) && $_POST['shareaholic']['api_key'] != $api_key) {
        ShareaholicUtilities::get_new_location_name_ids($_POST['shareaholic']['api_key']);
      }

      if (isset($_POST['shareaholic']['api_key'])) {
        ShareaholicUtilities::update_options(array('api_key' => $_POST['shareaholic']['api_key']));
      }

      if (isset($_POST['shareaholic']['disable_tracking'])) {
        ShareaholicUtilities::update_options(array('disable_tracking' => $_POST['shareaholic']['disable_tracking']));
      }

      if (isset($_POST['shareaholic']['disable_og_tags'])) {
        ShareaholicUtilities::update_options(array('disable_og_tags' => $_POST['shareaholic']['disable_og_tags']));
      }
      
      if (isset($_POST['shareaholic']['disable_admin_bar_menu'])) {
        ShareaholicUtilities::update_options(array('disable_admin_bar_menu' => $_POST['shareaholic']['disable_admin_bar_menu']));
      }

      if (isset($_POST['shareaholic']['disable_debug_info'])) {
        ShareaholicUtilities::update_options(array('disable_debug_info' => $_POST['shareaholic']['disable_debug_info']));
      }
      
      if (isset($_POST['shareaholic']['disable_internal_share_counts_api'])) {
        ShareaholicUtilities::update_options(array('disable_internal_share_counts_api' => $_POST['shareaholic']['disable_internal_share_counts_api']));
      }

      ShareaholicUtilities::log_event("UpdatedSettings");
      // clear cache after settings update
      ShareaholicUtilities::clear_cache();
    }

    ShareaholicUtilities::load_template('advanced_settings', array(
      'settings' => ShareaholicUtilities::get_settings(),
      'action' => $action
    ));
  }

  /**
   * Checks for any deprecations and then shows them
   * to the end user.
   */
  private static function draw_deprecation_warnings() {
    $deprecations = ShareaholicDeprecation::all();
    if (!empty($deprecations)) {
      ShareaholicUtilities::load_template('deprecation_warnings', array(
        'deprecation_warnings' => $deprecations
      ));
    }
  }

  /**
   * Outputs the actual html for the form
   */
  private static function draw_admin_form() {
    $action = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
    $settings = ShareaholicUtilities::get_settings();

    if (!ShareaholicUtilities::has_accepted_terms_of_service()) {
      ShareaholicUtilities::load_template('terms_of_service_modal', array(
        'image_url' => SHAREAHOLIC_ASSET_DIR . 'img'
      ));
    }

    ShareaholicUtilities::load_template('settings', array(
      'shareaholic_url' => Shareaholic::URL,
      'settings' => $settings,
      'action' => $action,
      'share_buttons' => (isset($settings['share_buttons'])) ? $settings['share_buttons'] : array(),
      'recommendations' => (isset($settings['recommendations'])) ? $settings['recommendations'] : array(),
      'directory' => dirname(plugin_basename(__FILE__)),
    ));
  }

  /**
   * This function is in charge the logic for
   * showing whatever it is we want to show a user
   * about whether they have verified their api
   * key or not.
   */
  private static function draw_verify_api_key() {
    if (!ShareaholicUtilities::api_key_verified()) {
      $settings = ShareaholicUtilities::get_settings();
      $api_key = $settings['api_key'];
      $verification_key = $settings['verification_key'];
      ShareaholicUtilities::load_template('verify_api_key_js', array(
        'verification_key' => $verification_key
      ));
    }
  }
  
  /**
   * This function is in charge of determining whether to send the "get started" email
   */
   public static function welcome_email() {
     // check whether email has been sent
     if (ShareaholicUtilities::get_option('welcome_email_sent') != true) {
       ShareaholicAdmin::send_welcome_email();
       // set flag that the email has been sent
       ShareaholicUtilities::update_options(array('welcome_email_sent' => true));
     }
   }
  
  /**
   * This function is in charge of sending the "get started" email
   */
  public static function send_welcome_email() {
    $site_url = get_bloginfo('url');
    $api_key = ShareaholicUtilities::get_option('api_key');
    $payment_url = 'https://shareaholic.com/account';
    $shr_wp_dashboard_url = admin_url('admin.php?page=shareaholic-settings');
    $sign_up_link = 'https://shareaholic.com/publisher_tools/'.ShareaholicUtilities::get_option('api_key').'/verify?verification_key='.ShareaholicUtilities::get_option('verification_key').'&redirect_to='.'https://shareaholic.com/publisher_tools/'.ShareaholicUtilities::get_option('api_key').'/websites/edit?verification_key='.ShareaholicUtilities::get_option('verification_key');
    
    $to = get_bloginfo('admin_email');
    $subject = 'Thank you for installing Shareaholic for WordPress!';
    $message = "
    <p>Hi there,</p>
    
    <p>Thank you for installing Shareaholic on $site_url! You are one step closer to growing your website traffic and revenue with our award winning  all-in-one content amplification platform. Completing your set-up is easy, just follow these three easy steps and you'll be ready to go:</p>
        
    <p><strong>Step 1. Customize to your needs</strong><br /><br />
    
    Personalize the design of the Share Buttons and Related Content Recommendations App to match your website using the \"Customize\" buttons in your <a href='$shr_wp_dashboard_url'>Shareaholic App Manager in WordPress</a>, then choose where you want them to appear on your website using the checkboxes!
            
    <p><strong>Step 2: Sign-up for a free Shareaholic account</strong><br /><br />
    
    This will allow you to add more (free!) features like Analytics, Floating Share Buttons, Follow Buttons and more. <strong><a href='$sign_up_link'>Click here to sign-up</a></strong>, or <a href='$sign_up_link'>login to an existing Shareaholic account</a> and we'll automatically sync the plugin settings with your account.</p>
    
    <p><strong>Step 3: Control your earnings and setup how you would like to get paid</strong><br /><br />
    
    Decide how much you would like to earn from Promoted Content (native ads that appear in the Related Content app) and other monetization apps by editing your settings in the \"Monetization\" section of the plugin. Next, visit the \"Username and email address\" <a href='$payment_url'>section of your Shareaholic.com account</a> to add your PayPal information, so you can collect the revenue you generate from Shareaholic.</p>
    
    <p>Have questions? Simply reply to this email and we will help you out!</p>

    <p>Let's get started,<br /><br />
    
    Mary Anne & Cameron<br />
    Shareaholic Happiness Team<br />
    <a href='http://support.shareaholic.com'>support.shareaholic.com</a><br /><br />
    <img width='200' height='36' src='https://shareaholic.com/assets/layouts/shareaholic-logo.png' alt='Shareaholic' title='Shareaholic' /><br />
    <p style='font-size:12px;color:#C3C2C2;'>This is an automated, one-time e-mail sent by your WordPress CMS directly to the website admin</p><br />
    <img width='0' height='0' src='https://www.google-analytics.com/collect?v=1&tid=UA-12964573-6&cid=$api_key&t=event&ec=email&ea=open&el=$site_url-$api_key&cs=lifecycle&cm=email&cn=wp_welcome_email' />";
    
    $headers = "From: Shareaholic <hello@shareaholic.com>\r\n";
    $headers.= "Reply-To: Mary Anne <hello@shareaholic.com>\r\n";
    $headers.= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers.= "MIME-Version: 1.0\r\n";
    $headers.= "Content-type: text/html; charset=utf-8\r\n";
    
    if (function_exists('wp_mail')){
      wp_mail($to, $subject, $message, $headers);
    }
  }
}