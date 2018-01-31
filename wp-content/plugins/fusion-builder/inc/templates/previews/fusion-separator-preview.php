<?php
global $fusion_settings;
if ( ! $fusion_settings ) {
	$fusion_settings = Fusion_Settings::get_instance();
}

$sep_style_type = $fusion_settings->get( 'separator_style_type' );
$sep_border_color = $fusion_settings->get( 'sep_color' );
$sep_border_size = $fusion_settings->get( 'separator_border_size' );

?>
<script type="text/template" id="fusion-builder-block-module-separator-preview-template">
	<# var style_type = 'default' === params.style_type ? '<?php echo esc_attr( $sep_style_type ); ?>' : params.style_type;
	if ( params.style_type === 'none' || ( 'default' === params.style_type && 'none' === style_type  ) ) { #>
		<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}</h4>
	<# } else {
		if ( style_type == "single|solid") {
			var sep_style = "sep-single sep-solid";

		} else if ( style_type == "single|dotted") {
			var sep_style = "sep-single sep-dotted";

		} else if ( style_type == "single|dashed") {
			var sep_style = "sep-single sep-dashed";

		} else if ( style_type == "double|solid") {
			var sep_style = "sep-double sep-solid";

		} else if ( style_type == "double|dashed") {
			var sep_style = "sep-double sep-dashed";

		} else if ( style_type == "double|dotted") {
			var sep_style = "sep-double sep-dotted";

		} else {
			var sep_style = "sep-" + style_type;
		}

		var sep_border_size = ( '' === params.border_size ) ? '<?php echo esc_attr( $sep_border_size ); ?>' : params.border_size;
		var sep_border_color = ( '' === params.sep_color ) ? '<?php echo esc_attr( $sep_border_color ); ?>' : params.sep_color;

		var alignment = 'margin:0 auto';
		if ( 'center' != params.alignment ) {
			alignment = 'float:' + params.alignment;
		}
		#>

		<div class="fusion-separator fusion-full-width-sep {{ sep_style }}" style= "{{ alignment }};width:{{ params.width }};border-width:{{ sep_border_size }}px;border-color:{{ sep_border_color }};border-left:none;border-right:none;"></div>
	<# } #>

</script>
