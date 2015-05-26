<?php

// hook this addon in
add_filter( 'Widget_Wrangler_Addons', 'ww_taxonomies_addon' );

// add this addon to the Widget_Wrangler object
function ww_taxonomies_addon($addons){
  $addons['Taxonomies'] = new WW_Taxonomies();
  return $addons;
}

/*
 * WW_Taxonomies allows for setting widgets on taxonomy term routes
 */
class WW_Taxonomies {
  
  /*
   * Construct the addon.  Add unique WordPress hooks
   */
  function __construct(){
    add_filter( 'widget_wrangler_find_all_page_widgets', array( $this, 'ww_find_taxonomy_term_widgets' ) );
  }

  /*
   * Find the widgets for a taxonomy term page
   *
   * @param (mixed) - array if widgets previously found, null if not
   *
   * @return (mixed) - array if widgets found, null if not
   */
  function ww_find_taxonomy_term_widgets($widgets){
    if (is_null($widgets) &&
        (is_tax() || is_category() || is_tag()) &&
        $term = get_queried_object())
    {
      $where = array(
        'type' => 'taxonomy',
        'variety' => 'term',
        'extra_key' => $term->term_id,
      );
      if ($term_data = $this->ww->_extras_get($where)){
        // look for explicitly set preset
        if (isset($term_data->data['preset_id']) && $term_data->data['preset_id'] != 0){
          $preset = $this->ww->presets->get_preset($term_data->data['preset_id']);
          $this->ww->presets->current_preset_id = $preset->id;
          $widgets = $preset->widgets;
        }
        else {
          $widgets = $term_data->widgets;
        }
      }
      else {
        // see if the taxonomy is overriding
        $where['variety'] = 'taxonomy';
        $where['extra_key'] = $term->taxonomy;
        if ($tax_data = $this->ww->_extras_get($where)){
          if (isset($tax_data->data['override_default'])){
            $widgets = $this->_find_taxonomy_widgets($tax_data);
          }
        }
      }
    }
    
    return $widgets;
  }
  
  /*
   * Find the widgets for a taxonomy page.  Terms can use the parent taxonomy's
   *  widget configuration as their default. 
   *
   * @param (mixed) - array if widgets previously found, null if not
   *
   * @return (mixed) - array if widgets found, null if not
   */
  function _find_taxonomy_widgets($tax_data){
    if (isset($tax_data->data['preset_id']) && $tax_data->data['preset_id'] != 0){
      $preset = $this->ww->presets->get_preset($tax_data->data['preset_id']);
      $this->ww->presets->current_preset_id = $preset->id;
      $widgets = $preset->widgets;
    }
    else {
      $widgets = $tax_data->widgets;
    }
    return $widgets;
  }
}
