<script>
Shareaholic.click_objects['unverified_app_settings'] = {
  selector: Shareaholic.click_objects['app_settings'].selector,
  url: function(button) {
    url = first_part_of_url + 'verify'
      + '?verification_key=<?php echo $verification_key ?>'
      + '&embedded=true'
      + '&redirect_to='
      + encodeURIComponent(
        Shareaholic.click_objects['app_settings'].url(button)
      );
    return url;
  },
  callback: function(button) {
    Shareaholic.click_objects['app_settings'].callback(button);
  },
  close: Shareaholic.click_objects['app_settings'].close
}

Shareaholic.click_objects['unverified_general_settings'] = {
  selector: Shareaholic.click_objects['general_settings'].selector,
  url: function(button) {
    url = first_part_of_url + 'verify'
      + '?verification_key=<?php echo $verification_key ?>'
      + '&redirect_to='
      + encodeURIComponent(
        Shareaholic.click_objects['general_settings'].url(button)
      );
    return Shareaholic.click_objects['general_settings'].url(button);
  },
  callback: function(button) {
    Shareaholic.click_objects['general_settings'].callback(button);
  },
  close: Shareaholic.click_objects['general_settings'].close
}
</script>
