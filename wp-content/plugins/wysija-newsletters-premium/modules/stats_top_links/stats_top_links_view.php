<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_top_links_view extends WYSIJA_view_back {

	/**
	 *
	 * @param Array $data
	  [module_name] => stats_top_links
	  [top_links] => Array
	  (
	  [count] => 10
	  [links] => Array
	  (
	  [5] => Array
	  (
	  [clicks] => 17
	  [url_id] => 5
	  [url] => http://domain.com/path/to/url5
	  [email_id] => 3
	  [email_subject] => Email subject 1
	  [lists] => Array
	  (
	  [2] => List 2's name
	  [1] => List 1's name
	  )

	  )

	  [1] => Array
	  (
	  [clicks] => 12
	  [url_id] => 1
	  [url] => [url] => http://domain.com/path/to/url1
	  [email_id] => 5
	  [email_subject] => Email subject 5
	  [lists] => Array
	  (
	  [3] => List 3's name
	  )

	  )
	  )

	  )

	  [order_direction] => Array
	  (
	  [clicks] =>
	  )
	 */
	public function hook_stats($data) {
		?>
		<div class="container-top-links container" rel="<?php echo $data['module_name']; ?>">
			<h3 class="title"><?php echo __('Top links', WYSIJA); ?></h3>
			<?php if ($data['top_links']['count'] == 0) { ?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
			<?php }
			else { ?>
				<table class="widefat fixed">
					<thead>
					<th class="check-column">&nbsp;</th>
					<th class="link_column"><?php echo __('Link', WYSIJA); ?></th>
					<!--th class="sortable sort-filter <?php echo $data['order_direction']['clicks']; ?>" rel="click">
						<a href="javascript:void(0);" class="orderlink">
							<span><?php echo __('Clicks'); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th --><!-- Disable sortby by link -->
					<th class="click_column <?php echo $data['order_direction']['clicks']; ?>" rel="click">
						<span><?php echo __('Clicks'); ?></span>
						</a>
					</th>
					<th><?php echo __('Newsletters', WYSIJA); ?></th>
					<th><?php echo __('Lists', WYSIJA); ?></th>
					</thead>
					<tbody class="list:user user-list">
						<?php
						$i				   = 1;
						$alt				 = false;
						$helper_extend_links = WYSIJA::get('extend_links', 'helper', false, WYSIJANLP);
						$email_helper		= WYSIJA::get('email', 'helper');
						foreach ($data['top_links']['links'] as $link) {
							?>
							<tr class="<?php echo $alt ? 'alternate' : '';
							$alt = !$alt;
							?>">
								<td><?php echo $i;
							$i++;
							?></td>
								<td>
									<?php
									add_filter('wysija_link', array( $helper_extend_links, 'render_link' ), 11, 2);
									echo apply_filters('wysija_link', '', $link['url']);
									?>
								</td>
								<td><?php echo $link['clicks']; ?></td>
								<td>
									<?php
									if (!empty($link['emails'])) {
										$email_subjects = array( );
										foreach ($link['emails'] as $emails) {
											$email_subjects[] = $emails['subject'];
										}
										$count			= 0;
										$alt_text		 = implode(', ', $email_subjects);
										foreach ($link['emails'] as $email_id => $emails) {
											$count++;
											// if ($count++ != 0)
											// echo ', ';
											if ($count > 5)
												break;
											$link_view = $email_helper->getVIB(array( 'email_id' => $email_id ));
											echo '<a class="newsletter" href="'.$link_view.'" target="_blank" title="'.$alt_text.'">- '.trim($emails['subject']).'</a>';
										}
									}
									?>
								</td>
								<td><?php
					if (!empty($link['lists'])) {
						$lists = array_values($link['lists']);
						echo implode(', ', $lists);
					}
									?></td>
							</tr>
				<?php
			}
			?>
					</tbody>
				</table>
			<?php } ?>
			<?php
			$this->model->countRows = $data['top_links']['count'];
			if (empty($this->viewObj))
				$this->viewObj = new stdClass();
			$this->viewObj->msgPerPage = __('Show', WYSIJA).':';
			$this->viewObj->title = '';
			$this->limitPerPage();
			?>
		</div>
		<?php
	}

}