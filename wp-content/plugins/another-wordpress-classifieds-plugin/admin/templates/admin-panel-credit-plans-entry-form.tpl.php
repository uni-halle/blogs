<tr style="" class="inline-edit-row quick-edit-row alternate inline-editor" id="edit-1">
    <td class="colspanchange" colspan="5">
        <?php $id = awpcp_get_property($plan, 'id', false) ?>
        <form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="post">
        <fieldset class="inline-edit-col-left"><div class="inline-edit-col">
                <h4><?php echo $id ? _x( 'Edit Credit Plan Details', 'credit plans form', 'AWPCP' ) : _x( 'New Credit Plan Details', 'credit plans form', 'AWPCP' ); ?></h4>

                <label>
                    <span class="title"><?php echo esc_html( __( 'Name', 'AWPCP' ) ); ?></span>
                    <span class="input-text-wrap"><input type="text" value="<?php echo esc_attr( awpcp_get_property( $plan, 'name' ) ); ?>" name="name"></span>
                </label>

                <label>
                    <span class="title"><?php echo esc_html( __( 'Credits', 'AWPCP' ) ); ?></span>
                    <span class="input-text-wrap formatted-field"><input type="text" value="<?php echo esc_attr( $plan ? $plan->credits : '' ); ?>" name="credits"></span>
                </label>

                <label>
                    <span class="title"><?php echo esc_html( __( 'Price', 'AWPCP' ) ); ?></span>
                    <span class="input-text-wrap formatted-field"><input type="text" value="<?php echo esc_attr( $plan ? $plan->price : '' ); ?>" name="price"></span>
                </label>
        </fieldset>

        <fieldset class="inline-edit-col-right"><div class="inline-edit-col">
                <label><span class="title"><?php echo esc_html( __( 'Description', 'AWPCP' ) ); ?></span></label>
                <textarea name="description" cols="54" rows="6"><?php echo stripslashes( awpcp_get_property( $plan, 'description' ) ) ?></textarea>
        </fieldset>

        <p class="submit inline-edit-save">
            <?php $label = $id ? __('Update', 'AWPCP') : __('Add', 'AWPCP') ?>
            <?php $cancel = __('Cancel', 'AWPCP'); ?>
            <a class="button-secondary cancel alignleft" title="<?php echo esc_attr( $cancel ); ?>" href="#inline-edit" accesskey="c"><?php echo esc_html( $cancel ); ?></a>
            <a class="button-primary save alignright" title="<?php echo esc_attr( $label ); ?>" href="#inline-edit" accesskey="s"><?php echo esc_html( $label ); ?></a>
            <img alt="" src="<?php echo esc_attr( admin_url( '/images/wpspin_light.gif' ) ); ?>" style="display: none;" class="waiting">
            <input type="hidden" value="<?php echo esc_attr( $id ) ?>" name="id">
            <input type="hidden" value="<?php echo esc_attr( $_POST['action'] ); ?>" name="action">
            <br class="clear">
        </p>
        </form>
    </td>
</tr>
