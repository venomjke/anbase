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
}