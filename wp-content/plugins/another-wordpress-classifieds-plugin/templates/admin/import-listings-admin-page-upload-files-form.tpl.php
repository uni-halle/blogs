<?php $page_id = 'awpcp-admin-csv-importer' ?>
<?php $page_title = awpcp_admin_page_title( __( 'Import Listings', 'another-wordpress-classifieds-plugin' ) ); ?>

<?php include( AWPCP_DIR . '/admin/templates/admin-panel-header.tpl.php') ?>

            <h3><?php echo esc_html( __( 'Upload Source Files', 'another-wordpress-classifieds-plugin' ) ); ?></h3>

            <form id="awpcp-import-listings-upload-source-files" enctype="multipart/form-data" method="post">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="awpcp-importer-csv-file"><?php echo esc_html( __( 'CSV file', 'another-wordpress-classifieds-plugin' ) ); ?></label>
                            </th>
                            <td>
                                <input id="awpcp-importer-csv-file" type="file" name="csv_file" />
                                <br/><?php echo awpcp_form_error( 'csv_file', $form_errors ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="awpcp-importer-images-source"><?php echo esc_html( __( 'Images source', 'another-wordpress-classifieds-plugin' ) ); ?></label>
                            </th>
                            <td>
                                <label><input id="awpcp-importer-images-source" type="radio" name="images_source" value="none"<?php echo $form_data['images_source'] == 'none' ? ' checked="checked"' : ''; ?> /> <?php echo __( "Don't import images", 'another-wordpress-classifieds-plugin' ); ?></label>
                                <br>
                                <label><input id="awpcp-importer-images-source" type="radio" name="images_source" value="zip"<?php echo $form_data['images_source'] == 'zip' ? ' checked="checked"' : ''; ?> /> <?php echo __( 'ZIP file', 'another-wordpress-classifieds-plugin' ); ?></label>
                                <br>
                                <label><input type="radio" name="images_source" value="local"<?php echo $form_data['images_source'] == 'local' ? ' checked="checked"' : ''; ?> /> <?php echo __( 'Local directory', 'another-wordpress-classifieds-plugin' ); ?></label>
                            </td>
                        </tr>
                        <tr data-usableform="show-if:images_source:zip">
                            <th scope="row">
                                <label for="awpcp-importer-zip-file"><?php echo esc_html( __( 'Zip file containing images', 'another-wordpress-classifieds-plugin' ) ); ?></label>
                            </th>
                            <td>
                                <input id="awpcp-importer-zip-file" type="file" name="zip_file" />
                                <br/><?php echo awpcp_form_error( 'zip_file', $form_errors ); ?>
                            </td>
                        </tr>
                        <tr data-usableform="show-if:images_source:local">
                            <th scope="row">
                                <label for="awpcp-importer-local-path"><?php echo esc_html( __( 'Local directory path', 'another-wordpress-classifieds-plugin' ) ); ?></label>
                            </th>
                            <td>
                                <input id="awpcp-importer-local-path" type="text" name="local_path" value="<?php echo esc_attr( $form_data['local_path'] ); ?>"/>
                                <br/><?php echo awpcp_form_error( 'local_path', $form_errors ); ?>
                                <p class="awpcp-helptext"><?php echo str_replace( '<uploads-directory-path>', '<code>' . awpcp()->settings->get_runtime_option( 'awpcp-uploads-dir' ) . '</code>', __( 'The relative path to a directory inside <uploads-directory-path>.', 'another-wordpress-classifieds-plugin' ) ); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                    <input type="submit" class="button-primary button" name="upload_files" value="<?php echo esc_html( __( 'Upload Source Files', 'another-wordpress-classifieds-plugin' ) ); ?>"></input>
                </p>
            </form>

        </div><!-- end of .awpcp-main-content -->
    </div><!-- end of .page-content -->
</div><!-- end of #page_id -->
