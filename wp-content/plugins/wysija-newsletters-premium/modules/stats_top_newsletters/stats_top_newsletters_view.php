<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_top_newsletters_view extends WYSIJA_view_back {

	/**
	 *
	 * @param type $data
	  [module_name] => stats_top_newsletters
	  [top_newsletters] => Array
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
		foreach ($data['top_newsletters'] as $newsletter_type => $top_newsletters) {
		?>
		<div class="container-top-newsletters container" rel="<?php echo $data['module_name']; ?> <?php echo $newsletter_type; ?>">
			<h3 class="title"><?php echo ($newsletter_type === 'standard') ? __('Top newsletters', WYSIJA) : __('Top auto-responders', WYSIJA); ?></h3>
		<?php if ($top_newsletters['count'] == 0) {
			?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
				<?php
			}
			else {
				?>
				<table class="widefat fixed">
					<thead>
					<th class="check-column">&nbsp;</th>
					<th class="newsletter"><?php echo __('Newsletter', WYSIJA); ?></th>
					<th class="sent sortable sort-filter <?php echo $data['order_direction']['sent']; ?>" rel="sent"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Sent'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="opens sortable sort-filter <?php echo $data['order_direction']['opens']; ?>" rel="open"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Opens'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="clicks sortable sort-filter <?php echo $data['order_direction']['clicks']; ?>" rel="click"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Clicks'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="unsubscribes sortable sort-filter <?php echo $data['order_direction']['unsubscribes']; ?>" rel="unsubscribe"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Unsubscribes'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="rates" rel="rates"><?php echo __('Rates'); ?></th>
					<th class="lists" rel="sent_at"><?php echo __('Lists'); ?></th>
					<th class="sent_at" rel="sent_at"><?php echo __('Sent on'); ?></th>
					</thead>
					<tbody class="list:user user-list">
			<?php
			$i			  = 0;
			$alt			= false;
			$email_helper   = WYSIJA::get('email', 'helper');
			$numbers_helper = WYSIJA::get('numbers', 'helper');
			foreach ($top_newsletters['emails'] as $email) {
				$full_url  = $email_helper->getVIB($email);
				$css_class = $alt ? 'alternate' : '';
				$alt	   = !$alt;
				$i++;
				?>
							<tr class="<?php echo $css_class; ?>">
								<td><?php echo $i; ?></td>
								<td class="newsletter">
									<a href="<?php echo $full_url ?>" target="_blank" class="viewnews" title="<?php _e('Preview in new tab', WYSIJA) ?>">
				<?php echo $email['subject']; ?>
									</a>
									<br/>
									<a href="admin.php?page=wysija_campaigns&id=<?php echo $email['email_id'] ?>&action=viewstats" class="stats" target="_blank"><?php _e('See detailed stats', WYSIJA) ?></a>
								</td>
								<td>
				<?php
				// echo $email['sent'];
				// echo ' (';
				echo $data['show_sent_as_total_of_sent_newsletters'] ?
						($email['sent'] + $email['opens'] + $email['clicks']) :
						$numbers_helper->calculate_percetage($email['sent'], $email['total']).'%';
				// echo ')';
				?>
								</td>
								<td>
									<?php
									// echo $email['opens'];
									// echo ' (';
									echo $numbers_helper->calculate_percetage($email['opens'], $email['total']).'%';
									// echo ')';
									?>
								</td>
								<td>
									<?php
									// echo $email['clicks'];
									// echo ' (';
									echo $numbers_helper->calculate_percetage($email['clicks'], $email['total']).'%';
									// echo ')';
									?>
								</td>
								<td>
									<?php
									// echo $email['clicks'];
									// echo ' (';
									// echo $numbers_helper->calculate_percetage($email['unsubscribes'], $email['total']).'%';
									echo $email['unsubscribes_percent'].'%';
									// echo ')';
									?>
								</td>
								<td><?php $this->render_chart_of_rates($email); ?></td>
								<td>
									<?php
									if (!empty($email['lists'])) {
										echo '<ul>';
										$list_style = count($email['lists']) > 1 ? '- ' : '';
										foreach ($email['lists'] as $list_id => $list_name) {
											$list_id = $list_id; // not in use yet
											echo '<li>'.$list_style.$list_name.'</li>';
										}
									}
									?>
								</td>
								<td><?php echo $this->fieldListHTML_created_at($email['sent_at']); ?></td>
							</tr>
									<?php
								}
								?>
					</tbody>
				</table>
					<?php } ?>
					<?php
					$this->model->countRows = $top_newsletters['count'];
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
		}

		/**
		 * Render the chart of rates (unopens, opens, clicks)
		 * @param array $record Array( <br/>
		 * 			['sent'] => int
		 * 			['open'] => int,
		 * 			['clicks'] => int,
		 */
		protected function render_chart_of_rates(Array $record) {
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
		<?php if (!empty($this->font_family)) {
			?>
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

}