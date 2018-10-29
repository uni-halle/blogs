        <?php
        if (is_active_sidebar('primary-widget-area')) :
            dynamic_sidebar('primary-widget-area'); 
        endif;
          // A second sidebar for widgets, just because.
        if (is_active_sidebar('secondary-widget-area')) :
            dynamic_sidebar('secondary-widget-area'); 
        endif;
   