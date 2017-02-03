<?php

    global $wpdb;

/* Statistikanzeige eingrenzen*/
$result = $wpdb->get_results(
"
  SELECT MIN(b.meta_value) AS min,
  MAX(b.meta_value) AS max
  FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c
  WHERE a.id = b.post_id
  AND a.id = c.post_id
  AND a.post_status = 'publish'
  AND b.meta_key = 'zeitraum_0_ende'
  AND c.meta_key = 'status'
  AND c.meta_value = '4'
"
);
$minEndDate = $result[0]->min;
$minSqlDate = ( isset($_GET['startdatum']) ? $_GET['startdatum'] : $minEndDate );
$maxEndDate = $result[0]->max;
$maxSqlDate = ( isset($_GET['enddatum']) ? $_GET['enddatum'] : $maxEndDate );

    /* Projekte nach Semester */
    /* WIR HOLLEN ALLE PROJEKTE AUS DER DATENBANK *******************************************************************************************/
    $result = $wpdb->get_results(
	"
		SELECT DISTINCT b.post_id
		AS id, b.meta_value
		AS ende
		FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c
		WHERE a.ID = b.post_id
    AND a.id = c.post_id
		AND a.post_status = 'publish'
		AND b.meta_key = 'zeitraum_0_ende'
    AND b.meta_value >= " . $minSqlDate . "
    AND b.meta_value <= " . $maxSqlDate . "
    AND c.meta_key = 'status'
    AND c.meta_value = '4'
		ORDER BY b.meta_value
	"
	);
    $return = array();

    foreach ($result as $project) {
        $semester = convertDateToSemester($project->ende, 1);
        $return[$semester]++;
    }
    /****************************************************************************************************************************************/
    /* WIR WOLLEN ALLE SCHLÜSSEL UNSERER ARRAYS JETZT FÜR DEN NUTZBAR LESBAR MACHEN ("w15" -> "WiSe 2015/16") *******************************/
    $projekteProSemester = makeSemesterKeysReadable($return);
    $projekteInsgesamt = makeSemesterKeysReadable(cumulateSemester($return));
    /****************************************************************************************************************************************/
    $projekteProSemester = json_encode(array('keys' => array_keys($projekteProSemester), 'values' => array_values($projekteProSemester)));
    $projekteInsgesamt = json_encode(array('keys' => array_keys($projekteInsgesamt), 'values' => array_values($projekteInsgesamt)));


    /* Projekte nach Status */
    /* WIR HOLLEN ALLE PROJEKTE AUS DER DATENBANK, ABER NUR WENN SIE DEN ENTSPRECHENDEN STATUS HABEN UND GRUPPIEREN SIE GLEICH DANACH *******/
    $result = $wpdb->get_results(
	"
		SELECT b.meta_value
		AS status, COUNT(b.meta_value)
		AS anzahl
		FROM $wpdb->posts a, $wpdb->postmeta b
		WHERE a.ID = b.post_id
		AND a.post_status = 'publish'
		AND b.meta_key = 'status'
    AND b.meta_value IN (1,2,3)
    GROUP BY b.meta_value
	"
	);

    $return = array();

    foreach ($result as $project) {
        $project = (array) $project;
        $return[$project['status']] = $project['anzahl'];
    }
    $projekteNachStatusCount = count($return);
    /****************************************************************************************************************************************/
    $projekteNachStatus = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));







    $result = $wpdb->get_results(
    "
    SELECT b.meta_value
		AS ende, c.meta_value
		AS anzahl
		FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d
		WHERE a.ID = b.post_id
    AND a.id = c.post_id
    AND a.id = d.post_id
		AND a.post_status = 'publish'
		AND b.meta_key = 'zeitraum_0_ende'
    AND b.meta_value >= " . $minSqlDate . "
    AND b.meta_value <= " . $maxSqlDate . "
    AND c.meta_key = 'aufzeichnungsanzahl'
    AND d.meta_key = 'status'
    AND d.meta_value = '4'
		ORDER BY b.meta_value
    "
    );


    $return = array();

    foreach ($result as $project) {
        $semester = convertDateToSemester($project->ende, 1);
        if ($project->anzahl >= 0) {
            $return[$semester] += $project->anzahl;
        }
    }


    $aufzeichnungenProSemester = makeSemesterKeysReadable($return);
    $aufzeichnungenInsgesamt = makeSemesterKeysReadable(cumulateSemester($return));

    $aufzeichnungenProSemester = json_encode(array('keys' => array_keys($aufzeichnungenProSemester), 'values' => array_values($aufzeichnungenProSemester)));
    $aufzeichnungenInsgesamt = json_encode(array('keys' => array_keys($aufzeichnungenInsgesamt), 'values' => array_values($aufzeichnungenInsgesamt)));















	$result = $wpdb->get_results(
	"
		SELECT b.meta_value
		AS name, COUNT(b.meta_value)
		AS anzahl
		FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d
		WHERE a.ID = b.post_id
    AND a.ID = c.post_id
    AND a.ID = d.post_id
		AND a.post_status = 'publish'
		AND b.meta_key = 'facharbeitsgruppe_0_arbeitsgruppe'
		AND b.meta_value != ''
    AND c.meta_key = 'status'
    AND c.meta_value = '4'
		AND d.meta_key = 'zeitraum_0_ende'
    AND d.meta_value >= " . $minSqlDate . "
    AND d.meta_value <= " . $maxSqlDate . "
    GROUP BY b.meta_value
	"
	);
	$return = array();
	foreach ($result as $workgroup) {
		$return[$workgroup->name] = $workgroup->anzahl;
	}
	/************************************************************************************************************************************/
	$arbeitsgruppenInsgesamt = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));




/* Arbeitsgruppen ohne Aufzeichnungen */
	/* WIR HOLEN ALLE FACH-AGs AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE FACH-AG GIBT ********/
	$result = $wpdb->get_results(
	"
		SELECT b.meta_value
		AS name, COUNT(c.meta_value)
		AS anzahl
		FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d, $wpdb->postmeta e
		WHERE a.ID = b.post_id
    AND a.ID = c.post_id
    AND a.ID = d.post_id
    AND a.ID = e.post_id
		AND a.post_status = 'publish'
		AND b.meta_key = 'facharbeitsgruppe_0_arbeitsgruppe'
		AND b.meta_value != ''
		AND c.meta_key = 'kategorie'
		AND c.meta_value != 'Aufzeichnung/Streaming Einzelveranstaltung'
		AND c.meta_value != 'Aufzeichnung/Streaming Veranstaltungsreihe'
    AND d.meta_key = 'status'
    AND d.meta_value = '4'
		AND e.meta_key = 'zeitraum_0_ende'
    AND e.meta_value >= " . $minSqlDate . "
    AND e.meta_value <= " . $maxSqlDate . "
    GROUP BY b.meta_value
	"
	);
	$return = array();
	foreach ($result as $workgroup) {
		$return[$workgroup->name] = $workgroup->anzahl;
	}
	/************************************************************************************************************************************/
	$arbeitsgruppenOhneAufzeichnungen = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));










    /* Projekte nach Kategorie-Clustern */
	/* WIR HOLEN ALLE KATEGORIEN AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE KATEGORIE GIBT ****/

    $result = $wpdb->get_results(
	"
    SELECT b.meta_value
    AS name, COUNT(b.meta_value)
    AS anzahl
    FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c , $wpdb->postmeta d
    WHERE a.ID = b.post_id
    AND a.ID = c.post_id
    AND a.ID = d.post_id
    AND a.post_status = 'publish'
    AND b.meta_key = 'kategorie'
    AND c.meta_key = 'status'
    AND c.meta_value = '4'
		AND d.meta_key = 'zeitraum_0_ende'
    AND d.meta_value >= " . $minSqlDate . "
    AND d.meta_value <= " . $maxSqlDate . "
    GROUP BY b.meta_value
	"
	);
    /************************************************************************************************************************************/
    /* WIR LEGEN EIN ZÄHL-ARRAY FEST UND EIN ZUORDNUNGS-ARRAY. ANHAND LETZTEREM WERDEN DIE KATEGORIEN DEN CLUSTEN ZUGEORDNET ************/
	$return = array('Vorlesungsaufzeichnungen' => 0, 'E-Klausuren' => 0, 'Lerninhalte' => 0, 'Weitere Kategorien' => 0);
    $assign = array(
                    'Vorlesungsaufzeichnungen' => array(
                                     'Aufzeichnung/Streaming Einzelveranstaltung',
                                     'Aufzeichnung/Streaming Veranstaltungsreihe'
                                    ),
                    'E-Klausuren' => array(
                                     'E-Klausur'
                                    ),
                    'Lerninhalte' => array(
                                     'Lerninhalte entwickeln/bearbeiten/erweitern',
                                     'Lernobjekte/Lernarrangements umsetzen'
                                    ),
                    'Weitere Kategorien' => array(
                                     'ARSnova',
                                     'E-Konzept-Entwicklung',
                                     'Informationsveranstaltung',
                                     'Podcast/Screencast-Erstellung',
                                     'Schulung/Workshop',
                                     'Sonstige Beratung',
                                     'Sonstiges'
                                    )
                   );
    /************************************************************************************************************************************/
    /* WIR GEHEN UNSERE GEZÄHLTEN KATEGORIEN DURCH UND SUCHEN IM ZUORDNUNGSARRAY NACH EINER PASSENDEN ZUORDNUNG UND ADDIEREN DIE ANZAHL */
    foreach ($result as $category) {
        $key = getArrayKey($category->name, $assign);
        /* Falls wir eine neue Kategorie anlegen oder eine Kategorie keinem Cluster zuordnen können, zählen wir sie als Extra-Cluster */
        if (!$key) {
            $key = $category->name;
        }
        $return[$key] += $category->anzahl;
    }
	/************************************************************************************************************************************/
	$projekteNachKategorienCount = count($return);
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

      <!-- Projekte insgesamt -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">
                          <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle abgeschlossenen Projekte des Servicebereichs. Jedes Projekt wird dem Semester seiner Fertigstellung zugeordnet." class="fa fa-info-circle text-gray"></a>
                          Abgeschlossene Projekte
                          <small></small>
                      </h3>
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
        <div class="box-footer">
          <div class="pull-right">
            <a onclick="saveCanvas(this, 'projekteInsgesamt')" download="Abgeschlossene Projekte, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
          </div>
        </div>
      </div>
      <!-- /.box -->

  <!-- Projekte nach Arbeitsgruppen -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">
                  <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle abgeschlossenen Projekte des Servicebereichs, sortiert nach Facharbeitsgruppe. Jedes Projekt zählt für die erstgenannte Facharbeitsgruppe, mögliche Kooperationen werden nicht berücksichtigt." class="fa fa-info-circle text-gray"></a>
                  Abgeschlossene Projekte nach Facharbeitsgruppe
                  <small></small>
              </h3>
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
    <div class="box-footer">
      <div class="pull-right">
        <a onclick="saveCanvas(this, 'projekteNachArbeitsgruppen')" download="Abgeschlossene Projekte nach Facharbeitsgruppe, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
      </div>
    </div>
  </div>
  <!-- /.box -->

  <!-- LVA -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">
                  <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle Vorlesungsaufzeichnungen. Nur als abgeschlossen gekennzeichnete Projekte werden einbezogen. Gezählt wird die dokumentierte Aufzeichnungsanzahl in einzelnen Veranstaltungsblöcken. Alle Vorlesungsaufzeichnungen werden dem Semester zugeordnet, in dem das Projekt abgeschlossenen wurde." class="fa fa-info-circle text-gray"></a>
                  Einzelne Vorlesungsaufzeichnungen
                  <small></small>
              </h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body">
      <div class="chart">
        <canvas id="aufzeichnungenInsgesamt"></canvas>
      </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <div class="pull-right">
        <a onclick="saveCanvas(this, 'aufzeichnungenInsgesamt')" download="Einzelne Vorlesungsaufzeichnungen, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
      </div>
    </div>
  </div>
  <!-- /.box -->

        <!-- Projekte nach Kategorien -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">
                              <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden alle abgeschlossenen Projekte des Servicebereichs, sortiert nach geclusterten Kategorien." class="fa fa-info-circle text-gray"></a>
                              Abgeschlossene Projekte nach Kategorie
                              <small></small>
                          </h3>
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
          <div class="box-footer">
            <div class="pull-right">
              <a onclick="saveCanvas(this, 'projekteNachKategorien')" download="Abgeschlossene Projekte nach Kategorie, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
            </div>
          </div>
        </div>
        <!-- /.box -->

        <!-- Projekte nach Status -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">
                              <a href="javascript:return false;" data-toggle="tooltip" data-placement="bottom" title="Angezeigt werden die Projekte des Servicebereichs, deren Status in Planung, in Bearbeitung oder pausierend ist. Nicht enthalten sind abgeschlossene oder abgebrochene Projekte." class="fa fa-info-circle text-gray"></a>
                              Offene Projekte nach Status
                              <small></small>
                          </h3>
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
          <div class="box-footer">
            <div class="pull-right">
              <a onclick="saveCanvas(this, 'projekteNachStatus')" download="Offene Projekte nach Status, <?php echo date(d. '.' . m . '.' . Y) ?>.png" class="btn btn-default">Bild speichern</a>
            </div>
          </div>
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
  var pieChartOptions = {responsive: true, legend: {position: "right"}};
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
                                            },
                                            ticks: {
                                              beginAtZero: true
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
                                            },
                                            ticks: {
                                              beginAtZero: true
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

	/* Projekte insgesamt */
    $(function () {
		chartData = JSON.parse('<?php echo $projekteProSemester; ?>');
        chartDataCumu = JSON.parse('<?php echo $projekteInsgesamt; ?>');
		var ctx = $("#projekteInsgesamt");
		var LineChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "Projekte je Semester",
          type: 'bar',
					data: chartData['values'],
         	backgroundColor: "rgba(0,138,163,0.4)",
          borderColor: "rgba(0,138,163,1)",
          borderWidth: 1,
          hoverBackgroundColor: "rgba(0,138,163,0.5)",
          hoverBorderColor: "rgba(0,138,163,1)",
          yAxisID: 'y-axis-1'
				}, {
					label: "Projekte kumuliert",
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
         		backgroundColor: "rgba(0,138,163,0.5)",
        		borderColor: "rgba(0,138,163,1)",
        		borderWidth: 1,
        		hoverBackgroundColor: "rgba(0,138,163,0.6)",
        		hoverBorderColor: "rgba(0,138,163,1)",
					},
					{
						label: "Ohne Vorlesungsaufzeichnungen",
						data: chartData_2['values'],
         		backgroundColor: "rgba(155,195,75,0.4)",
      			borderColor: "rgba(155,195,75,1)",
      			borderWidth: 1,
      			hoverBackgroundColor: "rgba(155,195,75,0.5)",
      			hoverBorderColor: "rgba(155,195,75,1)",
					}]
				},
				options: defaultChartOptions
		});
	});

    /* LVA insgesamt */
    $(function () {
		chartData = JSON.parse('<?php echo $aufzeichnungenProSemester; ?>');
        chartDataCumu = JSON.parse('<?php echo $aufzeichnungenInsgesamt; ?>');
		var ctx = $("#aufzeichnungenInsgesamt");
		var LineChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "Aufzeichnungen je Semester",
          type: 'bar',
					data: chartData['values'],
         	backgroundColor: "rgba(0,138,163,0.4)",
        	borderColor: "rgba(0,138,163,1)",
        	borderWidth: 1,
        	hoverBackgroundColor: "rgba(0,138,163,0.5)",
        	hoverBorderColor: "rgba(0,138,163,1)",
          yAxisID: 'y-axis-1'
				}, {
					label: "Aufzeichnungen kumuliert",
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

	/* Projekte nach Status */
	$(function () {
		chartData = JSON.parse('<?php echo $projekteNachStatus; ?>');
		var ctx = $("#projekteNachStatus");
		var PieChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ["In Planung", "In Bearbeitung", "Pausiert"],
				datasets: [{
					data: chartData['values'],
         		   	backgroundColor: randomColor({count: <?php echo $projekteNachStatusCount; ?>})
					}]
				},
			options: pieChartOptions
		});
	});

	/* Projekte nach Kategorien */
	$(function () {
		chartData = JSON.parse('<?php echo $projekteNachKategorien; ?>');
		var ctx = $("#projekteNachKategorien");
		var PieChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: chartData['keys'],
				datasets: [{
					data: chartData['values'],
		            backgroundColor: randomColor({count: <?php echo $projekteNachKategorienCount; ?>})
				}]
			},
			options: pieChartOptions
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




<?php

    /* FRIEDHOF */

    /* Projekte nach Kategorien */
	/* WIR HOLEN ALLE KATEGORIEN AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE KATEGORIE GIBT ****/
	/*$result = $wpdb->get_results(
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
	}*/
	/************************************************************************************************************************************/
	/*$countProjektKategorien = count($return);
	$projekteNachKategorien = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));*/

















    	/* Projekte insgesamt */
	/* FESTLEGEN DER DATUMSGRENZEN DER JEWEILIGEN SEMESTER (W = WINTERSEMESTER | S = SOMMERSEMESTER) ****************************************/
	/*define('W_START', '1001');
	define('W_END', '0331');
	define('S_START', '0401');
	define('S_END', '0930');
	/****************************************************************************************************************************************/
	/* ALS ERSTES SUCHEN WIR DAS ERSTE BEGONNENE PROJEKTE UND DAS LETZTE BEGONNENE PROJEKT UND RECHNEN ANHAND DERER DEN ZEITRAUM AUS ********/
	/*$firstProject = $wpdb->get_results(
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
	/*$return = array();
	/* WIR GEHEN DEN ZEITRAUM DURCH UND SUCHEN FÜR JEDES SEMESTER DIE ANZAHL DER PROJEKTE RAUS **********************************************/
	/*$count = 0;
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
	/*$return = makeSemesterKeysReadable($return);
	/****************************************************************************************************************************************/
	/*$projekteInsgesamt = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Projekte je Semester */
	/*$result = $wpdb->get_results(
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
		/*$start = $project->start;
		/* Als nächstes überprüfen wir, ob das Projekt ein Ende hat, ansonsten setzen wir das aktuelle Datum als Bezugspunkt */
		/*$result = $wpdb->get_results(
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
		/*$startSemester = convertDateToSemester($start);
		$endSemester = convertDateToSemester($ende);
		/* Dauer entspricht der Differenz zwischen den Jahren mal 2 (da 2 Semester pro Jahr) */
		/*$duration = ($endSemester['y'] - $startSemester['y']) * 2;
		if ($startSemester['s'] == $endSemester['s']) {
			/* Sollte das Projekt im selben Semestertyp (w oder s) beginnen und enden, müssen wir noch ein Semester dazuzählen */
			/*$duration++;
		} elseif ($startSemester['s'] == 's' && $endSemester['s'] == 'w') {
			/* Sollte ein Projekt im Sommersemester starten und im Wintersemester enden, müssen wir zwei Semester dazuzählen */
			/*$duration += 2;
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
		/*$year = $startSemester['y'];
		for ($i = 1; $i <= $duration; $i++) {
			/* Wir gehen jedes Semester seit dem Start eines Projekts entsprechend der Dauer durch */
			/*if ($i > 1) {
				/* Hier gehen wir NUR rein, wenn wir NICHT im ersten Semester sind, da wir das nicht verändern müssen */
				/*if ($i % 2 == 0) {
					/* Durch diese Modulo-Rechnung können wir immer zwischen Sommer und Winter wechseln */
					/*if ($startSemester['s'] == 's') {
						/* Haben wir mit Sommer begonnen, sind wir jetzt bei Winter */
						/*$currSemester = 'w';
					/*} elseif ($startSemester['s'] == 'w') {
						/* Haben wir mit Winter begonnen, sind wir jetzt bei Sommer */
						/*$currSemester = 's';
					}
				} else {
					/* Müssen wir die Jahreszeit nicht wechseln, geben wir unserem aktuellen Semester die ursprüngliche Jahreszeit */
					/*$currSemester = $startSemester['s'];
				}
				if ($currSemester == 's') {
					/* Da in unserer Logik immer Sommersemester die sind, bei denen das "neue" Jahr beginnt, zählen wir im Sommer um 1 nach oben */
					/*$year++;
				}
				/* Als letztes ergänzen wir noch das Jahr für das aktuelle Semester */
				/*$currSemester .= $year;
			} else {
				/* Sind wir im ersten Durchlauf, können wir einfach das Startsemester nutzen */
				/*$currSemester = $startSemester['d'];
			}
			/* Wir setzen den Zähler des errechneten Semesters an dessen Stelle im Return-Array um 1 nach oben */
			/*$return[$currSemester]++;
		}
		/************************************************************************************************************************************/
	/*}
	/* WIR WOLLEN ALLE SCHLÜSSEL UNSERES ARRAYS JETZT FÜR DEN NUTZBAR LESBAR MACHEN ("w15" -> "WiSe 2015/16") *******************************/
	/*$return = makeSemesterKeysReadable($return);
	/****************************************************************************************************************************************/
	/*$projekteJeSemester = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Arbeitsgruppen insgesamt */
	/* WIR HOLEN ALLE FACH-AGs AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE FACH-AG GIBT ********/
	/*$result = $wpdb->get_results(
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
	/*$coop = false;
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
	/*$arbeitsgruppenInsgesamt = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/* Arbeitsgruppen ohne Aufzeichnungen */
	/* WIR HOLEN ALLE FACH-AGs AUS DER DATENBANK UND ZÄHLEN AUCH GLEICH PER SQL NACH WIE VIELE EINTRÄGE ES FÜR JEDE FACH-AG GIBT ********/
	/*$result = $wpdb->get_results(
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
	/*$coop = false;
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
	/*$arbeitsgruppenOhneAufzeichnungen = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

	/*Projekte nach Status */
	/* WIR HOLEN UNS ALLE PROJEKTE AUS DER DATENBANK UND GRUPPIEREN SIE NACH FORTSCHRITTSSTAND (IN DER DB IN 5er-SCHRITTEN) *****************/
	/*$result = $wpdb->get_results(
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
	/*foreach ($result as $projects) {
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
			/* Projekte ohne Fortschitts-Angabe zählen wir als 0% Abgeschlossen */
			/*$return['0'] += $projects->anzahl;
		}
	}
	/****************************************************************************************************************************************/
	/*$projekteNachStatus = json_encode(array('keys' => array_keys($return), 'values' => array_values($return)));

?>
