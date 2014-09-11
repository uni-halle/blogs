<label class="awpcp-category-dropdown-label" for="awpcp-category-dropdown"><?php echo $label ?><?php echo $required ? '<span class="required">*</span>' : ''; ?></label>

<?php if ( $use_multiple_dropdowns ): ?>

<?php $hash = uniqid(); ?>
<input id="awpcp-category-dropdown-<?php echo $hash; ?>" type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $selected ); ?>" />
<select class="awpcp-category-dropdown awpcp-dropdown <?php echo $required ? 'required' : ''; ?>" id="awpcp-category-dropdown" target="<?php echo $hash; ?>" chain="<?php echo esc_attr( join( ',', $chain ) ); ?>" next-default-option="<?php echo $labels['default-option-second-level']; ?>">
    <option class="default" value=""><?php echo $labels['default-option-first-level']; ?></option>
<?php foreach ( $categories['root'] as $category ): ?>
    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
<?php endforeach; ?>
</select>

<?php else: ?>

<select class="awpcp-category-dropdown  awpcp-dropdown <?php echo $required ? 'required' : ''; ?>" id="awpcp-category-dropdown" name="<?php echo esc_attr( $name ); ?>">
    <option value=""><?php echo $labels['default-option-first-level']; ?></option>
    <?php echo get_categorynameidall( $selected ); ?>
</select>

<?php endif; ?>
