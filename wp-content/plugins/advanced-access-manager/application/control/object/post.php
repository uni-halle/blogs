<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class aam_Control_Object_Post extends aam_Control_Object {

    /**
     * Object Identifier
     */
    const UID = 'post';

    /**
     * Object Action: COMMENT
     *
     * Control access to commenting ability
     */
    const ACTION_COMMENT = 'comment';

    /**
     * Object Action: READ
     *
     * Either Object can be read by user or not
     */
    const ACTION_READ = 'read';

    /**
     * Object Action: EXCLUDE
     *
     * If object is a part of frontend menu either exclude it from menu or not
     */
    const ACTION_EXCLUDE = 'exclude';

    /**
     * Object Action: TRASH
     *
     * Manage access to object trash ability
     */
    const ACTION_TRASH = 'trash';

    /**
     *
     */
    const ACTION_DELETE = 'delete';

    /**
     *
     */
    const ACTION_EDIT = 'edit';

    /**
     *
     * @var type
     */
    private $_post;

    /**
     *
     * @var type
     */
    private $_option = array();

    /**
     * Indicator that settings where inherited
     *
     * @var boolean
     *
     * @access private
     */
    private $_inherited = false;

    /**
     * Init Post Object
     *
     * @param WP_Post|Int $object
     *
     * @return void
     *
     * @access public
     */
    public function init($object) {
        //make sure that we are dealing with WP_Post object
        if ($object instanceof WP_Post){
            $this->setPost($object);
        } elseif (intval($object)) {
            $this->setPost(get_post($object));
        }

        if ($this->getPost()){
            $this->read();
        }
    }

    /**
     * Read the Post AAM Metadata
     *
     * Get all settings related to specified post
     *
     * @return void
     *
     * @access public
     */
    public function read() {
        $option = get_post_meta($this->getPost()->ID, $this->getOptionName(), true);
        //try to inherit it from parent category
        if (empty($option)
                && (aam_Core_ConfigPress::getParam('aam.post.inherit', 'true') == 'true')) {
            $terms = $this->retrievePostTerms();
            //use only first term for inheritance
            $term_id = array_shift($terms);
            //try to get any parent access
            $option = $this->inheritAccess($term_id);
        }
        //even if parent category is empty, try to read the parent subject
        if (empty($option)){
            $option = $this->getSubject()->readParentSubject(
                    self::UID, $this->getPost()->ID
            );
        }

        $this->setOption(
                apply_filters('aam_post_access_option', $option, $this)
        );
    }

    /**
     * Generate option name
     * 
     * @return string
     * 
     * @access protected
     */
    protected function getOptionName() {
        $subject = $this->getSubject();
        //prepare option name
        $meta_key = 'aam_' . self::UID . '_access_' . $subject->getUID();
        $meta_key .= ($subject->getId() ? $subject->getId() : '');

        return $meta_key;
    }

    /**
     * Inherit access from parent term
     * 
     * Go throught the hierarchical branch of terms and retrieve access from the 
     * first parent term that has access defined.
     * 
     * @param int $term_id
     * 
     * @return array
     * 
     * @access private
     */
    private function inheritAccess($term_id) {
        $term = new aam_Control_Object_Term($this->getSubject(), $term_id);
        $access = $term->getOption();
        if (isset($access['post']) && $access['post']) {
            $result = array('post' => $access['post']);
            $this->setInherited(true);
        } elseif (is_object($term->getTerm()) && $term->getTerm()->parent) {
            $result = $this->inheritAccess($term->getTerm()->parent);
        } else {
            $result = array();
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function __sleep(){
        return array('_post', '_option', '_inherited');
    }

    /**
     * @inheritdoc
     */
    public function cacheObject(){
        return true;
    }

    /**
     * @inheritdoc
     */
    public function save($params = null) {
        if (is_array($params)) {
            $this->setInherited(false);
            update_post_meta($this->getPost()->ID, $this->getOptionName(), $params);
            //set flag that this subject has custom settings
            $this->getSubject()->setFlag(aam_Control_Subject::FLAG_MODIFIED);
        }
        //fire internal hook
        do_action_ref_array('aam_object_saved', $this, $params);
    }

    /**
     * Get Object Unique ID
     *
     * @return string
     *
     * @access public
     */
    public function getUID() {
        return self::UID;
    }

    /**
     *
     * @return type
     */
    public function delete() {
        return delete_post_meta($this->getPost()->ID, $this->getOptionName());
    }

    /**
     * Retrieve list of all hierarchical terms the object belongs to
     *
     * @return array
     *
     * @access private
     */
    private function retrievePostTerms() {
        $taxonomies = get_object_taxonomies($this->getPost());
        if (is_array($taxonomies) && count($taxonomies)) {
            //filter taxonomies to hierarchical only
            $filtered = array();
            foreach ($taxonomies as $taxonomy) {
                if (is_taxonomy_hierarchical($taxonomy)) {
                    $filtered[] = $taxonomy;
                }
            }
            $terms = wp_get_object_terms(
                    $this->getPost()->ID, $filtered, array('fields' => 'ids')
            );
        } else {
            $terms = array();
        }

        return $terms;
    }

    /**
     * Set Post. Cover all unexpectd wierd issues with WP Core
     *
     * @param WP_Post $post
     *
     * @return void
     *
     * @access public
     */
    public function setPost($post) {
        if ($post instanceof WP_Post){
            $this->_post = $post;
        } else {
            $this->_post = (object) array('ID' => 0);
        }
    }

    /**
     * Get Post
     *
     * @return WP_Post|stdClass
     *
     * @access public
     */
    public function getPost() {
        return $this->_post;
    }

    /**
     *
     * @param type $option
     */
    public function setOption($option) {
        $this->_option = (is_array($option) ? $option : array());
    }

    /**
     *
     * @return type
     */
    public function getOption() {
        return $this->_option;
    }

    /**
     * Set inherited flag
     *
     * If post does not have access specified, it'll try to inherit it from the
     * parent category and if parent category has access defined it'll inherit all
     * settings and set _inherited flag to true.
     *
     * @param boolean $flag
     *
     * @return void
     *
     * @access public
     */
    public function setInherited($flag){
        $this->_inherited = $flag;
    }

    /**
     *
     * @return type
     */
    public function getInherited(){
        return $this->_inherited;
    }

    /**
     *
     * @param type $area
     * @param type $action
     * @return type
     */
    public function has($area, $action) {
        $response = false;
        if (isset($this->_option['post'][$area][$action])) {
            $response = (intval($this->_option['post'][$area][$action]) ? true : false);
        }

        return $response;
    }

}