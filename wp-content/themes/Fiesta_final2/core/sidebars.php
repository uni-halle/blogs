<?php
// tokens
  $ew = '<!-- end_widget -->'; 
  $bt = '<!-- begin_title -->';  
  $et = '<!-- end_title -->'; 
  $bi = '<!-- begin_id -->'; 
  $ei = '<!-- end_id -->'; 
  $bc = '<!-- begin_class -->'; 
  $ec = '<!-- end_class -->'; 
  
  $sidebars = array(
  
   'default' => array(
      'name' => __('Primary Widget Area',THEME_NS),
      'id' => 'primary-widget-area',
      'description' => __("This is the default sidebar, visible on 2 or 3 column layouts. If no widgets are active, the default theme widgets will be displayed instead.", THEME_NS)
    ),
    
    'secondary' =>  array(
      'name' => __('Secondary Widget Area',THEME_NS),
      'id' => 'secondary-widget-area',
      'description' => __("This sidebar is active only on a 3 column setup.", THEME_NS)
    ),
    
    'top' => array(
      'name' => __('First Top Widget Area',THEME_NS),
      'id' => 'first-top-widget-area',
      'description' => __("This sidebar is displayed above the main content.", THEME_NS)
    ),
    
    'top2' => array(
      'name' => __('Second Top Widget Area',THEME_NS),
      'id' => 'second-top-widget-area',
      'description' => __("This sidebar is displayed above the main content.", THEME_NS)
    ),
    
   
    'bottom' => array(
      'name' => __('First Bottom Widget Area',THEME_NS),
      'id' => 'first-bottom-widget-area',
      'description' => __("This sidebar is displayed below the main content.", THEME_NS)
    ),
    
    'bottom2' => array(
      'name' => __('Second Bottom Widget Area',THEME_NS),
      'id' => 'second-bottom-widget-area',
      'description' => __("This sidebar is displayed below the main content.", THEME_NS)
    ),
    
   
    'footer' => array(
      'name' => __('First Footer Widget Area',THEME_NS),
      'id' => 'first-footer-widget-area',
      'description' => __("The first footer widget area. You can add a text widget for custom footer text.", THEME_NS) 
    ),
    
    'footer2' => array(
      'name' => __('Second Footer Widget Area',THEME_NS),
      'id' => 'second-footer-widget-area',
      'description' => __("The second footer widget area.", THEME_NS) 
    ),
    
    'footer3' => array(
      'name' => __('Third Footer Widget Area',THEME_NS),
      'id' => 'third-footer-widget-area',
      'description' => __("The third footer widget area.", THEME_NS) 
    ),
    
    'footer4' => array(
      'name' => __('Fourth Footer Widget Area',THEME_NS),
      'id' => 'fourth-footer-widget-area',
      'description' => __("The fourth footer widget area.", THEME_NS) 
    ),

  );
  
  $args = array(
    'before_widget' => $bi . '%1$s' . $ei . $bc . 'widget %2$s' .$ec,
    'before_title' => $bt,
    'after_title' => $et,
    'after_widget' => $ew
  );
  
if (function_exists('register_sidebar')) {

    foreach ($sidebars as $sidebar)
    {
      register_sidebar( array_merge($sidebar, $args));
    }
}
 
 function art_get_widget_param(&$widget, $startToken, $endToken){
  if (!$widget) return "";
  $stPos = strpos($widget, $startToken);
  $etPos = strpos($widget, $endToken);
  $result = "";
  if( $stPos !== false &&  $etPos !== false){
      $start = $stPos + strlen($startToken);
      $result= substr($widget, $start, $etPos - $start);
      $widget = substr($widget, 0, $start) . substr($widget, $etPos);
  }
  $widget = str_replace($startToken, '', $widget);
  $widget = str_replace($endToken, '', $widget);
  return $result;
 }
 
  function art_get_widget_id(&$widget){
    global $bi,  $ei;
    return art_get_widget_param($widget, $bi, $ei);
 }
 
function art_get_widget_class(&$widget){
    global $bc, $ec;
    return art_get_widget_param($widget, $bc, $ec);
 }
 
 function art_get_widget_title(&$widget){
    global $bt, $et;
    return art_get_widget_param($widget, $bt, $et);
 }
 
 function art_get_dynamic_sidebar_data($name){
    global $ew, $sidebars;
    if (!function_exists('dynamic_sidebar')) return false;
    ob_start();
    $success = dynamic_sidebar($sidebars[$name]['id']);
    $content = ob_get_clean();
    if ($success) {
      $data = explode($ew, $content);
      $widgets = array();
      for($i = 0; $i < count($data)-1; $i++){
        $widget = $data[$i];
        if(!str_replace(array(' ', "\n", '\r'), '', $widget)) continue;
        $widgets[] = array(
          'id' => art_get_widget_id($widget),
          'class' => art_get_widget_class($widget),
          'title' => art_get_widget_title($widget),
          'content' => $widget
        );
      }
      return $widgets;
    } 
    $sidebar = art_option('sidebars.'.$name);
    if ($sidebar) {
      $blocks = explode(',', $sidebar);
      $blocks_count = count($blocks);
      if ($blocks_count > 0) {
        $widgets = array();
        for($i = 0; $i < $blocks_count; $i++){
          $block = $blocks[$i];
          $id = 'art-'.$block . '-widget';
          $class = $id;
          $title = '';
          $content = '';
          switch($block) {
            case 'search':
                $title = __('Search', THEME_NS);
                $content = art_get_search();
            break;
            case 'archive': 
                $title = __('Archives', THEME_NS);
                ob_start();
                wp_get_archives('type=monthly&title_li=');// 2.6 not supported echo=0
                $content =  '<ul>'.ob_get_clean().'</ul>';
            break;
            case 'categories':
                $title =  __('Categories', THEME_NS);
                $content = '<ul>'.wp_list_categories('show_count=1&title_li=&echo=0').'</ul>';
            break;
            case 'blogroll':
                $title = __('Bookmarks', THEME_NS);
                $content = '<ul>'.wp_list_bookmarks('title_li=&categorize=0&echo=0').'</ul>';
            break;
            case 'vmenu':
                $id = null;
                $content = art_get_vmenu();
            break;
          }
		  if ($title || $content) {
			  $widgets[] = array(
				'id' => $id,
				'class' => $class,
				'title' => $title,
				'content' => $content
			  );
		  }
        }
        return $widgets;
      }
    }
    return false;
 }
 
 
 function art_print_widgets($widgets, $style){
    if (!is_array($widgets) || count($widgets) < 1) return false;
    for($i = 0; $i < count($widgets); $i++){
      $widget = $widgets[$i];
      $id = $widget['id'];
      if ($id) {
           $widget_style = art_get_widget_style($id, $style);
           $callback = 'art_print_'.strtolower($widget_style).'_widget';
           if (function_exists($callback)) {
              call_user_func($callback, $widget);
           } 
      } else {
          echo $widget['content'];
      }    
    }
    return true;
 }


 function art_print_post_widget($widget){
    art_post_box(
      $widget['title'],
      $widget['content'],
      $widget['id'],
      $widget['class']);
 }

 function art_print_block_widget($widget){
    echo art_get_block(
      $widget['title'],
      $widget['content'],
      $widget['id'],
      $widget['class']);
 }
 
 function art_print_simple_widget($widget){
    $title = $widget['title'];
    if ($title != ''){
      $title = art_parse_template('widget_header', array('title' => $title));
    }
    echo art_parse_template('widget', array(
        'caption' => $title,
        'id' => $widget['id'],
        'class' => $widget['class'],
        'content' => $widget['content']));
 }
 
 
 function art_dynamic_sidebar($name){
    global $sidebars;
    $key = 'sidebars_style.'.$name;
    $style = art_option($key);
    if (in_array($name, array('top', 'bottom', 'footer'))) {
        $places = array();
        $sum_count = 0;
        foreach ($sidebars as $key => $sidebar)
        {
          if (strpos($key, $name) !== false){
            $widgets = art_get_dynamic_sidebar_data($key);
            if (is_array($widgets)){
              $count = count($widgets);
              if ($count > 0){
                $sum_count += $count;
                $places[$key] = $widgets;
              }
            }
          }
        }
        if ($sum_count == 0) {
          return false;
        }
        $cells = array();
        $place_count = count($places);
        foreach ($places as $place)
        {
            ob_start();
            art_print_widgets($place, $style); 
            $content = ob_get_clean();
            $cells[] = art_parse_template('layout_cell', array(
              'count' => $place_count,
              'content' => $content,
            ));
        }
        echo art_parse_template('layout', array('cells' => implode('' , $cells)));
        return true;
    } 
    $widgets = art_get_dynamic_sidebar_data($name);
    return art_print_widgets($widgets, $style);
 }
 
function art_get_sidebar($name){
    ob_start();
    art_dynamic_sidebar($name);
    return ob_get_clean();
}