<?php
class SandBoxSandBoxController extends w8v_Controller_Action_AdminMenu {
	public function SandBoxAction($content) {
		$this->debug ( $this->application ()->Settings ()->name );
		return $content;
	}
}