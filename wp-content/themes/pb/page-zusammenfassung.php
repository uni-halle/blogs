<?php
	global $wpdb;

	/* Projekte insgesamt */
	/* FESTLEGEN DER DATUMSGRENZEN DER JEWEILIGEN SEMESTER (W = WINTERSEMESTER | S = SOMMERSEMESTER) ****************************************/
	define('W_START', '1001');
	define('W_END', '0331');
	define('S_START', '0401');
	define('S_END', '0930');
	/****************************************************************************************************************************************/
	/* ALS ERSTES SUCHEN WIR DAS ERSTE BEGONNENE PROJEKTE UND DAS LETZTE BEGONNENE PROJEKT UND RECHNEN ANHAND DERER DEN ZEITRAUM AUS ********/
	$firstProject = $wpdb->get_results(
	"
		SELECT $wpdb->postmeta.meta_value
		AS datum 
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND meta_key = 'zeitraum_0_begin'
		ORDER BY meta_value
		LIMIT 1
	"
	);
	$lastProject = $wpdb->get_results(
	"
		SELECT $wpdb->postmeta.meta_value
		AS datum
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND meta_key = 'zeitraum_0_begin'
		ORDER BY meta_value
		DESC
		LIMIT 1
	"
	);
	$firstSemester = convertDateToSemester($firstProject[0]->datum);
	$lastSemester = convertDateToSemester($lastProject[0]->datum);
	$duration = ($lastSemester['y'] - $firstSemester['y']) * 2;
	if ($firstSemester['s'] == $lastSemester['s']) {
		$duration++;
	} elseif ($firstSemester['s'] == 's' && $lastSemester['s'] == 'w') {
		$duration += 2;
	}
	/****************************************************************************************************************************************/
	$return = array();
	/* WIR GEHEN DEN ZEITRAUM DURCH UND SUCHEN FÜR JEDES SEMESTER DIE ANZAHL DER PROJEKTE RAUS **********************************************/
	$count = 0;
	for ($i = 1; $i <= $duration; $i++) {
		if ($i > 1) {
			if ($i % 2 == 0) {
				if ($firstSemester['s'] == 's') {
					$currSemester = 'w';
				} elseif ($firstSemester['s'] == 'w') {
					$currSemester = 's';
				}
			} else {
				$currSemester = $firstSemester['s'];
			}
			if ($currSemester == 's') {
				$year++;
			}
		} else {
			$currSemester = $firstSemester['s'];
			$year = $firstSemester['y'];
		}
		if ($currSemester == 's') {
			$startDate = '20'.$year.S_START;
			$endDate = '20'.$year.S_END;
		} elseif ($currSemester == 'w') {
			$startDate = '20'.$year.W_START;
			$_year = $year + 1;
			$endDate = '20'.$_year.W_END;
		}
		$result = $wpdb->get_results(
		"
			SELECT COUNT(a.meta_value)
			AS anzahl
			FROM $wpdb->postmeta a, $wpdb->postmeta b, $wpdb->posts c
			WHERE a.post_id = b.post_id
			AND a.meta_key = 'zeitraum_0_begin'
			AND a.meta_value >= $startDate
			AND b.meta_key = 'zeitraum_0_begin'
			AND b.meta_value <= $endDate
			AND a.post_id = c.id
			AND c.post_status = 'publish'
		"
		);
		$count += $result[0]->anzahl;
		$return[$currSemester.$year] = $count;
	}
	/****************************************************************************************************************************************/
	/* WIR WOLLEN ALLE SCHLÜSSEL UNSERES ARRAYS JETZT FÜR DEN NUTZBAR LESBAR MACHEN ("w15" -> "WiSe 2015/16") *******************************/
	$return = makeSemesterKeysReadable($return);
	/****************************************************************************************************************************************/
	$projekteInsgesamt = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Projekte je Semester */
	$result = $wpdb->get_results(
	"
		SELECT $wpdb->postmeta.post_id
		AS id, meta_value
		AS start
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND meta_key = 'zeitraum_0_begin'
		ORDER BY meta_value
	"
	);
	$return = array();
	foreach ($result as $project) {
		/* START UND ENDE EINES PROJEKTS RAUSFINDEN *****************************************************************************************/
		/* Start können wir einfach aus der Datenbank lesen, da jedes Projekt einen Anfang an */
		$start = $project->start;
		/* Als nächstes überprüfen wir, ob das Projekt ein Ende hat, ansonsten setzen wir das aktuelle Dateum als Bezugspunkt */
		$result = $wpdb->get_results(
		"
			SELECT $wpdb->postmeta.meta_value
			AS ende
			FROM $wpdb->posts, $wpdb->postmeta
			WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
			AND $wpdb->posts.post_status = 'publish'
			AND meta_key = 'zeitraum_0_ende'
			AND post_id = $project->id
		"
		);
		if ($result) {
			$ende = $result[0]->ende;
		} else {
			$ende = date('Ymd');
		}
		/************************************************************************************************************************************/
		/* DAUER EINES PROJEKTS AUSRECHNEN **************************************************************************************************/
		$startSemester = convertDateToSemester($start);
		$endSemester = convertDateToSemester($ende);
		/* Dauer entspricht der Differenz zwischen den Jahren mal 2 (da 2 Semester pro Jahr) */
		$duration = ($endSemester['y'] - $startSemester['y']) * 2;
		if ($startSemester['s'] == $endSemester['s']) {
			/* Sollte das Projekt im selben Semestertyp (w oder s) beginnen und enden, müssen wir noch ein Semester dazuzählen */
			$duration++;
		} elseif ($startSemester['s'] == 's' && $endSemester['s'] == 'w') {
			/* Sollte ein Projekt im Sommersemester starten und im Wintersemester enden, müssen wir zwei Semester dazuzählen */
			$duration += 2;
		}
		/* BEISPIELE
		 * START | ENDE | DAUER       | Ergebnise des Scripts
		 *   W15 |  S16 | 2 Semester  | (16 - 15) * 2 = 2
		 *   S15 |  S16 | 3 Semester  | (16 - 15) * 2 = 2 + 1 = 3
		 *   W15 |  W16 | 3 Semester  | (16 - 15) * 2 = 2 + 1 = 3
		 *   S15 |  W16 | 4 Semester  | (16 - 15) * 2 = 2 + 2 = 4
		 */
		/************************************************************************************************************************************/
		/* SETZT FÜR JEDES SEMESTER IN DEM DAS PROJEKT LÄUFT ANHAND DER DAUER DEN ZÄHLER UM 1 NACH OBEN *************************************/
		$year = $startSemester['y'];
		for ($i = 1; $i <= $duration; $i++) {
			/* Wir gehen jedes Semester seit dem Start eines Projekts entsprechend der Dauer durch */
			if ($i > 1) {
				/* Hier gehen wir NUR rein, wenn wir NICHT im ersten Semester sind, da wir das nicht verändern müssen */
				if ($i % 2 == 0) {
					/* Durch diese Modulo-Rechnung können wir immer zwischen Sommer und Winter wechseln */
					if ($startSemester['s'] == 's') {
						/* Haben wir mit Sommer begonnen, sind wir jetzt bei Winter */
						$currSemester = 'w';
					} elseif ($startSemester['s'] == 'w') {
						/* Haben wir mit Winter begonnen, sind wir jetzt bei Sommer */
						$currSemester = 's';
					}
				} else {
					/* Müssen wir die Jahreszeit nicht wechseln, geben wir unserem aktuellen Semester die ursprüngliche Jahreszeit */
					$currSemester = $startSemester['s'];
				}
				if ($currSemester == 's') {
					/* Da in unserer Logik immer Sommersemester die sind, bei denen das "neue" Jahr beginnt, zählen wir im Sommer um 1 nach oben */
					$year++;
				}
				/* Als letztes ergänzen wir noch das Jahr für das aktuelle Semester */
				$currSemester .= $year;
			} else {
				/* Sind wir im ersten Durchlauf, können wir einfach das Startsemester nutzen */
				$currSemester = $startSemester['d'];
			}
			/* Wir setzen den Zähler des errechneten Semesters an dessen Stelle im Return-Array um 1 nach oben */
			$return[$currSemester]++;
		}
		/************************************************************************************************************************************/
	}
	/* WIR WOLLEN ALLE SCHLÜSSEL UNSERES ARRAYS JETZT FÜR DEN NUTZBAR LESBAR MACHEN ("w15" -> "WiSe 2015/16") *******************************/
	$return = makeSemesterKeysReadable($return);
	/****************************************************************************************************************************************/
	$projekteJeSemester = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Arbeitsgruppen insgesamt */
	/* WIR HOLEN ALLE FACH-AGs AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE FACH-AG GIBT ********/
	$result = $wpdb->get_results(
	"
		SELECT $wpdb->postmeta.meta_value
		AS name, COUNT(meta_value)
		AS anzahl
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND meta_key = 'facharbeitsgruppe_0_arbeitsgruppe'
		AND meta_value != ''
		GROUP BY meta_value
	"
	);
	$return = array();
	foreach ($result as $workgroup) {
		$return[$workgroup->name] = $workgroup->anzahl;
	}
	/************************************************************************************************************************************/
	/* WIR GEHEN NUN DIE ARBETISGRUPPEN SOLANGE DURCH, BIS WIR KEINE KOOPERATIONEN MEHR FINDEN UND ADDIEREN DIESE JEWEILS HINZU *********/
	$coop = true;
	$i = 1;
	while ($coop) {
		$result = $wpdb->get_results(
		"
			SELECT $wpdb->postmeta.meta_value
			AS name, COUNT(meta_value)
			AS anzahl
			FROM $wpdb->posts, $wpdb->postmeta
			WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
			AND $wpdb->posts.post_status = 'publish'
			AND meta_key = 'facharbeitsgruppe_' . $i . '_arbeitsgruppe'
			AND meta_value != ''
			GROUP BY meta_value
		"
		);
		if ($result) {
			foreach ($result as $workgroup) {
				$return[$workgroup->name] += $workgroup->anzahl;
				$return[$workgroup->name] = strval($return[$workgroup->name]);
			}
			$i++;
		} else {
			$coop = $false;
		}
	}
	/************************************************************************************************************************************/
	$arbeitsgruppenInsgesamt = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Arbeitsgruppen ohne Aufzeichnungen */
	/* WIR HOLEN ALLE FACH-AGs AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE FACH-AG GIBT ********/
	$result = $wpdb->get_results(
	"
		SELECT a.meta_value
		AS name, COUNT(b.meta_value)
		AS anzahl
		FROM $wpdb->postmeta a, $wpdb->postmeta b, $wpdb->posts c
		WHERE a.post_id = b.post_id
		AND a.meta_key = 'facharbeitsgruppe_0_arbeitsgruppe'
		AND a.meta_value != ''
		AND b.meta_key = 'kategorie'
		AND b.meta_value != 'Aufzeichnung/Streaming Einzelveranstaltung'
		AND b.meta_value != 'Aufzeichnung/Streaming Veranstaltungsreihe'
		AND c.post_status = 'publish'
		AND a.post_id = c.id
		GROUP BY a.meta_value
	"
	);
	$return = array();
	foreach ($result as $workgroup) {
		$return[$workgroup->name] = $workgroup->anzahl;
	}
	/************************************************************************************************************************************/
	/* WIR GEHEN NUN DIE ARBETISGRUPPEN SOLANGE DURCH, BIS WIR KEINE KOOPERATIONEN MEHR FINDEN UND ADDIEREN DIESE JEWEILS HINZU *********/
	$coop = true;
	$i = 1;
	while ($coop) {
		$result = $wpdb->get_results(
		"
			SELECT a.meta_value
			AS name, COUNT(b.meta_value)
			AS anzahl
			FROM $wpdb->postmeta a, $wpdb->postmeta b, $wpdb->posts c
			WHERE a.post_id = b.post_id
			AND a.meta_key = 'facharbeitsgruppe_' . $i . '_arbeitsgruppe'
			AND a.meta_value != ''
			AND b.meta_key = 'kategorie'
			AND b.meta_value != 'Aufzeichnung/Streaming Einzelveranstaltung'
			AND b.meta_value != 'Aufzeichnung/Streaming Veranstaltungsreihe'
			AND a.post_id = c.id
			AND c.post_status = 'publish'
			GROUP BY a.meta_value
		"
		);
		if ($result) {
			foreach ($result as $workgroup) {
				$return[$workgroup->name] += $workgroup->anzahl;
				$return[$workgroup->name] = strval($return[$workgroup->name]);
			}
			$i++;
		} else {
			$coop = $false;
		}
	}
	/************************************************************************************************************************************/
	$arbeitsgruppenOhneAufzeichnungen = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/*Projekte nach Status */
	/* WIR HOLEN UNS ALLE PROJEKTE AUS DER DATENBANK UND GRUPPIEREN SIE NACH FORTSCHRITTSSTAND (IN DER DB IN 5er-SCHRITTEN) *****************/
	$result = $wpdb->get_results(
	"
		SELECT $wpdb->postmeta.meta_value
		AS fortschritt, COUNT(meta_value)
		AS anzahl
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND meta_key = 'fortschritt'
		GROUP BY meta_value
		ORDER BY CONVERT(meta_value, UNSIGNED INTEGER)
	"
	);
	$return = array(0 => 0, '25' => 0, '50' => 0, '75' => 0, '100' => 0);
	/****************************************************************************************************************************************/
	/* DA 5er-SCHRITTE ZU FEIN FÜR DAS DIAGRAMM SIND, GEHEN WIR JETZT UNSERE GRUPPEN DURCH UND SORTIEREN SIE ETWAS GROBER IN 25er-SCHRITTE **/
	foreach ($result as $projects) {
		if ($projects->fortschritt == 100) {
			$return['100'] += $projects->anzahl;
		} elseif ($projects->fortschritt >= 75 && $projects->fortschritt <= 99) {
			$return['75'] += $projects->anzahl;
		} elseif ($projects->fortschritt >= 50 && $projects->fortschritt <= 74) {
			$return['50'] += $projects->anzahl;
		} elseif ($projects->fortschritt >= 25 && $projects->fortschritt <= 49) {
			$return['25'] += $projects->anzahl;
		} elseif ($projects->fortschritt >= 0 && $projects->fortschritt <= 24) {
			$return['0'] += $projects->anzahl;
		} else {
			/* Projekte ohne Fortschitts-Angabe zählen wir als 0% abgeschlossen */
			$return['0'] += $projects->anzahl;
		}
	}
	/****************************************************************************************************************************************/
	$projekteNachStatus = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Projekte nach Kategorien */
	/* WIR HOLEN ALLE KATEGORIEN AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE KATEGORIE GIBT ****/
	$result = $wpdb->get_results(
	"
		SELECT $wpdb->postmeta.meta_value
		AS name, COUNT(meta_value)
		AS anzahl
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND meta_key = 'kategorie'
		GROUP BY meta_value
	"
	);
	$return = array();
	foreach ($result as $category) {
		$return[$category->name] = $category->anzahl;
	}
	/************************************************************************************************************************************/
	$countProjektKategorien = count($return);
	$projekteNachKategorien = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));
?>

<?php get_header(); ?>

  <!-- Content Header (Page header) -->

  <section class="content-header">
    <h1>
      Statistiken
      <small>Zusammenfassung</small>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php bloginfo('url') ?>">Projektdatenbank</a></li>
    	<li><a href="/statistiken/zusammenfassung">Statistiken</a></li>
      <li class="active">Zusammenfassung</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">

				<!-- Projekte insgesamt -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Projekte insgesamt</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="chart">
							<canvas id="projekteInsgesamt"></canvas>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

          <!-- Projekte je Semester -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Projekte je Semester</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="projekteJeSemester"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  
		<!-- Projekte nach Arbeitsgruppen -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Projekte nach Arbeitsgruppen</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="chart">
					<canvas id="projekteNachArbeitsgruppen"></canvas>
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->

					<!-- Projekte nach Status -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Projekte nach Status</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="chart">
								<canvas id="projekteNachStatus"></canvas>
							</div>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->

					<!-- Projekte nach Kategorien -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Projekte nach Kategorien</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="chart">
								<canvas id="projekteNachKategorien"></canvas>
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
  	$("ul.sidebar-menu li#zusammenfassung").addClass("active");
	
	/* Diagramme */
	var chartData;
	var defaultChartOptions = {responsive: true};

	/* Projekte insgesamt */
	$(function () {
		chartData = JSON.parse('<?php echo $projekteInsgesamt; ?>');
		var ctx = $("#projekteInsgesamt");
		var LineChart = new Chart(ctx, {
				type: 'line',
				data: {
						labels: chartData['keys'],
						datasets: [{
								label: "Projekte",
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

	/* Projekte je Semester */
	$(function () {
		chartData = JSON.parse('<?php echo $projekteJeSemester; ?>');
		var ctx = $("#projekteJeSemester");
		var BarChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "Projekte",
					data: chartData['values'],
         		   	backgroundColor: "rgba(0,138,163,0.4)",
            		borderColor: "rgba(0,138,163,1)",
            		borderWidth: 3,
            		hoverBackgroundColor: "rgba(0,138,163,0.6)",
            		hoverBorderColor: "rgba(0,138,163,1)",
					}]
				},
			options: defaultChartOptions
		});
	});

	/* Projekte nach Arbeitsgruppen */
	$(function () {
		chartData_1 = JSON.parse('<?php echo $arbeitsgruppenInsgesamt; ?>');
		chartData_2 = JSON.parse('<?php echo $arbeitsgruppenOhneAufzeichnungen; ?>');
		var ctx = $("#projekteNachArbeitsgruppen");
		var BarChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: chartData_1['keys'],
					datasets: [{
						label: "Projekte",
						data: chartData_1['values'],
         		   		backgroundColor: "rgba(0,138,163,0.4)",
            			borderColor: "rgba(0,138,163,1)",
            			borderWidth: 3,
            			hoverBackgroundColor: "rgba(0,138,163,0.6)",
            			hoverBorderColor: "rgba(0,138,163,1)",
					},
					{
						label: "Ohne Vorlesungsaufzeichnungen",
						data: chartData_2['values'],
         		   		backgroundColor: "rgba(255,153,0,0.4)",
            			borderColor: "rgba(255,153,0,1)",
            			borderWidth: 3,
            			hoverBackgroundColor: "rgba(255,153,0,0.6)",
            			hoverBorderColor: "rgba(255,153,0,1)",
					}]
				},
				options: {
					responsive: true,
					scales: {
						yAxes: [{
		    				ticks: {
		        				suggestedMin: 0
		        			}
		    			}]
					}
				}
		});
	});

	/* Projekte nach Status */
	$(function () {
		chartData = JSON.parse('<?php echo $projekteNachStatus; ?>');
		var ctx = $("#projekteNachStatus");
		var BarChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ["<25%", ">=25%", ">=50%", ">=75%", "100%"],
				datasets: [{
					label: "Projekte",
					data: chartData['values'],
         		   	backgroundColor: "rgba(0,138,163,0.4)",
            		borderColor: "rgba(0,138,163,1)",
            		borderWidth: 3,
            		hoverBackgroundColor: "rgba(0,138,163,0.6)",
            		hoverBorderColor: "rgba(0,138,163,1)",
					}]
				},
			options: defaultChartOptions
		});
	});

	/* Projekte nach Kategorien */
	var fillColor = randomColor({
 		count: <?php echo $countProjektKategorien; ?>,
	});
	$(function () {
		chartData = JSON.parse('<?php echo $projekteNachKategorien; ?>');
		var ctx = $("#projekteNachKategorien");
		var PieChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: chartData['keys'],
				datasets: [{
					data: chartData['values'],
		            backgroundColor: fillColor
				}]
			},
			options: defaultChartOptions
		});
	});

});
</script>
