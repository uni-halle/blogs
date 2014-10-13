<?php if ( $label ): ?>
<label class="awpcp-category-dropdown-label" for="awpcp-category-dropdown"><?php echo $label ?><?php echo $required ? '<span class="required">*</span>' : ''; ?></label>
<?php endif; ?>

<?php if ( $use_multiple_dropdowns ): ?>

<?php $hash = uniqid(); ?>
<input id="awpcp-category-dropdown-<?php echo $hash; ?>" type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $selected ); ?>" />
<select class="awpcp-category-dropdown awpcp-dropdown <?php echo $required ? 'required' : ''; ?>" id="awpcp-category-dropdown" target="<?php echo esc_attr( $hash ); ?>" chain="<?php echo esc_attr( join( ',', $chain ) ); ?>" next-default-option="<?php echo esc_attr( $placeholders['default-option-second-level'] ); ?>">
    <option class="default" value=""><?php echo esc_html( $placeholders['default-option-first-level'] ); ?></option>
<?php foreach ( $categories['root'] as $category ): ?>
    <option value="<?php echo esc_attr( $category->id ); ?>"><?php echo esc_html( $category->name ); ?></option>
<?php endforeach; ?>
</select>

<?php else: ?>

<select class="awpcp-category-dropdown  awpcp-dropdown <?php echo $required ? 'required' : ''; ?>" id="awpcp-category-dropdown" name="<?php echo esc_attr( $name ); ?>">
    <option value=""><?php echo esc_html( $placeholders['default-option-first-level'] ); ?></option>
    <?php echo get_categorynameidall( $selected ); ?>
</select>

<?php endif; ?>
