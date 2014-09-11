jQuery(document).ready(function() {
 // jQuery('#tabvanilla > ul').tabs({ fx: { height: 'toggle', opacity: 'toggle' } });
 jQuery('#tabvanilla').tabs({ fx: { height: 'toggle', opacity: 'toggle' } }); // Targeting the main parent container of the tabbed widget
 jQuery('#featuredvid > ul').tabs();
});