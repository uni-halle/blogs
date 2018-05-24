<?php

/*
*   File: Bsb4Utilities.php
*   Project: Fallportal Child Theme 
*
*   Copyright(c) 2018 codemacher UG (haftungsbeschränkt) All Rights Reserved.
*
*   Created on : 30.04.2018, 13:27:26
*/
namespace BootstrapBasic4;

if (!class_exists('\\BootstrapBasic4\\Bsb4Utilities')) {

  class Bsb4Utilities {
    protected static $wissen_cats = array(5,7,8,9);
    
    protected static $faelle_cats = array(2,6,10,3,4);
    public static function faelle_count() {
      $args = array(
          'include' => \BootstrapBasic4\Bsb4Utilities::$faelle_cats
      );
      $categories = get_categories($args);
      
      $total = 0;
      foreach ($categories as $category) {
        $total += $category->count;
        
      }
      return $total;
    }
  }
};