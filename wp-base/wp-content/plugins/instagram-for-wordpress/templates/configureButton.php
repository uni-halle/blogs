<input type="button" value="Configure Widget" onclick="openConfigure<?php print $instance['token'] ?>();" class="simpleSetupButton button-primary">

<input type="button" value="Refresh Settings" class="simpleSetupButton button" id="refreshButton<?php print $instance['token'] ?>" onclick="refreshWidget<?php print $instance['token'] ?>();">

<script>
  if (!window.completedAction) {
    window.completedAction = false;
  }

  function openConfigure<?php print $instance['token'] ?>() {
    window._lbox = lightbox({
      content : '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/config/<?php print $instance['token'] ?>"></iframe>',
      frameCls : '',
      closeCallback: function() {
        refreshWidget<?php print $instance['token'] ?>();
      }      
    });
  }
  
  function detectPayment<?php print $instance['token'] ?>() {
    if (location.href.replace('auth=', '') != location.href && !completedAction) {
      window._lbox = lightbox({
        content : '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/setup/<?php print $instance['token'] ?>"></iframe>',
        frameCls : '',
        closeCallback: function() {
          refreshWidget<?php print $instance['token'] ?>();
        }      
      });     
      completedAction = true; 
    }  
  }

  function detectUpgrade<?php print $instance['token'] ?>() {
    if (location.href.replace('upgraded=<?php print $instance['token'] ?>', '') != location.href && !completedAction) {
      window._lbox = lightbox({
        content : '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/config/<?php print $instance['token'] ?>/subscription?waiting=1"></iframe>',
        frameCls : '',
        closeCallback: function() {
          refreshWidget<?php print $instance['token'] ?>();
        }      
      });      
      completedAction = true;
    }  
  }

  function refreshWidget<?php print $instance['token'] ?>() {
    jQuery('#refreshButton<?php print $instance['token'] ?>').parent().parent().find('input[type=submit]').click();
    jQuery('#setupButton<?php print $instance['token'] ?>').parent().parent().find('input[type=submit]').click();
  }
  
  jQuery(document).ready(function() {
    <?php
      if (!$instance['setup']) {
    ?>
      detectPayment<?php print $instance['token'] ?>();    
      var token = '<?php print $instance['token'] ?>';
      if (location.href.replace('widget=' + token, '') != location.href) {
        window._lbox = lightbox({
          content: '<iframe frameborder="0" border="0" src="http://wordpress.ink361.com/setup?widget=<?php print $instance['token'] ?>&step=2"></iframe>',
          frameCls: '',
          closeCallback: function() {
            refreshWidget<?php print $instance['token'] ?>();
          },
        });
      }
    <?php
      } else if (!$instance['settings']['waiting']) {
    ?>    
      detectUpgrade<?php print $instance['token'] ?>();
    <?php
      }
    ?>
  });
</script>

<?php include("message.php"); ?>
