jQuery(document).ready(function(){
	var tblId = 'gmpMgrTbl';
	jQuery('#'+ tblId).jqGrid({
		url: mgrTblDataUrl
	,	datatype: 'json'
	,	autowidth: true
	,	shrinkToFit: true
	,	colNames:[toeLangGmp('ID'), toeLangGmp('Title'), toeLangGmp('Parent'), toeLangGmp('Actions')]
	,	colModel:[
			{name: 'id', index: 'id', searchoptions: {sopt: ['eq']}, width: '50', align: 'center', key: true}
		,	{name: 'title', index: 'title', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'parent', index: 'parent', searchoptions: {sopt: ['eq']}, align: 'center', sortable: false}
		,	{name: 'actions', index: 'actions', searchoptions: {sopt: ['eq']}, align: 'center', sortable: false}
		]
	,	postData: {
			search: {
				text_like: jQuery('#'+ tblId+ 'SearchTxt').val()
			}
		}
	,	rowNum: 10
	,	rowList: [10, 20, 30, 1000]
	,	pager: '#'+ tblId+ 'Nav'
	,	sortname: 'sort_order'
	,	viewrecords: true
	,	sortorder: 'asc'
	,	jsonReader: { repeatitems : false, id: '0' }
	,	caption: toeLangGmp('Current Marker Category')
	,	height: '100%'
	,	emptyrecords: toeLangGmp('You have no Marker Categories for now.')
	,	multiselect: true
	,	sortable: true
	,	onSelectRow: function(rowid, e) {
			var tblId = jQuery(this).attr('id')
			,	selectedRowIds = jQuery('#'+ tblId).jqGrid ('getGridParam', 'selarrrow')
			,	totalRows = jQuery('#'+ tblId).getGridParam('reccount')
			,	totalRowsSelected = selectedRowIds.length;
			if(totalRowsSelected) {
				jQuery('#gmpMgrRemoveGroupBtn').removeAttr('disabled');
				if(totalRowsSelected == totalRows) {
					jQuery('#cb_'+ tblId).prop('indeterminate', false);
					jQuery('#cb_'+ tblId).attr('checked', 'checked');
				} else {
					jQuery('#cb_'+ tblId).prop('indeterminate', true);
				}
			} else {
				jQuery('#gmpMgrRemoveGroupBtn').attr('disabled', 'disabled');
				jQuery('#cb_'+ tblId).prop('indeterminate', false);
				jQuery('#cb_'+ tblId).removeAttr('checked');
			}
			gmpCheckUpdate(jQuery(this).find('tr:eq('+rowid+')').find('input[type=checkbox].cbox'));
			gmpCheckUpdate('#cb_'+ tblId);
		}
	,	gridComplete: function(a, b, c) {
			var tblId = jQuery(this).attr('id');
			jQuery('#gmpMgrRemoveGroupBtn').attr('disabled', 'disabled');
			jQuery('#cb_'+ tblId).prop('indeterminate', false);
			jQuery('#cb_'+ tblId).removeAttr('checked');
			if(jQuery('#'+ tblId).jqGrid('getGridParam', 'records'))	// If we have at least one row - allow to clear whole list
				jQuery('#gmpMgrClearBtn').removeAttr('disabled');
			else
				jQuery('#gmpMgrClearBtn').attr('disabled', 'disabled');
			// Custom checkbox manipulation
			gmpInitCustomCheckRadio('#'+ jQuery(this).attr('id') );
			gmpCheckUpdate('#cb_'+ jQuery(this).attr('id'));
			tooltipsterize( jQuery('#'+ tblId) );
		}
	,	loadComplete: function() {
			var tblId = jQuery(this).attr('id');
			if (this.p.reccount === 0) {
				jQuery(this).hide();
				jQuery('#'+ tblId+ 'EmptyMsg').show();
			} else {
				jQuery(this).show();
				jQuery('#'+ tblId+ 'EmptyMsg').hide();
			}
		}
	}).jqGrid('sortableRows', {
		update: function (e, ui) {
			var ids = jQuery('#'+ tblId).jqGrid('getDataIDs');
			jQuery.sendFormGmp({
				data: { mod: 'marker_groups', action: 'resortMarkerGroups', ids: ids }
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#gmpMarkersListGrid').trigger('reloadGrid');
					}
				}
			});
		}
	});
	jQuery('#'+ tblId+ 'NavShell').append( jQuery('#'+ tblId+ 'Nav') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-selbox').insertAfter( jQuery('#'+ tblId+ 'Nav').find('.ui-paging-info') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-table td:first').remove();
	jQuery('#'+ tblId+ 'SearchTxt').keyup(function(){
		var searchVal = jQuery.trim( jQuery(this).val() );
		if(searchVal && searchVal != '') {
			gmpGridDoListSearch({
				text_like: searchVal
			}, tblId);
		}
	});
	jQuery('#'+ tblId+ 'EmptyMsg').insertAfter(jQuery('#'+ tblId+ '').parent());
	jQuery('#'+ tblId+ '').jqGrid('navGrid', '#'+ tblId+ 'Nav', {edit: false, add: false, del: false});
	jQuery('#cb_'+ tblId+ '').change(function(){
		jQuery(this).attr('checked')
			? jQuery('#gmpMgrRemoveGroupBtn').removeAttr('disabled')
			: jQuery('#gmpMgrRemoveGroupBtn').attr('disabled', 'disabled');
	});
	jQuery('#gmpMgrRemoveGroupBtn').click(function(){
		var selectedRowIds = jQuery('#gmpMgrTbl').jqGrid ('getGridParam', 'selarrrow')
		,	listIds = [];
		for(var i in selectedRowIds) {
			var rowData = jQuery('#gmpMgrTbl').jqGrid('getRowData', selectedRowIds[ i ]);
			listIds.push( rowData.id );
		}
		var mapLabel = '';
		if(listIds.length == 1) {	// In table label cell there can be some additional links
			var labelCellData = gmpGetGridColDataById(listIds[0], 'title', 'gmpMgrTbl');
			mapLabel = labelCellData;
		}
		var confirmMsg = listIds.length > 1
			? toeLangGmp('Are you sur want to remove '+ listIds.length+ ' marker categories?')
			: toeLangGmp('Are you sure want to remove "'+ mapLabel+ '" marker category?')
		if(confirm(confirmMsg)) {
			jQuery.sendFormGmp({
				btn: this
			,	data: {mod: 'marker_groups', action: 'removeGroup', listIds: listIds}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#gmpMgrTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});
	jQuery('#gmpMgrClearBtn').click(function(){
		if(confirm(toeLangGmp('Clear whole marker categories list?'))) {
			jQuery.sendFormGmp({
				btn: this
			,	data: {mod: 'marker_groups', action: 'clear'}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#gmpMgrTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});

	gmpInitCustomCheckRadio('#'+ tblId+ '_cb');
});
function gmpRemoveMarkerGroupFromTblClick(markerGroupId){
	if(!confirm(toeLangGmp('Remove Marker Category?'))) {
		return false;
	}
	if(markerGroupId == ''){
		return false;
	}
	var msgEl = jQuery('#mgrRemoveElemLoader__'+ markerGroupId);

	jQuery.sendFormGmp({
		msgElID: msgEl
	,	data: {action: 'remove', mod: 'marker_groups', id: markerGroupId}
	,	onSuccess: function(res) {
			if(!res.error){
				jQuery('#gmpMgrTbl').trigger( 'reloadGrid' );
				setTimeout(function(){
					msgEl.hide('500', function(){
						jQuery(this).parents('tr:first').remove();
					});
				}, 500);
			}
		}
	});
}