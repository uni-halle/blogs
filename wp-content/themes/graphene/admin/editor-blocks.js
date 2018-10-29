jQuery(document).ready(function($) {

	/* Adjust the content area width as page template is changed */
	$(document).on('change', '.editor-page-attributes__template select', function(){
		template = $(this).val();

		if 		( template.indexOf('onecolumn') > -1 ) 		width = grapheneEditorJs.widthOneCol;
		else if ( template.indexOf('twocolumn') > -1 ) 		width = grapheneEditorJs.widthTwoCol;
		else if ( template.indexOf('threecolumn') > -1 ) 	width = grapheneEditorJs.widthThreeCol;
		else 												width = grapheneEditorJs.contentWidth;

		wideWidth = Math.floor( width * 1.25 );
		$('#graphene-editor-css').append('\
			body.gutenberg-editor-page .editor-post-title__block,\
			body.gutenberg-editor-page .editor-default-block-appender,\
			body.gutenberg-editor-page .editor-block-list__block {\
			    max-width: ' + width + 'px !important;\
			}\
			body.gutenberg-editor-page .editor-block-list__block[data-align="wide"] {\
			    max-width: ' + wideWidth + 'px !important;\
			}\
		');
		console.log( width );
	});
});