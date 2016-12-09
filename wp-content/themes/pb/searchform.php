<form action="<?php echo esc_url(home_url()); ?>" method="get" class="sidebar-form">
  <div class="input-group">
    <input type="text" value="<?php echo get_search_query() ?>" name="s" class="form-control" placeholder="Suche">
        <span class="input-group-btn">
          <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
  </div>
</form>
