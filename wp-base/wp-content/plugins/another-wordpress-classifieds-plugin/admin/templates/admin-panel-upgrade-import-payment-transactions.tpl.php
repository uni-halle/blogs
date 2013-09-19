<form id="upgrade-import-payment-transactions-form" class="awpcp-upgrade-form" data-action="<?php echo $action; ?>">

	<p><?php _ex('Before you can use AWPCP again we need to upgrade your database. This operation may take a few minutes, depending on the amount of information stored. Please press the Upgrade button shown below to start the process.', 'awpcp upgrade', 'AWPCP'); ?></p>

	<div class="progress-bar">
		<div class="progress-bar-value"></div>
	</div>

	<p class="submit">
		<input type="submit" value="Upgrade" class="button-primary" id="submit" name="submit">
	</p>
</form>

<?php $message = _x('Congratulations. AWPCP has been successfully upgraded. You can now access all features. <a href="%s">Click here to Continue</a>.', 'awpcp upgrade', 'AWPCP'); ?>
<p class="awpcp-upgrade-completed-message"><?php echo sprintf($message, $url); ?></p>
