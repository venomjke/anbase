<?php defined("BASEPATH") or die("No direct access to script");


/*
*	Это джигурда для того, чтобы не возникло проблем во время Extends
*/
if(!class_exists("Users"))
	require_once APPPATH."modules/users/libraries/users.php";

/**
* @author  Alex.strigin <apstrigin@gmail.com>
* @company Flyweb
*/
class Manager_Users extends Users
{
	
	function __construct()
	{
		parent::__construct();

		/*
		*
		*	Загрузка моделей
		*/
		$this->ci->load->model('manager/m_manager');

	}


	/**
	 * Проверка, является ли user манагером
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function is_logged_in_as_manager ()
	{
		if($this->is_logged_in() && $this->is_manager()){

			return TRUE;
		}
		return FALSE;
	}

}