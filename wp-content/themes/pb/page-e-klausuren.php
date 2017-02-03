<?php
	global $wpdb;

	/* Statistikanzeige eingrenzen*/
	$result = $wpdb->get_results(
	"
	  SELECT MIN(b.meta_value) AS min,
	  MAX(b.meta_value) AS max
	  FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d
	  WHERE a.id = b.post_id
	  AND a.id = c.post_id
		AND a.id = d.post_id
	  AND a.post_status = 'publish'
	  AND b.meta_key = 'zeitraum_0_ende'
	  AND c.meta_key = 'status'
	  AND c.meta_value = '4'
		AND d.meta_key = 'kategorie'
	  AND d.meta_value = 'E-Klausur'
	"
	);
	$minEndDate = $result[0]->min;
	$minSqlDate = ( isset($_GET['startdatum']) ? $_GET['startdatum'] : $minEndDate );
	$maxEndDate = $result[0]->max;
	$maxSqlDate = ( isset($_GET['enddatum']) ? $_GET['enddatum'] : $maxEndDate );

    /* E-Klausuren pro Facharbeitsgruppe (OHNE Kooperationen) */
    /* WIR HOLLEN ALLE KLAUSUREN AUS DER DATENBANK UND SORTIEREN SIE GLEICH NACH GRUPPEN ****************************************************/
    $result = $wpdb->get_results(
    "
		SELECT c.meta_value
    AS name, COUNT(b.meta_value)
    AS anzahl
    FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d, $wpdb->postmeta e
    WHERE a.ID = b.post_id
    AND a.ID = c.post_id
    AND a.ID = d.post_id
    AND a.ID = e.post_id
    AND a.post_status = 'publish'
    AND b.meta_key LIKE 'klausurverlauf_%_teilnehmer'
    AND c.meta_key = 'facharbeitsgruppe_0_arbeitsgruppe'
    AND d.meta_key = 'status'
    AND d.meta_value = '4'
    AND e.meta_key = 'zeitraum_0_ende'
		AND e.meta_value >= " . $minSqlDate . "
    AND e.meta_value <= " . $maxSqlDate . "
		GROUP BY c.meta_value
    "
    );
	$return = array();

    foreach ($result as $workgroup) {
		$return[$workgroup->name] = $workgroup->anzahl;
	}
    /****************************************************************************************************************************************/
    $klausurenProArbeitsgruppe = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));


	/* E-Klausuren+Teilnehmer insgesamt und pro Semester */
	/* WIR HOLEN ALLE KLAUSUREN + DATEN AUS DER DATENBANK, GEHEN IHRE DATEN DURCH UND ADDIEREN DIE ANZAHL JEDES SEMESTER DAZU ***************/
	$result = $wpdb->get_results(
	"
		SELECT a.ID
    AS id, b.meta_value
    AS start, c.meta_value
		AS anzahl
    FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d
    WHERE a.ID = b.post_id
    AND a.ID = c.post_id
    AND a.ID = d.post_id
    AND a.post_status = 'publish'
    AND b.meta_key = 'zeitraum_0_ende'
		AND b.meta_value >= " . $minSqlDate . "
    AND b.meta_value <= " . $maxSqlDate . "
		AND c.meta_key LIKE 'klausurverlauf_%_teilnehmer'
    AND d.meta_key = 'status'
    AND d.meta_value = '4'
		ORDER BY b.meta_value
    "
	);



	$return = array();
    $teilnehmer = array();
    /****************************************************************************************************************************************/
	/* WIR WEISEN DIE KLAUSUREN DEN SEMESTERN ZU UND RECHNEN GLEICHZEITIG DIE TEILNEHMER PRO SEMESTER AUS ***********************************/
    foreach ($result as $test) {
        /* Wir überprüfen bei jedem Klausurenprojekt, wann es begonnen wurde und addieren für jeweilige Semester eins dazu */
				$startSemester = convertDateToSemester($test->start);
        $return[$startSemester['d']]++;

        /* Die Teilnehmer addieren wir dann dem entsprechenden Semester hinzu */
				if ($test) {
				    $teilnehmer[$startSemester['d']] += $test->anzahl;
				}
    }
    /****************************************************************************************************************************************/
    /* WIR WOLLEN ALLE SCHLÜSSEL UNSERES ARRAYS JETZT FÜR DEN NUTZBAR LESBAR MACHEN ("w15" -> "WiSe 2015/16") *******************************/
    $klausurenTeilnehmerProSemester = makeSemesterKeysReadable($teilnehmer);
    $klausurenTeilnehmer = makeSemesterKeysReadable(cumulateSemester($teilnehmer));
    $klausurenProSemester = makeSemesterKeysReadable($return);
    $klausurenInsgesamt = makeSemesterKeysReadable(cumulateSemester($return));
    /****************************************************************************************************************************************/
    $klausurenTeilnehmerProSemester = json_encode(array('keys' => array_keys($klausurenTeilnehmerProSemester), 'values' => array_values($klausurenTeilnehmerProSemester)));
    $klausurenTeilnehmer = json_encode(array('keys' => array_keys($klausurenTeilnehmer), 'values' => array_values($klausurenTeilnehmer)));
    $klausurenProSemester = json_encode(array('keys' => array_keys($klausurenProSemester), 'values' => array_values($klausurenProSemester)));
    $klausurenInsgesamt = json_encode(array('keys' => array_keys($klausurenInsgesamt), 'values' => array_values($klausurenInsgesamt)));
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
		<div class="col-sm-12">
			<div class="box<?php echo ( (isset($_GET['startdatum']) || isset($_GET['enddatum'])) ? '' : ' collapsed-box' ); ?>">
				<form role="form" method="get" action="">
					<div class="box-header with-border">
							<h3 class="box-title">Filter</h3>
								<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
										<i class="fa fa-<?php echo ( (isset($_GET['startdatum']) || isset($_GET['enddatum'])) ? 'minus' : 'plus' ); ?>"></i></button>
								</div>
						</div>
			<div class="box-body">

			<div class="row">

			<div class="col-sm-4">
					<!-- Select multiple-->
					<div class="form-group">
						<label>Startdatum</label>
						<select class="form-control" name="startdatum">
						<?php
						$thisFilter = 'startdatum';
						$semesterRange = listSemesterInRange($minEndDate, $maxEndDate);
						foreach ($semesterRange as $key => $value) {
							$option = '<option value="' . $value['start'] . '"';
							if (isset($_GET[$thisFilter])) {
								if ($_GET[$thisFilter] >= $value['start'] && $_GET[$thisFilter] <= $value['end']) {
									$option .= ' selected';
								}
							}
							$option .= '>' . convertDateToSemester($value['start'], 4) . '</option>';
							echo $option;
						}
						?>
					</select>
				</div>
			</div>

			<div class="col-sm-4">
				<!-- Select multiple-->
					<div class="form-group">
						<label>Enddatum</label>
						<select class="form-control" name="enddatum">
						<?php
						$thisFilter = 'enddatum';
						$semesterRange = listSemesterInRange($minEndDate, $maxEndDate);
						foreach ($semesterRange as $key => $value) {
							$option = '<option value="' . $value['end'] . '"';
							if (isset($_GET[$thisFilter])) {
								if ($_GET[$thisFilter] >= $value['start'] && $_GET[$thisFilter] <= $value['end']) {
									$option .= ' selected';
								}
							} else {
								if ($maxEndDate >= $value['start'] && $maxEndDate <= $value['end'] ) {
									$option .= ' selected';
								}
							}
							$option .= '>' . convertDateToSemester($value['end'], 4) . '</option>';
							echo $option;
						}
						?>
						</select>
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
        <div class="col-md-12">
            <!-- E-Klausuren je Semester und kumuliert -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
                        <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle E-Klausuren zuzüglich Nachklausuren. Nur als abgeschlossen gekennzeichnete E-Klausuren werden einbezogen. Jede E-Klausur wird dem Semester zugeordnet, in dem das Projekt abgeschlossenen wurde." class="fa fa-info-circle text-gray"></a>
                        E-Klausuren mit Nachklausuren
                        <small></small>
                    </h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
                <!-- /.box-header -->
				<div class="box-body">
					<div class="chart">
						<canvas id="klausurenInsgesamt"></canvas>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<div class="pull-right">
						<a onclick="saveCanvas(this, 'klausurenInsgesamt')" download="E-Klausuren mit Nachklausuren, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
					</div>
				</div>
			</div>
			<!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <!-- E-Klausuren je Facharbeitsgruppe -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
						<a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle E-Klausuren zuzüglich Nachklausuren, sortiert nach Facharbeitsgruppe. Nur als abgeschlossen gekennzeichnete E-Klausuren werden einbezogen. Jede E-Klausur zählt für die erstgenannte Facharbeitsgruppe, mögliche Kooperationen werden nicht berücksichtigt." class="fa fa-info-circle text-gray"></a>
            E-Klausuren mit Nachklausuren nach Facharbeitsgruppe
            <small></small>
        	</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
                <!-- /.box-header -->
				<div class="box-body">
					<div class="chart">
						<canvas id="klausurenProArbeitsgruppe"></canvas>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<div class="pull-right">
						<a onclick="saveCanvas(this, 'klausurenProArbeitsgruppe')" download="E-Klausuren mit Nachklausuren nach Facharbeitsgruppe, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
					</div>
				</div>
			</div>
			<!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <!-- E-Klausuren-Teilnehmer je Semester und kumuliert -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
                        <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle Klausurteilnehmer zuzüglich wiederholter Klausurteilnahmen. Nur als abgeschlossen gekennzeichnete E-Klausuren werden einbezogen. Jede E-Klausur wird dem Semester zugeordnet, in dem das Projekt abgeschlossenen wurde. Wiederholungstermine zählen zum Semester, in dem die E-Klausur ursprünglich geschrieben wurde." class="fa fa-info-circle text-gray"></a>
                        Klausurteilnehmer mit Wiederholungen
                        <small></small>
                    </h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
                <!-- /.box-header -->
				<div class="box-body">
					<div class="chart">
						<canvas id="klausurenTeilnehmer"></canvas>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<div class="pull-right">
						<a onclick="saveCanvas(this, 'klausurenTeilnehmer')" download="Klausurteilnehmer mit Wiederholungen, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
					</div>
				</div>
			</div>
			<!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->

<?php get_footer(); ?>

<script type="text/javascript">
    $(function () {
        /* Navigationsmenü */
        $("ul.sidebar-menu li#statistiken").addClass("active");
        $("ul.sidebar-menu li#klausuren").addClass("active");

        /* Diagramme */
        var chartData;
        var chartDataCumu;
				var defaultChartOptions = {
			                              responsive: true,
			                              scales: {
			                                xAxes: [{
			                                  gridLines: {
			                                    display:false
			                                  }
			                                }],
			                                yAxes: [{
			                                  gridLines: {
			                                    display:false
			                                  },
			                                  ticks: {
			                                    beginAtZero: true
			                                  }
			                                }]
			                              }
			                          };
        var cumuChartOptions = {
                                    responsive: true,
                                    scales: {
																			xAxes: [{
																				gridLines: {
																					display:false
																				}
																			}],
                                        yAxes: [{
                                                type: "linear",
                                                display: true,
                                                position: "left",
                                                id: "y-axis-1",
                                                scaleLabel: {
                                                    display: true,
                                                    labelString: 'Absolut',
                                                    fontColor: "rgba(0,138,163,1)"
                                                },
                                                gridLines:{
                                                    display: false
                                                },
                                                labels: {
                                                    show:true
                                                }
                                            }, {
                                                type: "linear",
                                                display: true,
                                                position: "right",
                                                id: "y-axis-2",
                                                scaleLabel: {
                                                    display: true,
                                                    labelString: 'Kumuliert',
                                                    fontColor: "rgba(155,195,75,1)"
                                                },
                                                gridLines:{
                                                    display: false
                                                },
                                                labels: {
                                                    show:true
                                                }
                                            }]
                                    },
		                                legend: {
		                                  onClick: function(e, legendItem) {
		                                    var defaultLegendClick = Chart.defaults.global.legend.onClick;
		                                    var index = legendItem.datasetIndex;
		                                    this.chart.options.scales.yAxes[index].display = (this.chart.options.scales.yAxes[index].display ? false : true );
		                                    defaultLegendClick.call(this, e, legendItem);
		                                  }
		                                }
                                   };

        /* E-Klausuren insgesamt */
        $(function () {
            chartData = JSON.parse('<?php echo $klausurenProSemester; ?>');
            chartDataCumu = JSON.parse('<?php echo $klausurenInsgesamt; ?>');
            var ctx = $("#klausurenInsgesamt");
            var ChartObj = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData['keys'],
                    datasets: [{
                        label: "E-Klausuren je Semester",
                        type: 'bar',
                        data: chartData['values'],
                        backgroundColor: "rgba(0,138,163,0.4)",
                        borderColor: "rgba(0,138,163,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(0,138,163,0.5)",
                        hoverBorderColor: "rgba(0,138,163,1)",
                        yAxisID: 'y-axis-1'
                    }, {
                        label: "E-Klausuren kumuliert",
                        type: 'line',
                        data: chartDataCumu['values'],
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(155,195,75,0.4)",
												borderWidth: 2,
												borderColor: "rgba(155,195,75,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(155,195,75,1)",
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(155,195,75,1)",
                        pointHoverBorderColor: "rgba(155,195,75,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 3,
                        pointHitRadius: 3,
                        yAxisID: 'y-axis-2'
                    }]
                },
                options: cumuChartOptions
            });
        });

        /* E-Klausuren nach Arbeitsgruppe */
        $(function () {
            chartData = JSON.parse('<?php echo $klausurenProArbeitsgruppe; ?>');
            var ctx = $("#klausurenProArbeitsgruppe");
            var ChartObj = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData['keys'],
                    datasets: [{
                        label: "E-Klausuren",
                        data: chartData['values'],
                        backgroundColor: "rgba(0,138,163,0.4)",
                        borderColor: "rgba(0,138,163,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(0,138,163,0.5)",
                        hoverBorderColor: "rgba(0,138,163,1)",
                    }]
                },
                options: defaultChartOptions
            });
        });

        /* E-Klausuren-Teilnehmer insgesamt */
        $(function () {
            chartData = JSON.parse('<?php echo $klausurenTeilnehmerProSemester; ?>');
            chartDataCumu = JSON.parse('<?php echo $klausurenTeilnehmer; ?>');
            var ctx = $("#klausurenTeilnehmer");
            var ChartObj = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData['keys'],
                    datasets: [{
                        label: "Teilnehmer je Semester",
												type: 'bar',
                        data: chartData['values'],
                        backgroundColor: "rgba(0,138,163,0.4)",
                        borderColor: "rgba(0,138,163,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(0,138,163,0.5)",
                        hoverBorderColor: "rgba(0,138,163,1)",
                        yAxisID: 'y-axis-1'
                    }, {
                        label: "Teilnehmer kumuliert",
												type: 'line',
                        data: chartDataCumu['values'],
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(155,195,75,0.4)",
												borderWidth: 2,
												borderColor: "rgba(155,195,75,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(155,195,75,1)",
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(155,195,75,1)",
                        pointHoverBorderColor: "rgba(155,195,75,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 3,
                        pointHitRadius: 3,
                        yAxisID: 'y-axis-2'
                    }]
                },
                options: cumuChartOptions
            });
        });
    });

		// Bild speichern
		function saveCanvas(element, canvas) {
		  base64 = document.getElementById(canvas).toDataURL('image/png');
		  element.href = base64;
		}

		// Hintergrund füllen
		Chart.plugins.register({
		  beforeDraw: function(chartInstance) {
		    var ctx = chartInstance.chart.ctx;
		    ctx.fillStyle = "rgba(255,255,255,1)";
		    ctx.fillRect(0, 0, chartInstance.chart.width, chartInstance.chart.height);
		  }
		});
</script>
