<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model users represents user auth data. 
*	tables: users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*/
class M_User extends MY_Model{


	/*
	*
	*	Возможные состояния пользователя
	*
	*/
	const USER_ACTIVE	   = 1;
	const USER_NON_ACTIVE  = 0;
	
	/*
	*
	*	Основные роли пользователей
	*
	*/
	const USER_ROLE_ADMIN 	= 'Админ';
	const USER_ROLE_MANAGER = 'Менеджер';
	const USER_ROLE_AGENT   = 'Агент';


	public function __construct(){

		parent::__construct();

		/*
		*
		*	Структура таблицы
		*
		*/
		$this->table       = 'users';
		$this->primary_key = 'id';
		$this->fields      =  array('id','login','password','email','activated','name','middle_name','last_name','phone','role','created','last_login','last_ip');

		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*/
		$this->validate = array();
	}


}