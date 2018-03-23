<?php
/**
 * The default template for displaying page content
 *
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<!--
	<header>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>
-->
	<div class="entry-content">

<?php
// $theurl = 'http://be-up.doom/wp-content/uploads/2018/01/kbd.pdf';

// if(!function_exists('extcheck')) {

//     function extcheck($extension) {
// 		$path_parts = pathinfo($extension);
// 		$theext = $path_parts["extension"];

//         $mime_types = array(

//             'txt' => 'text-o',
//             'htm' => 'text/html',
//             'html' => 'text/html',
//             'php' => 'text/html',
//             'css' => 'text/css',
//             'js' => 'application/javascript',
//             'json' => 'application/json',
//             'xml' => 'application/xml',
//             'swf' => 'application/x-shockwave-flash',
//             'flv' => 'video/x-flv',

//             // images
//             'png' => 'image/png',
//             'jpe' => 'image/jpeg',
//             'jpeg' => 'image/jpeg',
//             'jpg' => 'image/jpeg',
//             'gif' => 'image/gif',
//             'bmp' => 'image/bmp',
//             'ico' => 'image/vnd.microsoft.icon',
//             'tiff' => 'image/tiff',
//             'tif' => 'image/tiff',
//             'svg' => 'image/svg+xml',
//             'svgz' => 'image/svg+xml',

//             // archives
//             'zip' => 'application/zip',
//             'rar' => 'application/x-rar-compressed',
//             'exe' => 'application/x-msdownload',
//             'msi' => 'application/x-msdownload',
//             'cab' => 'application/vnd.ms-cab-compressed',

//             // audio/video
//             'mp3' => 'audio/mpeg',
//             'qt' => 'video/quicktime',
//             'mov' => 'video/quicktime',

//             // adobe
//             'pdf' => 'pdf-o',
//             'psd' => 'image/vnd.adobe.photoshop',
//             'ai' => 'application/postscript',
//             'eps' => 'application/postscript',
//             'ps' => 'application/postscript',

//             // ms office
//             'doc' => 'application/msword',
//             'rtf' => 'application/rtf',
//             'xls' => 'application/vnd.ms-excel',
//             'ppt' => 'application/vnd.ms-powerpoint',

//             // open office
//             'odt' => 'application/vnd.oasis.opendocument.text',
//             'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
//         );

//         if (array_key_exists($theext, $mime_types)) {
// 			return '<i class="fa fa-file-' . $mime_types[$theext] . '" aria-hidden="true"></i>';
//         }
//         else {
// 			return 'nomime';
//         }
//     }
// };

// echo extcheck($theurl);
?>




		<?php the_content(); ?>
		<?php edit_post_link( __( '(Edit)', 'foundationpress' ), '<span class="edit-link">', '</span>' ); ?>
	</div>
	<footer>
		<?php
			wp_link_pages(
				array(
					'before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ),
					'after'  => '</p></nav>',
				)
			);
		?>
		<?php $tag = get_the_tags(); if ( $tag ) { ?><p><?php the_tags(); ?></p><?php } ?>
	</footer>
</article>
