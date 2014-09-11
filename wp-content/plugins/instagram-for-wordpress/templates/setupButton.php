<input type="button" value="Setup Widget" onclick="openSetup<?php print $instance['token'] ?>();" class="simpleSetupButton button-primary" id="setupButton<?php print $instance['token'] ?>">

<script>
  function openSetup<?php print $instance['token'] ?>() {      
    lightbox({
      content : '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/setup?widget=<?php print $instance['token'] ?>&referring=' + escape(location.href) + '"></iframe>',
      frameCls : '',
      closeCallback: function() {
        jQuery('#setupButton<?php print $instance['token'] ?>').parent().parent().find('input[type=submit]').click();      
      }      
    });
  }
  
  function detectPayment<?php print $instance['token'] ?>() {
    if (location.href.replace('auth=', '') != location.href || location.href.replace('wpInstall=', '') != location.href) {
      window._lbox = lightbox({
        content : '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/setup/<?php print $instance['token'] ?>"></iframe>',
        frameCls : '',
        closeCallback: function() {
          refreshWidget<?php print $instance['token'] ?>();
        }
      });
    }
  }

  function refreshWidget<?php print $instance['token'] ?>() {
    jQuery('#refreshButton<?php print $instance['token'] ?>').parent().parent().find('input[type=submit]').click();
    jQuery('#setupButton<?php print $instance['token'] ?>').parent().parent().find('input[type=submit]').click();
  }

  jQuery(document).ready(function() {
    <?php
      if (!$instance['setup']) {
        if ($instance['waiting']) {
    ?>
          detectPayment<?php print $instance['token'] ?>();
    <?php
        }
    ?>
      var token = '<?php print $instance['token'] ?>';
      if (location.href.replace('widget=' + token, '') != location.href) {
        window._lbox = lightbox({
          content: '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/setup?widget=<?php print $instance['token'] ?>&step=2"></iframe>',
          frameCls: '',
          closeCallback: function() {
            refreshWidget<?php print $instance['token'] ?>();
          }
        });
      }
    <?php
      }
    ?>
  });
</script>

<?php include("message.php"); ?>
