<?php

defined('WYSIJANLP') or die('Restricted access');

/**
 * This class help extending the config page with various hooks and filters
 */
class WYSIJANLP_help_extend_config extends WYSIJA_help {

    function __construct() {
        if (method_exists('WYSIJA_help', 'WYSIJA_help')) parent::WYSIJA_help();
        else parent::__construct();
    }

    function premium_activated_message(){
        // display that premium is activated
        $model_config = WYSIJA::get('config', 'model');
        if ($model_config->getValue('premium_key')) {
                $date_expiry = $model_config->getValue('premium_expire_at' );
                if( $date_expiry > time() ){
                    ?>
                    <tr>
                            <td class="premium_activated" colspan="2">
                                    <i class="dashicons dashicons-awards"></i><?php echo __('Your Premium is activated.', WYSIJA); ?>
                                    <span class="expiring_at"><?php


                                    $date_expiry = date( get_option('date_format'), $date_expiry );

                                    echo '('.sprintf(__('Valid until %s',WYSIJA),$date_expiry).')';
                                    ?></span>
                            </td>
                    </tr>
                    <?php
                }
        }
    }

    /**
     * function to add or remove some tabs filter-based
     * @param type $tabs
     * @return type
     */
    function premium_tabs_name($tabs) {
        $model_config = WYSIJA::get('config', 'model');
        // check whether the user is premium or not
        if ($model_config->getValue('premium_key')) {
            // change premium tab label
            $tabs['bounce'] = __('Bounce Handling', WYSIJA);

            // allow bounce handling to be displayed on non multisite and on multisite which have
            if (is_multisite()) {
                if (WYSIJA::current_user_can('manage_network'))
                    $tabs['bounce'] = 'MS ' . $tabs['bounce'];
                else
                    unset($tabs['bounce']);
            }
        } else {
            // remove bounce tab
            unset($tabs['bounce']);
        }
        return $tabs;
    }

    /**
     * advanced tab fields filter-based
     * @param type $fields
     * @return type
     */
    function advanced_settings_fields_filter1($fields) {
        $model_config = WYSIJA::get('config', 'model');
        if ($model_config->getValue('premium_key')) {
            unset($fields['bounce_email']);
        }
        return $fields;
    }

    /**
     * advanced tab fields filter-based
     * @param type $fields
     * @return string
     */
    function advanced_settings_fields_filter2($fields) {
        $model_config = WYSIJA::get('config', 'model');
        if ($model_config->getValue('premium_key')) {
            $fields['dkim'] = array(
                'type' => 'dkim',
                'label' => __('DKIM signature', WYSIJA),
                'desc' => __('Improve your spam score. Mailpoet can sign all your emails with DKIM. [link]Read more.[/link]', WYSIJA),
                'link' => '<a href="http://support.wysija.com/knowledgebase/guide-to-dkim-in-wysija/" target="_blank" title="' . __("Preview page", WYSIJA) . '">');
        }
        return $fields;
    }

    /**
     *
     * @param type $html_content
     * @param type $arg
     * @return string
     */
    function bounce_tab_content($html_content, $arg) {
        $this->viewObj = $arg['viewObj'];
        $model_config = WYSIJA::get('config', 'model');
        if ($model_config->getValue('premium_key')) {
            $html_content .='<div id="bounce" class="wysija-panel hidden">';
            $html_content .= '<div class="intro">';
            $html_content.= '<p><h3>' . __('How does it work?', WYSIJA) . '</h3></p>';
            $html_content.= '<ol>';
            $html_content.= '  <li>' . __('Create an email account dedicated solely to bounce handling.', WYSIJA) . '</li>';
            $html_content.= '  <li>' . __('Fill out the form below so we can connect to it.', WYSIJA) . '</li>';
            $html_content.= '  <li>' . __('Take it easy, the plugin does the rest.', WYSIJA) . '</li>';
            $html_content.= '</ol>';
            $html_content.= '<p class="description">' . __('Need help?', WYSIJA) . ' ' . str_replace(array('[link]', '[/link]'), array('<a href="http://support.wysija.com/knowledgebase/automated-bounce-handling-install-guide/">', '</a>'), __('Check out [link]our guide[/link] on how to fill out the form.', WYSIJA)) . '</p>';
            $html_content.= '</div>';


            $html_content.='
            <div id="innertabs">' . $this->bounce_tab_innertabs() . '

                <div id="connection" class="wysija-innerpanel hidden">
                    ' . $this->bounce_tab_connection() . '
                </div>
                <div id="actions" class="wysija-innerpanel hidden">
                    <p class="description">' . __('There are plenty of reasons for bounces. Configure what to do in each scenario.', WYSIJA) . '</p>
                    <div id="bounce-msg-error"></div>

                    ' . $this->bounce_tab_rules() . '
                </div>
            </div>';
            $html_content .='<p class="submit"><input type="submit" value="' . esc_attr(__('Save settings', WYSIJA)) . '" class="button-primary wysija" /></p>';
            $html_content .='</div>';
        }

        return $html_content;
    }

    /**
     * part of the bounce tab content
     * @param type $current
     * @return string
     */
    function bounce_tab_innertabs($current = 'connection') {
        $tabs = array(
            'connection' => __('Settings', WYSIJA),
            'actions' => __('Actions & Notifications', WYSIJA)
        );
        $html_content = '<h3 id="wysija-innertabs" class="nav-tab-wrapper">';
        foreach ($tabs as $tab => $name) {
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            $html_content.= "<a class='nav-tab$class' href='#$tab'>$name</a>";
        }
        $html_content.='</h2>';
        return $html_content;
    }

    /**
     * part of the bounce tab content
     * @return string
     */
    function bounce_tab_connection() {
        $form_fields = array();
        $form_fields['bounce_email'] = array(
            'type' => 'input',
            'size' => 38,
            'label' => __('Bounce Email', WYSIJA));

        $form_fields['bounce_host'] = array(
            'type' => 'input',
            'size' => 38,
            'label' => __('Hostname', WYSIJA));

        $form_fields['bounce_login'] = array(
            'type' => 'input',
            'size' => 38,
            'label' => __('Login', WYSIJA));
        $form_fields['bounce_password'] = array(
            'type' => 'password',
            'size' => 38,
            'label' => __('Password', WYSIJA));
        $form_fields['bounce_port'] = array(
            'type' => 'input',
            'label' => __('Port', WYSIJA),
            'size' => '4',
            'style' => 'width:10px;');
        $form_fields['bounce_connection_method'] = array(
            'type' => 'dropdown',
            'values' => array('pop3' => 'POP3', 'imap' => 'IMAP', 'pear' => __('POP3 without imap extension', WYSIJA), 'nntp' => 'NNTP'),
            'label' => __('Connection method', WYSIJA));
        $form_fields['bounce_connection_secure'] = array(
            'type' => 'radio',
            'values' => array('' => __('No', WYSIJA), 'ssl' => __('Yes', WYSIJA)),
            'label' => __('Secure connection(SSL)', WYSIJA));
        $form_fields['bounce_selfsigned'] = array(
            'type' => 'selfsigned',
            'label' => __('Self-signed certificates', WYSIJA));

        $multisite_prefix = '';
        if (is_multisite()) {
            $temp_array = array();
            $multisite_prefix = 'ms_';
            foreach ($form_fields as $key => $field) {
                $field['id'] = $key;
                $temp_array[$multisite_prefix . $key] = $field;
            }
            $form_fields = $temp_array;
        }

        $html_content = '<table class="form-table"><tbody>';
        $html_content .= $this->viewObj->buildMyForm($form_fields, '', 'config');

        $name = $multisite_prefix . 'bouncing_emails_each';
        $id = str_replace('_', '-', $name);

        $value = $this->viewObj->model->getValue($name);
        $helper_forms = WYSIJA::get('forms', 'helper');
        $field = $helper_forms->dropdown(array('name' => 'wysija[config][' . $name . ']', 'id' => $id), array('fifteen_min' => __('15 minutes', WYSIJA),
            'thirty_min' => __('30 minutes', WYSIJA),
            'hourly' => __('1 hour', WYSIJA),
            'two_hours' => __('2 hours', WYSIJA),
            'twicedaily' => __('Twice daily', WYSIJA),
            'daily' => __('Day', WYSIJA)), $value);

        $checked = '';
        if ($this->viewObj->model->getValue($multisite_prefix . 'bounce_process_auto'))
            $checked = ' checked="checked" ';
        $html_content .= '<tr><td><label for="bounce-process-auto"><input type="checkbox" ' . $checked . ' id="bounce-process-auto" value="1" name="wysija[config][' . $multisite_prefix . 'bounce_process_auto]" />
            ' . __('Activate bounce and check every...', WYSIJA) . '</label></td><td id="bounce-frequency">' . $field . '</td></tr>';

        // try to connect button
        $html_content .= '<tr><td><a class="button-secondary" id="bounce-connector">' . __('Does it work? Try to connect.', WYSIJA) . '</a></td><td></td></tr>';


        $html_content .= '</tbody></table>';
        return $html_content;
    }

    /**
     * part of the bounce tab content
     * @return string
     */
    function bounce_tab_rules() {

        $helper_rules = WYSIJA::get('rules', 'helper');
        $rules = $helper_rules->getRules(false, true);

        $step2 = array();
        $values_DDP = array('' => __('Do nothing', WYSIJA), 'delete' => __('Delete the user', WYSIJA), "unsub" => __('Unsubscribe the user', WYSIJA));

        if (!is_multisite()) {
            $model_list = WYSIJA::get('list', 'model');
            // get lists which have users  and are enabled
            $query = 'SELECT * FROM [wysija]list WHERE is_enabled > 0';
            $array_list = $model_list->query('get_res', $query);

            foreach ($array_list as $list) {
                $values_DDP['unsub_' . $list['list_id']] = sprintf(__('Unsubscribe the user and add him to the list "%1$s" ', WYSIJA), $list['name']);
            }
        }


        foreach ($rules as $rule) {
            if (isset($rule['behave']))
                continue;
            $label = $rule['title'];
            if (isset($rule['action_user_min']) && $rule['action_user_min'] > 0) {
                $label.=' ' . sprintf(_n('after %1$s try', 'after %1$s tries', $rule['action_user_min'], WYSIJA), $rule['action_user_min']);
            }
            $step2['bounce_rule_' . $rule['key']] = array(
                'type' => 'dropdown',
                'values' => $values_DDP,
                'label' => $label);
            if (isset($rule['action_user'])) {
                $step2['bounce_rule_' . $rule['key']]['default'] = $rule['action_user'];
            }
            if (isset($rule['forward'])) {
                $step2['bounce_rule_' . $rule['key']]['forward'] = $rule['forward'];
            }
        }

        $form_fields = '<ol>';
        $i = 0;
        $formHelp = WYSIJA::get('forms', 'helper');
        foreach ($step2 as $row => $col_params) {
            if (is_multisite()) {
                $field_name = 'ms_' . $row;
            }
            else
                $field_name = $row;

            $form_fields.='<li>';
            $value = $this->viewObj->model->getValue($row);
            if (!$value && isset($col_params['default']))
                $value = $col_params['default'];

            if (isset($col_params['label']))
                $label = $col_params['label'];
            else
                $label = ucfirst($row);
            $desc = '';
            if (isset($col_params['desc']))
                $desc = '<p class="description">' . $col_params['desc'] . '</p>';
            $form_fields.='<label for="' . $row . '">' . $label . $desc . ' </label>';

            if (isset($col_params['forward'])) {
                $value_forward = $this->viewObj->model->getValue($row . '_forwardto');
                if ($value_forward === false) {
                    $model_user = WYSIJA::get('user', 'model');
                    $model_user->getFormat = OBJECT;

                    $data_user = $model_user->getOne(false, array('wpuser_id' => WYSIJA::wp_get_userdata('ID')));

                    $value_forward = $data_user->email;
                }

                $form_fields.='<input  id="' . $row . '" size="30" type="text" class="bounce-forward-email" name="wysija[config][' . $field_name . "_forwardto" . ']" value="' . esc_attr($value_forward) . '" />';
            } else {

                $form_fields.=$formHelp->dropdown(array('id' => $row, 'name' => 'wysija[config][' . $field_name . ']'), $col_params['values'], $value, '');
            }

            $i++;
            $form_fields.='</li>';
        }
        $form_fields.='</ol>';
        return $form_fields;
    }

    function wysija_sending_frequency() {
        $html = '<b>' . __('Tip', WYSIJA) . '</b>: ' . __('you can send as fast as every 15 minutes since Mailpoet guarantees Premium users can send this fast.', WYSIJA);
        return $html;
    }

    function add_text_cron_premium($content) {
        $model_config = WYSIJA::get('config', 'model');
        if (WYSIJA::is_plugin_active('wysija-newsletters-premium/index.php') && $model_config->getValue('premium_key')) {

            $content = __('I\'m a premium user, MailPoet.com will make sure my emails get sent on time.', WYSIJA) . '<br/>';
            $content .= __('If I want I can [link]create an additional cron job[/link] on my end to increase the sending frequency.', WYSIJA) . '<br/><span>' . __('Use this URL in your cron job: [cron_url]') . '</span>';
        }
        return $content;
    }

    /**
     * some values in the settings needs to be overridden by ms values
     * eg bounce with ms_bounce
     * @param array $ms_overriden
     * @return array
     */
    function ms_override($ms_overriden){
        $model_config = WYSIJA::get('config' , 'model');
        if($model_config->getValue('premium_key')){
             $bounce_value = array('bounce','bouncing');
            return array_merge($ms_overriden, $bounce_value);
        }
        return $ms_overriden;
    }

        /**
     * Filter, add capabilities which are only available for Premium users
     * @param array $capabilities list of possible capabilities
     * @return array list of possible capabilities
     */
    function wysija_capabilities($capabilities) {
        $model_config = WYSIJA::get('config', 'model');
        if (WYSIJA::is_plugin_active('wysija-newsletters-premium/index.php') && $model_config->getValue('premium_key')) {
            $tmp = array();
            $is_added_stats_dashboard = false;
            foreach ($capabilities as $role => $capability) {
                $tmp[$role] = $capability;
                if ($role === 'subscribers') { // add this setting, right below Subscribers setting
                    $tmp['stats_dashboard'] = array(
                        'label' => __('Who can view the statistics page?', WYSIJA)
                    );
                    $is_added_stats_dashboard = true;
                }
            }

            if (!$is_added_stats_dashboard) {// if Subscribers setting is not available, by default, put it at the bottom
                $tmp['stats_dashboard'] = array(
                    'label' => __('Who can view the statistics page?', WYSIJA)
                );
            }
            $capabilities = $tmp;
        }
        return $capabilities;
    }
}
