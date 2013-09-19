<tr class="inline-edit-row quick-edit-row alternate inline-editor delete" id="delete-1">
    <td class="colspanchange" colspan="<?php echo $columns ?>">
        <form action="" method="post">
        <fieldset class="inline-edit-col-left"><div class="inline-edit-col">
                <label>
                    <span class="title delete-title" style="width: 100%"><?php _e('Are you sure you want to delete this item?', 'AWPCP' ) ?></span>
                </label>
        </fieldset>

        <p class="submit inline-edit-save">
            <?php $url = $this->page_url( array( 'action' => 'delete', 'id' => absint( $_POST['id'] ) ) ); ?>
            <?php $cancel = __('Cancel', 'AWPCP'); ?>
            <?php $delete = __('Delete', 'AWPCP'); ?>
            <a class="button-secondary cancel alignleft" title="<?php echo $cancel; ?>" href="#inline-edit" accesskey="c"><?php echo $cancel; ?></a>
            <a class="button-primary alignright" title="<?php echo $delete; ?>" href="<?php echo esc_url( $url ); ?>" accesskey="s"><?php echo $delete; ?></a>
            <br class="clear">
        </p>
        </form>
    </td>
</tr>
