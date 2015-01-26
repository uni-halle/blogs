<div class='error'>
  <p><strong>Shareaholic now supports Shortcodes! Yay!</strong> Please upgrade your theme to use the Shareaholic shortcode by <strong><a href="http://wordpress.org/plugins/shareaholic/installation/" target="_new">following these installation instructions</a></strong>.</p>
  <dl>
  <?php foreach($deprecation_warnings as $function => $places) { ?>
    <dt>
    <code><?php echo $function ?>()</code> <?php echo sprintf(__('will be deprecated SOON. Please update the code found in the following files at your convenience:', 'shareaholic')); ?></dt>
    <?php foreach($places as $file => $numbers) { ?>
      <dd>
      <?php echo $file ?> at line(s) <?php echo implode(', ', $numbers) ?><br /><br />
      </dd>
    <?php } ?>
  <?php } ?>
  </dl>
</div>

 