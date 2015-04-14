<tr>
    <td>
        <label class="tooltip"
               for="<?php esc_attr_e($display_type_name)?>_galleries_per_page"
               title="<?php esc_attr_e($galleries_per_page_help)?>">
            <?php esc_html_e($galleries_per_page_label) ?>
        </label>
    </td>
    <td>
        <input
            id="<?php esc_attr_e($display_type_name)?>_galleries_per_page"
            name="<?php esc_attr_e($display_type_name) ?>[galleries_per_page]"
            type="number"
            min="0"
            value="<?php esc_attr_e($galleries_per_page)?>"
            placeholder="#"
        />
    </td>
</tr>