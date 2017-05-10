<?php get_header(); ?>

<?php
global $wpdb;
$kategorie = get_field_object('field_58986d3f50e66');
foreach ($kategorie['choices'] as $key => $value) {
  $result = $wpdb->get_results(
  "
    SELECT COUNT(a.id) AS insgesamt
    FROM $wpdb->posts a, $wpdb->postmeta b
    WHERE a.id = b.post_id
    AND a.post_type = 'post'
    AND a.post_status = 'publish'
    AND b.meta_key = 'kategorie'
    AND b.meta_value = '" . $key . "'
  "
  );
  $statistics['kategorie'][$key]['insgesamt'] = $result[0]->insgesamt;
  $result = $wpdb->get_results(
  "
    SELECT COUNT(a.id) AS blockiert
    FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c
    WHERE a.id = b.post_id
    AND a.id = c.post_id
    AND a.post_type = 'post'
    AND a.post_status = 'publish'
    AND b.meta_key = 'kategorie'
    AND b.meta_value = '" . $key . "'
    AND c.meta_key = 'status'
    AND c.meta_value != '1'
  "
  );
  $statistics['kategorie'][$key]['blockiert'] = $result[0]->blockiert;
  $result = $wpdb->get_results(
  "
    SELECT COUNT(a.id) AS ausgeliehen
    FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c
    WHERE a.id = b.post_id
    AND a.id = c.post_id
    AND a.post_type = 'post'
    AND a.post_status = 'publish'
    AND b.meta_key = 'kategorie'
    AND b.meta_value = '" . $key . "'
    AND c.meta_key = 'entleiher'
    AND c.meta_value != ''
  "
  );
  $statistics['kategorie'][$key]['ausgeliehen'] = $result[0]->ausgeliehen;
  $statistics['kategorie'][$key]['verfügbar']['absolut'] = $statistics['kategorie'][$key]['insgesamt'] - $statistics['kategorie'][$key]['blockiert'] - $statistics['kategorie'][$key]['ausgeliehen'];
  $statistics['kategorie'][$key]['verfügbar']['prozentual'] = round((100 / $statistics['kategorie'][$key]['insgesamt'] ) * $statistics['kategorie'][$key]['verfügbar']['absolut']);
}
$user = wp_get_current_user();
$result = $wpdb->get_results(
"
  SELECT COUNT(a.id) AS meine
  FROM $wpdb->posts a, $wpdb->postmeta b
  WHERE a.id = b.post_id
  AND a.post_type = 'post'
  AND a.post_status = 'publish'
  AND b.meta_key = 'entleiher'
  AND b.meta_value = '" . $user->user_login . "'
"
);
$statistics['meine'] = $result[0]->meine;
?>


  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Schnellstart
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">

		<div class="row">

      <div class="col-md-4 col-xs-12">
        <div class="info-box bg-light-blue">
                  <a href="/katalog?kategorie=Literatur" style="color: #ffffff;"><span class="info-box-icon"><i class="ion-ios-book-outline"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Literatur</span>
                    <span class="info-box-number"><?php echo $statistics['kategorie']['Literatur']['insgesamt'] ?></span>

                    <div class="progress">
                      <div class="progress-bar" style="width: <?php echo $statistics['kategorie']['Literatur']['verfügbar']['prozentual']; ?>%"></div>
                    </div>
                        <span class="progress-description">
                          <?php echo $statistics['kategorie']['Literatur']['verfügbar']['absolut']; ?> verfügbar
                        </span>
                  </div>
                </a>
                  <!-- /.info-box-content -->
                </div>
              </div>

              <div class="col-md-4 col-xs-12">
                <div class="info-box bg-light-blue">
                          <a href="/katalog?kategorie=Technik" style="color: #ffffff;"><span class="info-box-icon"><i class="ion-ios-game-controller-b-outline"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Technik</span>
                            <span class="info-box-number"><?php echo $statistics['kategorie']['Technik']['insgesamt']; ?></span>

                            <div class="progress">
                              <div class="progress-bar" style="width: <?php echo $statistics['kategorie']['Technik']['verfügbar']['prozentual']; ?>%"></div>
                            </div>
                                <span class="progress-description">
                                  <?php echo $statistics['kategorie']['Technik']['verfügbar']['absolut']; ?> verfügbar
                                </span>
                          </div>
                          </a>
                          <!-- /.info-box-content -->
                        </div>
                      </div>

                      <div class="col-md-4 col-xs-12">
                        <div class="info-box bg-light-blue">
                                  <a href="/katalog?kategorie=Sonstiges" style="color: #ffffff;"><span class="info-box-icon"><i class="ion-ios-americanfootball-outline"></i></span>

                                  <div class="info-box-content">
                                    <span class="info-box-text">Sonstiges</span>
                                    <span class="info-box-number"><?php echo $statistics['kategorie']['Sonstiges']['insgesamt']; ?></span>

                                    <div class="progress">
                                      <div class="progress-bar" style="width: <?php echo $statistics['kategorie']['Sonstiges']['verfügbar']['prozentual']; ?>%"></div>
                                    </div>
                                        <span class="progress-description">
                                          <?php echo $statistics['kategorie']['Sonstiges']['verfügbar']['absolut']; ?> verfügbar
                                        </span>
                                  </div>
                                  </a>
                                  <!-- /.info-box-content -->
                                </div>
                              </div>

      </div>
      <div class="row">

        <div class="col-md-4 col-xs-12">
  			          <div class="info-box">
  			            <a href="/meine"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-person-outline"></i></span>
  				            <div class="info-box-content">
  				              <span class="info-box-text text-light-blue">Von mir ausgeliehen</span>
  				              <span class="info-box-number text-light-blue" style="font-size: 350%; line-height: 60px; font-weight: 100;"><?php echo $statistics['meine']; ?></span>
  				            </div>
                    </a>
  			            <!-- /.info-box-content -->
  			          </div>
  			          <!-- /.info-box -->
  			        </div>

        <div class="col-md-4 col-xs-12">
                  <div class="info-box">
                    <form role="form" method="get" action="/katalog">
                    <a href="/katalog"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-pricetag-outline"></i></span></a>
                      <div class="info-box-content">
                        <span class="info-box-text text-light-blue">Artikelsuche</span>
                        <div class="input-group">
                          <input type="text" name="s" placeholder="Suche" class="form-control" style="padding: 0; border:0; border-bottom: 1px solid #3c8dbc; height: auto; font-size:30px; font-weight: 100;">
                        </div>
                      </div>
                    <!-- /.info-box-content -->
                  </form>
                  </div>
                  <!-- /.info-box -->
                </div>

              </div>
              <div class="row">

							<div class="col-md-4 col-xs-12">
												<div class="info-box">
                          <form role="form" method="get" action="/checkout">
													<a href="/checkout"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-paperplane-outline"></i></span></a>
														<div class="info-box-content">
															<span class="info-box-text text-light-blue">Ausleihe</span>
															<div class="input-group">
								                <input type="text" name="i" placeholder="Nummer" class="form-control" style="padding: 0; border:0; border-bottom: 1px solid #3c8dbc; height: auto; font-size:30px; font-weight: 100;">
                                <input type="hidden" name="c" value="1">
                              </div>
														</div>
													<!-- /.info-box-content -->
                        </form>
												</div>
												<!-- /.info-box -->
											</div>
											<div class="col-md-4 col-xs-12">
																<div class="info-box">
                                  <form role="form" method="get" action="/checkin">
																	<a href="/checkin"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-box-outline"></i></span></a>
																		<div class="info-box-content">
																			<span class="info-box-text text-light-blue">Rücknahme</span>
																			<div class="input-group">
																				<input type="text" name="i" placeholder="Nummer" class="form-control" style="padding: 0; border: 0; border-bottom: 1px solid #3c8dbc; height: auto; font-size:30px; font-weight: 100;">
                                        <input type="hidden" name="c" value="1">
                                      </div>
																		</div>
																	<!-- /.info-box-content -->
                                </form>
																</div>
																<!-- /.info-box -->
															</div>

                            </div>
  </section>
  <!-- /.content -->

<?php get_footer(); ?>

<script>
	$(function () {
		/* Navigationsmenü */
    	$("ul.sidebar-menu li#schnellstart").addClass("active");
	});
</script>
