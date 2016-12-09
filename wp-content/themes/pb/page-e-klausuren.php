<?php
	global $wpdb;

	/* E-Klausuren insgesamt */
	/* WIR HOLEN ALLE KLAUSUREN + DATEN AUS DER DATENBANK, GEHEN IHRE DAUER DURCH UND ADDIEREN DIE ANZAHL JEDES SEMESTER DAZU ***********/
	$result = $wpdb->get_results(
	"
		SELECT c.meta_value
		AS typ, a.meta_value
		AS start, d.meta_value
		AS ende, b.meta_value
		AS anzahl
		FROM $wpdb->postmeta a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d, $wpdb->posts e
		WHERE a.post_id = b.post_id
		AND a.post_id = c.post_id
		AND a.post_id = d.post_id
		AND a.meta_key = 'zeitraum_0_begin'
		AND b.meta_key = 'klausurteilnehmer'
		AND c.meta_key = 'kategorie'
		AND c.meta_value = 'E-Klausur'
		AND b.meta_value > 1
		AND d.meta_key = 'zeitraum_0_ende'
		AND a.post_id = e.id
		AND e.post_status = 'publish'
		ORDER BY a.meta_value
	"
	);
	$return = array();
	foreach ($result as $test) {
		$startSemester = convertDateToSemester($test->start);
		$endSemester = convertDateToSemester($test->ende);
		$duration = ($endSemester['y'] - $startSemester['y']) * 2;
		if ($startSemester['s'] == $endSemester['s']) {
			$duration++;
		} elseif ($startSemester['s'] == 's' && $endSemester['s'] == 'w') {
			$duration += 2;
		}
		$year = $startSemester['y'];
		for ($i = 1; $i <= $duration; $i++) {
			if ($i > 1) {
				if ($i % 2 == 0) {
					if ($startSemester['s'] == 's') {
						$currSemester = 'w';
					} elseif ($startSemester['s'] == 'w') {
						$currSemester = 's';
					}
				} else {
					$currSemester = $startSemester['s'];
				}
				if ($currSemester == 's') {
					$year++;
				}
				$currSemester .= $year;
			} else {
				$currSemester = $startSemester['d'];
			}
			$return[$currSemester] += $test->anzahl;
		}
	}
	/* DIESER BLOCK MACHT DIE STATISTIK KUMULATIV- EINFACH RAUSLÖSCHEN FALLS NICHT BENÖTIGT! ********************************************/
	$j = 1;
	foreach ($return as $semester => $count) {
		if ($j > 1) {
			$return[$semester] += $prevSemesterCount;
		}
		$prevSemesterCount = $return[$semester];
		$j++;
	}
	/************************************************************************************************************************************/
	$return = makeSemesterKeysReadable($return);
	/************************************************************************************************************************************/
	$klausurenInsgesamt = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));
?>

<?php get_header(); ?>

  <!-- Content Header (Page header) -->

  <section class="content-header">
    <h1>
      Statistiken
      <small>E-Klausuren</small>
    </h1>
    <ol class="breadcrumb">
		<li><a href="<?php bloginfo('url') ?>">Projektdatenbank</a></li>
    	<li><a href="/statistiken/zusammenfassung">Statistiken</a></li>
    	<li class="active">E-Klausuren</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">

				<!-- E-Klausuren insgesamt -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">E-Klausuren insgesamt</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="chart">
							<canvas id="klausurenInsgesamt"></canvas>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

      </div>
    </div>
    <!-- /.row -->

  </section>
  <!-- /.content -->

  <?php get_footer(); ?>

<script>
$(function () {
	/* Navigationsmenü */
  	$("ul.sidebar-menu li#statistiken").addClass("active");
  	$("ul.sidebar-menu li#klausuren").addClass("active");
	
	/* Diagramme */
	var chartData;
	var defaultChartOptions = {responsive: true};

	/* E-Klausuren insgesamt */
	$(function () {
		chartData = JSON.parse('<?php echo $klausurenInsgesamt; ?>');
		var ctx = $("#klausurenInsgesamt");
		var LineChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "E-Klausuren",
					data: chartData['values'],
				    fill: false,
				    lineTension: 0.1,
				    backgroundColor: "rgba(0,138,163,0.4)",
				    borderColor: "rgba(0,138,163,1)",
				    borderCapStyle: 'butt',
				    borderDash: [],
				    borderDashOffset: 0.0,
				    borderJoinStyle: 'miter',
				    pointBorderColor: "rgba(0,138,163,1)",
				    pointBackgroundColor: "#fff",
				    pointBorderWidth: 3,
				    pointHoverRadius: 5,
				    pointHoverBackgroundColor: "rgba(255,255,255,1)",
				    pointHoverBorderColor: "rgba(0,138,163,1)",
				    pointHoverBorderWidth: 3,
				    pointRadius: 0,
					pointHitRadius: 5
				}]
			},
			options: defaultChartOptions
		});
	});

});
</script>
