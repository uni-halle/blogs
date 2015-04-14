<?php

class C_Pope_Cache_Transients implements I_Pope_Cache_Driver
{
    static $initialized = FALSE;
    static $group_name = 'pope_cache';

    public static function initialize()
    {
        if (self::$initialized)
            return;

        if (is_multisite())
            self::$group_name = self::$group_name . '_' . get_current_blog_id();

        self::$initialized = TRUE;
    }

    public static function add_key_prefix($prefix)
    {
        // there's a chance this will create IDs longer than be stored in wp_options.option_name
        // self::$group_name = self::$group_name . '_' . $prefix;
    }

    public static function lookup($key, $default = NULL)
    {
        $cache = C_Photocrati_Cache::get_instance(self::$group_name);
        return $cache->lookup($key, $default);
    }

    public static function update($key, $value)
    {
        $cache = C_Photocrati_Cache::get_instance(self::$group_name);
        $cache->update($key, $value);
    }

    public static function flush()
    {
        $cache = C_Photocrati_Cache::get_instance(self::$group_name);
        $cache->flush(self::$group_name);
    }

}

class C_Pope_Cache_WPOptions implements I_Pope_Cache_Driver
{
    static $initialized = FALSE;
    static $_empty = TRUE;
    static $_name = 'pope_cache';
    static $_cache = array();

    public static function initialize()
    {
        if (self::$initialized)
            return;

        if (is_multisite())
            self::$_cache = get_blog_option(NULL, self::$_name, array());
        else
            self::$_cache = get_option(self::$_name, array());

        self::$initialized = TRUE;
    }

    public static function add_key_prefix($prefix)
    {
    }

    public static function lookup($key, $default = NULL)
    {
        self::initialize();

        if (!empty(self::$_cache[$key]))
            return self::$_cache[$key];
        else
            return $default;
    }

    public static function update($key, $value)
    {
        self::initialize();

        if (!empty(self::$_cache[$key]) && self::$_cache[$key] == $value)
            return;

        self::$_cache[$key] = $value;

        if (empty(self::$_cache))
            return;

        if (self::$_empty) {
            if (is_multisite()) {
                add_blog_option(NULL, self::$_name, self::$_cache);
            } else {
                add_option(self::$_name, self::$_cache);
            }
            self::$_empty = FALSE;
        } else {
            if (is_multisite()) {
                update_blog_option(NULL, self::$_name, self::$_cache);
            } else {
                update_option(self::$_name, self::$_cache);
            }
        }
    }

    public static function flush()
    {
        self::$_cache = array();
        if (is_multisite())
            delete_blog_option(NULL, self::$_name);
        else
            delete_option(self::$_name);
    }

}