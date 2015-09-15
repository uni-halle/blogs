<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_new_subscribers_view extends WYSIJA_view_back {

	/**
	 * Max lenght of list names which will be used by Amchart
	 * @var int
	 */
	protected $list_name_max_length = 45;

	/**
	 *
	 * @param type $data
	  [module_name] => statsnewsubscribers
	  [new_subscribers] => Array
	  (
	  [0] => Array
	  (
	  [time] => 2002,1,1
	  [List 1's name] => 4
	  [List 2's name] => 2
	  [List 3's name] => 7
	  )
	  ...
	  [n] => Array
	  (
	  [time] => 2002,1,1
	  [List 1's name] => 4
	  [List 2's name] => 2
	  [List 3's name] => 7
	  )
	  )

	  [list_names] => Array
	  (
	  [0] => List 1's name
	  [1] => List 2's name
	  [2] => List 3's name
	  )
	 * @return type
	 */
	public function hook_stats($data) {
		$div_id = 'chartdiv'.rand(1, 1000000); // make sure id is unique, even this hook is called more than once
		?>
		<div class="container-<?php echo $div_id; ?> container" rel="<?php echo $data['module_name']; ?>">
			<h3 class="title"><?php echo $data['new_subscribers']['total']; ?> <?php echo $data['new_subscribers']['total'] > 1 ? __('new subscribers', WYSIJA) : __('new subscriber'); ?></h3>
			<?php if (!empty($data['new_subscribers']['time'])) { ?>
				<div id="<?php echo $div_id; ?>" class="stats-open-rate" style="width: 100%; height: 400px; display:block"></div>
			<?php }
			else { ?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
		<?php } ?>
		</div>
		<?php if (empty($data['new_subscribers']['time'])) return; ?>
		<script type="text/javascript">
			dateFormat = '<?php echo $data['js_date_format'] ?>';
			chartData = <?php echo json_encode($data['new_subscribers']['time']); ?>;
			divId = '<?php echo $div_id; ?>';

			jQuery(chartData).each(function(index, element) {
				// format Datetime
				if (typeof jQuery.browser.msie !== 'undefined' && jQuery.browser.msie) {
					_tmp = chartData[index].time.split(',');
					_year = typeof _tmp[0] !== 'undefined' ? _tmp[0] : 1970;
					_month = typeof _tmp[1] !== 'undefined' ? _tmp[1] : 1;
					_day = typeof _tmp[2] !== 'undefined' ? _tmp[1] : 1;
					chartData[index].time = new Date(_year, _month, _day);
				}
				else {
					if ('WebkitAppearance' in document.documentElement.style) chartData[index].time = chartData[index].time.replace(/,/g, "/");
					chartData[index].time = new Date(chartData[index].time);
				}
				chartData[index].balloonText = jQuery.datepicker.formatDate(dateFormat, chartData[index].time);
			});
			//AmCharts.ready(function (){
			// SERIAL CHART
			chart = new AmCharts.AmSerialChart();
			chart.dataProvider = chartData;
			chart.categoryField = "time";
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
				chart.categoryAxis.parseDates = true;
				chart.categoryAxis.minPeriod = 'DD';
				chart.categoryAxis.dateFormats = [
					{period: 'fff', format: 'JJ:NN:SS'},
					{period: 'ss', format: 'JJ:NN:SS'},
					{period: 'mm', format: 'JJ:NN'},
					{period: 'hh', format: 'JJ:NN'},
					{period: 'DD', format: 'YYYY/MM/DD'},
					{period: 'MM', format: 'YYYY/MM'},
					{period: 'YYYY', format: 'YYYY'}
				];

				// value
				var valueAxis = new AmCharts.ValueAxis();
				valueAxis.maximum = <?php echo $data['max_value']; ?>;
				chart.addValueAxis(valueAxis);

		<?php
		foreach ($data['list_names'] as $list_name) {
			?>
					// Graph
					graph = new AmCharts.AmGraph();
					// graph.type = "smoothedLine"; // this line makes the graph smoothed line.
					graph.balloonText = "[[balloonText]]  : [[value]]";
					graph.bullet = "round";
					graph.bulletSize = 5;
					graph.lineThickness = 2;
					// graph.valueField = "<?php // echo htmlentities($list_name);  ?>";
					// graph.title = "<?php //echo htmlentities($list_name);  ?>";
					graph.valueField = "<?php echo $list_name; ?>";
					graph.title = "<?php echo $this->format_list_name($list_name); ?>";
					graph.lineColor = "<?php echo $this->get_random_color(); ?>";

					chart.addGraph(graph);
			<?php
		}
		?>

			// LEGEND
			var legend = new AmCharts.AmLegend();
			legend.markerType = "circle";
			chart.addLegend(legend);

			// WRITE
			chart.write(divId);

			//});
		</script>
		<?php
	}

	/**
	 * Format a list name, in case it's too long
	 * @param string $list_name
	 * @return string
	 */
	protected function format_list_name($list_name) {
		if (strlen($list_name) > $this->list_name_max_length) {
			$list_name = substr($list_name, 0, $this->list_name_max_length - 3 - 1).'...';
		}
		return $list_name;
	}

}