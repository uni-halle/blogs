<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_subscriptions_view extends WYSIJA_view {

	/**
	 * @var int
	 */
	protected $threshold_to_disable_labels = 4;

	public function hook_stats($data) {
		$div_id = 'chartdiv'.rand(1, 1000000); // make sure id is unique, even this hook is called more than once
		?>
		<div class="container-<?php echo $div_id; ?> container stats_subscriptions-container" rel="<?php echo $data['module_name']; ?>">
			<?php
			if (!empty($data['charts'])) {
				foreach ($data['charts'] as $chart_id => $chart_data) {
					?>
					<h3 class="title"><?php echo $chart_data['name']; ?></h3>
					<div id="<?php echo $div_id.$chart_id; ?>" class="stats_subscriptions" style="width: 100%; height: 400px; display:block"></div>
					<script type="text/javascript">
						chartData = <?php echo json_encode($chart_data['data']); ?>;
						divId = '<?php echo $div_id.$chart_id; ?>';
						chart = new AmCharts.AmSerialChart();
						chart.dataProvider = chartData;
						chart.categoryField = "name";
						chart.startDuration = 1;
				<?php if (!empty($this->font_family)) { ?>
								chart.fontFamily = '<?php echo implode(',', $this->font_family); ?>';
				<?php } ?>
							chart.fontSize = <?php echo $this->font_size; ?>;

							// Set pointer of balloon
							var balloon = chart.balloon;
							balloon.cornerRadius = 0;
							balloon.pointerWidth = 5;

							// AXES
							// category
							var categoryAxis = chart.categoryAxis;
							categoryAxis.gridPosition = "start";
							categoryAxis.axisColor = "#DADADA";
							categoryAxis.dashLength = 3;
				<?php if (count($chart_data['data']) > $this->threshold_to_disable_labels) { ?>
						categoryAxis.labelsEnabled = false;
				<?php } ?>

					// value
					var valueAxis = new AmCharts.ValueAxis();
					valueAxis.dashLength = 3;
					valueAxis.axisAlpha = 0.2;
					valueAxis.title = "<?php echo __('Subscribers', WYSIJA); ?>";
					valueAxis.minorGridEnabled = true;
					valueAxis.minorGridAlpha = 0.08;
					valueAxis.gridAlpha = 0.15;
					valueAxis.minimum = 0;
					chart.addValueAxis(valueAxis);

					// GRAPHS
					// column graph
					var graph = new AmCharts.AmGraph();
					graph.type = "column";
					//graph.title = "Income";
					graph.valueField = "subscribed";
					graph.lineAlpha = 0;
					graph.fillColors = "#ADD981";
					graph.fillAlphas = 0.8;
					graph.balloonText = "[[category]]: [[value]]";
					graph.fillColors = '#c97575';
					chart.addGraph(graph);



					// WRITE
					chart.write(divId);
					</script>
					<?php
					if (count($chart_data['data']) > $this->threshold_to_disable_labels) {
						echo '<p class="tip">'.__("Hover your mouse over the bars to discover the forms' names.").'</p>';
					}
				}
				?>

			<?php }
			else { ?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
		<?php } ?>
		</div>
		<?php
	}

}