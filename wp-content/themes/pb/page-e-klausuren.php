<?php
	global $wpdb;

    /* E-Klausuren pro Facharbeitsgruppe (OHNE Kooperationen) */
    /* WIR HOLLEN ALLE KLAUSUREN AUS DER DATENBANK UND SORTIEREN SIE GLEICH NACH GRUPPEN ****************************************************/ 
    $result = $wpdb->get_results(
    "
        SELECT c.meta_value 
        AS name, COUNT(c.meta_value) 
        AS anzahl 
        FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d 
        WHERE a.ID = b.post_id 
        AND a.ID = c.post_id 
        AND a.ID = d.post_id 
        AND a.post_status = 'publish' 
        AND b.meta_key = 'Kategorie' 
        AND b.meta_value = 'E-Klausur' 
        AND c.meta_key = 'facharbeitsgruppe_0_arbeitsgruppe' 
        AND d.meta_key = 'status' 
        AND d.meta_value = '4'
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
        AS start
        FROM $wpdb->posts a, $wpdb->postmeta b, $wpdb->postmeta c, $wpdb->postmeta d 
        WHERE a.ID = b.post_id 
        AND a.ID = c.post_id 
        AND a.ID = d.post_id  
        AND a.post_status = 'publish' 
        AND b.meta_key = 'zeitraum_0_begin' 
        AND c.meta_key = 'Kategorie' 
        AND c.meta_value = 'E-Klausur' 
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
        
        /* Der Einfachheit halber fragen wir für die jeweilige Klausur gleich noch alle Teilnehmer (inkl. Nachklausuren) ab */
        $res = $wpdb->get_results(
        "
            SELECT a.meta_value 
            FROM $wpdb->postmeta a
            WHERE a.post_id = ".$test->id." 
            AND a.meta_key LIKE 'klausurverlauf_%_teilnehmer' 
            GROUP BY a.post_id
        "
        );
        
        /* Die Teilnehmer addieren wir dann dem entsprechenden Semester hinzu */
        $teilnehmer[$startSemester['d']] += $res[0]->meta_value;
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



    $timestamp = date('d.m.Y');
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

				<!-- E-Klausuren je Semester und kumuliert -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
                            E-Klausuren insgesamt (mit Nachklausuren)
                            <small><?php echo $timestamp; ?></small>
                        </h3>
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
      
    <div class="row">
      <div class="col-md-12">

				<!-- E-Klausuren je Facharbeitsgruppe -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
                            E-Klausuren nach Facharbeitsgruppen (ohne Kooperationen, mit Nachklausuren)
                            <small><?php echo $timestamp; ?></small>
                        </h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="chart">
							<canvas id="klausurenProArbeitsgruppe"></canvas>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

      </div>
    </div>
    <!-- /.row -->
      
    <div class="row">
      <div class="col-md-12">

				<!-- E-Klausuren-Teilnehmer je Semester und kumuliert -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
                            E-Klausuren-Teilnehmer (mit Wiederholungen)
                            <small><?php echo $timestamp; ?></small>
                        </h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="chart">
							<canvas id="klausurenTeilnehmer"></canvas>
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
    var chartDataCumu;
	var defaultChartOptions = {responsive: true};
    var cumuChartOptions = {
                                responsive: true,
                                scales: {    
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
                                                fontColor: "rgba(255,153,0,1)"
                                            },
                                            gridLines:{
                                                display: false
                                            },
                                            labels: {
                                                show:true
                                            }
                                        }]
                                }
                               };

	/* E-Klausuren insgesamt */
	$(function () {
		chartData = JSON.parse('<?php echo $klausurenProSemester; ?>');
        chartDataCumu = JSON.parse('<?php echo $klausurenInsgesamt; ?>');
		var ctx = $("#klausurenInsgesamt");
		var LineChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "E-Klausuren pro Semester",
                    type: 'bar',
					data: chartData['values'],
         		   	backgroundColor: "rgba(0,138,163,0.4)",
            		borderColor: "rgba(0,138,163,1)",
            		borderWidth: 3,
            		hoverBackgroundColor: "rgba(0,138,163,0.6)",
            		hoverBorderColor: "rgba(0,138,163,1)",
                    yAxisID: 'y-axis-1'
				}, {
					label: "E-Klausuren (kumuliert)",
                    type: 'line',
					data: chartDataCumu['values'],
				    fill: false,
				    lineTension: 0.1,
				    backgroundColor: "rgba(255,153,0,0.4)",
                    borderColor: "rgba(255,153,0,1)",
				    borderCapStyle: 'butt',
				    borderDash: [],
				    borderDashOffset: 0.0,
				    borderJoinStyle: 'miter',
				    pointBorderColor: "rgba(0,138,163,1)",
				    pointBackgroundColor: "#fff",
				    pointBorderWidth: 3,
				    pointHoverRadius: 5,
				    pointHoverBackgroundColor: "rgba(255,153,0,0.6)",
				    pointHoverBorderColor: "rgba(255,153,0,1)",
				    pointHoverBorderWidth: 3,
				    pointRadius: 0,
					pointHitRadius: 5,
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
		var LineChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "E-Klausuren",
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
    
    /* E-Klausuren-Teilnehmer insgesamt */
	$(function () {
		chartData = JSON.parse('<?php echo $klausurenTeilnehmerProSemester; ?>');
        chartDataCumu = JSON.parse('<?php echo $klausurenTeilnehmer; ?>');
		var ctx = $("#klausurenTeilnehmer");
		var LineChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartData['keys'],
				datasets: [{
					label: "E-Klausuren pro Semester",
                    type: 'bar',
					data: chartData['values'],
         		   	backgroundColor: "rgba(0,138,163,0.4)",
            		borderColor: "rgba(0,138,163,1)",
            		borderWidth: 3,
            		hoverBackgroundColor: "rgba(0,138,163,0.6)",
            		hoverBorderColor: "rgba(0,138,163,1)",
                    yAxisID: 'y-axis-1'
				}, {
					label: "E-Klausuren (kumuliert)",
                    type: 'line',
					data: chartDataCumu['values'],
				    fill: false,
				    lineTension: 0.1,
				    backgroundColor: "rgba(255,153,0,0.4)",
                    borderColor: "rgba(255,153,0,1)",
				    borderCapStyle: 'butt',
				    borderDash: [],
				    borderDashOffset: 0.0,
				    borderJoinStyle: 'miter',
				    pointBorderColor: "rgba(0,138,163,1)",
				    pointBackgroundColor: "#fff",
				    pointBorderWidth: 3,
				    pointHoverRadius: 5,
				    pointHoverBackgroundColor: "rgba(255,153,0,0.6)",
				    pointHoverBorderColor: "rgba(255,153,0,1)",
				    pointHoverBorderWidth: 3,
				    pointRadius: 0,
					pointHitRadius: 5,
                    yAxisID: 'y-axis-2'
				}]
			},
            
			options: cumuChartOptions
		});
	});
    


});
</script>
