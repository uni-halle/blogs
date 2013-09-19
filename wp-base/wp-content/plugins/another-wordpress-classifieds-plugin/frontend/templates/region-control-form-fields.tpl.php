    <div class="awpcp-region-control-region-fields">

    <?php $ordered = array('country', 'state', 'city', 'county'); ?>
    <?php $hidden = true; ?>

    <?php foreach ($ordered as $name):

            if (!isset($fields[$name])) { continue; }

            $field = $fields[$name];

            // hide if the previous field is hidden, current field has 1 or less entries
            // and the user already selected a region of this type
            $hidden = $hidden && (count($field['entries']) == 1);
            $container_class = count($field['entries']) == 1 ? "single {$field['class']}" : $field['class'];
            $input_class = $field['required'] ? ' required' : '';
    ?>

        <?php if (!$hidden): ?>
        <p class="awpcp-form-spacer <?php echo $container_class; ?>" region-field="<?php echo $name ?>" data-region-field-name="<?php echo esc_attr($field['name']); ?>">

            <?php if (!empty($field['options'])): ?>

            <label for="<?php echo esc_attr($field['name']); ?>"><?php echo $field['label']; ?>&nbsp;<span class="helptext hidden">(<?php echo $field['help']; ?>)</span></label>

            <select id="<?php echo esc_attr($field['name']); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr($field['name']); ?>">
                <?php echo $field['options']; ?>
            </select>
            <input class="inputbox hidden<?php echo $input_class; ?>" size="35" type="text" value="<?php echo awpcp_esc_attr($field['value']); ?>" />

            <?php else: ?>

            <label for="<?php echo esc_attr($field['name']); ?>"><?php echo $field['label']; ?>&nbsp;<span class="helptext">(<?php echo $field['help']; ?>)</span></label>

            <select class="hidden" class="<?php echo $input_class; ?>">
                <?php echo $field['options']; ?>
            </select>
            <input id="<?php echo esc_attr($field['name']); ?>" class="inputbox<?php echo $input_class; ?>" size="35" type="text" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo awpcp_esc_attr($field['value']); ?>" />

            <?php endif ?>

            <?php echo awpcp_form_error($field['name'], $errors); ?>
        </p>
        <?php else: ?>
        <p class="awpcp-form-spacer <?php echo $container_class; ?>">
            <?php $value = $field['entries'][0]->region_name ?>
            <?php echo $field['label']; ?>: <strong><?php echo stripslashes($value); ?></strong>
        </p>
        <input type="hidden" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo awpcp_esc_attr($value); ?>" />
        <?php endif ?>

    <?php endforeach ?>

    </div>
