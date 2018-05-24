<?php

/*
 *   File: Bsb4Acf.php
 *   Project: Fallportal Child Theme 
 *
 *   Copyright(c) 2018 codemacher UG (haftungsbeschränkt) All Rights Reserved.
 *
 *   Created on : 10.04.2018, 15:00:23
 */

namespace BootstrapBasic4;

if (!class_exists('\\BootstrapBasic4\\Bsb4Acf')) {

  class Bsb4Acf {

    private $field_keys = array(
        'rel_diskussion_zusfaelle' => 'field_59ab08a26be08',
        'rel_diskussion_interpretation' => 'field_59add130be848',
        'rel_interpretation_diskussion' => 'field_59b6f611a5d65',
    );

    public function addActionsFilters() {
      add_action('admin_enqueue_scripts', array(&$this, 'acf_admin_style'));

      add_filter('acf/update_value/name=klassenstufe', array(&$this, 'updateValueKlassenstufe'), 10, 3);

      /** reziproke Beziehung zu sich selbst */
      add_filter('acf/update_value/key=' . $this->field_keys['rel_diskussion_zusfaelle'], array(&$this, 'reciprocalRelationDiskussionZusfaelle'), 10, 3);

      /** reziproke Beziehung von Fällen und Interpretationen */
      add_filter('acf/update_value/key=' . $this->field_keys['rel_diskussion_interpretation'], array(&$this, 'reciprocalRelationDiskussionInterpretation'), 10, 3);
      add_filter('acf/update_value/key=' . $this->field_keys['rel_interpretation_diskussion'], array(&$this, 'reciprocalRelationDiskussionInterpretation'), 10, 3);
    }

    function acf_admin_style() {
      wp_enqueue_style('fallarchiv-admin-acf-styles', get_stylesheet_directory_uri() . '/css/admin_acf.css');
    }

    public function updateValueKlassenstufe($value, $post_id, $field) {
      $klasse = get_term(intval($value), 'klasse');
      $klasse_num = intval($klasse->name);
      update_field('klassenstufe_num', $klasse_num, $post_id);
      return $value;
    }

    public function reciprocalRelationDiskussionZusfaelle($value, $post_id, $field) {
      return $this->_reciprocalRelationship($value, $post_id, $field, $this->field_keys['rel_diskussion_zusfaelle'], $this->field_keys['rel_diskussion_zusfaelle']);
    }

    public function reciprocalRelationDiskussionInterpretation($value, $post_id, $field) {
      return $this->_reciprocalRelationship($value, $post_id, $field, $this->field_keys['rel_diskussion_interpretation'], $this->field_keys['rel_interpretation_diskussion']);
    }

    /**
     * Quelle: https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-reciprocal-relationship.php
     * @param type $value
     * @param type $post_id
     * @param type $field
     */
    private function _reciprocalRelationship($value, $post_id, $field, $key_a, $key_b) {
      // set the two fields that you want to create
      // a two way relationship for
      // these values can be the same field key
      // if you are using a single relationship field
      // on a single post type
      // the field key of one side of the relationship
      //$key_a = 'field_5a54f6367c764';
      // the field key of the other side of the relationship
      // as noted above, this can be the same as $key_a
      //$key_b = 'field_5a3ba71e83154';
      // figure out wich side we're doing and set up variables
      // if the keys are the same above then this won't matter
      // $key_a represents the field for the current posts
      // and $key_b represents the field on related posts
      if ($key_a != $field['key']) {
        // this is side b, swap the value
        $temp = $key_a;
        $key_a = $key_b;
        $key_b = $temp;
      }

      // get both fields
      // this gets them by using an acf function
      // that can gets field objects based on field keys
      // we may be getting the same field, but we don't care
      $field_a = acf_get_field($key_a);
      $field_b = acf_get_field($key_b);

      // set the field names to check
      // for each post
      $name_a = $field_a['name'];
      $name_b = $field_b['name'];

      // get the old value from the current post
      // compare it to the new value to see
      // if anything needs to be updated
      // use get_post_meta() to a avoid conflicts
      $old_values = get_post_meta($post_id, $name_a, true);
      // make sure that the value is an array
      if (!is_array($old_values)) {
        if (empty($old_values)) {
          $old_values = array();
        } else {
          $old_values = array($old_values);
        }
      }
      // set new values to $value
      // we don't want to mess with $value
      $new_values = $value;
      // make sure that the value is an array
      if (!is_array($new_values)) {
        if (empty($new_values)) {
          $new_values = array();
        } else {
          $new_values = array($new_values);
        }
      }
      // get differences
      // array_diff returns an array of values from the first
      // array that are not in the second array
      // this gives us lists that need to be added
      // or removed depending on which order we give
      // the arrays in
      // this line is commented out, this line should be used when setting
      // up this filter on a new site. getting values and updating values
      // on every relationship will cause a performance issue you should
      // only use the second line "$add = $new_values" when adding this
      // filter to an existing site and then you should switch to the
      // first line as soon as you get everything updated
      // in either case if you have too many existing relationships
      // checking end updated every one of them will more then likely
      // cause your updates to time out.
      //$add = array_diff($new_values, $old_values);
      $add = $new_values;
      $delete = array_diff($old_values, $new_values);

      // reorder the arrays to prevent possible invalid index errors
      $add = array_values($add);
      $delete = array_values($delete);
      if (!count($add) && !count($delete)) {
        // there are no changes
        // so there's nothing to do
        return $value;
      }

      // do deletes first
      // loop through all of the posts that need to have
      // the recipricol relationship removed
      for ($i = 0; $i < count($delete); $i++) {
        $related_values = get_post_meta($delete[$i], $name_b, true);
        if (!is_array($related_values)) {
          if (empty($related_values)) {
            $related_values = array();
          } else {
            $related_values = array($related_values);
          }
        }
        // we use array_diff again
        // this will remove the value without needing to loop
        // through the array and find it
        $related_values = array_diff($related_values, array($post_id));
        // insert the new value
        if (count($related_values) === 1) {
          update_post_meta($add[$i], $name_b, $related_values[0]);
        } else {
          update_post_meta($delete[$i], $name_b, $related_values);
        }
        // insert the acf key reference, just in case
        update_post_meta($delete[$i], '_' . $name_b, $key_b);
      }

      // do additions, to add $post_id
      for ($i = 0; $i < count($add); $i++) {
        $related_values = get_post_meta($add[$i], $name_b, true);
        if (!is_array($related_values)) {
          if (empty($related_values)) {
            $related_values = array();
          } else {
            $related_values = array($related_values);
          }
        }
        if (!in_array($post_id, $related_values)) {
          // add new relationship if it does not exist
          $related_values[] = $post_id;
        }
        // update value
        if (count($related_values) === 1) {
          update_post_meta($add[$i], $name_b, $related_values[0]);
        } else {
          update_post_meta($add[$i], $name_b, $related_values);
        }
        // insert the acf key reference, just in case
        update_post_meta($add[$i], '_' . $name_b, $key_b);
      }
      return $value;
    }

  }

};
