<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_newsletter_view extends WYSIJA_view_back {

	public function hook_newsletter_top($data) {
		if (empty($data['emails_count']))
			return;
		if (!empty($data['dataset']))
			foreach ($data['dataset'] as $index => $dataset) {
				$data['dataset'][$index]['container'] = 'chart'.md5($dataset['title'].rand());
				echo '<div id="'.$data['dataset'][$index]['container'].'" style="height:300px;" class="left"></div>';
			}
		?>
		<script type="text/javascript">
		<?php
		foreach ($data['dataset'] as $dataset) {
			?>
					//chart.draw(dataTable, options);
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
					chart.marginLeft = 0;
					chart.marginTop = 0;
					chart.balloonText = "[[label]][[value]] ([[percents]]%)";
					chart.colors = ["<?php echo implode('", "', $this->get_all_colors()); ?>"];
			<?php if (!empty($this->font_family)) { ?>
						chart.fontFamily = '<?php echo implode(',', $this->font_family); ?>';
			<?php } ?>
					chart.fontSize = <?php echo $this->font_size; ?>;

					// Set pointer of balloon
					var balloon = chart.balloon;
					balloon.cornerRadius = 0;
					balloon.pointerWidth = 5;

					// WRITE
					chart.write('<?php echo $dataset['container']; ?>');
			<?php
		}
		?>
		</script>
		<?php
	}

}