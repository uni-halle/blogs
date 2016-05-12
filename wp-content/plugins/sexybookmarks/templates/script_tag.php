<!-- This site is powered by Shareaholic - https://shareaholic.com -->
<script type='text/javascript' data-cfasync='false'>
  //<![CDATA[
    _SHR_SETTINGS = <?php echo json_encode($base_settings); ?>;
  //]]>
</script>
<script type='text/javascript' data-cfasync='false'
        src='<?php echo ShareaholicUtilities::asset_url('assets/pub/shareaholic.js') ?>'
        data-shr-siteid='<?php echo $api_key; ?>' async='async' <?php echo $overrides ?>>
</script>
