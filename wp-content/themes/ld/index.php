<?php get_header(); ?>

<?php

// Die Seite index.php wird automatisch
// beim Aufruf von /katalog geladen

function useMetaQueryKeyWildcard( $where ) {
	$where = str_replace("meta_key = 'zusatzinformationen_%", "meta_key LIKE 'zusatzinformationen_%", $where);
	$where = str_replace("meta_key = 'bibliografischeangaben_%", "meta_key LIKE 'bibliografischeangaben_%", $where);
	$where = str_replace("meta_key = 'autoren_%", "meta_key LIKE 'autoren_%", $where);
	return $where;
}
add_filter('posts_where', 'useMetaQueryKeyWildcard');

if ($_GET['kategorie']) {
	$filterQuery[] = array (
		'key'	=> 'kategorie',
		'value' => $_GET['kategorie'],
		'compare'	=> '='
	);
}
if ($_GET['s']) {
	$filterQuery[] = array (
		'relation' => 'OR',
		array (
			'key'	=> 'artikelnummer',
			'value' => (isValidArticleNumber($_GET['s']) ? str_replace('#', '', $_GET['s']) : ''),
			'compare'	=> '='
		),
		array (
			'key'	=> 'autoren_%_name',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		),
		array (
			'key'	=> 'titel',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		),
		array (
			'key'	=> 'untertitel',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		),
		array (
			'key'	=> 'erscheinungsjahr',
			'value' => $_GET['s'],
			'compare'	=> '='
		),
		array (
			'key'	=> 'bibliografischeangaben_%_wert',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		),
		array (
			'key'	=> 'bezeichnung',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		),
		array (
			'key'	=> 'zusatzinformationen_%_wert',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		),
		array (
			'key'	=> 'ort',
			'value' => $_GET['s'],
			'compare'	=> 'LIKE'
		)
	);
}
if ($_GET['ausleihe']) {
  switch ($_GET['ausleihe']){
		case ('ja'):
			$filterQuery[] = array (
				'relation' => 'AND',
				array (
					'key'	=> 'status',
					'value' => '1',
					'compare'	=> '='
				),
				array (
					'relation' => 'OR',
					array (
						'key'	=> 'entleiher',
						'value' => '',
						'compare'	=> 'NOT EXISTS'
					),
					array (
						'key'	=> 'entleiher',
						'value' => '',
						'compare'	=> '='
					)
				)
			);
			break;
		case ('nein'):
			$filterQuery[] = array (
				'relation' => 'AND',
				array (
					'key'	=> 'status',
					'value' => '1',
					'compare'	=> '='
				),
				array (
					'key'	=> 'entleiher',
					'value' => array(''),
					'compare'	=> 'NOT IN'
				)
			);
			break;
  }
}

$args = array (
	'posts_per_page' => -1,
	'meta_query' => $filterQuery
	);

$posts = query_posts($args);
$countPosts = count($posts);
?>

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Katalog
      <small><?php echo $countPosts; ?> Artikel</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-sm-12">
      	<div class="box">
          <form role="form" method="get" action="/katalog">
          	<div class="box-header with-border">
             		<h3 class="box-title">Filter</h3>
                  <div class="box-tools pull-right">
                    	<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                      <i class="fa fa-minus"></i></button>
                  </div>
              </div>
			  <div class="box-body">

				<div class="row">

				<div class="col-sm-3">
                <!-- Select multiple-->
                <div class="form-group">
                  <label>Kategorie</label>
                  <select class="form-control" name="kategorie">
                    <option value="">Alle Kategorien</option>
					<?php
					$thisFilter = 'kategorie';
					$thisField = 'field_58986d3f50e66';
					$field = get_field_object($thisField);
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

				<div class="col-sm-6">
					<div class="form-group">
            <label>Suchbegriff</label>
            <input type="text" class="form-control" name="s" value="<?php echo ($_GET['s'] ? $_GET['s'] : ''); ?>">
          </div>
				</div>

				<div class="col-sm-3">
					<!-- Select multiple-->
					<div class="form-group">
						<label>Verfügbarkeit</label>
						<select class="form-control" name="ausleihe">
							<option value="">Alle Artikel</option>
							<option value="ja" <?php echo (($_GET['ausleihe'] == 'ja') ? ' selected' : ''); ?> >Verfügbare Artikel</option>
							<option value="nein" <?php echo (($_GET['ausleihe'] == 'nein') ? ' selected' : ''); ?> >Ausgeliehene Artikel</option>
					  </select>
				  </div>
				</div>

				</div>
				<!-- /.row -->

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-default btn-flat">Filtern</button>
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
		include('box-artikel.php');
		?>

		<?php endwhile; ?>
		<?php else : ?>
		<?php endif; ?>
		<?php wp_reset_query(); ?>

		<!-- Button -->
		<div>
			<a href="<?php echo '/export?list=' . implode(',', $postList); ?>" target="export" class="btn btn-default<?php if($countPosts == 0) { echo ' disabled'; } ?>" role="button">Artikel exportieren</a>
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
    	$("ul.sidebar-menu li#katalog").addClass("active");
	});
</script>
