<?php defined("BASEPATH") or die("No direct access to script");

/*
*	Это джигурда для того, чтобы не возникло проблем во время Extends
*/
if(!class_exists("Users"))
	require_once APPPATH."modules/users/libraries/users.php";

/**
*
*	@author  - Alex.strigin
*	@company - Flyweb
*
*/
class Admin_Users extends Users{

	function __construct() {
		
		parent::__construct();
		
		/*
		*
		*	Загрузка моделей
		*
		*/
		$this->ci->load->model('admin/m_admin');
	}


	/**
	 * Залогинен ли пользователь как админ, а?
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function is_logged_in_as_admin()
	{
		if ($this->is_logged_in() && $this->is_admin()) {	
			return true;
		}
		return false;
	}

}


