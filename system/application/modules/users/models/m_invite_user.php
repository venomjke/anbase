<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model invites users represents date that need for invite login 
*	tables: invites_users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*/
class M_Invite_user extends MY_Model{


	public function __construct(){

		/*
		*
		*	Структура модели
		*
		*/
		$this->table 	   = 'invites_users';
		$this->primary_key = 'user_id';
		$this->fields 	   = array('key_id','user_id','user_agent','last_ip','last_login');
		$this->result_mode = 'object';
		/*
		*
		*
		*	Правила валидации
		*
		*/
		$this->validate = array();
	}
}