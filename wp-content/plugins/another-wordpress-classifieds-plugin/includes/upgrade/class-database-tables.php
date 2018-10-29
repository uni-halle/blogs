<?php

function awpcp_database_tables() {
    return new AWPCP_Database_Tables( awpcp_database_helper() );
}

class AWPCP_Database_Tables {

    private $database_helper;

    public function __construct( $database_helper ) {
        $this->database_helper = $database_helper;
    }

    public function get_categories_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_CATEGORIES . " (
            `category_id` INT(10) NOT NULL AUTO_INCREMENT,
            `category_parent_id` INT(10) NOT NULL,
            `category_name` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `category_order` INT(10) NULL DEFAULT 0,
            PRIMARY KEY  (`category_id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_listings_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_ADS . " (
            `ad_id` INT(10) NOT NULL AUTO_INCREMENT,
            `adterm_id` INT(10) NOT NULL DEFAULT 0,
            `payment_term_type` VARCHAR(64) NOT NULL DEFAULT 'fee',
            `ad_fee_paid` FLOAT(7,2) NOT NULL,
            `ad_category_id` INT(10) NOT NULL,
            `ad_category_parent_id` INT(10) NOT NULL,
            `ad_title` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_details` TEXT CHARACTER SET <charset> COLLATE <collate> NOT NULL,
            `ad_contact_name` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_contact_phone` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `phone_number_digits` VARCHAR(25) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_contact_email` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `websiteurl` VARCHAR( 375 ) CHARACTER SET <charset> COLLATE <collate> NOT NULL,
            `ad_city` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_state` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_country` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_county_village` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
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
            `ad_key` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `ad_transaction_id` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `payment_gateway` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `payment_status` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `payer_email` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `is_featured_ad` TINYINT(1) DEFAULT NULL,
            `posterip` VARCHAR(50) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `user_id` INT(10) DEFAULT NULL,
            `renew_email_sent` TINYINT(1) NOT NULL DEFAULT 0,
            `renewed_date` DATETIME,
            PRIMARY KEY  (`ad_id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_listing_regions_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_AD_REGIONS . " (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `ad_id` INT(10) NOT NULL,
            `country` VARCHAR(64) COLLATE <collate> DEFAULT '',
            `county` VARCHAR(64) COLLATE <collate> DEFAULT '',
            `state` VARCHAR(64) COLLATE <collate> DEFAULT '',
            `city` VARCHAR(64) COLLATE <collate> DEFAULT '',
            `region_id` INT(10) DEFAULT NULL,
            INDEX `country_index` (`country`),
            INDEX `county_index` (`county`),
            INDEX `state_index` (`state`),
            INDEX `city_index` (`city`),
            INDEX `region_id_index` (`region_id`),
            PRIMARY KEY  (`id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_fees_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_ADFEES . " (
            `adterm_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `adterm_name` VARCHAR(100) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `description` TEXT COLLATE <collate> NOT NULL,
            `credits` INT(10) NOT NULL DEFAULT 0,
            `amount` FLOAT(6,2) UNSIGNED NOT NULL DEFAULT '0.00',
            `recurring` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `rec_period` INT(5) UNSIGNED NOT NULL DEFAULT 0,
            `rec_increment` VARCHAR(5) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `buys` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `imagesallowed` INT(5) UNSIGNED NOT NULL DEFAULT 0,
            `regions` INT(10) NOT NULL DEFAULT 1,
            `is_featured_ad_pricing` TINYINT(1) DEFAULT NULL,
            `categories` TEXT CHARACTER SET <charset> COLLATE <collate>,
            `characters_allowed` INT(1) NOT NULL DEFAULT 0,
            `title_characters` INT(1) NOT NULL DEFAULT 0,
            `private` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY  (`adterm_id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_payments_table_definition() {
        $table_defintion =
        'CREATE TABLE IF NOT EXISTS ' . AWPCP_TABLE_PAYMENTS . " (
            `id` VARCHAR(64) CHARACTER SET <charset> COLLATE <collate> NOT NULL,
            `items` TEXT,
            `data` TEXT,
            `errors` TEXT,
            `user_id` INT(10),
            `status` VARCHAR(32) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT 'open',
            `payment_status` VARCHAR(32) CHARACTER SET <charset> COLLATE <collate>,
            `payment_gateway` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `payer_email` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `version` TINYINT(1),
            `created` DATETIME NOT NULL,
            `updated` DATETIME NOT NULL,
            `completed` DATETIME,
            PRIMARY KEY  (`id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_credit_plans_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_CREDIT_PLANS . " (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `description` VARCHAR(500) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `credits` INT(10) NOT NULL,
            `price` FLOAT,
            `created` DATETIME NOT NULL,
            `updated` DATETIME NOT NULL,
            PRIMARY KEY  (`id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_media_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_MEDIA . " (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `ad_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `name` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `path` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `mime_type` VARCHAR(100) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '',
            `enabled` TINYINT(1) NOT NULL DEFAULT 0,
            `status` VARCHAR(20) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT '" . AWPCP_Media::STATUS_APPROVED . "',
            `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
            `metadata` TEXT CHARACTER SET <charset> COLLATE <collate> NOT NULL,
            `created` DATETIME NOT NULL,
            PRIMARY KEY  (`id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_listing_meta_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_AD_META . " (
            `meta_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `awpcp_ad_id` BIGINT(10) UNSIGNED NOT NULL,
            `meta_key` VARCHAR(255),
            `meta_value` LONGTEXT,
            PRIMARY KEY  (`meta_id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";

        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }

    public function get_tasks_table_definition() {
        $table_defintion =
        "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_TASKS . " (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) CHARACTER SET <charset> COLLATE <collate> NOT NULL,
            `status` VARCHAR(50) CHARACTER SET <charset> COLLATE <collate> NOT NULL DEFAULT 'new',
            `priority` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `execute_after` DATETIME NOT NULL,
            `metadata` TEXT CHARACTER SET <charset> COLLATE <collate> NOT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY  (`id`)
        ) DEFAULT CHARSET=<charset> COLLATE=<collate>;";
        return $this->database_helper->replace_charset_and_collate( $table_defintion );
    }
}
