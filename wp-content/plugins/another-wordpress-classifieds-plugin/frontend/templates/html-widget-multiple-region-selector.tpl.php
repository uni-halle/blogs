<div id="awpcp-multiple-region-selector-<?php echo esc_attr( $uuid ); ?>" class="awpcp-multiple-region-selector" uuid="<?php echo esc_attr( $uuid ); ?>">

    <ul data-bind="foreach: regions">
        <li class="awpcp-region-selector">
            <ul class="awpcp-region-selector-partials" data-bind="foreach: partials">
                <li class="awpcp-region-selector-partial" data-bind="visible: visible">
                    <label data-bind="attr: { 'for': id }, text: label"></label>

                    <select class="multiple-region" data-bind="attr: { id: id }, options: options, optionsText: 'name', optionsValue: 'id', optionsCaption: caption, value: selectedOption, visible: showSelectField, disable: $root.options.disabled">
                    </select>

                    <input class="multiple-region inputbox" type="text" data-bind="attr: { id: id }, value: selectedText, visible: showTextField, disable: $root.options.disabled" />

                    <span class="loading-message" data-bind="visible: loading"><?php echo esc_html( _x( 'loading...', 'loading region options', 'AWPCP' ) ); ?></span>

                    <input type="hidden" data-bind="attr: { name: param }, value: selected" />
                </li>
            </ul>

            <a class="button remove-region" href="#" data-bind="click: $root.onRemoveRegion(), visible: $root.showRemoveRegionButton, text: $root.getLocalizedText('remove-region')"></a>
            <span class="awpcp-error" data-bind="text: error, visible: error"></span>
        </li>
    </ul>

    <a class="button add-region" href="#" data-bind="click: onAddRegion, visible: showAddRegionButton, text: $root.getLocalizedText('add-region')"></a>
    <?php echo awpcp_form_error('regions', $errors); ?>
</div>
