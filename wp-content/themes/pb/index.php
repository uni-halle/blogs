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
	'meta_query' => $filterQuery
	);
$posts = get_posts($args);
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
		foreach ($posts as $post):
			setup_postdata ($post);
			$postList[] = $post->ID;
      $fields = get_fields();
		?>

		  <!-- Custom Tabs -->
		            <div class="nav-tabs-custom">
		              <ul class="nav nav-tabs pull-right">
		                <li class="active"><a href="#tab-<?php the_ID(); ?>-1" data-toggle="tab">Projekt</a></li>
		                <li><a href="#tab-<?php the_ID(); ?>-2" data-toggle="tab">Kontakt</a></li>
		                <li class="pull-left header">
                    <?php
                    if ($fields ['veroffentlichung'] && $fields ['zustimmung']) {
                      echo '<i class="ion ion-earth"></i>';
                    }
                    ?>
                    <?php the_title(); ?>
                    </li>
		              </ul>
		              <div class="tab-content">
		                <div class="tab-pane active" id="tab-<?php the_ID(); ?>-1">
					<div class="row">
					<div class="col-sm-4">
                      <dl>
                        <dt>Kategorie</dt>
                        <dd><?php echo $fields['kategorie']; ?></dd>
						<dt>Kurzbeschreibung</dt>
						<dd><?php echo $fields['kurzbeschreibung']; ?></dd>
						<?php
						if ($fields['aufzeichnungsanzahl']){
							echo '<dt>Aufzeichnungsanzahl</dt>';
							echo '<dd>' . $fields['aufzeichnungsanzahl'] . '</dd>';
						}
            if ($fields['klausurteilnehmer']){
							echo '<dt>Klausurteilnehmer</dt>';
							echo '<dd>' . $fields['klausurteilnehmer'] . '</dd>';
						}
						?>
              <?php
							if ($fields['label']){
								echo '<dt>Label</dt>';
								echo '<dd>' . implode ('<br>', $fields['label']) . '</dd>';
							}
                        ?>
                        <?php
							echo '<dt>Status</dt>';
							echo '<dd>';
							switch ($fields['status']) {
								case 1:
							        echo 'In Planung';
							        break;
								case 2:
								    echo 'In Bearbeitung';
								    break;
								case 3:
									echo 'Pausiert';
									break;
								case 4:
									echo 'Abgeschlossen';
									break;
								case 5:
									echo 'Abgebrochen';
									break;
							}
							echo '</dd>';
                        ?>
                        <?php
							echo '<dt>Zeitraum</dt>';
							echo '<dd>';
							echo ($fields['zeitraum'][0]['ende'] ? '' : 'seit ');
							echo date("d.m.Y", strtotime($fields['zeitraum'][0]['begin']));
							echo ($fields['zeitraum'][0]['ende'] ? ' &ndash; ' . date("d.m.Y", strtotime($fields['zeitraum'][0]['ende'])) : '');
							echo '</dd>';
                        ?>
                        <?php
							if ($fields['arbeitsbereich'][0]['arbeitsbereich']){
								echo '<dt>Arbeitsbereich</dt>';
								echo '<dd>';
								foreach ($fields['arbeitsbereich'] as $key => $value ){
									echo $fields['arbeitsbereich'][$key]['arbeitsbereich'] . (++$key < count($fields['arbeitsbereich']) ? '<br>' : '');
								}
								echo '</dd>';
							}
						?>
                        <?php
							if ($fields['facharbeitsgruppe'][0]['arbeitsgruppe']){
								echo '<dt>Facharbeitsgruppe</dt>';
								echo '<dd>';
								foreach ($fields['facharbeitsgruppe'] as $key => $value ){
									echo $fields['facharbeitsgruppe'][$key]['arbeitsgruppe'] . (++$key < count($fields['facharbeitsgruppe']) ? '<br>' : '');
								}
								echo '</dd>';
							}
						?>
                        <?php
							if ($fields['themenarbeitsgruppe'][0]['arbeitsgruppe']){
								echo '<dt>Themenarbeitsgruppe</dt>';
								echo '<dd>';
								foreach ($fields['themenarbeitsgruppe'] as $key => $value ){
									echo $fields['themenarbeitsgruppe'][$key]['arbeitsgruppe'] . (++$key < count($fields['themenarbeitsgruppe']) ? '<br>' : '');
								}
								echo '</dd>';
							}
						?>
                        <?php
							echo '<dt>Betreuer</dt>';
							echo '<dd>';
							foreach ($fields['betreuer'] as $key => $value ){
								echo $fields['betreuer'][$key]['mitarbeiter'] . (++$key < count($fields['betreuer']) ? '<br>' : '');
							}
							echo '</dd>';
						?>
                      </dl>
				  </div>
			  	  </div>
				  <!-- /.row -->
		                </div>
		                <!-- /.tab-pane -->
		                <div class="tab-pane" id="tab-<?php the_ID(); ?>-2">
						<div class="row">
						<div class="col-sm-4">
		                <dl>
                          <dt>Fakultät</dt>
                          <dd><?php echo $fields['fakultat']; ?></dd>
                        <?php
							if ($fields['einrichtung']){
								echo '<dt>Einrichtung</dt>';
								echo '<dd>';
								foreach ($fields['einrichtung'] as $key => $value ){
									echo $fields['einrichtung'][$key]['bezeichnung'] . (++$key < count($fields['einrichtung']) ? '<br>' : '');
								}
								echo '</dd>';
							}
						?>
						<?php
							if ($fields['ansprechpartner']){
								echo '<dt>Ansprechpartner</dt>';
								echo '<dd>';
								foreach ($fields['ansprechpartner'] as $key => $value ){
									echo $fields['ansprechpartner'][$key]['anrede'] . ' ';
									echo ($fields['ansprechpartner'][$key]['titel'] ? $fields['ansprechpartner'][$key]['titel'] . ' ' : '');
									echo $fields['ansprechpartner'][$key]['vorname'] . ' ';
									echo $fields['ansprechpartner'][$key]['nachname'] . '<br>';
									echo '<a href="mailto:' . $fields['ansprechpartner'][$key]['e-mail'] . '">&rarr;&nbsp;' . $fields['ansprechpartner'][$key]['e-mail'] . '</a>';
									echo ($fields['ansprechpartner'][$key]['webseite'] ? '<br>' . '<a href="' . $fields['ansprechpartner'][$key]['webseite'] . '" target="extern"> &rarr;&nbsp;' . $fields['ansprechpartner'][$key]['webseite'] . '</a>' : '') . (++$key < count($fields['ansprechpartner']) ? '<br>' : '');
								}
								echo '</dd>';
							}
                        ?>
					</dl>
					</div>
					</div>
					<!-- /.row -->
		                </div>
		                <!-- /.tab-pane -->
		              </div>
		              <!-- /.tab-content -->
                  <div class="progress progress-xxs" style="margin: 0;">
                    <div class="progress-bar <?php echo ($fields['fortschritt'] == 100 ? 'progress-bar-success' : 'progress-bar-warning'); ?> progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $fields['fortschritt'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $fields['fortschritt'] ?>%">
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="pull-right">
                      <?php
                      if ($fields['verweis']) {
                        echo '<a href="' . $fields['verweis'] . '" target="backend" class="btn btn-default" role="button" style="margin-right: 10px;">Projekt ansehen</a>';
                      }
                      ?>
                      <a href="/wp-admin/post.php?post=<?php the_ID(); ?>&action=edit" target="backend" class="btn btn-default" role="button">Projekt bearbeiten</a>
                    </div>
                  </div>
		            </div>

        <?php
          endforeach;
          wp_reset_postdata();
        ?>

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
