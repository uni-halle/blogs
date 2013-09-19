<tr class="inline-edit-row quick-edit-row alternate inline-editor delete" id="delete-1">
    <td class="colspanchange" colspan="<?php echo $columns ?>">
        <form class="awpcp-delete-form" action="<?php echo admin_url('admin-ajax.php') ?>" method="post">
        <fieldset><div class="inline-edit-col">
                <label>
                    <span class="title delete-title" style="width: 100%"><?php _e('Are you sure you want to delete this item?', 'AWPCP') ?></span>
                </label>
        </fieldset>

        <p class="submit inline-edit-save">
            <a class="button-secondary cancel alignleft" title="Cancel" href="#inline-edit" accesskey="c"><?php _e('Cancel', 'AWPCP') ?></a>
            <a class="button-primary delete alignleft" title="Delete" href="#inline-edit" accesskey="s"><?php _e('Delete', 'AWPCP') ?></a>
            <img alt="" src="http://local.wordpress.org/wp-admin/images/wpspin_light.gif" style="display: none;" class="waiting">
            <input type="hidden" value="<?php echo esc_attr( $_POST['id'] ); ?>" name="id">
            <input type="hidden" value="<?php echo esc_attr( $_POST['action'] ); ?>" name="action">
            <br class="clear">
        </p>
        </form>
    </td>
</tr>
