<?php if ( $args['label'] ): ?>
<label for="<?php echo $args['id']; ?>"><?php echo $args['label']; ?><?php if ( $args['required'] ): ?><span class="required">*</span><?php endif; ?></label>
<?php endif; ?>
<select id="<?php echo $args['id']; ?>" name="<?php echo $args['name']; ?>" class="<?php echo implode( ' ', $args['class'] ); ?>" dropdown-field>
    <?php if ( $args['default'] ): ?>
    <option value=""><?php echo $args['default'] ?></option>
    <?php endif; ?>
    <?php foreach ( $args['users'] as $k => $user ): ?>
    <option value="<?php echo esc_attr( $user->ID ); ?>"<?php if ( $args['include-full-user-information'] ): ?> data-user-information="<?php echo esc_attr( json_encode( $user ) ); ?>"<?php endif; ?> <?php echo $args['selected'] == $user->ID ? 'selected="selected"' : ''; ?>>
        <?php echo $user->display_name; ?>
    </option>
    <?php endforeach; ?>
</select>
