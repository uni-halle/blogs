<p class="awpcp-form-spacer awpcp-form-spacer-title">
    <label for="<?php echo esc_attr( $html['id'] ); ?>"><?php echo esc_html( $label ); ?><?php echo $required ? '*' : ''; ?></label>
    <input class="inputbox required" id="<?php echo esc_attr( $html['id'] ); ?>" type="text" size="50" name="<?php echo esc_attr( $html['name'] ); ?>" value="<?php echo awpcp_esc_attr( $value ); ?>" data-max-characters="<?php echo esc_attr( $characters_allowed ); ?>" data-remaining-characters="<?php echo esc_attr( $remaining_characters ); ?>"/>
    <br/><label for="<?php echo esc_attr( $html['id'] ); ?>" class="characters-left"><span class="characters-left-placeholder"><?php echo esc_html( $remaining_characters_text ); ?></span>&nbsp;<?php echo esc_html( $characters_allowed_text ); ?></label>
    <?php echo awpcp_form_error( $html['name'], $errors ); ?>
</p>
