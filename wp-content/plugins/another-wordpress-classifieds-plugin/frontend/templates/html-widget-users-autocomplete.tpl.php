<?php if ( $args['label'] ): ?>
<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['label'] ); ?><?php if ( $args['required'] ): ?><span class="required">*</span><?php endif; ?></label>
<?php endif; ?>
<input type="hidden" name="<?php echo esc_attr( $args['name'] ); ?>" autocomplete-selected-value>
<input id="<?php echo esc_attr( $args['id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['class'] ) ); ?>" type="text" autocomplete-field>
