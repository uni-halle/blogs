<tr style="" class="inline-edit-row quick-edit-row alternate inline-editor" id="edit-1">
    <td class="colspanchange" colspan="<?php echo $columns ?>">
        <?php $id = awpcp_get_property($fee, 'id', false); ?>
        <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">

        <?php if ( function_exists( 'awpcp_price_cats' ) ): ?>
        <?php $message = __( 'If this is a Fee for specific categories then select those categories below, otherwise leave the categories unchecked.', 'AWPCP' ); ?>
        <?php echo awpcp_print_message( $message ); ?>
        <?php endif; ?>

        <?php $class = 'inline-edit-col-left'; ?>
        <fieldset class="<?php echo $class; ?>">
            <div class="inline-edit-col">
                <h4><?php echo $id ? __( 'Edit', 'AWPCP' ) : __( 'Add', 'AWPCP' ); ?></h4>

                <label>
                    <span class="title"><?php _e('Name', 'AWPCP'); ?></span>
                    <span class="input-text-wrap"><input type="text" value="<?php echo awpcp_get_property($fee, 'name'); ?>" name="name"></span>
                </label>

                <label>
                    <span class="title"><?php _e('Price', 'AWPCP'); ?></span>
                    <span class="input-text-wrap formatted-field"><input type="text" value="<?php echo $fee ? $fee->price : number_format(0, 2); ?>" name="price"></span>
                </label>

                <label>
                    <span class="title"><?php _e('Credits', 'AWPCP'); ?></span>
                    <span class="input-text-wrap formatted-field"><input type="text" value="<?php echo $fee ? $fee->credits : number_format(0, 0); ?>" name="credits"></span>
                </label>

                <label>
                    <span class="title"><?php _e('Duration', 'AWPCP'); ?></span>
                    <span class="input-text-wrap"><input type="text" value="<?php echo awpcp_get_property($fee, 'duration_amount', 30); ?>" name="duration_amount"></span>
                </label>

                <label>
                    <span class="title"><?php _e('Units', 'AWPCP'); ?></span>
                    <?php $selected = awpcp_get_property($fee, 'duration_interval'); ?>
                    <?php $intervals = AWPCP_Fee::get_duration_intervals(); ?>
                    <select name="duration_interval">
                        <?php foreach ($intervals as $interval): ?>
                        <option value="<?php echo $interval ?>" <?php echo $selected == $interval ? 'selected="selected"' : ''; ?>><?php echo AWPCP_Fee::get_duration_interval_label($interval); ?></option>
                        <?php endforeach ?>
                    </select>
                </label>

        <?php // split form in two columns of fields if the categories list is not going to be shown ?>
        <?php if ( ! function_exists( 'awpcp_price_cats' ) ): ?>
        </fieldset>
        <fieldset class="inline-edit-col-center inline-edit-categories">
            <div class="inline-edit-col">
                <h4>&nbsp;</h4>
        <?php endif; ?>

                <label class="clearfix">
                    <span class="title"><?php _e('Images Allowed', 'AWPCP'); ?></span>
                    <span class="input-text-wrap"><input type="text" value="<?php echo awpcp_get_property($fee, 'images', 1); ?>" name="images"></span>
                </label>

                <label class="clearfix">
                    <span class="title"><?php _e( 'Characters in Title', 'AWPCP' ); ?></span>
                    <?php $value = $fee ? $fee->get_characters_allowed_in_title() : get_awpcp_option( 'characters-allowed-in-title', 0 ); ?>
                    <span class="input-text-wrap"><input type="text" value="<?php echo $value; ?>" name="title_characters"></span>
                    <span class="helptext"><?php _e( '0 means no limit.', 'AWPCP' ); ?></span>
                </label>

                <label class="clearfix">
                    <span class="title"><?php _e('Characters in Description', 'AWPCP'); ?></span>
                    <?php $value = $fee ? $fee->get_characters_allowed() : get_awpcp_option( 'maxcharactersallowed', 0 ); ?>
                    <span class="input-text-wrap"><input type="text" value="<?php echo $value; ?>" name="characters"></span>
                    <span class="helptext"><?php _e( '0 means no limit.', 'AWPCP' ); ?></span>
                </label>

                <?php if (function_exists('awpcp_featured_ads')): ?>
                <label class="alignleft">
                    <?php $checked = awpcp_get_property($fee, 'featured', 0); ?>
                    <input type="checkbox" value="1" <?php echo $checked ? 'checked="checked"' : '' ?> name="featured">
                    <span class="checkbox-title"><?php _e('This Plan is for Featured Ads.', 'AWPCP'); ?></span>
                </label>
                <?php endif ?>
            </div>

        <?php // if a list of categories has to be shown, the fields will be in the the first column ?>
        <?php if ( function_exists( 'awpcp_price_cats' ) ): ?>
        </fieldset>
        <fieldset class="inline-edit-col-center inline-edit-categories">
            <div class="inline-edit-col">

                <input type="hidden" value="0" name="categories[]">
                <span class="title inline-edit-categories-label">
                    <?php _e('Categories', 'AWPCP'); ?>
                    &nbsp;<a href="#" data-categories="all"><?php _ex( 'All', 'all categories', 'AWPCP' ); ?></a>
                    &nbsp;|&nbsp;<a href="#" data-categories="none"><?php _ex( 'None', 'no categories', 'AWPCP' ); ?></a>
                </span>

                <div class="cat-checklist category-checklist">
                <?php echo awpcp_get_categories_checkboxes(awpcp_get_property($fee, 'categories', array())); ?>
                </div>
        <?php endif ?>

                <label class="alignleft">
                    <?php $private = awpcp_get_property( $fee, 'private', 0 ); ?>
                    <input type="checkbox" value="1" <?php echo $private ? 'checked="checked"' : '' ?> name="private">
                    <span class="checkbox-title"><?php _e( 'Hide Fee Plan from public?', 'AWPCP' ); ?></span>
                </label>

            </div>
        </fieldset>

        <p class="submit inline-edit-save">
            <?php $label = $id ? __('Update', 'AWPCP') : __('Add', 'AWPCP'); ?>
            <?php $cancel = __('Cancel', 'AWPCP'); ?>
            <a class="button-secondary cancel alignleft" title="<?php echo $cancel; ?>" href="#inline-edit" accesskey="c"><?php echo $cancel; ?></a>
            <a class="button-primary save alignright" title="<?php echo $label ?>" href="#inline-edit" accesskey="s"><?php echo $label; ?></a>
            <img alt="" src="http://local.wordpress.org/wp-admin/images/wpspin_light.gif" style="display: none;" class="waiting">
            <input type="hidden" value="<?php echo esc_attr( $id ); ?>" name="id">
            <input type="hidden" value="<?php echo esc_attr( $_POST['action'] ); ?>" name="action">
            <br class="clear">
        </p>
        </form>
    </td>
</tr>
