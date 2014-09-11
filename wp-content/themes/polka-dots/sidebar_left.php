	<div id="sidebar_left" class="sidecol">
	<ul>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>

  <li>
    <h2>
      <?php _e('Categories'); ?>
    </h2>
    <ul>
      <?php wp_list_cats('optioncount=1&hierarchical=1');    ?>
    </ul>
  </li>

<?php endif; ?>

</ul>
</div>
