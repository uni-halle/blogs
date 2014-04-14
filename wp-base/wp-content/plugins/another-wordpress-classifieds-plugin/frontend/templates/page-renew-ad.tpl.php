<div id="classiwrapper">

	<h2><?php _e('Renew Ad', 'AWPCP') ?></h2>

<?php if (in_array($step, array('renew-ad', 'error', 'post-checkout'))): ?>

	<?php echo $content ?>

<?php elseif ($step == 'checkout'): ?>

	<?php foreach ($header as $part): ?>
	<p><?php echo $part ?></p>
	<?php endforeach ?>

	<?php $msg = __('Please click the payment button below to proceed with Payment for your Ad renewal. You will be asked to pay %s.', 'AWPCP') ?>
	<p><?php echo sprintf($msg, $amount) ?></p>
	<?php echo $content ?>

<?php endif ?>

</div>