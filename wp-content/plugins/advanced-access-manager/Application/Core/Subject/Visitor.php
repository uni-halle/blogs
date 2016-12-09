<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Visitor subject
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Subject_Visitor extends AAM_Core_Subject {

    /**
     * Subject UID: VISITOR
     */
    const UID = 'visitor';

    /**
     * Retrieve Visitor Subject
     *
     * @return null|AAM_Core_Subject
     *
     * @access protected
     */
    protected function retrieveSubject() {
        return null;
    }

    /**
     *
     * @return type
     */
    public function getCapabilities() {
        return array();
    }

    /**
     *
     * @param type $value
     * @param type $object
     * @param type $id
     * @return type
     */
    public function updateOption($value, $object, $id = 0) {
        return AAM_Core_API::updateOption(
                        $this->getOptionName($object, $id), $value
        );
    }

    /**
     *
     * @param type $object
     * @param type $id
     * @return type
     */
    public function readOption($object, $id = 0) {
        return AAM_Core_API::getOption(
                        $this->getOptionName($object, $id)
        );
    }

    /**
     * 
     * @param type $object
     * @param type $id
     * @return type
     */
    public function getOptionName($object, $id) {
        return 'aam_' . self::UID . "_{$object}" . ($id ? "_{$id}" : '');
    }

    /**
     *
     * @return type
     */
    public function getUID() {
        return self::UID;
    }

}