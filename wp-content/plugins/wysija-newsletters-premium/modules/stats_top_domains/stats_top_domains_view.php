<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_top_domains_view extends WYSIJA_view_back {

	/**
	 *
	 * @param type $data
	  [module_name] => stats_top_domains
	  [top_domains] => Array
	  (
	  [domains] => Array
	  (
	  [0] => Array
	  (
	  [domain] => orange.fr
	  [sent] => 33
	  [opens] => 33
	  [clicks] => 9
	  )

	  [1] => Array
	  (
	  [domain] => gmail.com
	  [sent] => 20
	  [opens] => 19
	  [clicks] => 2
	  )

	  [n] => Array
	  (
	  [domain] => wanadoo.fr
	  [sent] => 17
	  [opens] => 17
	  [clicks] => 10
	  )
	  )

	  [count] => 418
	  )

	  [order_direction] => Array
	  (
	  [sent] =>
	  [opens] =>
	  [clicks] =>
	  )
	 */
	public function hook_stats($data) {
		?>
		<div class="container-top-domains container" rel="<?php echo $data['module_name']; ?>">
			<h3 class="title"><?php echo __('Top domains', WYSIJA); ?></h3>
			<?php if ($data['top_domains']['count'] == 0) { ?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
			<?php }
			else { ?>
				<table class="widefat fixed">
					<thead>
					<th class="check-column">&nbsp;</th>
					<th><?php echo __('Domain', WYSIJA); ?></th>
					<th class="sortable sort-filter <?php echo $data['order_direction']['sent']; ?>" rel="sent">
						<a href="javascript:void(0);" class="orderlink">
							<span><?php echo __('Sent'); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th class="sortable sort-filter <?php echo $data['order_direction']['opens']; ?>" rel="open"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Opens'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="sortable sort-filter <?php echo $data['order_direction']['clicks']; ?>" rel="click"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Clicks'); ?></span><span class="sorting-indicator"></span></a></th>
					<?php if ($data['show_rates']) { ?>
						<th class="rates" rel="rates"><?php echo __('Rates'); ?></th>
			<?php } ?>
					</thead>
					<tbody class="list:user user-list">
						<?php
						$i   = 1;
						$alt = false;
						foreach ($data['top_domains']['domains'] as $domain) {
							?>
							<tr class="<?php echo $alt ? 'alternate' : '';
							$alt = !$alt;
							?>">
								<td><?php echo $i;
							$i++;
							?></td>
								<td><?php echo $domain['domain']; ?></td>
								<td>
								<?php echo $data['show_sent_as_total_of_sent_newsletters'] ? $domain['sent'] + $domain['opens'] + $domain['clicks'] : $domain['sent']; ?>
								</td>
								<td><?php echo !empty($domain['total']) ? round(($domain['opens'] / $domain['total']) * 100,0) : 0; ?>%</td>
								<td><?php echo !empty($domain['total']) ? round(($domain['clicks'] / $domain['total']) * 100,0) : 0; ?>%</td>
							<?php if ($data['show_rates']) { ?>
									<td><?php $this->render_chart_of_rates($domain); ?></td>
							<?php } ?>
							</tr>
				<?php
			}
			?>
					</tbody>
				</table>
			<?php } ?>
			<?php
			$this->model->countRows = $data['top_domains']['count'];
			if (empty($this->viewObj))
				$this->viewObj = new stdClass();
			$this->viewObj->msgPerPage = __('Show', WYSIJA).':';
			$this->viewObj->title = '';
			$this->limitPerPage();
			?>
			<div class="cl"></div>
		</div>
		<?php
	}

	/**
	 * Render the chart of rates (unopens, opens, clicks)
	 * @param array $record Array( <br/>
	 * 			['sent'] => int
	 * 			['open'] => int,
	 * 			['clicks'] => int,
	 */
	protected function render_chart_of_rates(Array $record) {
		$is_pie_chart = false;
		if ($is_pie_chart)
			$this->render_pie_chart($record);
		else
			$this->render_stacked_chart($record);
	}

	protected function render_pie_chart(Array $record) {
		// prepare data of PieChart
		$data = array(
			array(
				'label' => __('Clicked', WYSIJA),
				'stats' => !empty($record['clicks']) ? (int)$record['clicks'] : 0
			),
			array(
				'label' => __('Opened', WYSIJA),
				'stats' => !empty($record['opens']) ? (int)$record['opens'] : 0
			),
			array(
				'label' => __('Unopened', WYSIJA),
				'stats' => !empty($record['sent']) ? (int)$record['sent'] : 0
			)
		);

		$container = 'top-newsletter-'.md5(rand().time()); // the Id of container element
		echo '<div id="'.$container.'" style="min-height:150px; width:100%"></div>';
		?>
		<script type="text/javascript">
			chartData = <?php echo json_encode($data); ?>;
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
			chart.hideLabelsPercent = 1;
			chart.labelsEnabled = false;
			//	    chart.balloonText = "[[label]][[value]] ([[percents]]%)";
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
			chart.write('<?php echo $container; ?>');
		</script>
		<?php
	}

	protected function render_stacked_chart($record) {

		// prepare data of Stacked chart
		if (!empty($record['total'])) {
			$record['clicks'] = !empty($record['clicks']) ? round(($record['clicks']/$record['total'])*100,0) : 0;
			$record['opens'] = !empty($record['opens']) ? round(($record['opens']/$record['total']) * 100,0) : 0;
			$record['sent'] = !empty($record['sent']) ? round(($record['sent']/$record['total'])*100,0) : 0;
		} else {
			$record['clicks'] = $record['opens'] = $record['sent'] = 0;
		}

		$container = 'top-newsletter-'.md5(rand().time()); // the Id of container element
		echo '<div id="'.$container.'" style="min-height:50px; width:100%"></div>';
		?>
		<script type="text/javascript">
			chartData = [<?php echo json_encode($record); ?>];
			// SERIAL CHART
			var chart = new AmCharts.AmSerialChart();
			chart.dataProvider = chartData;
			chart.categoryField = "domain";
			chart.marginLeft = -10;
			chart.height = '35';
			chart.marginTop = 0;
			chart.fontSize = 14;
			chart.startDuration = 1;
			chart.plotAreaFillAlphas = 0.2;
			chart.rotate = true;

			var balloon = chart.balloon;
			balloon.fontSize = 10;
			balloon.showBullet = true;
			balloon.borderAlpha = 0;
			balloon.fillAlpha=0;
			balloon.cornerRadius = 0;
			balloon.horizontalPadding=0;
			balloon.verticalPadding=0;

			// AXES
			// category
			var categoryAxis = chart.categoryAxis;
			categoryAxis.gridPosition = "start";
			categoryAxis.axisColor = "#DADADA";
			categoryAxis.axisAlpha = 0;
			categoryAxis.gridAlpha = 0;
			categoryAxis.labelsEnabled = false;

			// value
			var valueAxis = new AmCharts.ValueAxis();
			// valueAxis.stackType = "3d"; // This line makes chart 3D stacked (columns are placed one behind another)
			valueAxis.axisColor = "#DADADA";
			valueAxis.stackType = 'regular';
			valueAxis.unit = "%";
			valueAxis.labelsEnabled = false;
			valueAxis.axisAlpha = 0;
			valueAxis.gridAlpha = 0;
			valueAxis.maximum = 100;
			chart.addValueAxis(valueAxis);

			// GRAPHS
			// first graph
			var graph1 = new AmCharts.AmGraph();
			graph1.valueField = "clicks";
			graph1.type = "column";
			graph1.lineAlpha = 0;
			graph1.fillAlphas = 1;
			graph1.balloonText = "<?php echo __('Clicked', WYSIJA); ?>: [[value]]";
			graph1.stackable = true;
			graph1.lineColor = "<?php echo $this->get_random_color(); ?>";
			chart.addGraph(graph1);



			// second graph
			var graph2 = new AmCharts.AmGraph();
			graph2.valueField = "opens";
			graph2.type = "column";
			graph2.lineAlpha = 0;
			graph2.fillAlphas = 1;
			graph2.balloonText = "<?php echo __('Opened', WYSIJA); ?>: [[value]]";
			graph2.stackable = true;
			graph2.lineColor = "<?php echo $this->get_random_color(); ?>";
			chart.addGraph(graph2);

			// second graph
			var graph3 = new AmCharts.AmGraph();
			graph3.valueField = "sent";
			graph3.type = "column";
			graph3.lineAlpha = 0;
			graph3.fillAlphas = 1;
			graph3.balloonText = "<?php echo __('Unopened', WYSIJA); ?>: [[value]]";
			graph3.stackable = true;
			graph3.lineColor = "<?php echo $this->get_random_color(); ?>";
			chart.addGraph(graph3);

		<?php if (!empty($this->font_family)) { ?>
				// chart.fontFamily = '<?php echo implode(',', $this->font_family); ?>';
		<?php } ?>
			chart.fontSize = <?php echo $this->font_size; ?>;
			chart.colors = ["<?php echo implode('", "', $this->get_all_colors()); ?>"];

			// Set pointer of balloon
			var balloon = chart.balloon;
			balloon.cornerRadius = 0;
			balloon.pointerWidth = 5;

			// WRITE
			chart.write('<?php echo $container; ?>');
		</script>
		<?php
		$this->reset_color();
	}

}