<?php
/**
 * Page Options Metabox options.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<script>
	var avadaPOMessages = {
		importJSONWarning: '<?php echo esc_js( 'The file imported must be an exported JSON file from Fusion Page Options.', 'Avada' ); ?>',
		saveTitleWarning: '<?php echo esc_js( 'To save custom page options, you must enter a name and ensure your page is saved first.', 'Avada' ); ?>'
	};
</script>

<div class="avada-po-warning">
	<?php esc_attr_e( 'Settings have changed, you need to save the page before you export or save page options.', 'Avada' ); ?>
</div>

<div class="pyre_metabox_field">
	<div class="pyre_desc">
		<label><?php esc_html_e( 'Save Page Options', 'Avada' ); ?></label>
		<p><?php esc_html_e( 'Save your current Page Options as a custom set to be reused on any page or post that utilizes Fusion Page Options.', 'Avada' ); ?></p>
	</div>
	<div class="pyre_field">
		<input type="text" id="fusion-new-page-options-name" value="" placeholder="<?php esc_attr_e( 'Enter a name', 'Avada' ); ?>">
		<a href="#" id="fusion-page-options-save" class="button button-primary" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>" data-post_type="<?php echo esc_attr( get_post_type() ); ?>">
			<?php esc_html_e( 'Save Page Options', 'Avada' ); ?>
		</a>
	</div>
</div>

<div class="pyre_metabox_field">
	<div class="pyre_desc">
		<label><?php esc_html_e( 'Manage Page Options', 'Avada' ); ?></label>
		<p><?php esc_html_e( 'Select a set of saved Page Options, then choose to import or delete them.', 'Avada' ); ?></p>
	</div>

	<div class="pyre_field">
		<select id="fusion-saved-page-options-select" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>" style="width:100%;">
			<option value=""><?php esc_html_e( 'Select A Page Option Set', 'Avada' ); ?></option>
			<?php
			global $post;
			$saved_post = $post;

			$args = array(
				'post_type'      => 'avada_page_options',
				'posts_per_page' => -1,
			);

			$query = new WP_Query( $args );
			?>
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : ?>
					<?php $query->the_post(); ?>
					<option value="<?php the_ID(); ?>">
						<?php the_title(); ?>
					</option>
				<?php endwhile; ?>

			<?php endif; ?>

			<?php $post = $saved_post ? $saved_post : $post; ?>
			<?php wp_reset_postdata(); ?>
			?>
		</select>

		<p id="fusion-page-options-buttons-wrap">
			<a href="#" id="fusion-page-options-import-saved" class="button button-primary"><?php esc_html_e( 'Import', 'Avada' ); ?></a>
			<a href="#" id="fusion-page-options-delete-saved" class="button button-primary"><?php esc_html_e( 'Delete', 'Avada' ); ?></a>
		</p>
	</div>
</div>

<div class="pyre_metabox_field">
	<div class="pyre_desc">
		<label><?php esc_html_e( 'Export Page Options', 'Avada' ); ?></label>
		<p><?php esc_html_e( 'Click the export button to export your current set of Page Options as a json file.', 'Avada' ); ?></p>
	</div>
	<div class="pyre_field">
		<div>&nbsp;</div>
		<a href="<?php echo esc_url_raw( wp_nonce_url( admin_url( 'admin-ajax.php?action=download-avada-po&post_id=' . $post->ID . '' ) ) ); ?>" id="fusion-page-options-export" class="button button-primary" data-post_id="<?php the_ID(); ?>">
			<?php esc_html_e( 'Export Page Options', 'Avada' ); ?>
		</a>
	</div>
</div>

<div class="pyre_metabox_field">
	<div class="pyre_desc">
		<label><?php esc_html_e( 'Import Page Options', 'Avada' ); ?></label>
		<p><?php esc_html_e( 'Click Import to select a set of Page Options (json file) to be used.', 'Avada' ); ?></p>
	</div>
	<div class="pyre_field">

		<div id="fusion_page_options_file_upload" class="file-upload">
			&nbsp;
			<input type="file" id="fusion-page-options-file-input" style="display:none;opacity:0;" />
		</div>

		<a href="#" id="fusion-page-options-import" class="button button-primary" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>">
			<?php esc_html_e( 'Import Page Options', 'Avada' ); ?>
		</a>
	</div>
</div>

<div id="fusion-page-options-loader" style="display: none;">
	<img src="<?php echo esc_url( admin_url( 'images/spinner.gif' ) ); ?>" />
</div>

<input type="hidden" id="fusion-page-options-nonce" value="<?php echo esc_attr( wp_create_nonce( 'fusion-page-options-nonce' ) ); ?>" />

<div id="avada-po-dialog" title="<?php esc_attr_e( 'Warning ', 'Avada' ); ?>"></div>
