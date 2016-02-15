<script type="text/javascript">
	var	timeline_json = <?php echo $timeline_object; ?>;
	window.timeline<?php echo $div_id; ?> = new TL.Timeline("timeline-<?php echo $div_id; ?>", timeline_json);
</script>