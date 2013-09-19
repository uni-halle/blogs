<?php
include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
function ocmx_theme_update($package, $delete_package = true) {
		global $wp_filesystem;

		$upgrade_folder = WP_Filesystem_Base::wp_content_dir() . 'upgrade/'		;
		echo $upgrade_folder;
/*
		//Clean up contents of upgrade directory beforehand.
		$upgrade_files = $wp_filesystem->dirlist($upgrade_folder);
		if ( !empty($upgrade_files) ) {
			foreach ( $upgrade_files as $file )
				$wp_filesystem->delete($upgrade_folder . $file['name'], true);
		}

		//We need a working directory
		$working_dir = $upgrade_folder . basename($package, '.zip');

		// Clean up working directory
		if ( $wp_filesystem->is_dir($working_dir) )
			$wp_filesystem->delete($working_dir, true);

		// Unzip package to working directory
		$result = unzip_file($package, $working_dir); //TODO optimizations, Copy when Move/Rename would suffice?

		// Once extracted, delete the package if required.
		if ( $delete_package )
			unlink($package);

		if ( is_wp_error($result) ) {
			$wp_filesystem->delete($working_dir, true);
			return $result;
		}

		return $working_dir;
		*/
	}
	