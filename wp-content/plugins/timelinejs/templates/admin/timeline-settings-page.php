<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

$categories = get_categories(array('type'=>'post','orderby'=>'name','taxonomy'=>'category'));
$specific_categories = get_option('display_only_these_post_categories_as_a_timeline');

if(!is_array($specific_categories)) {
	$specific_categories = array();
}

?>

<div class="wrap">
<h1>General Settings</h1>

<form method="post" action="options.php" novalidate="novalidate">

	 <?php settings_fields( 'timeline-settings-group' ); ?>
	 <?php do_settings_sections( 'timeline-settings-group' ); ?>

	<table class="form-table">
	<tr>
		<td><label for="display_post_categories_as_inline_timeline">Display Post Categories inline with your theme?</label></td>
		<td><input type="checkbox" name="display_post_categories_as_inline_timeline" id="display_post_categories_as_inline_timeline" value="1" <?php checked( '1', get_option( 'display_post_categories_as_inline_timeline' ) ); ?>></td>
	</tr>
	<tr>
		<td><label for="display_post_categories_as_a_timeline">Display Post Categories as a Timeline?</label></td>
		<td>
			<input type="radio" name="display_post_categories_as_a_timeline" value="none" <?php checked( 'none', get_option( 'display_post_categories_as_a_timeline' ) ); ?>>None <br>
			<input type="radio" name="display_post_categories_as_a_timeline" value="all" <?php checked( 'all', get_option( 'display_post_categories_as_a_timeline' ) ); ?>>All <br>
			<input type="radio" name="display_post_categories_as_a_timeline" value="specific" <?php checked( 'specific', get_option( 'display_post_categories_as_a_timeline' ) ); ?>>Specific Categories
		</td>
	</tr>
	<tr id="post_cats_as_timeline" class="timeline-settings-conditional">
		<td><label for="only_display_other_timeline_hotswap">Only display other Timeline Enabled Categories in Category Hot Swap Menu?</label></td>
		<td><input type="checkbox" id="only_display_other_timeline_hotswap" name="only_display_other_timeline_hotswap" value="1" <?php checked( '1', get_option( 'only_display_other_timeline_hotswap' ) ); ?>></td>
	</tr>
	</table>

	<table id="timeline_category_whitelist" class="form-table timeline-settings-conditional">
		<tr>
			<td style="vertical-align:top;"><label for="display_only_these_post_categories_as_a_timeline">Display only these Post Categories as a Timeline:</label></td>
		<td>
			<select multiple="multiple" size="10" name="display_only_these_post_categories_as_a_timeline[]" id="display_only_these_post_categories_as_a_timeline">
			<?php foreach($categories as $category) {

			$selected = '';
			if(in_array($category->term_id, $specific_categories)) {
				$selected = 'selected="selected"';
			}

			?>

				<option value="<?php echo $category->term_id ?>" <?php echo $selected ?>><?php echo $category->name ?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	</table>

	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

</form>
<style>
	.timeline-settings-conditional {display:none;}
</style>
<script>
jQuery(document).ready(function(){

	var	postCatsAsTimeline = '<?php echo get_option( 'display_post_categories_as_a_timeline' ); ?>';

	if(postCatsAsTimeline == 'specific') {
		jQuery('#timeline_category_whitelist').css('display','block');
	}

	jQuery('input[name="display_post_categories_as_a_timeline"]').change(function() {
		if ( jQuery(this).val() == 'specific' ) {
			jQuery('#timeline_category_whitelist').css('display','block');
		} else {
			jQuery('#timeline_category_whitelist').css('display','none');
		}

	});

	if (jQuery('#display_post_categories_as_a_timeline').is(':checked')) {
		jQuery('#timeline_category_whitelist').css('display', 'block');
	}

	jQuery('#display_post_categories_as_a_timeline').change(function() {
		if (jQuery(this).is(':checked')) {
			jQuery('#post_cats_as_timeline').css('display', 'block');
		} else {
			jQuery('#post_cats_as_timeline').css('display', 'none');
		}
		
	});
});
</script>

</div>