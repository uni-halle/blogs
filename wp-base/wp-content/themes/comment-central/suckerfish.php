<div id="suckerfishnav">

  <?php wp_list_pages('title_li='); ?>

  <li><a href="#">Archives</a>

    <ul>

      <?php wp_get_archives(); ?>

    </ul>

  </li>

  <li><a href="#">Categories</a>

    <ul>

      <?php wp_list_categories('title_li='); ?>

    </ul>

  </li>

</div>
