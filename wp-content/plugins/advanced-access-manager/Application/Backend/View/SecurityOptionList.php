<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

return array(
    'login-timeout' => array(
        'title' => __('Login Timeout', AAM_KEY),
        'descr' => sprintf(__('Delay the login process for %s second to significantly reduce the chance for brute force or dictionary attack.', AAM_KEY), AAM_Core_Config::get('security.login.timeout', 1)),
        'value' => AAM_Core_Config::get('login-timeout', false)
    ),
    'login-ip-track' => array(
        'title' => __('Track IP Address', AAM_KEY),
        'descr' => __('Track the IP address for the last successful user login and trigger double authentication via email when the same username/password combination is used to login from a different IP address.', AAM_KEY),
        'value' => AAM_Core_Config::get('login-ip-track', false),
    ),
    'brute-force-lockout' => array(
        'title' => __('Brute Force Lockout', AAM_KEY),
        'descr' => sprintf(__('Automatically reject login attempts if number of unsuccessful login attempts is more than %s over the period of %s.', AAM_KEY), AAM_Core_Config::get('security.login.attempts', 20), AAM_Core_Config::get('security.login.period', '2 minutes')),
        'value' => AAM_Core_Config::get('brute-force-lockout', false),
    )
);