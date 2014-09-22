<label>
  <input type='checkbox' name='shareaholic[disable_share_buttons]'
  <?php if (get_post_meta($post->ID, 'shareaholic_disable_share_buttons', true)) { ?>
    checked
  <?php } ?>>
  <?php echo sprintf(__('Hide Share Buttons', 'shareaholic')); ?>
</label>

<br>

<label>
  <input type='checkbox' name='shareaholic[disable_recommendations]'
  <?php if (get_post_meta($post->ID, 'shareaholic_disable_recommendations', true)) { ?>
    checked
  <?php } ?>>
  <?php echo sprintf(__('Hide Related Content', 'shareaholic')); ?>
</label>

<br>

<label>
  <input type='checkbox' name='shareaholic[exclude_recommendations]'
  <?php if (get_post_meta($post->ID, 'shareaholic_exclude_recommendations', true)) { ?>
    checked
  <?php } ?>>
  <?php echo sprintf(__('Exclude from Related Content', 'shareaholic')); ?>
</label>

<br>

<label>
  <input type='checkbox' name='shareaholic[disable_open_graph_tags]'
  <?php if (get_post_meta($post->ID, 'shareaholic_disable_open_graph_tags', true)) { ?>
    checked
  <?php } ?>>
  <?php echo sprintf(__('Do not include Open Graph tags', 'shareaholic')); ?>
</label>