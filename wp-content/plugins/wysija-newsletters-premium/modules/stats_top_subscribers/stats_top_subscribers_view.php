<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_module_view_stats_top_subscribers_view extends WYSIJA_view_back {

	public function hook_stats($data) {
		?>
		<div class="container-top-subscribers container" rel="<?php echo $data['module_name']; ?>">
			<h3 class="title"><?php echo __('Top subscribers', WYSIJA); ?></h3>
			<?php if ($data['top_subscribers']['count'] == 0) { ?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
			<?php }
			else { ?>
				<table class="widefat fixed">
					<thead>
					<th class="check-column">&nbsp;</th>
					<th class="subscriber"><?php echo __('Subscriber', WYSIJA); ?></th>
					<th class="clicks sortable sort-filter <?php echo $data['order_direction']['clicks']; ?>" rel="click"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Clicks'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="opens sortable sort-filter <?php echo $data['order_direction']['opens']; ?>" rel="open"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Opens'); ?></span><span class="sorting-indicator"></span></a></th>
					<th class="lists"><?php echo __('Lists', WYSIJA); ?></th>
					<th class="date"><?php echo __('Subscribed since', WYSIJA); ?></th>
					</thead>
					<tbody class="list:user user-list">
						<?php
						$i			= 1;
						$alt		  = false;
						$links_helper = WYSIJA::get('links', 'helper');
						foreach ($data['top_subscribers']['subscribers'] as $subscriber) {
							?>
							<tr class="<?php echo $alt ? 'alternate' : '';
							$alt = !$alt; ?>">
								<td class="check-column"><?php echo $i++; ?></td>
								<td class="username column-username">
									<table>
										<tr>
											<td class="avatar"><?php echo get_avatar($subscriber['email'], 58); ?></td>
											<td>
												<strong><?php echo $subscriber['email']; ?></strong>
				<?php echo $subscriber['firstname'].' '.$subscriber['lastname']; ?>
												<br />
												<a href="<?php echo $links_helper->detailed_subscriber($subscriber['user_id']); ?>" target='_blank' class='submitedit'><?php echo __('View stats & edit', WYSIJA); ?></a>
											</td>
										</tr>
									</table>
								</td>
								<td><?php echo empty($subscriber['clicks']) ? 0 : $subscriber['clicks']; ?></td>
								<td><?php echo empty($subscriber['opens']) ? 0 : $subscriber['opens']; ?></td>
								<td>
									<?php
									if (!empty($subscriber['lists'])) {
										$lists = array_values($subscriber['lists']);
										echo implode(', ', $lists);
									}
									?>
								</td>
								<td><?php echo $this->fieldListHTML_created_at($subscriber['created_at']); ?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			<?php } ?>
			<?php
			$this->model->countRows = $data['top_subscribers']['count'];
			if (empty($this->viewObj))
				$this->viewObj = new stdClass();
			$this->viewObj->msgPerPage = __('Show', WYSIJA).':';
			$this->viewObj->title = '';
			$this->limitPerPage();
			?>
			<div class="cl"></div>
			<style>
				.submitedit {
					display: none;
				}
				.user-list tr:hover .submitedit{
					display:inline;
				}
			</style>
		</div>
		<?php
	}

	public function hook_subscriber_bottom($data) {
		?>
		<div class="container-top-subscribers container" rel="<?php echo $data['module_name']; ?>">
			<h3 class="title"><?php echo __('Opened newsletters', WYSIJA); ?></h3>
			<?php if (empty($data['opened_newsletters'])) { ?>
				<div class="notice-msg updated inline"><ul><li><?php echo $data['messages']['data_not_available']; ?></li></ul></div>
		<?php }
		else { ?>
				<table class="widefat fixed">
					<thead>
					<th class="check-column">&nbsp;</th>
					<th><?php echo __('Newsletter', WYSIJA); ?></th>
					<th><?php echo __('Link', WYSIJA); ?></th>
					<th class="sortable sort-filter <?php echo $data['order_direction']['clicks']; ?>" rel="click"><a href="javascript:void(0);" class="orderlink"><span><?php echo __('Clicks'); ?></span><span class="sorting-indicator"></span></a></th>
					<th><?php echo __('Device', WYSIJA); ?></th>
					<th><?php echo __('Date', WYSIJA); ?></th>
					</thead>
					<tbody class="list:user user-list">
						<?php
						$i			= 1;
						$alt		  = false;
						$email_helper = WYSIJA::get('email', 'helper');
						foreach ($data['opened_newsletters'] as $email_id => $email) {
							$full_url = $email_helper->getVIB($email);
							if (empty($email['urls'])) {
								?>
								<tr class="<?php echo $alt ? 'alternate' : '';
								$alt = !$alt;
								?>">
									<td><?php echo $i;
								$i++;
								?></td>
									<td>
										<a href="<?php echo $full_url ?>" target="_blank" class="viewnews" title="<?php _e('Preview in new tab', WYSIJA) ?>">
					<?php echo $email['subject']; ?>
										</a>
									</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php echo __('N/A', WYSIJA); ?></td>
									<td><?php echo $this->fieldListHTML_created_at($email['sent_at']); ?></td>
								</tr>
								<?php
							}
							else {
								$validation_helper = WYSIJA::get('validation', 'helper');
								$css_class		 = 'stats-url-link';
								if (!$data['is_premium']) {
									$helper_licence = WYSIJA::get('licence', 'helper');
									$url_checkout   = $helper_licence->get_url_checkout('subscriber_stats');
								}

								foreach ($email['urls'] as $url) {

									// @todo: move to a helper, see view/subscribers.php::subscribers_stats
									if ($data['is_premium']) {
										$label = preg_replace('/^http[s]?:\/\//', '', $url['url']); //remove http://, https://
										if ($validation_helper->isUrl($url['url'])) {
											$label = '<a href="'.$url['url'].'" target="_blank" class="'.$css_class.'">'.$label.'</a>';
										}
									}
									else {
										$label = '<p>';
										$label.= str_replace(
												array( '[link]', '[/link]' ), array( '<a title="'.__('Get Premium now', WYSIJA).'" target="_blank" href="'.$url_checkout.'">', '</a>' ), __("Get [link]MailPoet Premium[/link] to see the link.", WYSIJA));
										$label.= '</p>';
									}
									?>
									<tr class="<?php echo $alt ? 'alternate' : '';
						$alt = !$alt;
						?>">
										<td><?php echo $i;
						$i++;
						?></td>
										<td>
											<a href="<?php echo $full_url ?>" target="_blank" class="viewnews" title="<?php _e('Preview in new tab', WYSIJA) ?>">
									<?php echo $email['subject']; ?>
											</a>
										</td>
										<td><?php echo $label; ?></td>
										<td><?php echo $url['number_clicked']; ?></td>
										<td><?php echo __('N/A', WYSIJA); ?></td>
										<td><?php echo $this->fieldListHTML_created_at($email['sent_at']); ?></td>
									</tr>
						<?php
					}
				}
			}
			?>
					</tbody>
				</table>
		<?php } ?>
			<div class="cl"></div>
		</div>
		<?php
	}

}