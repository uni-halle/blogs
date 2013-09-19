<tr style="" class="inline-edit-row quick-edit-row alternate inline-editor" id="edit-1">
    <td class="colspanchange" colspan="<?php echo $columns ?>">
        <form action="<?php echo admin_url('admin-ajax.php') ?>" method="post">

        <?php $label = $action == 'debit' ? __('Remove Credit', 'AWPCP') : __('Add Credit', 'AWPCP'); ?>

        <fieldset class="inline-edit-col-wide">
            <div class="inline-edit-col">
                <h4><?php echo $label ?></h4>

                <label>
                    <span class="title"><?php _e('Amount', 'AWPCP'); ?></span>
                    <span class="input-text-wrap formatted-field"><input type="text" value="" name="amount"></span>
                </label>
            </div>
        </fieldset>

        <p class="submit inline-edit-save">
            <?php $cancel = __('Cancel', 'AWPCP'); ?>
            <a class="button-secondary cancel alignleft" title="<?php echo $cancel; ?>" href="#inline-edit" accesskey="c"><?php echo $cancel; ?></a>
            <a class="button-primary save alignright" title="<?php echo $label ?>" href="#inline-edit" accesskey="s"><?php echo $label ?></a>
            <img alt="" src="http://local.wordpress.org/wp-admin/images/wpspin_light.gif" style="display: none;" class="waiting">
            <input type="hidden" value="<?php echo esc_attr( $user->ID ); ?>" name="user">
            <input type="hidden" value="<?php echo esc_attr( $_POST['action'] ); ?>" name="action">
            <br class="clear">
        </p>

        </form>
    </td>
</tr>
