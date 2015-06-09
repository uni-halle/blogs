<?php echo $message; ?>

<div class="awpcp-updated updated">
    <p>
        <?php $msg = _x("Thank you for using Another Wordpress Classifieds Plugin, the #1 Wordpress Classifieds Plugin.  Please direct support requests, enhancement ideas and bug reports to the %s.",
                            '... to the <a>AWPCP Support Website link</a>',
                            'AWPCP'); ?>
        <?php echo sprintf($msg, '<a href="http://www.awpcp.com/forum/">' . __("AWPCP Support Website", "AWPCP") . '</a>'); ?>
    </p>
</div>

<?php if ($hasextrafieldsmodule == 1 && !($extrafieldsversioncompatibility == 1)): ?>
<div id="message" class="awpcp-updated updated fade">
    <p>
        <?php _e("The version of the extra fields module that you are using is not compatible with this version of Another Wordpress Classifieds Plugin.", "AWPCP"); ?>
        <a href="http://www.awpcp.com/contact/"><?php _e("Please request updated Extra Fields module files", "AWPCP"); ?></a>.
    </p>
</div>
<?php endif; ?>

<?php $main_page_name = get_awpcp_option('main-page-name');  // check if there is a duplicate page conflict ?>
<?php $page_conflict = checkforduplicate(add_slashes_recursive(sanitize_title($main_page_name))); ?>

<?php if ($page_conflict > 1): ?>
<div class="error">
    <p>
        <?php _e("It appears you have a potential problem that could result in the malfunctioning of Another Wordpress Classifieds plugin. A check of your database was performed and duplicate entries were found that share the same post_name value as your classifieds page. If for some reason you uninstall and then reinstall this plugin and the duplicate pages remain in your database, it could break the plugin and prevent it from working. To fix this problem you can manually delete the duplicate pages and leave only the page with the ID of your real classifieds page, or you can use the link below to rebuild your classifieds page. The process will include first deleting all existing pages with a post name value identical to your classifieds page. Note that if you recreate the page, it will be assigned a new page ID so if you are referencing the classifieds page ID anywhere outside of the classifieds program you will need to adjust the old ID to the new ID.", "AWPCP"); ?>
    </p>

    <ul>
        <li><?php _e("Number of duplicate pages", "AWPCP"); ?>: <strong><?php echo $page_conflict; ?></strong></li>
        <li><?php _e("Duplicated post name", "AWPCP"); ?>: <strong><?php echo $main_page_name ?></strong></li>
    </ul>

    <p>
        <a class="button-primary button" href="<?php echo esc_url( add_query_arg( 'page', 'awpcp-admin-settings', admin_url( 'admin.php' ) ) ); ?>">
            <?php _e("Restore the Classifieds pages to fix the conflict.", "AWPCP"); ?>
        </a>
    </p>
</div>
<?php endif; ?>

<div class="metabox-holder">
    <div class="meta-box-sortables" <?php echo empty($sidebar) ? '' : ' style="float:left;width:70%;"'; ?>>

        <div class="postbox">
            <h3 class="hndle1"><span><?php _e("Another Wordpress Classifieds Plugin Stats", "AWPCP"); ?><span></h3>
            <div class="inside">
                <ul>
                    <li><?php _e("AWPCP version", "AWPCP"); ?>: <strong><?php echo $awpcp_db_version; ?></strong>.</li>

                    <?php $totallistings = countlistings(1); ?>
                    <li><?php _e("Number of active listings currently in the system", "AWPCP"); ?>: <strong><?php echo $totallistings; ?></strong></li>

                    <?php $totallistings = countlistings(0); ?>
                    <li><?php _e("Number of inactive/expired/disabled listings currently in the system", "AWPCP"); ?>: <strong><?php echo $totallistings; ?></strong></li>
                </ul>

                <div style="border-top:1px solid #dddddd;">
                <?php if (get_awpcp_option('freepay') == 1): ?>
                    <?php if (adtermsset()): ?>
                        <?php $msg = __("You have setup your listing fees. To edit your fees go to %s.", "AWPCP"); ?>
                    <?php else: ?>
                        <?php $msg = __("You have not configured your Listing fees. Go to %s to set up your listing fees. Once that is completed, if you are running in pay mode, the options will automatically appear on the listing form for users to fill out.", "AWPCP"); ?>
                    <?php endif; ?>
                    <?php $url = add_query_arg('page', 'awpcp-admin-fees', admin_url('admin.php')); ?>
                    <p><?php echo sprintf( $msg, sprintf( '<a href="%s">%s</a>', esc_url( $url ), __( 'Fees', 'AWPCP' ) ) ); ?></p>
                <?php else: ?>
                    <?php $msg = __("You currently have your system configured to run in free mode. To change to 'pay' mode go to %s and Check the box labeled 'Charge Listing Fee? (Pay Mode).'", "AWPCP"); ?>
                    <?php $url = add_query_arg(array('page' => 'awpcp-admin-settings', 'g' => 'payment-settings'), admin_url('admin.php')); ?>
                    <p><?php echo sprintf( $msg, sprintf( '<a href="%s">%s</a>', esc_url( $url ), __( 'Payment Options', 'AWPCP' ) ) ); ?></p>
                <?php endif; ?>
                </div>

                <?php if (categoriesexist()): ?>

                <div style="border-top:1px solid #dddddd;">
                    <?php $msg = __("Go to the %s section to edit/delete current categories or add new categories.", "AWPCP"); ?>
                    <?php $url = awpcp_get_admin_categories_url(); ?>
                    <p><?php echo sprintf($msg, sprintf('<a href="%s">%s</a>', $url, __('Manage Categories', 'AWPCP'))); ?></p>

                    <ul>
                        <?php $totalcategories = countcategories(); ?>
                        <li style="margin-bottom:6px;list-style:none;">
                            <?php _e("Total number of categories in the system", "AWPCP"); ?>:
                            <strong><?php echo $totalcategories; ?></strong>
                        </li>

                        <?php $totalparentcategories = countcategoriesparents(); ?>
                        <li style="margin-bottom:6px;list-style:none;">
                            <?php _e("Number of Top Level parent categories", "AWPCP"); ?>:
                            <strong><?php echo $totalparentcategories; ?></strong>
                        </li>

                        <?php $totalchildrencategories = countcategorieschildren(); ?>
                        <li style="margin-bottom:6px;list-style:none;">
                            <?php _e("Number of sub level children categories", "AWPCP"); ?>:
                            <strong><?php echo $totalchildrencategories; ?></strong>
                        </li>
                    </ul>
                </div>

                <?php else: ?>

                <div style="border-top:1px solid #dddddd;">
                    <?php $msg = __("You have not categories defined. Go to the %s section to set up your categories.", "AWPCP"); ?>
                    <?php $url = add_query_arg('page', 'Cofigure3', admin_url('admin.php')); ?>
                    <p><?php echo sprintf( $msg, sprintf( '<a href="%s">%s</a>', esc_url( $url ), __( 'Manage Categories', 'AWPCP' ) ) ); ?></p>
                </div>

                <?php endif; ?>

                <?php if (get_awpcp_option('freepay') == 1): ?>
                <div style="border-top:1px solid #dddddd;">
                    <?php $msg = __("You currently have your system configured to run in pay mode. To change to 'free' mode go to %s and uncheck the box labeled 'Charge Listing Fee? (Pay Mode).'", "AWPCP"); ?>
                    <?php $url = add_query_arg(array('page' => 'awpcp-admin-settings', 'g' => 'payment-settings'), admin_url('admin.php')); ?>
                    <p><?php echo sprintf( $msg, sprintf( '<a href="%s">%s</a>', esc_url( $url ), __( 'Payment Options', 'AWPCP' ) ) ); ?></p>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <?php $href = admin_url( 'admin.php?page=awpcp-admin-settings' ); ?>
                <?php _e( 'AWPCP is highly customizable. Use the next button to go to the Settings section to fit AWPCP to your needs.', 'AWPCP' ); ?>
                <a href="<?php echo esc_url( $href ); ?>" class="button-primary"><?php _e( 'Configure AWPCP', 'AWPCP' ); ?></a>
            </div>
        </div>

    </div>
</div>
