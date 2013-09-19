<div id="<?php echo $controller->container_id ?>" class="wrap">

	<div class="page-content">

		<?php $href = add_query_arg(array('action' => 'place-ad'), $controller->url); ?>
		<?php $place_ad = __('Place Ad', 'AWPCP'); ?>
		<h2 class="awpcp-page-header"><?php echo $controller->page_title ?><a class="button-primary alignright" title="<?php echo $place_ad ?>" href="<?php echo $href ?>" accesskey="s"><?php $place_ad ?></a></h2>

		<?php if (isset($controller->message)): ?>
		<div id="message" class="updated"><?php echo $controller->message ?></div>
		<?php endif ?>

		<div class="awpcp-main-content">

			<div id="lookup-ads-by" class="row clearfix">
				<div class="legend">
					<b><?php _e('Look Up Ad By', 'AWPCP') ?></b>
				</div>
				<div class="form">
					<form method="post">
					<?php $filter = !empty($filter) ? $filter : 'titdet' ?>
					<?php foreach($filter_by as $f => $label): ?>
						<input type="radio" value="<?php echo $f ?>"
							<?php echo ($f == $filter) ? 'checked="checked"' : '' ?>
							name="lookupadbychoices"><?php echo $label ?>
					<?php endforeach ?>
						<input type="text" value="<?php echo $query ?>" name="lookupadidortitle">
						<input type="hidden" value="lookupadby" name="action">
						<input class="button" type="submit" value="<?php _e('Look Up Ad', 'AWPCP'); ?>">
					</form>
				</div>
			</div>

			<div id="sort-ads-by" class="row clearfix">
				<div class="legend">
					<b><?php _e('Sort Ads By', 'AWPCP') ?></b>
				</div>
				<?php $href = add_query_arg($query_args, $controller->url); ?>
				<?php $href = remove_query_arg('action', $href); ?>
				<?php $links = array(); ?>
				<div class="form">
					<?php foreach ($sort_modes as $key => $used):
							$href2 = add_query_arg(array('sortby' => $key), $href);
							if (!$used) {
								$links[] = '<a href="' . $href2 . '">' . $sort_names[$key] . '</a>';
							} else {
								$links[] = '<b>' . $sort_names[$key] . '</b>';
							}
						  endforeach ?>
					<?php echo join(' | ', $links); ?>
				</div>
			</div>

			<?php /*<div id="pagination" class="row clearfix">
				<div class="pages">
				<?php if ($offset > 0) {
					$params = array_merge($query_args, array('offset' => $results));
					$href = add_query_arg($params, $url);
					echo '<a href="' . $href . '">&laquo;</a>&nbsp;';
				}
				for ($i = 1; $i <= $num_pages; $i++) {
					$params = array_merge($query_args, array('offset' => ($i - 1) * $results));
					$href = add_query_arg($params, $url);
					if ($offset == ($i - 1) * $results) {
						echo $i . '&nbsp;';
						$current_page = $i;
					} else {
						echo '<a href="' . $href . '">' . $i . '</a>&nbsp;';	
					}
				}
				if ($current_page < $num_pages) {
					$params = array_merge($query_args, array('offset' => ($num_pages - 1) * $results));
					$href = add_query_arg($params, $url);
					echo '<a href="' . $href . '">&raquo;</a>&nbsp;';
				} ?>
				</div>

				<form id="ad-management-panel-listings-results" 
					  action="<?php echo add_query_arg($query_args, $url) ?>" method="get">
					<input type="hidden" name="page" value="<?php echo $query_args['page'] ?>" />
					<select name="results" 
					        onchange="document.getElementById('ad-management-panel-listings-results').submit();">
						<?php foreach(array(5,10,20,30,40,50,60,70,80,90,100) as $i) {
							$checked = ($i == $results ? 'selected="selected"' : '');
							echo '<option value="'. $i . '" ' . $checked . '>' . $i . '</option>';
						} ?>
					</select>
				</form>
			</div>*/ ?>

			<?php echo $pager ?>

			<form class="bulk-delete-form" action="<?php echo $controller->url ?>" method="post">
			<input type="hidden" name="action" value="delete-selected" />
			<input type="button" class="button trash-selected" value="<?php _e('Delete Selected Ads', 'AWPCP'); ?>">

			<table class="widefat fixed">
				<thead>
					<tr>
						<th style="width:18px"><input type="checkbox" onclick="jQuery(this).toggleCheckboxes();" /></th>
						<th><?php _e("Ad Headline","AWPCP") ?></th>
						<th style="width:30%"><?php _e("Manage Ad", "AWPCP"); ?></th>
						<?php /*if ($charge_listing_fee): ?>
						<th><?php _e('Pay Status', 'AWPCP') ?></th>
						<?php endif*/ // needed in Classifieds->Listings, but not here ?> 
						<th><?php _e('Fee Plan', 'AWPCP') ?></th>
						<th><?php _e('Start Date', 'AWPCP') ?></th>
						<th><?php _e('End Date', 'AWPCP') ?></th>
						<th><?php _e('Renewed', 'AWPCP') ?></th>
						<?php /*<th></th><!-- featured head/conditional on module existence -->*/ ?>
						<th><?php _e('Status', 'AWPCP') ?></th>
						<th><?php _e('Payment Status', 'AWPCP') ?></th>
						<?php if ($is_admin_user): ?>
						<th><?php _e('Owner', 'AWPCP') ?></th>
						<?php endif ?>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($items as $item): ?>
					<?php $params = array_merge($query_args, array('id'=>$item->ad_id)) ?>
					<?php $href = add_query_arg($params, $controller->url) ?>
					<?php $href2 = url_showad($item->ad_id); ?>
					<tr id="ad-<?php echo $item->ad_id ?>" data-id="<?php echo $item->ad_id ?>">

						<th scope="row">
							<input type="checkbox" name="selected[]" value="<?php echo $item->ad_id ?>" />
						</th>
						<td class="displayadscell" width="200">
							<a href="<?php echo $href2 ?>" target="_blank"><?php echo stripslashes($item->ad_title) ?></a>
						</td>

						<td>
							<div class="row-actions" style="visibility: visible">
								<a href="<?php echo $href2 ?>" target="_blank"><?php _e('View', 'AWPCP'); ?></a> | 
								<?php $href2 = add_query_arg(array('action'=>'edit-ad'),$href) ?>
								<a href="<?php echo $href2 ?>"><?php _e('Edit', 'AWPCP'); ?></a> | 
								<?php $href2 = add_query_arg(array('action'=>'delete-ad'),$href) ?>
								<span class="trash"><a href="<?php echo $href2 ?>"><?php _e('Delete', 'AWPCP'); ?></a></span>

								<?php if ($item->is_about_to_expire()): ?>
								<?php $href2 = add_query_arg(array('action'=>'renew-ad'), $href) ?>
								| <a href="<?php echo $href2 ?>"><?php _e('Renew Ad', 'AWPCP'); ?></a>
								<?php endif ?>

								<?php if ($allow_images): ?>
								<?php $href2 = add_query_arg(array('action'=>'add-image'),$href) ?>
								 | <a href="<?php echo $href2 ?>"><?php _e('Add Image', 'AWPCP'); ?></a>
								<?php $href2 = add_query_arg(array('action'=>'manage-images'),$href) ?>
									<?php if (($images = $item->get_total_images_uploaded()) > 0): ?>
								 | <a href="<?php echo $href2 ?>"><?php _e('Manage Images', 'AWPCP'); ?></a> (<?php echo $images ?>)
									<?php endif ?>
								<?php endif ?>
							</div>
						</td>
						<?php /*if ($charge_listing_fee): ?>
						<td>
							<?php $href2 = add_query_arg(array('action'=>'cps', 'changeto'=>'Completed'),$href) ?>
							<a href="<?php echo $href2 ?>"><?php _e('Pending', 'AWPCP') ?> <?php _e('Complete', 'AWPCP'); ?></a>
						</td>
						<?php endif*/ ?>
						<td><?php echo $item->get_fee_plan_name() ?></td>
						<td><?php echo $item->get_start_date() ?></td>
						<td><?php echo $item->get_end_date() ?></td>
						<td><?php echo $item->get_renewed_date() ?></td>
						<?php /*<td><!-- featured note --></td>*/ ?>

						<td><?php echo $item->disabled == 0 ? __('Enabled', 'AWPCP') : __('Disabled', 'AWPCP') ?></td>

						<?php if ($item->payment_status): ?>
						<td><?php echo $item->payment_status ?></td>
						<?php else: ?>
						<td><?php echo __('N/A', 'AWPCP') ?></td>
						<?php endif ?>

						<?php $user = get_userdata($item->user_id); ?>
						<?php if ($is_admin_user): ?>
						<th><?php echo is_object($user) ? $user->user_login : '-' ?></th>
						<?php endif ?>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
			</form>
		</div>

	</div>
</div>