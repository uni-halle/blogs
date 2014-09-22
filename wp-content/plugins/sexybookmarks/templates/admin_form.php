<form name="shareaholic_settings" method="post" action="<?php echo $action; ?>">
  <input type="hidden" name="already_submitted" value="Y">
  <p>API Key:<input type="text" name="shareaholic_api_key" value="<?php echo $api_key; ?>" size="30"></p>
  <p class="submit">
  <input type="submit" name="Submit" value="Update Options" />
  </p>
</form>

<form name='verify_api_key' method='post' action='<? echo Shareaholic::API_URL; ?>/v2/verify_api_key'>
  <input type="hidden" name="api_key" value="<?php echo $api_key; ?>">
  <input type="hidden" name="hashed_key" value="<?php echo $hashed_key; ?>">
  <input type='submit' name='Submit' value='Verify API key' />
</form>
