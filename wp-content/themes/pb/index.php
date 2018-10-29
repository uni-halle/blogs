<?php get_header(); ?>

<?php
foreach ($GLOBALS['filterParameter'] as $key => $name ) {
	if (empty($_GET[$name])) {
		continue;
	}
	$value = explode(',', $_GET[$name]);
  	$filterQuery[] = array (
      	'key'		=> $key,
      	'value'		=> $value,
      	'compare'	=> 'IN',
    	);
  	}
$args = array (
	'posts_per_page' => -1,
	'meta_key' => 'zeitraum_0_begin',
	'orderby' => 'zeitraum_0_begin',
	'meta_query' => $filterQuery
	);
$posts = query_posts($args);
$countPosts = count($posts);
?>

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Projekte
      <small><?php echo $countPosts; ?> Projekte</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php bloginfo('url') ?>">Projektdatenbank</a></li>
      <li class="active">Projekte</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-sm-12">
      	<div class="box">
          <form role="form" method="get" action="/">
          	<div class="box-header with-border">
             		<h3 class="box-title">Filter</h3>
                  <div class="box-tools pull-right">
                    	<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                  </div>
              </div>
			  <div class="box-body">

				<div class="row">

				<div class="col-sm-4">
                <!-- Select multiple-->
                <div class="form-group">
                  <label>Kategorie</label>
                  <select class="form-control" name="kategorie">
                    <option value="">Alle Kategorien</option>
					<?php
					$thisFilter = 'kategorie';
					$field = get_field_object($thisFilter);
					foreach ($field['choices'] as $key => $value) {
						$option = '<option value="' . $key . '"';
						if (isset($_GET[$thisFilter])) {
							if ($_GET[$thisFilter] == $key) {
								$option .= ' selected';
							}
						}
						$option .= '>' . $value . '</option>';
						echo $option;
					}
					?>
                  </select>
                </div>
				</div>

				<div class="col-sm-4">
                <!-- Select multiple-->
        				<div class="form-group">
                  <label>Facharbeitsgruppe</label>
                  <select class="form-control" name="facharbeitsgruppe">
                    <option value="">Alle Facharbeitsgruppen</option>
					<?php
					$thisFilter = 'facharbeitsgruppe';
					$field = get_field_object($thisFilter);
					foreach ($field['sub_fields'][0]['choices'] as $key => $value) {
						$option = '<option value="' . $key . '"';
						if (isset($_GET[$thisFilter])) {
							if ($_GET[$thisFilter] == $key) {
								$option .= ' selected';
							}
						}
						$option .= '>' . $value . '</option>';
						echo $option;
					}
					?>
					</select>
                </div>
				</div>

				<div class="col-sm-4">
				<!-- Select multiple-->
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status">
                    <option value="">Jeder Status</option>
					<?php
					$thisFilter = 'status';
					$field = get_field_object($thisFilter);
					foreach ($field['choices'] as $key => $value) {
						$option = '<option value="' . $key . '"';
						if (isset($_GET[$thisFilter])) {
							if ($_GET[$thisFilter] == $key) {
								$option .= ' selected';
							}
						}
						$option .= '>' . $value . '</option>';
						echo $option;
					}
					?>
                  </select>
                </div>
				</div>

				<div class="col-sm-4">
				</div>

				<div class="col-sm-4">
				</div>

				<div class="col-sm-4">
                <!-- checkbox -->
                <div class="form-group">
                  <div class="checkbox">
                    <label>
						<?php
						$thisFilter = 'veroffentlichung';
						$input = '<input type="checkbox" name="veroffentlichung" value="1"';
						if (isset($_GET[$thisFilter])) {
							if ($_GET[$thisFilter] == '1') {
								$input .= ' checked';
							}
						}
						$input .= '>';
						echo $input;
						?>
                    	Öffentliche Darstellung
                    </label>
                  </div>
                </div>
				</div>

				</div>
				<!-- /.row -->

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-default">Filtern</button>
                  </div>
                </div>

                <!-- /.box-footer-->
              </form>
            </div>
            <!-- /.box -->

      </div><!-- /.col -->
	</div><!-- /.row -->

    <div class="row">
    	<div class="col-sm-12">

		<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			$postList[] = $post->ID;
    	$fields = get_fields();
		?>

		<?php
		include('box-projekt.php');
		?>

		<?php endwhile; ?>
		<?php else : ?>
		<?php endif; ?>
		<?php wp_reset_query(); ?>

        <!-- Button -->
        <div>
          <a href="<?php echo '/export?list=' . implode(',', $postList); ?>" target="export" class="btn btn-default<?php if($countPosts == 0) { echo ' disabled'; } ?>" role="button">Projekte exportieren</a>
          <a href="<?php echo '/mail?list=' . implode(',', $postList); ?>" target="export" class="btn btn-default<?php if($countPosts == 0) { echo ' disabled'; } ?>" role="button" style="margin-left: 10px;">Kontakte exportieren</a>
        </div>

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->

<?php get_footer(); ?>

<script>
	$(function () {
		/* Navigationsmenü */
    	$("ul.sidebar-menu li#projekte").addClass("active");
	});
</script>
