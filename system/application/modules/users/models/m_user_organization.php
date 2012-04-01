<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*   Model user_organization represents relationship bettwen user and organization
*   tables: autologin_users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*
*/


class M_User_organization extends MY_Model{

	public function __construct(){

		/*
		*
		* Структура модели
		*
		*/
		$this->table 	   = 'users_organizations';
		$this->primary_key = 'id';
		$this->fields      = array('id','org_id','user_id');
		$this->result_mode = 'object';

		/*
		*
		*	Правила валидации
		*
		*/
		$this->validate = array();

	}

	/**
	 * Обертка над get_full_user_info
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get($where)
	{
		return $this->get_full_user_info($where);
	}


	/**
	 * Метод выбирает инфу о юзере из таблицы users_organizations присоединяя также инфу
	 * из users и orgs, а именно из users fio,role,phone а из orgs ceo
	 * 
	 * @param int id
	 * @return void
	 * @author 
	 **/
	public function get_full_user_info($where)
	{
		$this->select('
			users_organizations.org_id,
			users_organizations.user_id,
			users.name,
			users.last_name,
			users.middle_name,
			users.role,
			users.phone,
			organizations.ceo
		')->join('users','users.id = users_organizations.user_id')
		  ->join('organizations','users_organizations.org_id = organizations.id');

		return parent::get($where);
	}
}