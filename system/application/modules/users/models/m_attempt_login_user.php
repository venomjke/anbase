<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model users represents attempts users to log in. 
*	tables: attempts_login_users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*
*/

class M_Attempt_login_user extends MY_Model {


	public function __construct(){

		/*
		*
		*	Структура модели
		*
		*/
		$this->table 	   = 'attempts_login_users';
		$this->primary_key = 'id';
		$this->fields 	   = array('id','ip_address','time','login'); 
		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*
		*/
		$this->validate    = array();
	}
}