<div id="awpcp-multiple-region-selector-<?php echo esc_attr( $uuid ); ?>" class="awpcp-multiple-region-selector awpcp-multiple-region-selector-form-table" uuid="<?php echo esc_attr( $uuid ); ?>">
    <div data-bind="foreach: regions">
        <table class="awpcp-region-selector form-table">
            <tbody class="awpcp-region-selector-partials" data-bind="foreach: partials">
                <tr class="awpcp-region-selector-partial" data-bind="visible: visible">
                    <th scope="row">
                        <label data-bind="attr: { 'for': id }, text: label"></label>
                    </th>
                    <td>
                        <select class="multiple-region" data-bind="attr: { id: id }, options: options, optionsText: 'name', optionsValue: 'id', optionsCaption: caption, value: selectedOption, visible: showSelectField, disable: $root.options.disabled">
                        </select>

                        <input class="multiple-region regular-text" type="text" data-bind="attr: { id: id }, value: selectedText, visible: showTextField, disable: $root.options.disabled" />

                        <span class="loading-message" data-bind="visible: loading"><?php echo esc_html( _x( 'loading...', 'loading region options', 'AWPCP' ) ); ?></span>

                        <input type="hidden" data-bind="attr: { name: param }, value: selected" />
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr data-bind="visible: error">
                    <td colspan="2">
                        <span class="awpcp-error" data-bind="text: error"></span>
                    </td>
                </tr>
                <tr data-bind="visible: $root.showRemoveRegionButton">
                    <td colspan="2">
                        <a class="button remove-region" href="#" data-bind="click: $root.onRemoveRegion(), text: $root.getLocalizedText('remove-region')"></a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <a class="button add-region" href="#" data-bind="click: onAddRegion, visible: showAddRegionButton, text: $root.getLocalizedText('add-region')"></a>
    <?php echo awpcp_form_error('regions', $errors); ?>
</div>
