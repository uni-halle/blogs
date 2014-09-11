<?php
class SettingsDeliciousLinkSyncController extends w8v_Controller_Action_AdminMenu
{

	public function SettingsAction ( $content )
	{
		$dataObj = new DLinkSyncData ( );
		$this->view->data = $dataObj->post ();
		if ($_SERVER ['REQUEST_METHOD'] == 'POST' && $this->view->data ['id'] != '')
		{
			$dataObj->synclinks($this->view->data ['id'],$this->view->data ['password']);
		}
		return $content.$this->updated();
	}
}
