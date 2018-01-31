<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly ?>

<tr class="form-field wpcustom-category-form-field">

    <th scope="row">
        <label for="taxonomy_image"><?php echo $label['image']; ?></label>
    </th>

    <td>
        <input type="hidden" name="categoryimage_attachment" id="categoryimage_attachment" value="<?php echo $categoryimage_attachment; ?>">

        <div id="categoryimage_imageholder">
            <?php if (isset($categoryimage_image)): ?>
                <img src="<?php echo $categoryimage_image; ?>" width="180" id="categoryimage_image" />
            <?php endif; ?>
        </div>

        <div class="options">
            <button class="button" id="categoryimage_upload_button"><?php echo $label['upload_image']; ?></button>
            <button class="button" id="categoryimage_remove_button"><?php echo $label['remove_image']; ?></button>
        </div>
    </td>
</tr>