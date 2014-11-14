<?php

defined('WYSIJANLP') or die('Restricted access');

/**
 * class managing the admin vital part to integrate
 */
class WYSIJANLP_help_premium extends WYSIJA_help {

    function WYSIJANLP_help_premium() {
        parent::WYSIJA_help();
    }

    function init() {
        //add the hook only when necessary when we're at 2.1.6
        add_action('wysija_remove_action_check_total_subscribers', array($this, 'remove_checkTotalSubscribers'));
        $model_config = WYSIJA::get('config', 'model');

        if ($model_config->getValue('premium_key')) {
            add_action( 'mpoet_sending_frequency', array($this, 'remove_sending_frequencies'));
        }
        if (version_compare(WYSIJA::get_version(), '2.1.5') > 0) {
            //add premium hooks
            //only load the filters or hook which need to be loaded
           if (is_admin()) {
                //controllers/ajax/campaigns.php
                add_filter('wysija_send_spam_test', array($this, 'send_spam_test'), 10, 2);

                //controllers/ajax/campaigns.php
                add_filter('wysija_install_theme_premium', array($this, 'install_theme_premium'));

                // advanced menu
                add_filter('wysija_menus', array($this, 'wysija_menus'));

                if (isset($_REQUEST['page'])) {
                    if (strpos($_REQUEST['page'], 'wysija_') !== false) {
                        //controllers/back.php
                        add_action('wysija_various_check', array($this, 'various_checks'));


                        // filter to remove the "add your own stars and keep this plugin essentially free"
                        if ($model_config->getValue('premium_key')) {
                            add_filter('wysija_footer_add_stars', array($this, 'footer_add_stars'), 11);
                            add_filter('mpoet_premium_stats_link', array($this, 'update_page_stats_link'), 11);

                        }
                    }

                    switch ($_REQUEST['page']) {
                        case 'wysija_config':
                            //wp_enqueue_script('wysija-premium-admin-config', WYSIJANLP_URL.'js/admin-config.js', array( 'jquery' ), true, WYSIJA::get_version());
                            //wp_enqueue_style('wysija-premium-admin-config', WYSIJANLP_URL.'css/admin-config.css', array( 'jquery' ), true, WYSIJA::get_version());

                            $helper_extend_config = WYSIJA::get('extend_config', 'helper', false, WYSIJANLP);

                            //views/back/config.php
                            add_filter('wysija_extend_settings', array($helper_extend_config, 'premium_tabs_name'));
                            //add_filter('wysija_extend_settings_content', array($helper_extend_config, 'premium_tab_content'), 10, 2);

                            // allow bounce handling to be displayed on non multisite and on multisite which have


                            if ((is_multisite() && WYSIJA::current_user_can('manage_network')) || !is_multisite()) {
                                add_filter('wysija_extend_settings_content', array($helper_extend_config, 'bounce_tab_content'), 11, 2);
                            }

                            add_filter('wysija_settings_advanced', array($helper_extend_config, 'advanced_settings_fields_filter1'), 11);
                            add_filter('wysija_settings_advancednext', array($helper_extend_config, 'advanced_settings_fields_filter2'));
                            add_filter('wysija_sending_frequency', array($helper_extend_config, 'wysija_sending_frequency'));

                            add_filter('wysija_extend_cron_config', array($helper_extend_config, 'add_text_cron_premium'));

                            $helper_extend_statistics = WYSIJA::get('extend_statistics', 'helper', false, WYSIJANLP);

                            add_filter('hook_settings_super_advanced', array($helper_extend_statistics, 'hook_settings_super_advanced'), 11, 2);
                            add_filter('hook_settings_before_save', array($helper_extend_statistics, 'hook_settings_before_save'), 11, 2);
                            add_filter('wysija_capabilities', array($helper_extend_config, 'wysija_capabilities'), 11, 2);

                            break;
                        case 'wysija_subscribers':
                            $helper_extend_links = WYSIJA::get('extend_links', 'helper', false, WYSIJANLP);
                            add_filter('wysija_link', array($helper_extend_links, 'render_link'), 11, 2);
                            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') {
                                $helper_extend_statistics = WYSIJA::get('extend_statistics', 'helper', false, WYSIJANLP);
                                add_filter('hook_subscriber_left', array($helper_extend_statistics, 'hook_subscriber_left'), 11, 2);
                                add_filter('hook_subscriber_right', array($helper_extend_statistics, 'hook_subscriber_right'), 11, 2);
                                add_filter('hook_subscriber_bottom', array($helper_extend_statistics, 'hook_subscriber_bottom'), 11, 2);
                            }
                            break;

                        case 'wysija_campaigns':
                            $helper_extend_campaigns = WYSIJA::get('extend_campaigns', 'helper', false, WYSIJANLP);
                            //controllers/back/campaigns.php
                            add_action('wysija_manual_send', array($helper_extend_campaigns, 'manual_send'));

                            //views/back/campaigns.php
                            add_filter('wysija_send_ok', array($helper_extend_campaigns, 'can_site_send'), 11);
                            add_filter('wysija_links_stats',array($helper_extend_campaigns,'link_stats'),11,2); // not in use, since 2.7
                            add_filter('wysija_extend_step3', array($helper_extend_campaigns, 'extend_step3'));
                            add_filter('wysija_howspammy', array($helper_extend_campaigns, 'how_spammy'));

                            $helper_extend_links = WYSIJA::get('extend_links', 'helper', false, WYSIJANLP);
                            add_filter('wysija_link', array($helper_extend_links, 'render_link'), 11, 5);

                            // amchart library and hooks for the page campaigns/stats
                            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'viewstats') {
                                wp_enqueue_script('amcharts', WYSIJANLP_URL . "js/amcharts/amcharts.js", array(), WYSIJA::get_version());
                                $helper_extend_statistics = WYSIJA::get('extend_statistics', 'helper', false, WYSIJANLP);
                                add_filter('hook_newsletter_top', array($helper_extend_statistics, 'hook_newsletter_top'), 11, 2);
                                add_filter('hook_newsletter_bottom', array($helper_extend_statistics, 'hook_newsletter_bottom'), 11, 2);
                            }

                            break;
                        case 'wysija_statistics':
                            wp_enqueue_script('amcharts', WYSIJANLP_URL . "js/amcharts/amcharts.js", array(), WYSIJA::get_version());
                            $helper_extend_statistics = WYSIJA::get('extend_statistics', 'helper', false, WYSIJANLP);
                            add_filter('hook_stats', array($helper_extend_statistics, 'hook_stats'), 11, 2);
                            add_filter('custom_module_hook', array($helper_extend_statistics, 'custom_module_hook'), 11, 4);
                            break;
                        default:
                            break;
                    }
                }
                // ajax pages
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'wysija_ajax') {
                    switch ($_REQUEST['controller']) {
                        case 'statistics':
                            $helper_extend_statistics = WYSIJA::get('extend_statistics', 'helper', false, WYSIJANLP);
                            add_filter('custom_module_hook', array($helper_extend_statistics, 'custom_module_hook'), 11, 4);
                            break;
                    }
                }

                // install the central bounce table if we are on a multisite case and that the bounce table doesn't exist
                if (is_multisite()) {
                    $model_user = WYSIJA::get('user', 'model');
                    if (!$model_user->query("SHOW TABLES like '" . $model_user->get_site_prefix() . "bounce';")) {
                        $helper_install = WYSIJA::get('install', 'helper');
                        $helper_install->createTables(dirname(dirname(__FILE__)) . DS . 'sql' . DS . 'ms_install.sql', true);
                    }

                    $helper_extend_config = WYSIJA::get('extend_config', 'helper', false, WYSIJANLP);

                    add_filter('mpoet_ms_override',array($helper_extend_config, 'ms_override'));

                }

            }

        }

        //if the licence is not active then we need to send a request to wysija.com only in the case of a multisite
        //this allow the possibility of automatic domain recording
        $model_config = WYSIJA::get('config', 'model');
        if (is_multisite() && defined('WP_ADMIN') && isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'wysija_') !== false && !$model_config->getValue('premium_key')) {
            // we don't want to check a hundred times one child site automatically especially on a multisite with a thousand of sites.
            // so we set the automatic check to be lower than 4 tries
            // this means that once 3 tries are done then the activation will need to be done manually
            $number_of_checks = (int) get_option('wysija_check_premium');
            if ($number_of_checks < 4) {
                $helper_licence = WYSIJA::get('licence', 'helper');
                $res = $helper_licence->check(true);
                if (isset($res['result']) && !$res['result'])
                    WYSIJA::update_option('wysija_check_premium', (int) $number_of_checks + 1);
            }else {
                /* $hLicence=WYSIJA::get('licence','helper');
                  $dt=$hLicence->getDomainInfo();
                  $this->notice(str_replace(array('[link]','[/link]'),array('<a href="http://www.wysija.com/?wysijap=checkout&wysijashop-page=1&controller=orders&action=checkout&wysijadomain='.$dt.'" target="_blank">','</a>'),
                  __('Premium plugin is active on your site, but there is no licence corresponding to it. Purchase it [link]here[/link].',WYSIJA))); */
            }
        }


        // if we are on a  premium site, the trigger will be made from wysija.com
        if (defined('WP_ADMIN')) {
            if ($model_config->getValue('premium_key')) {
                $need_to_update_cron_wysija = false;
                // if we detect a premium with the any page view option on (cron_page_hit_trigger === 1)
                if ((int) $model_config->getValue('cron_page_hit_trigger') === 1) {

                    // we will change the value to cron_page_hit_trigger === 2 meaning that the scheduled tasks can only be processed using the specific cron url with a unique token
                    // this means that only wysija.com can trigger it
                    $model_config->save(array('cron_page_hit_trigger' => 2));

                    $need_to_update_cron_wysija = true;
                }

                // if we detect a premium user without wysija's cron we activate it for him
//                if ($model_config->getValue('cron_manual') !== true) {
//                    $model_config->save(array('cron_manual' => true));
//                    $need_to_update_cron_wysija = true;
//                }

                // as soon as we update that value we do a licence check to make sure that the cron URL on wysija.com is up to date and doesn have the old format
                if ($need_to_update_cron_wysija) {
                    $helper_licence = WYSIJA::get('licence', 'helper');
                    $helper_licence->check(true);
                }

                // Check if we already updated this option
                if (!get_option('mpoet_frequency_set')) {
                    $this->sending_frequency();

                    // Set the flag in DB
                    WYSIJA::update_option('mpoet_frequency_set', true);
                }

            }


        }
    }

    /**
     *
     */
    function update_page_stats_link($stats_link){

        return '<a href="admin.php?page=wysija_statistics" target="_blank">'.__('Test the statistics page.',WYSIJA).'</a>';
    }

    /**
     * Check sending frequency and sets it to 15 min, if we
     * have different value by user.
     *
     */
    function sending_frequency() {
    	$model_config = WYSIJA::get('config','model');
        if (!$model_config->getValue('premium_key')) {
            return;
    	}

        $frequency = $model_config->getValue('sending_emails_each');
        $helper_forms = WYSIJA::get('forms', 'helper');

        // Do nothing, if we have right frequency
        if (isset($helper_forms->eachValuesSec[$frequency]) && $helper_forms->eachValuesSec[$frequency] <= 900) {
            return;
        }

        $emails_number = $model_config->getValue('sending_emails_number');

        foreach ($helper_forms->eachValuesSec as $value => $sec) {
            if ( $value === $frequency && !empty($sec)) {
                $emails_number = round($emails_number / ($sec / 900));
            }
        }

        $model_config->save(array('sending_emails_each' => 'fifteen_min', 'sending_emails_number' => (int) $emails_number));
    }

    /**
     * Remove not recommend frequencies for premium users.
     *
     * @param array $values Frequency values.
     */
    function remove_sending_frequencies($values) {
        unset($values['thirty_min'], $values['hourly'], $values['two_hours']);
        return $values;
    }

    /**
     * hook to remove the check on the the subscriber total above below 2000
     */
    function remove_checkTotalSubscribers() {
        $mConfig = WYSIJA::get('config', 'model');
        if ($mConfig->getValue('premium_key'))
            remove_all_actions('wysija_check_total_subscribers');
    }

    /**
     * function handling licence key check or dkim autosetup based on some parameters set in hte db
     */
    function various_checks() {
        $mConfig = WYSIJA::get('config', 'model');
        //is the flag up for a licence check ?
        if (get_option('wysicheck') || (((isset($_REQUEST['action']) && $_REQUEST['action'] !== 'licok')) && $mConfig->getValue('premium_key'))) {
            $onedaysec = 7 * 24 * 3600;
            if (get_option('wysicheck') || (!$mConfig->getValue('premium_val') || time() > ((int) $mConfig->getValue('premium_val') + $onedaysec))) {
                $hLicence = WYSIJA::get('licence', 'helper');
                $res = $hLicence->check(true);
                //can't we contact the server through a curl url ? then let's launch a js verification
                if ($res['nocontact']) {
                    //redirect instantly to a page with a javascript file  where we check the domain is ok
                    $data = get_option('wysijey');
                    //remotely connect to host
                    wp_enqueue_script('wysija-verif-licence', 'http://www.wysija.com/?wysijap=checkout&wysijashop-page=1&controller=customer&action=checkDomain&js=1&data=' . $data, array('jquery'), time());
                }
            }
        }

        //is the flag up for dkim autosetup ? Then let's get the DKIM infor we need through javascript
        if (get_option('dkim_autosetup')) {
            $hLicence = WYSIJA::get('licence', 'helper');
            $data = $hLicence->getDomainInfo();
            wp_enqueue_script('wysija-setup-dkim', 'http://www.wysija.com/?wysijap=checkout&wysijashop-page=1&controller=customer&action=checkDkimNew&js=1&data=' . $data, array('jquery'), time());
        }

    }

    /**
     *
     * @return type
     */
    function croned_queue_process() {
        $helper_queue = WYSIJA::get('queue', 'helper');
        $helper_queue->report = false;
        WYSIJA::log('croned_queue process', true, 'cron');
        $helper_queue->process();
        return true;
    }

    /**
     * send spam test to mail-tester.com and get the resul
     * @param type $filter
     * @param type $arg
     * @return type
     */
    function send_spam_test( $filter, $arg ) {
        //send a message to wysija-WEBSITE-time@mail-tester.com
        $site = substr( trim( base64_encode( preg_replace( '#https?://(www\.)?#i', '', get_site_url() ) ), '=/' ), 0, 60 );

        $_REQUEST['receiver'] = 'wysija-' . $site . '-' . time() . '@mail-tester.com';
        return $arg->send_preview( true );
    }

    /**
     *
     * @param type $getpremiumtheme
     * @return type
     */
    function install_theme_premium($getpremiumtheme) {
        $mConfig = WYSIJA::get('config', 'model');

        if (!$mConfig->getValue('premium_key')) {

            $errormsg = str_replace(array('[link]', '[/link]'), array('<a title="' . __('Get Premium now', WYSIJA) . '" class="premium-tab ispopup" href="javascript:;" >', '</a>'), __('The theme is available in Premium version only. [link]12 good reasons to upgrade.[/link]', WYSIJA));
            $this->error($errormsg, 1);
            return false;
        }
        else
            return true;
    }

    /**
     * Main menu at admin site
     * @param type $menus
     * @return type
     */
    function wysija_menus($menus) {
        $model_config = WYSIJA::get('config', 'model');
        if (!$model_config->getValue('premium_key'))
            return $menus;
        unset($menus['premium']);
        $tmp_menus = array();
        $is_adding_statistics = false; // add Statistics after "Subscribers"; otherwise, add to the last.
        foreach ($menus as $key => $menu) {
            $tmp_menus[$key] = $menu;
            if ($key === 'subscribers') {
            if (WYSIJA::current_user_can('wysija_stats_dashboard'))
                $tmp_menus['statistics'] = array('title' => __('Statistics', WYSIJA));
                $is_adding_statistics = true;
            }
        }
        if (!$is_adding_statistics)
            if (WYSIJA::current_user_can('wysija_stats_dashboard'))
                $tmp_menus['statistics'] = array('title' => __('Statistics', WYSIJA));

        return $tmp_menus;
    }

    /**
     * cron run on a frequency decided by the administrator
     */
    function croned_bounce() {
        // bounce handling
        $helper_bounce = WYSIJA::get('bounce', 'helper');

        // in a multisite case we process first the bounce recording into the bounce table
        if (is_multisite()) {
            $helper_bounce->record_bounce_ms();

            // then we take actions from what
            return $helper_bounce->process_bounce_ms();
        } else {
            return $helper_bounce->process_bounce();
        }
    }

    /**
     * cron run every week
     */
    function croned_weekly() {
        @ini_set('max_execution_time', 0);

        //send daily report about emails sent
        $mConfig = WYSIJA::get('config', 'model');
        //if premium let's do a licence check
        if ($mConfig->getValue('premium_key')) {
            $helperS = WYSIJA::get('licence', 'helper');
            $helperS->check();
        }
    }

    /**
     *
     * @param string $message
     * @return string
     */
    function footer_add_stars($message) {
        return '';
    }

}
