<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend capability manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Capability extends AAM_Backend_Feature_Abstract {
    
    /**
     * Capability groups
     * 
     * @var array
     * 
     * @access private
     */
    private $_groups = array(
        'system' => array(
            'level_0', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5',
            'level_6', 'level_7', 'level_8', 'level_9', 'level_10'
        ),
        'post' => array(
            'delete_others_pages', 'delete_others_posts', 'edit_others_pages',
            'delete_posts', 'delete_private_pages', 'delete_private_posts',
            'delete_published_pages', 'delete_published_posts', 'delete_pages',
            'edit_others_posts', 'edit_pages', 'edit_private_posts',
            'edit_private_pages', 'edit_posts', 'edit_published_pages',
            'edit_published_posts', 'publish_pages', 'publish_posts', 'read',
            'read_private_pages', 'read_private_posts', 'edit_permalink'
        ),
        'backend' => array(
            'aam_manage', 'activate_plugins', 'add_users', 'update_plugins',
            'delete_users', 'delete_themes', 'edit_dashboard', 'edit_files',
            'edit_plugins', 'edit_theme_options', 'edit_themes', 'edit_users',
            'export', 'import', 'install_plugins', 'install_themes',
            'manage_options', 'manage_links', 'manage_categories', 'customize',
            'unfiltered_html', 'unfiltered_upload', 'update_themes',
            'update_core', 'upload_files', 'delete_plugins', 'remove_users',
            'switch_themes', 'list_users', 'promote_users', 'create_users'
        )
    );

    /**
     *
     * @return type
     */
    public function getTable() {
        $response = array('data' => array());

        $subject = AAM_Backend_View::getSubject();
        if ($subject instanceof AAM_Core_Subject_Role) {
            $response['data'] = $this->retrieveAllCaps();
        } else {
            foreach ($this->getCapabilityList($subject) as $cap) {
                $response['data'][] = array(
                    $cap,
                    $this->getGroup($cap),
                    $cap,
                    $this->prepareActionList($cap)
                );
            }
        }

        return json_encode($response);
    }
    
    /**
     * Update capability tag
     * 
     * @return string
     * 
     * @access public
     */
    public function update() {
        $capability = AAM_Core_Request::post('capability');
        $updated    = AAM_Core_Request::post('updated');
        $roles      = AAM_Core_API::getRoles();
        
        //first make sure that similar capability does not exist already
        $allcaps = AAM_Core_API::getAllCapabilities();
        
        if (!isset($allcaps[$updated])) {
            foreach($roles->role_objects as $role) {
                //check if capability is present for current role! Please notice, we
                //can not use the native WP_Role::has_cap function because it will
                //return false if capability exists but not checked
                if (isset($role->capabilities[$capability])) {
                    $role->add_cap($updated, $role->capabilities[$capability]);
                    $role->remove_cap($capability);
                }
            }
            $response = array('status' => 'success');
        } else {
            $response = array(
                'status'  => 'failure', 
                'message' => __('Capability already exists', AAM_KEY)
            );
        }
        
        return json_encode($response);
    }
    
    /**
     * Delete capability
     * 
     * This function delete capability in all roles.
     * 
     * @return string
     * 
     * @access public
     */
    public function delete() {
        $capability = AAM_Core_Request::post('capability');
        $roles      = AAM_Core_API::getRoles();
        $subject    = AAM_Backend_View::getSubject();
        
        if (is_a($subject, 'AAM_Core_Subject_Role')) {
            foreach($roles->role_objects as $role) {
                $role->remove_cap($capability);
            }
            $response = array('status' => 'success');
        } else {
            $response = array(
                'status'  => 'failure', 
                'message' => __('Can not remove the capability', AAM_KEY)
            );
        }
        
        return json_encode($response);
    }
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.capability.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'object/capability.phtml';
    }
    
    /**
     * 
     * @param AAM_Core_Subject_User $subject
     * @return type
     */
    protected function getCapabilityList(AAM_Core_Subject_User $subject) {
        $list = array();
        
        //IMPORTANT! Cause it is possible that user is not assigned to any role
        $roles = $subject->roles;
        
        if (is_array($roles)) {
            foreach($roles as $slug) {
                $role = AAM_Core_API::getRoles()->get_role($slug);
                if ($role) {
                    $list = array_keys($role->capabilities);
                    break;
                }
            }
        }
        return $list;
    }
    
    /**
     * 
     * @param type $cap
     * @return type
     */
    protected function prepareActionList($cap) {
        $subject = AAM_Backend_View::getSubject();
        $actions = array();
        
        $actions[] = ($subject->hasCapability($cap) ? 'checked' : 'unchecked');
        
        //allow to delete or update capability only for roles!
        if (AAM_Core_Config::get('manage-capability', false) 
                && is_a($subject, 'AAM_Core_Subject_Role')) {
            $actions[] = 'edit';
            $actions[] = 'delete';
        }
        
        return implode(
            ',', apply_filters('aam-cap-row-actions-filter', $actions, $subject)
        );
    }

    /**
     * Get list of user roles
     * 
     * @param array $roles
     * 
     * @return array
     * 
     * @access protected
     */
    protected function getUserRoles($roles) {
        $response = array();
        
        $names = AAM_Core_API::getRoles()->get_names();
        
        if (is_array($roles)) { 
            foreach($roles as $role) {
                if (isset($names[$role])) {
                    $response[] = translate_user_role($names[$role]);
                }
            }
        }
        
        return $response;
    }
    
    /**
     * 
     * @return type
     */
    protected function retrieveAllCaps() {
        $response = array();
        $caps     = AAM_Core_API::getAllCapabilities();
        
        foreach (array_keys($caps) as $cap) {
            $response[] = array(
                $cap,
                $this->getGroup($cap),
                $cap,
                $this->prepareActionList($cap)
            );
        }
        
        return $response;
    }

    /**
     * Get capability group list
     * 
     * @return array
     * 
     * @access public
     */
    public function getGroupList() {
        return apply_filters('aam-capability-groups-filter', array(
            __('System', AAM_KEY),
            __('Posts & Pages', AAM_KEY),
            __('Backend', AAM_KEY),
            __('Miscellaneous', AAM_KEY)
        ));
    }

    /**
     * Add new capability
     * 
     * @return string
     * 
     * @access public
     */
    public function add() {
        $capability = sanitize_text_field(AAM_Core_Request::post('capability'));

        if ($capability) {
            //add the capability to administrator's role as default behavior
            AAM_Core_API::getRoles()->add_cap('administrator', $capability);
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'failure');
        }

        return json_encode($response);
    }

    /**
     * Get capability group name
     * 
     * @param string $capability
     * 
     * @return string
     * 
     * @access protected
     */
    protected function getGroup($capability) {
        if (in_array($capability, $this->_groups['system'])) {
            $response = __('System', AAM_KEY);
        } elseif (in_array($capability, $this->_groups['post'])) {
            $response = __('Posts & Pages', AAM_KEY);
        } elseif (in_array($capability, $this->_groups['backend'])) {
            $response = __('Backend', AAM_KEY);
        } else {
            $response = __('Miscellaneous', AAM_KEY);
        }

        return apply_filters(
                'aam-capability-group-filter', $response, $capability
        );
    }

    /**
     * Register capability feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        $cap = AAM_Core_Config::get(self::getAccessOption(), 'administrator');
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'capability',
            'position'   => 15,
            'title'      => __('Capabilities', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array('AAM_Core_Subject_Role', 'AAM_Core_Subject_User'),
            'view'       => __CLASS__
        ));
    }

}