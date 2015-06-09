<p class="awpcp-form-spacer">
    <label for="<?php echo esc_attr( $html['id'] ); ?>"><?php echo esc_html( $label ); ?><?php echo $required ? '*' : ''; ?></label>
    <?php echo awpcp_form_error( $html['name'], $errors ); ?>
    <?php if ( ! empty( $help_text ) ): ?><label for="<?php echo esc_attr( $html['id'] ); ?>" class="helptext"><?php echo $help_text; ?></label><?php endif; ?>
    <label for="<?php echo esc_attr( $html['id'] ); ?>" class="characters-left"><span class="characters-left-placeholder"><?php echo esc_html( $remaining_characters_text ); ?></span>&nbsp;<?php echo esc_html( $characters_allowed_text ); ?></label>
    <textarea id="<?php echo esc_attr( $html['id'] ); ?>" class="awpcp-textarea textareainput required" <?php echo $html['readonly'] ? 'readonly="readonly"' : ''; ?> name="<?php echo esc_attr( $html['name'] ); ?>" rows="10" cols="50" data-max-characters="<?php echo esc_attr( $characters_allowed ); ?>" data-remaining-characters="<?php echo esc_attr( $remaining_characters ); ?>"><?php /* Content alerady escaped if necessary. Do not escape again here! */ echo $value; ?></textarea>
</p>
