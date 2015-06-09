<p class="awpcp-form-spacer">
    <label for="<?php echo esc_attr( $html['id'] ); ?>"><?php echo esc_html( $label ); ?><?php echo $required ? '*' : ''; ?><?php if ( ! empty( $help_text ) ): ?>&nbsp;<span class="helptext"><?php echo $help_text; ?></span><?php endif; ?></label>
    <input class="inputbox <?php echo esc_attr( $validators ); ?>" id="<?php echo esc_attr( $html['id'] ); ?>" <?php echo $html['readonly'] ? 'readonly="readonly"' : ''; ?> type="text" size="50" name="<?php echo esc_attr( $html['name'] ); ?>" value="<?php echo awpcp_esc_attr( $value ); ?>" />
    <?php echo awpcp_form_error( $html['name'], $errors ); ?>
</p>
