<tr>
    <td>
        <label for="<?php echo esc_attr($display_type_name) ?>_gallery_display_type"
               class="tooltip"
               title="<?php esc_attr_e($gallery_display_type_help)?>">
            <?php esc_html_e($gallery_display_type_label)?>
        </label>
    </td>
    <td>
        <select
            style="width: 400px"
            id="<?php esc_attr_e($display_type_name) ?>_gallery_display_type"
            name="<?php esc_attr_e($display_type_name)?>[gallery_display_type]">
            <?php foreach ($display_types as $display_type): ?>
            <option value="<?php esc_attr_e($display_type->name) ?>"
                <?php selected($display_type->name, $gallery_display_type) ?>>
                <?php esc_html_e(__($display_type->title, 'nggallery')); ?>
            </option>
            <?php endforeach ?>
        </select>
    </td>
</tr>