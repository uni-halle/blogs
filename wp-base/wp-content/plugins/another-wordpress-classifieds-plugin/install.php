<?php
/**
 * Installation and Upgrade functions
 */

global $wpdb;

define('AWPCP_TABLE_ADFEES', $wpdb->prefix . "awpcp_adfees");
define('AWPCP_TABLE_ADS', $wpdb->prefix . "awpcp_ads");
define('AWPCP_TABLE_AD_REGIONS', $wpdb->prefix . "awpcp_ad_regions");
define('AWPCP_TABLE_AD_META', $wpdb->prefix . 'awpcp_admeta');
define('AWPCP_TABLE_MEDIA', $wpdb->prefix . "awpcp_media");
define('AWPCP_TABLE_CATEGORIES', $wpdb->prefix . "awpcp_categories");
define('AWPCP_TABLE_PAYMENTS', $wpdb->prefix . 'awpcp_payments');
define('AWPCP_TABLE_CREDIT_PLANS', $wpdb->prefix . 'awpcp_credit_plans');
define('AWPCP_TABLE_PAGES', $wpdb->prefix . "awpcp_pages");
define('AWPCP_TABLE_PAGENAME', $wpdb->prefix . "awpcp_pagename");

define('AWPCP_TABLE_ADSETTINGS', $wpdb->prefix . "awpcp_adsettings");
define('AWPCP_TABLE_ADPHOTOS', $wpdb->prefix . "awpcp_adphotos");


class AWPCP_Installer {

    private static $instance = null;

    private function AWPCP_Installer() {
        $this->columns = new AWPCP_DatabaseColumnCreator();

        $this->create_ads_table =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_ADS . " (
            `ad_id` INT(10) NOT NULL AUTO_INCREMENT,
            `adterm_id` INT(10) NOT NULL DEFAULT 0,
            `payment_term_type` VARCHAR(64) NOT NULL DEFAULT 'fee',
            `ad_fee_paid` FLOAT(7,2) NOT NULL,
            `ad_category_id` INT(10) NOT NULL,
            `ad_category_parent_id` INT(10) NOT NULL,
            `ad_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_details` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `ad_contact_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_contact_phone` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_contact_email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `websiteurl` VARCHAR( 375 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `ad_city` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_state` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_country` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_county_village` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_item_price` INT(25) NOT NULL,
            `ad_views` INT(10) NOT NULL DEFAULT 0,
            `ad_postdate` DATE NOT NULL,
            `ad_last_updated` DATE NOT NULL,
            `ad_startdate` DATETIME NOT NULL,
            `ad_enddate` DATETIME NOT NULL,
            `disabled` TINYINT(1) NOT NULL DEFAULT 0,
            `disabled_date` DATETIME,
            `flagged` TINYINT(1) NOT NULL DEFAULT 0,
            `verified` TINYINT(1) NOT NULL DEFAULT 1,
            `verified_at` DATETIME,
            `ad_key` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `ad_transaction_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `payment_gateway` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `payment_status` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `payer_email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `is_featured_ad` TINYINT(1) DEFAULT NULL,
            `posterip` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `user_id` INT(10) DEFAULT NULL,
            `renew_email_sent` TINYINT(1) NOT NULL DEFAULT 0,
            `renewed_date` DATETIME,
            FULLTEXT KEY `titdes` (`ad_title`,`ad_details`),
            PRIMARY KEY  (`ad_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

        $this->create_ad_regions_table =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_AD_REGIONS . " (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `ad_id` INT(10) NOT NULL,
            `country` VARCHAR(64) COLLATE utf8_general_ci DEFAULT '',
            `county` VARCHAR(64) COLLATE utf8_general_ci DEFAULT '',
            `state` VARCHAR(64) COLLATE utf8_general_ci DEFAULT '',
            `city` VARCHAR(64) COLLATE utf8_general_ci DEFAULT '',
            `region_id` INT(10) DEFAULT NULL,
            INDEX `country_index` (`country`),
            INDEX `county_index` (`county`),
            INDEX `state_index` (`state`),
            INDEX `city_index` (`city`),
            INDEX `region_id_index` (`region_id`),
            PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

        $this->create_fees_table =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_ADFEES . " (
            `adterm_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `adterm_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `credits` INT(10) NOT NULL DEFAULT 0,
            `amount` FLOAT(6,2) UNSIGNED NOT NULL DEFAULT '0.00',
            `recurring` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `rec_period` INT(5) UNSIGNED NOT NULL DEFAULT 0,
            `rec_increment` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `buys` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `imagesallowed` INT(5) UNSIGNED NOT NULL DEFAULT 0,
            `is_featured_ad_pricing` TINYINT(1) DEFAULT NULL,
            `categories` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
            `characters_allowed` INT(1) NOT NULL DEFAULT 0,
            `title_characters` INT(1) NOT NULL DEFAULT 0,
            `private` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY  (`adterm_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

        $this->create_payments_table =
        'CREATE TABLE IF NOT EXISTS ' . AWPCP_TABLE_PAYMENTS . " (
            `id` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `items` TEXT,
            `data` TEXT,
            `errors` TEXT,
            `user_id` INT(10) NOT NULL,
            `status` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
            `payment_status` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci,
            `payment_gateway` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `payer_email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `version` TINYINT(1),
            `created` DATETIME NOT NULL,
            `updated` DATETIME NOT NULL,
            `completed` DATETIME,
            PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

        $this->create_credit_plans_table =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_CREDIT_PLANS . " (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `description` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `credits` INT(10) NOT NULL,
            `price` FLOAT,
            `created` DATETIME NOT NULL,
            `updated` DATETIME NOT NULL,
            PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

        $this->create_media_table =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_MEDIA . " (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `ad_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `path` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `mime_type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `enabled` TINYINT(1) NOT NULL DEFAULT 0,
            `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
            `created` DATETIME NOT NULL,
            PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

        $this->create_ad_meta_table =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_AD_META . " (
            `meta_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `awpcp_ad_id` BIGINT(10) UNSIGNED NOT NULL,
            `meta_key` VARCHAR(255),
            `meta_value` LONGTEXT,
            PRIMARY KEY  (`meta_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
    }

    public static function instance() {
        if (is_null(AWPCP_Installer::$instance)) {
            AWPCP_Installer::$instance = new AWPCP_Installer();
        }
        return AWPCP_Installer::$instance;
    }

    public function column_exists($table, $column) {
        global $wpdb;
        $wpdb->hide_errors();
        $result = $wpdb->query("SELECT `$column` FROM $table");
        $wpdb->show_errors();
        return $result !== false;
    }

    public function activate() {
        $this->install();
        flush_rewrite_rules();
    }

    /**
     * Creates AWPCP tables.
     *
     * If is not a fresh install it calls $this->upgrade().
     */
    public function install() {
        global $awpcp, $wpdb, $awpcp_db_version;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $version = get_option('awpcp_db_version');

        // if table exists, this is an upgrade
        $table = $wpdb->get_var("SHOW TABLES LIKE '" . AWPCP_TABLE_CATEGORIES . "'");
        if ( strcasecmp( $table, AWPCP_TABLE_CATEGORIES ) === 0 ) {
            return $this->upgrade($version, $awpcp_db_version);
        }


        // create Categories table
        $sql = "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_CATEGORIES . " (
            `category_id` INT(10) NOT NULL AUTO_INCREMENT,
            `category_parent_id` INT(10) NOT NULL,
            `category_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `category_order` INT(10) NULL DEFAULT 0,
            PRIMARY KEY  (`category_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        dbDelta($sql);


        // create Ad Fees table
        dbDelta($this->create_fees_table);


        // create Ads table
        dbDelta($this->create_ads_table);

        // Create ad metadata table.
        dbDelta( $this->create_ad_meta_table );

        // create awpcp_ad_regions table
        dbDelta( $this->create_ad_regions_table );

        // create awpcp_media table
        dbDelta( $this->create_media_table );



        // create Ad Photos table
        $sql = "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_ADPHOTOS . " (
            `key_id` INT(10) NOT NULL AUTO_INCREMENT,
            `ad_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `image_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            `disabled` TINYINT(1) NOT NULL DEFAULT 0,
            `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY  (`key_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        dbDelta($sql);


        // create Pagename table
        // TODO: not sure if this table is needed at all, we could use an option...
        $sql = "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_PAGENAME . " (
            `key_id` INT(10) NOT NULL AUTO_INCREMENT,
            `userpagename` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
            PRIMARY KEY  (`key_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        dbDelta($sql);


        // create Pages table
        $sql = 'CREATE TABLE IF NOT EXISTS ' . AWPCP_TABLE_PAGES . " (
            `page` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `id` INT(10) NOT NULL,
          PRIMARY KEY  (`page`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        dbDelta($sql);


        // create Payments table
        dbDelta($this->create_payments_table);

        // create Credit Plans table
        dbDelta($this->create_credit_plans_table);


        // insert deafult category
        $data = array('category_id' => 1,
                      'category_parent_id' => 0,
                      'category_name' => __('General', 'AWPCP'),
                      'category_order' => 0);
        $wpdb->insert(AWPCP_TABLE_CATEGORIES, $data);

        // insert default Fee
        $data = array('adterm_id' => 1,
                      'adterm_name' => __('30 Day Listing', 'AWPCP'),
                      'amount' => 9.99,
                      'recurring' => 1,
                      'rec_period' => 31,
                      'rec_increment' => 'D',
                      'buys' => 0,
                      'imagesallowed' => 6);
        $wpdb->insert(AWPCP_TABLE_ADFEES, $data);


        $result = update_option('awpcp_db_version', $awpcp_db_version);
        $awpcp->settings->update_option('show-quick-start-guide-notice', true, true);

        do_action('awpcp_install');

        return $result;
    }

    public function uninstall() {
        global $wpdb, $awpcp_plugin_path, $table_prefix, $awpcp;

        // Remove the upload folders with uploaded images
        $dirname = AWPCPUPLOADDIR;
        if (file_exists($dirname)) {
            require_once $awpcp_plugin_path.'/fileop.class.php';
            $fileop = new fileop();
            $fileop->delete($dirname);
        }

        // Delete the classifieds page(s)
        $pages = awpcp_pages();
        foreach ($pages as $page => $data) {
            wp_delete_post(awpcp_get_page_id_by_ref($page), true);
        }

        // Drop the tables
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_ADFEES );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_ADPHOTOS );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_ADS );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_ADSETTINGS );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_AD_META );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_AD_REGIONS );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_CATEGORIES );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_CREDIT_PLANS );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_MEDIA );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_PAGES );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_PAGENAME );
        $wpdb->query( "DROP TABLE IF EXISTS " . AWPCP_TABLE_PAYMENTS );

        // TODO: implement uninstall methods in other modules
        $tables = array($wpdb->prefix . 'awpcp_comments');
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS " . $table);
        }

        // remove AWPCP options from options table
        array_map('delete_option', array(
            'awpcp-pending-manual-upgrade',
            'awpcp_installationcomplete',
            'awpcp_pagename_warning',
            'widget_awpcplatestads',
            'awpcp_db_version',
            $awpcp->settings->option,
        ));

        // delete payment transactions
        $sql = 'SELECT option_name FROM ' . $wpdb->options . ' ';
        $sql.= "WHERE option_name LIKE 'awpcp-payment-transaction-%%'";
        array_map('delete_option', $wpdb->get_col($sql));

        // remove widgets
        unregister_widget("AWPCP_LatestAdsWidget");
        unregister_widget('AWPCP_RandomAdWidget');
        unregister_widget('AWPCP_Search_Widget');

        // Clear the ad expiration schedule
        wp_clear_scheduled_hook('doadexpirations_hook');
        wp_clear_scheduled_hook('doadcleanup_hook');
        wp_clear_scheduled_hook('awpcp_ad_renewal_email_hook');
        wp_clear_scheduled_hook('awpcp-clean-up-payment-transactions');

        // TODO: use deactivate_plugins function
        // http://core.trac.wordpress.org/browser/branches/3.2/wp-admin/includes/plugin.php#L548
        $current = get_option('active_plugins');
        $thepluginfile = sprintf("%s/awpcp.php", trim(AWPCP_BASENAME, '/'));
        array_splice($current, array_search( $thepluginfile, $current), 1 );
        update_option('active_plugins', $current);
        do_action('deactivate_' . $thepluginfile );
    }

    // TODO: remove settings table after another major release
    // TODO: remove pagename table after another major release
    public function upgrade($oldversion, $newversion) {
        global $wpdb;

        if (version_compare($oldversion, '1.8.9.4') < 0) {
            $this->upgrade_to_1_8_9_4($oldversion);
        }
        if (version_compare($oldversion, '1.9.9') < 0) {
            $this->upgrade_to_1_9_9($oldversion);
        }
        if (version_compare($oldversion, '2.0.0') < 0) {
            $this->upgrade_to_2_0_0($oldversion);
        }
        if (version_compare($oldversion, '2.0.1') < 0) {
            $this->upgrade_to_2_0_1($oldversion);
        }
        if (version_compare($oldversion, '2.0.5') < 0) {
            $this->upgrade_to_2_0_5($oldversion);
        }
        if (version_compare($oldversion, '2.0.6') < 0) {
            $this->upgrade_to_2_0_6($oldversion);
        }
        if (version_compare($oldversion, '2.0.7') < 0) {
            $this->upgrade_to_2_0_7($oldversion);
        }
        if (version_compare($oldversion, '2.1.3') < 0) {
            $this->upgrade_to_2_1_3($oldversion);
        }
        if (version_compare($oldversion, '2.2.1') < 0) {
            $this->upgrade_to_2_2_1($oldversion);
        }
        if (version_compare($oldversion, '3.0-beta23') < 0) {
            $this->upgrade_to_3_0_0($oldversion);
        }
        if ( version_compare( $oldversion, '3.0.2' ) < 0 ) {
            $this->upgrade_to_3_0_2( $oldversion );
        }

        do_action('awpcp_upgrade', $oldversion, $newversion);

        return update_option("awpcp_db_version", $newversion);
    }

    private function upgrade_to_1_8_9_4($version) {
        global $wpdb;

        // Try to enable the expired ads, bug in 1.0.6.17:
        if ($version == '1.0.6.17') {
            $query = "UPDATE ". AWPCP_TABLE_ADS ." SET DISABLED=0 WHERE ad_enddate >= NOW()";
            $wpdb->query($query);
        }

        if (!is_at_least_awpcp_version('1.8.7.1')) {
            // Fix the problem with disabled_date not being nullable from 1.8.7
            $query = "ALTER TABLE ". AWPCP_TABLE_ADS ." MODIFY disabled_date DATETIME";
            $wpdb->query($query);
        }

        // Upgrade featured ad columns for module
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'is_featured_ad')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `is_featured_ad` TINYINT(1) DEFAULT NULL");
        }

        // Upgrade for tracking poster's IP address
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'posterip')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `posterip` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL");
        }

        if (!$this->column_exists(AWPCP_TABLE_ADS, 'flagged')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `flagged` TINYINT(1) DEFAULT NULL");
        }

        // Upgrade for deleting ads that are marked as disabled or deleted
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'disabled_date')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `disabled_date` DATETIME DEFAULT NULL");
        }


        if (!$this->column_exists(AWPCP_TABLE_ADFEES, 'is_featured_ad_pricing')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADFEES . "  ADD `is_featured_ad_pricing` TINYINT(1) DEFAULT NULL");
        }

        if (!$this->column_exists(AWPCP_TABLE_ADFEES, 'categories')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADFEES . "  ADD `categories` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci");
        }


        if (!$this->column_exists(AWPCP_TABLE_CATEGORIES, 'category_order')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_CATEGORIES . "  ADD `category_order` INT(10) NULL DEFAULT 0 AFTER category_name");
            $wpdb->query("UPDATE " . AWPCP_TABLE_CATEGORIES . " SET category_order=0");
        }


        // Fix the shortcode issue if present in installed version
        $sql = "UPDATE " . $wpdb->posts . " SET post_content='[AWPCPCLASSIFIEDSUI]' ";
        $sql.= "WHERE post_content='[[AWPCPCLASSIFIEDSUI]]'";
        $wpdb->query($sql);


        $settings_table_exists = checkfortable( AWPCP_TABLE_ADSETTINGS );

        if ($settings_table_exists && !field_exists('tos')) {
            // add terms of service field
            $sql = 'INSERT INTO '. AWPCP_TABLE_ADSETTINGS .'(`config_option`,`config_value`,`config_diz`,`config_group_id`,`option_type`)
                VALUES ("tos","Terms of service go here...","Terms of Service for posting an ad - modify this to fit your needs:","1","0")';
            $wpdb->query($sql);

            $sql = 'INSERT INTO '. AWPCP_TABLE_ADSETTINGS .'(`config_option`,`config_value`,`config_diz`,`config_group_id`,`option_type`)
                VALUES ("requiredtos", "Display and require Terms of Service","Display and require Terms of Service","1","0")';
            $wpdb->query($sql);
        }

        if ($settings_table_exists && !field_exists('notifyofadexpired')) {
            //add notify of an expired ad field
            $sql = 'insert into '.AWPCP_TABLE_ADSETTINGS.'(`config_option`,`config_value`,`config_diz`,`config_group_id`,`option_type`)
                values ("notifyofadexpired","Notify admin of expired ads.","Notify admin of expired ads.","1","0")';

            $wpdb->query($sql);
        }

        if ($settings_table_exists && field_exists('notifyofadexpired')) {
            //Fix bug from 1.8.6.4:
            $wpdb->query("UPDATE " . AWPCP_TABLE_ADSETTINGS . " SET option_type =0 where config_option='notifyofadexpired'");
        }



        // Update ad_settings table to ad field config groud ID if field does not exist in installed version

        $cgid_column_name="config_group_id";
        $cgid_column_name_exists=mysql_query("SELECT $cgid_column_name FROM " . AWPCP_TABLE_ADSETTINGS);
        if ( $settings_table_exists && ( mysql_errno() || !$cgid_column_name_exists ) ) {
            $query=("ALTER TABLE " . AWPCP_TABLE_ADSETTINGS . "  ADD `config_group_id` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER config_diz");
            awpcp_query($query, __LINE__);

            $myconfig_group_ops_1=array('showlatestawpcpnews','uiwelcome','main_page_display','useakismet','contactformcheckhuman', 'contactformcheckhumanhighnumval','awpcptitleseparator','showcityinpagetitle','showstateinpagetitle','showcountryinpagetitle','showcategoryinpagetitle','showcountyvillageinpagetitle','awpcppagefilterswitch','activatelanguages','sidebarwidgetbeforecontent','sidebarwidgetaftercontent','sidebarwidgetbeforetitle','sidebarwidgetaftertitle','usesenderemailinsteadofadmin','awpcpadminaccesslevel','awpcpadminemail','useakismet');
            $myconfig_group_ops_2=array('addurationfreemode','autoexpiredisabledelete','maxcharactersallowed','notifyofadexpiring', 'notifyofadposted', 'adapprove', 'disablependingads', 'showadcount', 'displayadviews','onlyadmincanplaceads','allowhtmlinadtext', 'hyperlinkurlsinadtext', 'notice_awaiting_approval_ad', 'buildsearchdropdownlists','visitwebsitelinknofollow','groupbrowseadsby','groupsearchresultsby','displayadthumbwidth','adresultsperpage','displayadlayoutcode','awpcpshowtheadlayout');
            $myconfig_group_ops_3=array('freepay','paylivetestmode','paypalemail', 'paypalcurrencycode', 'displaycurrencycode', '2checkout', 'activatepaypal', 'activate2checkout','twocheckoutpaymentsrecurring','paypalpaymentsrecurring');
            $myconfig_group_ops_4=array('imagesallowdisallow', 'awpcp_thickbox_disabled','imagesapprove', 'imagesallowedfree', 'uploadfoldername', 'maximagesize','minimagesize', 'imgthumbwidth', 'imgmaxheight', 'imgmaxwidth');
            $myconfig_group_ops_5=array('useadsense', 'adsense', 'adsenseposition');
            $myconfig_group_ops_6=array('displayphonefield', 'displayphonefieldreqop', 'displaycityfield', 'displaycityfieldreqop', 'displaystatefield','displaystatefieldreqop', 'displaycountryfield', 'displaycountryfieldreqop', 'displaycountyvillagefield', 'displaycountyvillagefieldreqop', 'displaypricefield', 'displaypricefieldreqop', 'displaywebsitefield', 'displaywebsitefieldreqop', 'displaypostedbyfield');
            $myconfig_group_ops_7=array('requireuserregistration', 'postloginformto', 'registrationurl');
            $myconfig_group_ops_8=array('contactformsubjectline','contactformbodymessage','listingaddedsubject','listingaddedbody','resendakeyformsubjectline','resendakeyformbodymessage','paymentabortedsubjectline','paymentabortedbodymessage','adexpiredsubjectline','adexpiredbodymessage');
            $myconfig_group_ops_9=array('usesmtp','smtphost','smtpport','smtpusername','smtppassword');
            $myconfig_group_ops_10=array('userpagename','showadspagename','placeadpagename','page-name-renew-ad','browseadspagename','browsecatspagename','editadpagename','paymentthankyoupagename','paymentcancelpagename','replytoadpagename','searchadspagename','categoriesviewpagename');
            $myconfig_group_ops_11=array('seofriendlyurls','pathvaluecontact','pathvalueshowad','pathvaluebrowsecategory','pathvalueviewcategories','pathvaluecancelpayment','pathvaluepaymentthankyou');

            // assign a group value to each setting
            foreach($myconfig_group_ops_1 as $myconfig_group_op_1){add_config_group_id($cvalue=1,$myconfig_group_op_1);}
            foreach($myconfig_group_ops_2 as $myconfig_group_op_2){add_config_group_id($cvalue='2',$myconfig_group_op_2);}
            foreach($myconfig_group_ops_3 as $myconfig_group_op_3){add_config_group_id($cvalue='3',$myconfig_group_op_3);}
            foreach($myconfig_group_ops_4 as $myconfig_group_op_4){add_config_group_id($cvalue='4',$myconfig_group_op_4);}
            foreach($myconfig_group_ops_5 as $myconfig_group_op_5){add_config_group_id($cvalue='5',$myconfig_group_op_5);}
            foreach($myconfig_group_ops_6 as $myconfig_group_op_6){add_config_group_id($cvalue='6',$myconfig_group_op_6);}
            foreach($myconfig_group_ops_7 as $myconfig_group_op_7){add_config_group_id($cvalue='7',$myconfig_group_op_7);}
            foreach($myconfig_group_ops_8 as $myconfig_group_op_8){add_config_group_id($cvalue='8',$myconfig_group_op_8);}
            foreach($myconfig_group_ops_9 as $myconfig_group_op_9){add_config_group_id($cvalue='9',$myconfig_group_op_9);}
            foreach($myconfig_group_ops_10 as $myconfig_group_op_10){add_config_group_id($cvalue='10',$myconfig_group_op_10);}
            foreach($myconfig_group_ops_11 as $myconfig_group_op_11){add_config_group_id($cvalue='11',$myconfig_group_op_11);}
        }

        if ($settings_table_exists && get_awpcp_option_group_id('seofriendlyurls') == 1){ $wpdb->query("UPDATE " . AWPCP_TABLE_ADSETTINGS . " SET `config_group_id` = '11' WHERE `config_option` = 'seofriendlyurls'"); }
        if ($settings_table_exists && get_awpcp_option_type('main_page_display') == 1){ $wpdb->query("UPDATE " . AWPCP_TABLE_ADSETTINGS . " SET `config_value` = 0, `option_type` = 0, `config_diz` = 'Main page layout [ check for ad listings ] [ Uncheck for categories ]',config_group_id=1 WHERE `config_option` = 'main_page_display'"); }
        if ($settings_table_exists && get_awpcp_option_config_diz('paylivetestmode') != "Put payment gateways in test mode"){ $wpdb->query("UPDATE " . AWPCP_TABLE_ADSETTINGS . " SET `config_value` = 0, `option_type` = 0, `config_diz` = 'Put payment gateways in test mode' WHERE `config_option` = 'paylivetestmode'");}
        if ($settings_table_exists && get_awpcp_option_config_diz('adresultsperpage') != "Default number of ads per page"){ $wpdb->query("UPDATE " . AWPCP_TABLE_ADSETTINGS . " SET `config_value` = '10', `option_type` = 1, `config_diz` = 'Default number of ads per page' WHERE `config_option` = 'adresultsperpage'");}
        if ($settings_table_exists && get_awpcp_option_config_diz('awpcpshowtheadlayout') != "<div id=\"showawpcpadpage\"><div class=\"adtitle\">$ad_title</div><br/><div class=\"showawpcpadpage\">$featureimg<label>Contact Information</label><br/><a href=\"$quers/$codecontact\">Contact $adcontact_name</a>$adcontactphone $location $awpcpvisitwebsite</div>$aditemprice $awpcpextrafields <div class=\"fixfloat\"></div> $showadsense1<div class=\"showawpcpadpage\"><label>More Information</label><br/>$addetails</div>$showadsense2 <div class=\"fixfloat\"></div><div id=\"displayimagethumbswrapper\"><div id=\"displayimagethumbs\"><ul>$awpcpshowadotherimages</ul></div></div><span class=\"fixfloat\">$tweetbtn $sharebtn $flagad</span>$awpcpadviews $showadsense3</div>"){ $wpdb->query("UPDATE " . AWPCP_TABLE_ADSETTINGS . " SET `config_value` = '2', `option_type` = '2', `config_diz` = 'Modify as needed to control layout of single ad view page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.', `config_value` = '<div id=\"showawpcpadpage\"><div class=\"adtitle\">\$ad_title</div><br/><div class=\"showawpcpadpage\">\$featureimg<label>Contact Information</label><br/><a href=\"\$quers/\$codecontact\">Contact \$adcontact_name</a>\$adcontactphone \$location \$awpcpvisitwebsite</div>\$aditemprice \$awpcpextrafields <div class=\"fixfloat\"></div> \$showadsense1<div class=\"showawpcpadpage\"><label>More Information</label><br/>\$addetails</div>\$showadsense2 <div class=\"fixfloat\"></div><div id=\"displayimagethumbswrapper\"><div id=\"displayimagethumbs\"><ul>\$awpcpshowadotherimages</ul></div></div><span class=\"fixfloat\">\$tweetbtn \$sharebtn \$flagad</span>\$awpcpadviews \$showadsense3</div>' WHERE `config_option` = 'awpcpshowtheadlayout'");}

        ////
        // Match up the ad settings fields of current versions and upgrading versions
        ////

        if ($settings_table_exists) {

        if (!field_exists($field='userpagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('userpagename', 'AWPCP', 'Name for classifieds page. [CAUTION: Make sure page does not already exist]','10',1);");}
        if (!field_exists($field='showadspagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showadspagename', 'Show Ad', 'Name for show ads page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='placeadpagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('placeadpagename', 'Place Ad', 'Name for place ads page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='browseadspagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('browseadspagename', 'Browse Ads', 'Name browse ads apge. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='searchadspagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES        ('searchadspagename', 'Search Ads', 'Name for search ads page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='paymentthankyoupagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paymentthankyoupagename', 'Payment Thank You', 'Name for payment thank you page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='paymentcancelpagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paymentcancelpagename', 'Cancel Payment', 'Name for payment cancel page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='replytoadpagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('replytoadpagename', 'Reply To Ad', 'Name for reply to ad page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='browsecatspagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('browsecatspagename', 'Browse Categories', 'Name for browse categories page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='editadpagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('editadpagename', 'Edit Ad', 'Name for edit ad page. [CAUTION: existing page will be overwritten]','10',1);");}
        if (!field_exists($field='categoriesviewpagename')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES        ('categoriesviewpagename', 'View Categories', 'Name for categories view page. [ Dynamic Page]','10',1);");}
        if (!field_exists($field='freepay')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('freepay', 0, 'Charge Listing Fee?','3',0);");}
        if (!field_exists($field='requireuserregistration')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('requireuserregistration', 0, 'Require user registration?','7',0);");}
        if (!field_exists($field='postloginformto')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('postloginformto', '', 'Post login form to [Value should be the full URL to the wordpress login script. Example http://www.awpcp.com/wp-login.php **Only needed if registration is required and your login url is mod-rewritten ] ','7',1);");}
        if (!field_exists($field='registrationurl')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('registrationurl', '', 'Location of registraiton page [Value should be the full URL to the wordpress registration page. Example http://www.awpcp.com/wp-login.php?action=register **Only needed if registration is required and your login url is mod-rewritten ] ','7',1);");}
        if (!field_exists($field='main_page_display')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('main_page_display', 0, 'Main page layout [ check for ad listings | Uncheck for categories ]',1,0);");}
        if (!field_exists($field='activatelanguages')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('activatelanguages', 0, 'Activate Language Capability',1,0);");}
        if (!field_exists($field='awpcpadminaccesslevel')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('awpcpadminaccesslevel', 'admin', 'Set wordpress role of users who can have admin access to classifieds. Choices [admin,editor]. Currently no other roles will be granted access.',1,1);");}
        if (!field_exists($field='sidebarwidgetaftertitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('sidebarwidgetaftertitle', '</h3>', 'Code to appear after widget title',1,1);");}
        if (!field_exists($field='sidebarwidgetbeforetitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('sidebarwidgetbeforetitle', '<h3 class=\"widgettitle\">', 'Code to appear before widget title',1,1);");}
        if (!field_exists($field='sidebarwidgetaftercontent')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('sidebarwidgetaftercontent', '</div>', 'Code to appear after widget content',1,1);");}
        if (!field_exists($field='sidebarwidgetbeforecontent')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('sidebarwidgetbeforecontent', '<div class=\"widget\">', 'Code to appear before widget content',1,1);");}
        if (!field_exists($field='usesenderemailinsteadofadmin')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('usesenderemailinsteadofadmin', 0, 'Check this to use the name and email of the sender in the FROM field when someone replies to an ad. When unchecked the messages go out with the website name and WP admin email address in the from field. Some servers will not process outgoing emails that have an email address from gmail, yahoo, hotmail and other free email services in the FROM field. Some servers will also not process emails that have an email address that is different from the email address associated with your hosting account in the FROM field. If you are with such a webhost you need to leave this option unchecked and make sure your WordPress admin email address is tied to your hosting account.',1,0);");}
        if (!field_exists($field='awpcpadminemail')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('awpcpadminemail', '', 'Emails go out using your WordPress admin email. If you prefer to use a different email enter it here.',1,1);");}
        if (!field_exists($field='awpcptitleseparator')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('awpcptitleseparator', '-', 'The character to use to separate ad details used in browser page title [Example: | / - ]',1,1);");}
        if (!field_exists($field='showcityinpagetitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showcityinpagetitle', 1, 'Show city in browser page title when viewing individual ad',1,0);");}
        if (!field_exists($field='showstateinpagetitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showstateinpagetitle', 1, 'Show state in browser page title when viewing individual ad',1,0);");}
        if (!field_exists($field='showcountryinpagetitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showcountryinpagetitle', 1, 'Show country in browser page title when viewing individual ad',1,0);");}
        if (!field_exists($field='showcountyvillageinpagetitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES        ('showcountyvillageinpagetitle', 1, 'Show county/village/other setting in browser page title when viewing individual ad',1,0);");}
        if (!field_exists($field='showcategoryinpagetitle')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showcategoryinpagetitle', 1, 'Show category in browser page title when viewing individual ad',1,0);");}
        if (!field_exists($field='awpcppagefilterswitch')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('awpcppagefilterswitch', 1, 'Uncheck this if you need to turn off the awpcp page filter that prevents awpcp classifieds children pages from showing up in your wp pages menu [you might need to do this if for example the awpcp page filter is messing up your page menu. It means you will have to manually exclude the awpcp children pages from showing in your page list. Some of the pages really should not be visible to your users by default]',1,0);");}
        if (!field_exists($field='paylivetestmode')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paylivetestmode', 0, 'Put Paypal and 2Checkout in test mode.','3',0);");}
        if (!field_exists($field='useadsense')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('useadsense', 1, 'Activate adsense','5',0);");}
        if (!field_exists($field='adsense')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('adsense', 'Adsense code', 'Your adsense code [ Best if 468 by 60 text or banner. ]','5','2');");}
        if (!field_exists($field='adsenseposition')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('adsenseposition', '2', 'Adsense position. [ 1 - above ad text body ] [ 2 - under ad text body ] [ 3 - below ad images. ]','5',1);");}
        if (!field_exists($field='addurationfreemode')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('addurationfreemode', 0, 'Expire free ads after how many days? [0 for no expiry].','2',1);");}
        if (!field_exists($field='autoexpiredisabledelete')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('autoexpiredisabledelete', 0, 'Disable expired ads instead of deleting them?','2',0);");}
        if (!field_exists($field='imagesallowdisallow')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('imagesallowdisallow', 1, 'Allow images in ads? [Affects both free and paid]','4',0);");}
        if (!field_exists($field='awpcp_thickbox_disabled')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('awpcp_thickbox_disabled', 0, 'Turn off the thickbox/lightbox if it conflicts with other elements of your site','4',0);");}
        if (!field_exists($field='imagesallowedfree')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('imagesallowedfree', '4', ' Free mode number of images allowed?','4',1);");}
        if (!field_exists($field='uploadfoldername')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('uploadfoldername', 'uploads', 'Upload folder name. [ Folder must exist and be located in your wp-content directory ]','4',1);");}
        if (!field_exists($field='maximagesize')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('maximagesize', '150000', 'Maximum size per image user can upload to system.','4',1);");}
        if (!field_exists($field='minimagesize')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('minimagesize', '300', 'Minimum size per image user can upload to system','4',1);");}
        if (!field_exists($field='imgthumbwidth')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('imgthumbwidth', '125', 'Minimum height/width for uploaded images (used for both).','4',1);");}
        if (!field_exists($field='maxcharactersallowed')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('maxcharactersallowed', '750', 'What is the maximum number of characters the text of an ad can contain?','2',1);");}
        if (!field_exists($field='imgmaxheight')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,`config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('imgmaxheight', '480', 'Max image height. Images taller than this are automatically resized upon upload.','4',1);");}
        if (!field_exists($field='imgmaxwidth')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('imgmaxwidth', '640', 'Max image width. Images wider than this are automatically resized upon upload.','4',1);");}
        if (!field_exists($field='paypalemail')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paypalemail', 'xxx@xxxxxx.xxx', 'Email address for paypal payments [if running in paymode and if paypal is activated]','3',1);");}
        if (!field_exists($field='paypalcurrencycode')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paypalcurrencycode', 'USD', 'The currency in which you would like to receive your paypal payments','3',1);");}
        if (!field_exists($field='displaycurrencycode')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycurrencycode', 'USD', 'The currency to show on your payment pages','3',1);");}
        if (!field_exists($field='2checkout')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('2checkout', 'xxxxxxx', 'Account for 2Checkout payments [if running in pay mode and if 2Checkout is activated]','3',1);");}
        if (!field_exists($field='activatepaypal')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('activatepaypal', 1, 'Activate PayPal','3',0);");}
        if (!field_exists($field='activate2checkout')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('activate2checkout', 1, 'Activate 2Checkout ','3',0);");}
        if (!field_exists($field='paypalpaymentsrecurring')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paypalpaymentsrecurring', 0, 'Use recurring payments paypal [ this feature is not fully automated or fully integrated. For more reliable results do not use recurring ','3',0);");}
        if (!field_exists($field='twocheckoutpaymentsrecurring')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('twocheckoutpaymentsrecurring', 0, 'Use recurring payments 2checkout [ this feature is not fully automated or fully integrated. For more reliable results do not use recurring ','3',0);");}
        if (!field_exists($field='notifyofadexpiring')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('notifyofadexpiring', 1, 'Notify ad poster that their ad has expired?','2',0);");}
        if (!field_exists($field='notifyofadposted')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('notifyofadposted', 1, 'Notify admin of new ad.','2',0);");}
        if (!field_exists($field='listingaddedsubject')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('listingaddedsubject', 'Your classified ad listing has been submitted', 'Subject line for email sent out when someone posts an ad','8',1);");}
        if (!field_exists($field='listingaddedbody')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('listingaddedbody', 'Thank you for submitting your classified ad. The details of your ad are shown below.', 'Message body text for email sent out when someone posts an ad','8','2');");}
        if (!field_exists($field='imagesapprove')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('imagesapprove', 0, 'Hide images until admin approves them','4',0);");}
        if (!field_exists($field='adapprove')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('adapprove', 0, 'Disable ad until admin approves','2',0);");}
        if (!field_exists($field='displayadthumbwidth')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displayadthumbwidth', '80', 'Width for thumbnails in ad listings view [Only numerical value]','2',1);");}
        if (!field_exists($field='disablependingads')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('disablependingads', 1, 'Enable paid ads that are pending payment.','2',0);");}
        if (!field_exists($field='groupbrowseadsby')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('groupbrowseadsby', 1, 'Group ad listings by','2','3');");}
        if (!field_exists($field='groupsearchresultsby')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('groupsearchresultsby', 1, 'Group ad listings in search results by','2','3');");}
        if (!field_exists($field='showadcount')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showadcount', 1, 'Show how many ads a category contains.','2',0);");}
        if (!field_exists($field='adresultsperpage')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('adresultsperpage', '10', 'Default number of ads per page','2',1);");}
        if (!field_exists($field='noadsinparentcat')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('noadsinparentcat', 0, 'Prevent ads from being posted to top level categories?.','2',0);");}
        if (!field_exists($field='displayadviews')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displayadviews', 1, 'Show ad views','2',0);");}
        if (!field_exists($field='displayadlayoutcode')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displayadlayoutcode', '<div class=\"\$awpcpdisplayaditems\"><div style=\"width:\$imgblockwidth;padding:5px;float:left;margin-right:20px;\">\$awpcp_image_name_srccode</div><div style=\"width:50%;padding:5px;float:left;\"><h4>\$ad_title</h4> \$addetailssummary...</div><div style=\"padding:5px;float:left;\"> \$awpcpadpostdate \$awpcp_city_display \$awpcp_state_display \$awpcp_display_adviews \$awpcp_display_price </div><div class=\"fixfloat\"></div></div><div class=\"fixfloat\"></div>', 'Modify as needed to control layout of ad listings page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.','2','2');");}
        if (!field_exists($field='awpcpshowtheadlayout')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('awpcpshowtheadlayout', '<div id=\"showawpcpadpage\"><div class=\"adtitle\">\$ad_title</div><br/><div class=\"showawpcpadpage\">\$featureimg<label>Contact Information</label><br/><a href=\"\$quers/\$codecontact\">Contact \$adcontact_name</a>\$adcontactphone \$location \$awpcpvisitwebsite</div>\$aditemprice \$awpcpextrafields <div class=\"fixfloat\"></div> \$showadsense1<div class=\"showawpcpadpage\"><label>More Information</label><br/>\$addetails</div>\$showadsense2 <div class=\"fixfloat\"></div><div id=\"displayimagethumbswrapper\"><div id=\"displayimagethumbs\"><ul>\$awpcpshowadotherimages</ul></div></div><span class=\"fixfloat\">\$tweetbtn \$sharebtn \$flagad</span>\$awpcpadviews \$showadsense3</div>', 'Modify as needed to control layout of single ad view page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.','2','2');");}
        if (!field_exists($field='usesmtp')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('usesmtp', 0, 'Enable external SMTP server [ if emails not processing normally]', 9 ,0);");}
        if (!field_exists($field='smtphost')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('smtphost', 'mail.example.com', 'SMTP host [ if emails not processing normally]', 9 ,1);");}
        if (!field_exists($field='smtpport')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('smtpport', '25', 'SMTP port [ if emails not processing normally]', 9 ,1);");}
        if (!field_exists($field='smtpusername')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('smtpusername', 'smtp_username', 'SMTP username [ if emails not processing normally]', 9,1);");}
        if (!field_exists($field='smtppassword')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('smtppassword', '', 'SMTP password [ if emails not processing normally]', 9,1);");}
        if (!field_exists($field='onlyadmincanplaceads')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('onlyadmincanplaceads', 0, 'Only admin can post ads', '2',0);");}
        if (!field_exists($field='contactformcheckhuman')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('contactformcheckhuman', 1, 'Activate Math ad post and contact form validation', 1,0);");}
        if (!field_exists($field='useakismet')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('useakismet', 0, 'Use Akismet for Posting Ads/Contact Responses (strong anti-spam)', 1,0);");}
        if (!field_exists($field='contactformcheckhumanhighnumval')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('contactformcheckhumanhighnumval', '10', 'Math validation highest number', 1,1);");}
        if (!field_exists($field='contactformsubjectline')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('contactformsubjectline', 'Response to your AWPCP Demo Ad', 'Subject line for email sent out when someone replies to ad','8', 1);");}
        if (!field_exists($field='contactformbodymessage')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('contactformbodymessage', 'Someone has responded to your AWPCP Demo Ad', 'Message body text for email sent out when someone replies to ad', '8','2');");}
        if (!field_exists($field='resendakeyformsubjectline')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('resendakeyformsubjectline', 'The classified ad access key you requested', 'Subject line for email sent out when someone requests their ad access key resent','8', 1);");}
        if (!field_exists($field='resendakeyformbodymessage')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('resendakeyformbodymessage', 'You asked to have your classified ad ad access key resent. Below are all the ad access keys in the system that are tied to the email address you provided', 'Message body text for email sent out when someone requests their ad access key resent', '8','2');");}
        if (!field_exists($field='paymentabortedsubjectline')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paymentabortedsubjectline', 'There was a problem processing your classified ads listing payment', 'Subject line for email sent out when the payment processing does not complete','8', 1);");}
        if (!field_exists($field='paymentabortedbodymessage')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('paymentabortedbodymessage', 'There was a problem encountered during your attempt to submit payment for your classified ad listing. If funds were removed from the account you tried to use to make a payment please contact the website admin or the payment website customer service for assistance.', 'Message body text for email sent out when the payment processing does not complete','8','2');");}
        if (!field_exists($field='adexpiredsubjectline')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('adexpiredsubjectline', 'Your classifieds listing ad has expired', 'Subject line for email sent out when an ad has auto-expired','8', 1);");}
        if (!field_exists($field='adexpiredbodymessage')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('adexpiredbodymessage', 'This is an automated notification that your classified ad has expired.','Message body text for email sent out when an ad has auto-expired', '8','2');");}
        if (!field_exists($field='seofriendlyurls')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('seofriendlyurls', 0, 'Search Engine Friendly URLs? [ Does not work in some instances ]', '11',0);");}
        if (!field_exists($field='pathvaluecontact')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('pathvaluecontact', '3', 'If contact page link not working in seo mode change value until correct path is found. Start at 1', '11',1);");}
        if (!field_exists($field='pathvalueshowad')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('pathvalueshowad', '3', 'If show ad links not working in seo mode change value until correct path is found. Start at 1', '11',1);");}
        if (!field_exists($field='pathvaluebrowsecats')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('pathvaluebrowsecats', '2', 'If browse categories links not working in seo mode change value until correct path is found. Start at 1', '11',1);");}
        if (!field_exists($field='pathvalueviewcategories')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('pathvalueviewcategories', '2', 'If the view categories link is not working in seo mode change value until correct path is found. Start at 1', '11',1);");}
        if (!field_exists($field='pathvaluecancelpayment')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('pathvaluecancelpayment', '2', 'If the cancel payment buttons are not working in seo mode it means the path the plugin is using is not correct. Change the until the correct path is found. Start at 1', '11',1);");}
        if (!field_exists($field='pathvaluepaymentthankyou')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('pathvaluepaymentthankyou', '2', 'If the payment thank you page is not working in seo mode it means the path the plugin is using is not correct. Change the until the correct path is found. Start at 1', '11',1);");}
        if (!field_exists($field='allowhtmlinadtext')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('allowhtmlinadtext', 0, 'Allow HTML in ad text [ Not recommended ]', '2',0);");}
        if (!field_exists($field='htmlstatustext')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('htmlstatustext', 'No HTML Allowed', 'Display this text above ad detail text input box on ad post page', '2','2');");}
        if (!field_exists($field='hyperlinkurlsinadtext')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('hyperlinkurlsinadtext', 0, 'Make URLs in ad text clickable', '2',0);");}
        if (!field_exists($field='visitwebsitelinknofollow')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('visitwebsitelinknofollow', 1, 'Add no follow to links in ads', '2',0);");}
        if (!field_exists($field='notice_awaiting_approval_ad')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('notice_awaiting_approval_ad', 'All ads must first be approved by the administrator before they are activated in the system. As soon as an admin has approved your ad it will become visible in the system. Thank you for your business.','Text for message to notify user that ad is awaiting approval','2','2');");}
        if (!field_exists($field='displayphonefield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displayphonefield', 1, 'Show phone field','6',0);");}
        if (!field_exists($field='displayphonefieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displayphonefieldreqop', 0, 'Require phone','6',0);");}
        if (!field_exists($field='displaycityfield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycityfield', 1, 'Show city field.','6',0);");}
        if (!field_exists($field='displaycityfieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycityfieldreqop', 0, 'Require city','6',0);");}
        if (!field_exists($field='displaystatefield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaystatefield', 1, 'Show state field.','6',0);");}
        if (!field_exists($field='displaystatefieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaystatefieldreqop', 0, 'Require state','6',0);");}
        if (!field_exists($field='displaycountryfield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycountryfield', 1, 'Show country field.','6',0);");}
        if (!field_exists($field='displaycountryfieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycountryfieldreqop', 0, 'Require country','6',0);");}
        if (!field_exists($field='displaycountyvillagefield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycountyvillagefield', 0, 'Show County/village/other.','6',0);");}
        if (!field_exists($field='displaycountyvillagefieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaycountyvillagefieldreqop', 0, 'Require county/village/other.','6',0);");}
        if (!field_exists($field='displaypricefield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaypricefield', 1, 'Show price field.','6',0);");}
        if (!field_exists($field='displaypricefieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,  `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaypricefieldreqop', 0, 'Require price.','6',0);");}
        if (!field_exists($field='displaywebsitefield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaywebsitefield', 1, 'Show website field','6',0);");}
        if (!field_exists($field='displaywebsitefieldreqop')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaywebsitefieldreqop', 0, 'Require website','6',0);");}
        if (!field_exists($field='displaypostedbyfield')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('displaypostedbyfield', 1, 'Show Posted By field?','6',0);");}
        if (!field_exists($field='buildsearchdropdownlists')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,    `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('buildsearchdropdownlists', 0, 'The search form can attempt to build drop down country, state, city and county lists if data is available in the system. Limits search to available locations. Note that with the regions module installed the value for this option is overridden.','2',0);");}
        if (!field_exists($field='uiwelcome')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` ,   `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('uiwelcome', 'Looking for a job? Trying to find a date? Looking for an apartment? Browse our classifieds. Have a job to advertise? An apartment to rent? Post a classified ad.', 'The welcome text for your classified page on the user side',1,'2');");}
        if (!field_exists($field='showlatestawpcpnews')){$wpdb->query("INSERT  INTO " . AWPCP_TABLE_ADSETTINGS . " (`config_option` , `config_value` , `config_diz` , `config_group_id`, `option_type`    ) VALUES('showlatestawpcpnews', 1, 'Allow AWPCP RSS.',1,0);");}

        }

        // create or restore AWPCP pages
        // awpcp_create_pages();


        // Add new field websiteurl to awpcp_ads
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'websiteurl')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `websiteurl` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `ad_contact_email`");
        }

        $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  DROP INDEX `titdes`");
        $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD FULLTEXT KEY `titdes` (`ad_title`,`ad_details`)");


        // Add new field ad_fee_paid for sorting ads by paid listings first
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'ad_fee_paid')) {
             $query=("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `ad_fee_paid` FLOAT(7,2) NOT NULL AFTER `adterm_id`");
             awpcp_query($query, __LINE__);
        }

        // Increase the length value for the ad_item_price field
        $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . " CHANGE `ad_item_price` `ad_item_price` INT( 25 ) NOT NULL");

        // Ad new field add_county_village to awpcp_ads
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'ad_county_village')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `ad_county_village` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `ad_country`");
        }

        // Add field ad_views to table awpcp_ads to track ad views
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'ad_views')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `ad_views` INT(10) NOT NULL DEFAULT 0 AFTER `ad_item_price`");
        }

        // Insert new field ad_item_price into awpcp_ads table
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'ad_item_price')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `ad_item_price` INT( 10 ) NOT NULL AFTER `ad_country`");
        }
    }

    private function upgrade_to_1_9_9($version) {
        global $wpdb, $awpcp;

        // Add an user_id column to the Ads table
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'user_id')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `user_id` INT(10) DEFAULT NULL");

            // attempt to populate user_id column
            $users_emails = $wpdb->get_results("SELECT ID, user_email FROM " . $wpdb->users);
            $query = "UPDATE " . AWPCP_TABLE_ADS . " SET user_id = %d WHERE LOWER(ad_contact_email) = %s";
            foreach ($users_emails as $user) {
                $wpdb->query($wpdb->prepare($query, $user->ID, strtolower($user->user_email)));
            }
            $wpdb->show_errors();
        }


        // Add a renew_email_sent column to Ads table
        if (!$this->column_exists(AWPCP_TABLE_ADS, 'renew_email_sent')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `renew_email_sent` TINYINT(1) NOT NULL DEFAULT 0");
        }


        // Map old settings to the new Settings API system
        $table = $wpdb->get_var("SHOW TABLES LIKE '" . AWPCP_TABLE_ADSETTINGS . "'");
        if (strcmp($table, AWPCP_TABLE_ADSETTINGS) == 0) {
            $settings = $wpdb->get_results('SELECT * FROM ' . AWPCP_TABLE_ADSETTINGS);
            foreach ($settings as $setting) {
                switch (intval($setting->option_type)) {
                    case 0:
                        $value = intval($setting->config_value);
                        break;
                    case 1:
                    case 2:
                    case 3:
                        $value = $setting->config_value;
                        break;
                }
                $awpcp->settings->update_option($setting->config_option, $value, true);
            }
        }


        $translations = array(
            'userpagename' => 'main-page-name',
            'showadspagename' => 'show-ads-page-name',
            'placeadpagename' => 'place-ad-page-name',
            'editadpagename' => 'edit-ad-page-name',
            'page-name-renew-ad' => 'renew-ad-page-name',
            'replytoadpagename' => 'reply-to-ad-page-name',
            'browseadspagename' => 'browse-ads-page-name',
            'searchadspagename' => 'search-ads-page-name',
            'browsecatspagename' => 'browse-categories-page-name',
            'categoriesviewpagename' => 'view-categories-page-name',
            'paymentthankyoupagename' => 'payment-thankyou-page-name',
            'paymentcancelpagename' => 'payment-cancel-page-name');

        // rename page name settings
        foreach ($translations as $original => $translation) {
            $value = $awpcp->settings->get_option($original, null);
            // only translate settings that already exists, the others will
            // be defined when the settings are registered
            if ($value !== null) {
                $awpcp->settings->update_option($translation, $value, true);
            }
        }

        // create Pages table and map pagename to WP Pages IDs
        $table = $wpdb->get_var("SHOW TABLES LIKE '" . AWPCP_TABLE_PAGES . "'");
        if (strcmp($table, AWPCP_TABLE_PAGES) != 0) {
            $sql = 'CREATE TABLE IF NOT EXISTS ' . AWPCP_TABLE_PAGES . " (
                `page` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `id` INT(10) NOT NULL,
                PRIMARY KEY  (`page`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
            dbDelta($sql);

            $table = AWPCP_TABLE_PAGES;
        }

        // if this table has records then we already did the migration, skip.
        $count = count($wpdb->get_results('SELECT * FROM ' . AWPCP_TABLE_PAGES ));
        if ($count <= 0) {
            // map pagenames to ids
            $pages = array_values($translations);
            foreach ($pages as $page) {
                $name = $awpcp->settings->get_option($page, null);
                $sanitized = sanitize_title($name);

                if ($name == null || strcmp($sanitized, 'view-categories-page-name') === 0) {
                    continue;
                }

                $sql = "SELECT ID FROM $wpdb->posts WHERE post_name = '$sanitized' AND post_type = 'page'";
                $id = intval($wpdb->get_var($sql));
                $id = $id > 0 ? $id : -1;

                $params = array('page' => $page, 'id' => $id);
                $result = $wpdb->insert(AWPCP_TABLE_PAGES, $params);
            }
        }
    }

    private function upgrade_to_2_0_0($version) {
        global $awpcp;
        // Change Expired Ad subject line setting
        if (version_compare($version, '1.9.9.4 beta') <= 0) {
            $awpcp->settings->update_option('adexpiredsubjectline',
                'Your classifieds listing at %s has expired', $force=true);
        }
    }

    private function upgrade_to_2_0_1($version) {
        global $wpdb;

        // update CHARSET and COLLATE values for standard AWPCP tables and columns
        $tables = $wpdb->get_col("SHOW TABLES LIKE '%_awpcp_%'");
        awpcp_fix_table_charset_and_collate($tables);
    }

    private function upgrade_to_2_0_5($version) {
        global $wpdb, $awpcp;

        $translations = array(
            'userpagename' => 'main-page-name',
            'showadspagename' => 'show-ads-page-name',
            'placeadpagename' => 'place-ad-page-name',
            'editadpagename' => 'edit-ad-page-name',
            'page-name-renew-ad' => 'renew-ad-page-name',
            'replytoadpagename' => 'reply-to-ad-page-name',
            'browseadspagename' => 'browse-ads-page-name',
            'searchadspagename' => 'search-ads-page-name',
            'browsecatspagename' => 'browse-categories-page-name',
            'categoriesviewpagename' => 'view-categories-page-name',
            'paymentthankyoupagename' => 'payment-thankyou-page-name',
            'paymentcancelpagename' => 'payment-cancel-page-name');

        // Users who upgraded from 1.8.9.4 to 2.0.4 have an installation
        // with no AWPCP pages. The pages exist, but are not recognized
        // by the plugin.
        foreach ($translations as $old => $new) {
            // Find current stored id
            $query = 'SELECT page, id FROM ' . AWPCP_TABLE_PAGES . ' WHERE page = %s';
            $pages = $wpdb->get_results($wpdb->prepare($query, $new));

            // A page was found, move on.
            if (!empty($pages) && $pages[0]->id != -1) {
                continue;
            }

            // Let's try to find the pages using the old AND new names
            foreach (array($old, $new) as $option) {
                // The setting doesn't exist. Nothing to do.
                $name = $awpcp->settings->get_option($option, null);
                if ($name == null) {
                    continue;
                }

                $sanitized = sanitize_title($name);
                $sql = "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'page'";

                $id = intval($wpdb->get_var($wpdb->prepare($sql, $sanitized)));
                $id = $id > 0 ? $id : -1;

                $params = array('page' => $new, 'id' => $id);
                $result = $wpdb->update(AWPCP_TABLE_PAGES, $params, array('page' => $new));

                if ($id > 0) {
                    $awpcp->settings->update_option($new, $name, true);
                    break;
                }
            }
        }

        // Since pages automatic creation is not enabled, we need to create the
        // Renew Ad page manually.
        awpcp_create_subpage('renew-ad-page-name',
                             $awpcp->settings->get_option('renew-ad-page-name'),
                             '[AWPCP-RENEW-AD]');
    }

    private function upgrade_to_2_0_6($version) {
        global $awpcp;

        // force disable recurring payments
        $awpcp->settings->update_option('paypalpaymentsrecurring', 0, true);
        $awpcp->settings->update_option('twocheckoutpaymentsrecurring', 0, true);
    }

    private function upgrade_to_2_0_7($version) {
        global $wpdb;
        global $awpcp;

        // change Ad's title CSS class to avoid problems with Ad Blocker extensions
        $value = $awpcp->settings->get_option('awpcpshowtheadlayout');
        $value = preg_replace('/<div class="adtitle">/', '<div class="awpcp-title">', $value);
        $awpcp->settings->update_option('awpcpshowtheadlayout', $value);

        if (!$this->column_exists(AWPCP_TABLE_ADPHOTOS, 'is_primary')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADPHOTOS . "  ADD `is_primary` TINYINT(1) NOT NULL DEFAULT 0");
        }

        // add character limit to Fee plans
        if (!$this->column_exists(AWPCP_TABLE_ADFEES, 'characters_allowed')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADFEES . "  ADD `characters_allowed` INT(1) NOT NULL DEFAULT 0");
        }

        $fees = awpcp_get_fees();
        $characters_allowed = get_awpcp_option('maxcharactersallowed', 0);
        foreach ($fees as $fee) {
            $sql = 'UPDATE ' . AWPCP_TABLE_ADFEES . ' SET characters_allowed = %d WHERE adterm_id = %d';
            $wpdb->query($wpdb->prepare($sql, $characters_allowed, $fee->adterm_id));
        }
    }

    private function upgrade_to_2_1_3($version) {
        global $wpdb;

        if (!$this->column_exists(AWPCP_TABLE_ADS, 'renewed_date')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `renewed_date` DATETIME");
        }
    }

    private function upgrade_to_2_2_1($version) {
        global $wpdb;

        // Upgrade posterip for IPv6 address space
        if ($this->column_exists(AWPCP_TABLE_ADS, 'posterip')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  MODIFY `posterip` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
        }
    }

    private function upgrade_to_2_2_2($version) {
        global $wpdb;

        // Users who installed (not upgraded) version 2.2.1 got a posterip field
        // that doesn't not support more than 15 caharacters. We need to
        // upgrade the field again
        // https://github.com/drodenbaugh/awpcp/issues/347#issuecomment-13159975
        if ($this->column_exists(AWPCP_TABLE_ADS, 'posterip')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  MODIFY `posterip` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
        }
    }

    private function upgrade_to_3_0_0($version) {
        global $wpdb, $awpcp;

        /* Create Credit Plans table */
        dbDelta($this->create_credit_plans_table);

        /* Create Payments table and tell AWPCP to migrate Payment Transactions information */
        dbDelta($this->create_payments_table);

        /* Add payment_term_type columns to Ads table */

        if (!$this->column_exists(AWPCP_TABLE_ADS, 'payment_term_type')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADS . "  ADD `payment_term_type` VARCHAR(64) NOT NULL DEFAULT 'fee'");
        }

        /* Add credits, private, title_characters columns to Fees table */

        if (!$this->column_exists(AWPCP_TABLE_ADFEES, 'credits')) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_ADFEES . "  ADD `credits` INT(10) NOT NULL DEFAULT 0");
        }

        if ( ! $this->column_exists( AWPCP_TABLE_ADFEES, 'private' ) ) {
            $wpdb->query( "ALTER TABLE " . AWPCP_TABLE_ADFEES . " ADD `private` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0" );
        }

        if ( ! $this->column_exists( AWPCP_TABLE_ADFEES, 'title_characters' ) ) {
            $wpdb->query( "ALTER TABLE " . AWPCP_TABLE_ADFEES . " ADD `title_characters` INT(1) NOT NULL DEFAULT 0" );
        }

        /* Remove widget options that can break the Latest Ads Widget */
        $widget = get_option( 'widget_awpcp-latest-ads' );
        unset( $widget[0] );
        update_option( 'widget_awpcp-latest-ads', $widget );

        /* Increase min image file size */
        $size = $awpcp->settings->get_option( 'maximagesize', 150000 );
        if ( $size == 150000 ) {
            $awpcp->settings->update_option( 'maximagesize', 1048576 );
        }

        if ( is_null( $awpcp->settings->get_option( 'show-widget-modification-notice', null ) ) ) {
            $awpcp->settings->update_option('show-widget-modification-notice', true, true);
        }

        $query = "SELECT option_name FROM $wpdb->options ";
        $query.= "WHERE option_name LIKE 'awpcp-payment-transaction-%' ";
        $query.= "LIMIT 0, 100";

        $transactions = $wpdb->get_results( $query );

        if ( count( $transactions ) > 0 ) {
            update_option('awpcp-import-payment-transactions', true);
            update_option('awpcp-pending-manual-upgrade', true);
        }
    }

    private function upgrade_to_3_0_2($oldversion) {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $manual_upgrade_required = false;
        $settings = awpcp()->settings;

        // fix for all Ads being (visually) marked as featured (part of #527).
        $layout = $settings->get_option( 'displayadlayoutcode' );
        $layout = str_replace( 'awpcp_featured_ad_wrapper', '$isfeaturedclass', $layout );
        $settings->update_option( 'displayadlayoutcode', $layout );

        // create awpcp_ad_regions table
        dbDelta( $this->create_ad_regions_table );

        // create awpcp_media table
        dbDelta( $this->create_media_table );

        // Create ad metadata table.
        dbDelta( $this->create_ad_meta_table );

        // migrate old regions
        if ( $this->column_exists( AWPCP_TABLE_ADS, 'ad_country' ) ) {
            update_option( 'awpcp-migrate-regions-information', true );

            // the following option was used as the cursor during the first
            // upgrade. However, we had to rollback some of the modifications
            // and the upgrade had to be run again. The new cursor is:
            // 'awpcp-migrate-regions-info-cursor'.
            delete_option( 'awpcp-migrate-regions-information-cursor' );

            $manual_upgrade_required = true;
        }

        // migrate media regions
        if ( awpcp_table_exists( AWPCP_TABLE_ADPHOTOS ) ) {
            update_option( 'awpcp-migrate-media-information', true );

            $manual_upgrade_required = true;
        }

        // add columns required for email verification feature
        $this->columns->create( AWPCP_TABLE_ADS, 'verified', "TINYINT(1) NOT NULL DEFAULT 1" );
        $this->columns->create( AWPCP_TABLE_ADS, 'verified_at', "DATETIME" );

        // add payer email column
        $this->columns->create( AWPCP_TABLE_ADS, 'payer_email', "VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `payment_status`" );
        $this->columns->create( AWPCP_TABLE_PAYMENTS, 'payment_gateway', "VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `payment_status`" );
        $this->columns->create( AWPCP_TABLE_PAYMENTS, 'payer_email', "VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `payment_status`" );

        if ( $this->column_exists( AWPCP_TABLE_ADS, 'payer_email' ) ) {
            $wpdb->query( "UPDATE " . AWPCP_TABLE_ADS . " SET payer_email = ad_contact_email" );
        }

        if ( $manual_upgrade_required ) {
            update_option( 'awpcp-pending-manual-upgrade', true );
        }
    }
}


/**
 * Checks if a given settings exists in the Settings table and
 * inserts it it doesn't exists.
 */
function awpcp_insert_setting($field, $value, $description, $group, $type) {
    global $wpdb;

    if (!field_exists($field)) {
        $data = array('config_option' => $field, 'config_value' => $value,
                      'config_diz' => $description, 'config_group_id' => $group,
                      'option_type' => $type);
        $wpdb->insert(AWPCP_TABLE_ADSETTINGS, $data);
    }
}

/**
 * Set tables charset to utf8 and text-based columns collate to utf8_general_ci.
 */
function awpcp_fix_table_charset_and_collate($tables) {
    global $wpdb;

    $tables = is_array($tables) ? $tables : array($tables);

    $types = array('varchar', 'char', 'text', 'enum', 'set');

    foreach ($tables as $table) {
        $sql = "ALTER TABLE `$table` CHARACTER SET utf8 COLLATE utf8_general_ci";
        $wpdb->query($sql);

        $sql = "SHOW COLUMNS FROM `$table`";
        $columns = $wpdb->get_results($sql, ARRAY_N);

        $parts = array();
        foreach ($columns as $col) {
            foreach ($types as $type) {
                if (strpos($col[1], $type) !== false) {
                    $definition = "CHANGE `$col[0]` `$col[0]` $col[1] ";
                    $definition.= "CHARACTER SET utf8 COLLATE utf8_general_ci ";
                    $definition.= strcasecmp($col[2], 'NO') === 0 ? 'NOT NULL ' : '';

                    // TEXT columns can't have a default value in Strict mode.
                    if ( $type !== 'text' ) {
                        $definition.= strcasecmp($col[4], 'NULL') === 0 ? 'DEFAULT NULL' : "DEFAULT '$col[4]'";
                    }
                    $parts[] = $definition;
                    break;
                }
            }
        }

        $sql = "ALTER TABLE `$table` " . join(', ', $parts);
        $wpdb->query($sql);
    }
}
