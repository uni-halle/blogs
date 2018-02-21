<?php
class marker_groupsViewGmp extends viewGmp {
	public function getTabContent() {
		frameGmp::_()->getModule('templates')->loadJqGrid();
		frameGmp::_()->addScript('admin.mgr.list', $this->getModule()->getModPath() . 'js/admin.marker_groups.list.js');
		frameGmp::_()->addJSVar('admin.mgr.list', 'mgrTblDataUrl', uriGmp::mod('marker_groups', 'getListForTbl', array('reqType' => 'ajax')));
		frameGmp::_()->addStyle('admin.mgr', $this->getModule()->getModPath() . 'css/admin.marker.groups.css');

		$this->assign('addNewLink', frameGmp::_()->getModule('options')->getTabUrl('marker_groups_add_new'));
		return parent::getContent('mgrAdmin');
	}
	public function getEditMarkerGroup($id = 0) {
		frameGmp::_()->addScript('admin.mgr.edit', $this->getModule()->getModPath(). 'js/admin.marker_groups.edit.js');
		frameGmp::_()->addStyle('admin.mgr', $this->getModule()->getModPath() . 'css/admin.marker.groups.css');
		$editMarkerGroup = $id ? true : false;
		$allMarkerGroups = $this->getModel()->getAllMarkerGroups();
		$parentsList = array( 0 => __('None', GMP_LANG_CODE) );
		foreach($allMarkerGroups as $index => $group) {
			if($group['id'] == $id) continue;
			$parentsList[$group['id']] = $group['title'];
		}
		if($editMarkerGroup) {
			$markerGroup = $this->getModel()->getMarkerGroupById( $id );
			$this->assign('marker_group', $markerGroup);
			frameGmp::_()->addJSVar('admin.mgr.edit', 'mgrMarkerGroup', $markerGroup);
		}
		$this->assign('editMarkerGroup', $editMarkerGroup);
		$this->assign('parentsList', $parentsList);
		$this->assign('addNewLink', frameGmp::_()->getModule('options')->getTabUrl('marker_groups_add_new'));
		return parent::getContent('mgrEditMarkerGroup');
	}
	public function getListOperations($markerGroup) {
		$this->assign('marker_group', $markerGroup);
		$this->assign('editLink', $this->getModule()->getEditMarkerGroupLink( $markerGroup['id'] ));
		return parent::getContent('mgrListOperations');
	}
}