<?php defined("BASEPATH") or die("No direct access to script");



/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*	@author  - Alex.strigin
*	@company - Flyweb 
*
*/
class M_Admin extends M_User{

	function __construct(){

		parent::__construct();
	}

	/**
	 * Метод возвращает правила валидации
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_validation_rules()
	{
		return $this->validate;
	}


	/**
	 * Выбор списка сотрудников компании ( Менеджер | Админ )
	 *
	 * @return void
	 * @author 
	 **/
	public function get_list_staff($org_id)
	{
		$this->where("(users.role = '".M_User::USER_ROLE_MANAGER."' OR users.role = '".M_User::USER_ROLE_AGENT."')");
		return $this->get_users_organization($org_id);
	}

}