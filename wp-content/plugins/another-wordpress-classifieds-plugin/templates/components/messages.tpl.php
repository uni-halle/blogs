<div id="<?php echo esc_attr( 'awpcp-messages-' . $component_id ); ?>" class="awpcp-messages" data-component-id="<?php echo esc_attr( $component_id ); ?>">
    <ul class="awpcp-messages-list" data-bind="foreach: { data: messages, as: 'message' }">
        <li data-bind="css: [ 'awpcp-message', message.type ].join( ' ' ), html: message.content"></li>
    </ul>
</div>
