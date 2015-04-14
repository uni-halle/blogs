<?php
	if (!isset($id))
	{
		$id = 'ngg-image-' . $index;
	}
?>
<div id="<?php esc_attr_e($id) ?>" class="<?php esc_attr_e($class) ?>" <?php if (isset($image->style)) echo $image->style; ?>>
