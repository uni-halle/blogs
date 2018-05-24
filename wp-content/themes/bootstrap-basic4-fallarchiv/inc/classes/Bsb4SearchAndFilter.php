<?php
/*
 *   File: Bsb4SearchAndFilter.php
 *   Project: Fallportal Child Theme 
 *
 *   Copyright(c) 2018 codemacher UG (haftungsbeschränkt) All Rights Reserved.
 *
 *   Created on : 30.04.2018, 16:08:57
 */

namespace BootstrapBasic4;

if (!class_exists('\\BootstrapBasic4\\Bsb4SearchAndFilter')) {

  class Bsb4SearchAndFilter {

    static $filter_labels = array(
        '_sfm_klassenstufe_num' => 'Klassenstufe',
    );

    static function is_filtered() {
      global $searchandfilter;
      $sfid = \BootstrapBasic4\Bsb4SearchAndFilter::get_sfid();
      if ($sfid) {
        $sf_form = $searchandfilter->get($sfid);
        return $sf_form->current_query()->is_filtered();
      }
      return false;
    }

    static function get_sfid() {
      global $wp_query;
      if (key_exists('sfid', $wp_query->query) && isset($wp_query->query['sfid'])) {
        return $wp_query->query['sfid'];
      }
      return null;
    }

    static function render_active_filters() {
      global $searchandfilter;
      $sfid = \BootstrapBasic4\Bsb4SearchAndFilter::get_sfid();
      echo '<div class="filter_container">';
      if ($sfid) {
        $sf_form = $searchandfilter->get($sfid);
        $sf_current_query = $sf_form->current_query();
        $filter_array = $sf_current_query->get_array();
        foreach ($filter_array as $key => $values) {
          $render_button = true;
          if ($key === '_sfm_klassenstufe_num') {
            $min = $values['active_terms'][0]['value'];
            $max = $values['active_terms'][1]['value'];
            if ($min === '1' && $max === '13') {
              $render_button = false;
            } else {
              $label = 'Klassenstufe:' . $min . '-' . $max;
            }
          } else {
            $label = $values['name'] . ':' . $values['active_terms'][0]['name'];
          }

          if ($render_button) {
            ?>
            <button onclick="jQuery.resetFormField('<?php echo 'search-filter-form-' . $sfid; ?>', '<?php echo $key; ?>');"><?php echo $label; ?></button>
            <?php
          }
        }
      }
      echo '</div>';
    }

  }

};
