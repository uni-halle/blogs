<form name="shareaholic_settings" method="post" action="<?php echo $action; ?>">
  <input type="hidden" name="already_submitted" value="Y">
  <p>API Key:<input type="text" name="shareaholic_api_key" value="<?php echo $api_key; ?>" size="30"></p>
  <p class="submit">
  <input type="submit" name="Submit" value="Update Options" />
  </p>
</form>
