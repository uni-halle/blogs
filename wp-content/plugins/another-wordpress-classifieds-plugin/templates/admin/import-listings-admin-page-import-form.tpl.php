<?php $page_id = 'awpcp-admin-csv-importer' ?>
<?php $page_title = awpcp_admin_page_title( __( 'Import Listings', 'another-wordpress-classifieds-plugin' ) ); ?>

<?php include( AWPCP_DIR . '/admin/templates/admin-panel-header.tpl.php') ?>

        <h3><?php echo esc_html( $action_name ); ?></h3>
        <?php if ( $test_mode_enabled ): ?>
        <p><?php echo awpcp_render_warning( __( "You're currently testing the import operation. No listings will be created or modified in the database.", 'another-wordpress-classifieds-plugin' ) ); ?></p>
        <?php endif; ?>

        <form id="awpcp-import-listings-import-form" method="post">
            <div class="progress-bar">
                <div class="progress-bar-value" data-bind="progress: progress"></div>
            </div>

            <p data-bind="html: progressReport"></p>

            <ul class="awpcp-import-listings-messages-list" data-bind="visible: errors().length, foreach: errors">
                <li><span class="message-description" data-bind="html: description"></span><span class="message-content" data-bind="html: content"></span></li>
            </ul>

            <p data-bind="visible: completed"><?php echo __( 'All rows were processed. You can change the Configuration & Restart the import operation, or click the Finish button to delete the source and temporary files created during this import session (imported listings and images will be kept, of course).' ); ?></p>

            <p class="submit">
                <input type="submit" class="button" name="change_configuration" value="<?php echo esc_html( __( 'Change Configuration & Restart', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: paused() || completed()"></input>
                <input type="submit" class="button-primary button" name="start" value="<?php echo esc_html( $action_name ); ?>" data-bind="visible: paused() && ! completed(), click: start"></input>

                <input type="submit" class="button-primary button" name="start" value="<?php echo esc_html( __( 'Pause', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: ! paused() && ! completed(), click: pause"></input>

                <input type="submit" class="button-primary button" name="finish" value="<?php echo esc_html( __( 'Finish', 'another-wordpress-classifieds-plugin' ) ); ?>" data-bind="visible: completed"></input>
            </p>

            <div data-bind="visible: paused() && ! completed()">
                <hr>

                <p><?php echo __( "Press the button below to cancel the current import operation and discard the uploaded CSV file and ZIP file (if any). If you manually uploaded images to the directory specified in the Local Directory field, those won't be deleted.", 'another-wordpress-classifieds-plugin' ); ?></p>

                <p class="cancel-submit">
                    <input type="submit" class="button" name="cancel" value="<?php echo esc_html( __( 'Cancel', 'another-wordpress-classifieds-plugin' ) ); ?>"></input>
                </p>
            </div>
        </form>

        </div><!-- end of .awpcp-main-content -->
    </div><!-- end of .page-content -->
</div><!-- end of #page_id -->
