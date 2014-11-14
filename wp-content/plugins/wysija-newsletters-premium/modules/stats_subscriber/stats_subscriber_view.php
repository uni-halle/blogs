<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_subscriber_view extends WYSIJA_view_back {

	public function hook_subscriber_left($data) {
		// echo '<h3>'.sprintf(__('%1$s emails received.',WYSIJA),$data['emails_count']).'</h3>';
		if (empty($data['emails_count']))
			return;
		if (!empty($data['dataset']))
			foreach ($data['dataset'] as $index => $dataset) {
				$data['dataset'][$index]['container'] = 'chart'.md5($dataset['title'].rand());
				echo '<div id="'.$data['dataset'][$index]['container'].'" style="height:500px; width:100%"></div>';
			}
		?>
		<script type="text/javascript">
		<?php
		foreach ($data['dataset'] as $dataset) {
			//foreach ($dataset['columns'] as $column) {
			?>
						chartData = <?php echo json_encode($dataset['data']); ?>;
						//AmCharts.ready(function () {
						// PIE CHART
						chart = new AmCharts.AmPieChart();
						chart.dataProvider = chartData;
						chart.titleField = "label";
						chart.valueField = "stats";
						chart.outlineColor = "#FFFFFF";
						chart.outlineAlpha = 0.8;
						chart.outlineThickness = 2;
						chart.labelRadius = 10;
						chart.balloonText = "[[label]][[value]] ([[percents]]%)";
						chart.marginBottom = 0;
						chart.marginLeft = 0;
						chart.marginRight = 0;
						chart.marginTop = 0;
						chart.pieX = '50%';
						chart.pieY = '50%';
			<?php if (!empty($this->font_family)) { ?>
								chart.fontFamily = '<?php echo implode(',', $this->font_family); ?>';
			<?php } ?>
							chart.fontSize = <?php echo $this->font_size; ?>;
							chart.colors = ["<?php echo implode('", "', $this->get_all_colors()); ?>"];

							// Set pointer of balloon
							var balloon = chart.balloon;
							balloon.cornerRadius = 0;
							balloon.pointerWidth = 5;

							// WRITE
							chart.write('<?php echo $dataset['container']; ?>');
			<?php
			// }
			?>
					//});
			</script>
			<?php
		}
	}

}