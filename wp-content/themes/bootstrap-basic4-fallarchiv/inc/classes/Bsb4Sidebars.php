<?php

/*
 *   File: Bsb4Sidebars.php
 *   Project: Fallportal Child Theme 
 *
 *   Copyright(c) 2018 codemacher UG (haftungsbeschränkt) All Rights Reserved.
 *
 *   Created on : 10.04.2018, 18:37:40
 */

namespace BootstrapBasic4;

if (!class_exists('\\BootstrapBasic4\\Bsb4Sidebars')) {

  class Bsb4Sidebars {

    private $archive_slugs_faelle = array(
        'interpretation', 'schulform', 'schultraeger', 'klasse', 'fach', 'sozialform', 'werhandelt-sch', 'werhandelt-kj', 'werhandelt-eb', 'handlungsfeld-au', 'handlungsfeld-kj', 'handlungsfeld-eb', 'einrichtung', 'erhebungskontext', 'erhebungsmethode', 'auswertungsmethode', 'format', 'status', 'auswertungsmethode'
    );
    private $archive_slugs_wissen = array(
        'thema'
    );
    /**
     *
     * @var array Feld mit allen page_ids auf denen das Wissensmenü angezeigt werden soll 
     */
    private $page_ids_wissen = array(1620, 1635, 1637, 1639);

    public function addActionsFilters() {
      add_action('widgets_init', array(&$this, 'registerSidebars'), 100);
    }

    /**
     * 
     * @return boolean 
     */
    public function shouldShowFaelle($post_id) {
      global $wp_query;
      if (is_search() || isset($wp_query->query['sfid']) || is_post_type_archive('interpretation') || ((is_category() || is_single() || is_archive()) && $this->_isFaelle($post_id))) {
        return !$this->shouldShowWissen($post_id);
      }
      return false;
    }

    /**
     * 
     * @return boolean
     */
    public function shouldShowWissen($post_id) {
      if (((is_category || is_single || is_archive()) && $this->_isWissen($post_id))
              || (is_page && in_array($post_id, $this->page_ids_wissen))) {
        return true;
      }
      return false;
    }

    protected function _isFaelle($post_id) {
      $cats = get_the_category($post_id);
      foreach ($cats as $cat) {
        /* @var $cat WP_Term */
        if ($this->_isFallAtom($cat)) {
          return true;
        }
      }

      foreach ($this->archive_slugs_faelle as $slug) {
        if (is_tax($slug)) {
          return true;
        }
      }

      return false;
    }

    protected function _isWissen($post_id) {
      $cats = get_the_category($post_id);
      foreach ($cats as $cat) {
        /* @var $cat WP_Term */
        if ($this->_isWissenAtom($cat)) {
          return true;
        }
      }

      foreach ($this->archive_slugs_wissen as $slug) {
        if (is_tax($slug)) {
          return true;
        }
      }

      return false;
    }

    /**
     * 
     * @param WP_Term $term
     * @return boolean
     */
    protected function _isFallAtom($term) {
      if ($term->slug === 'faelle') {
        return true;
      } else if ($term->slug === 'allgemein') {
        return true;
      }

      if (isset($term->parent)) {
        $parent_term = get_term($term->parent);
        return $this->_isFallAtom($parent_term);
      }
      return false;
    }

    /**
     * 
     * @param WP_Term $term
     * @return boolean
     */
    protected function _isWissenAtom($term) {
      if ($term->slug === 'wissen') {
        return true;
      }

      if (isset($term->parent)) {
        $parent_term = get_term($term->parent);
        return $this->_isWissenAtom($parent_term);
      }
      return false;
    }

    function registerSidebars() {
      unregister_sidebar('sidebar-left');
      unregister_sidebar('sidebar-right');
    }

  }

}