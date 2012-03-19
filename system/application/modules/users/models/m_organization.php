<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model organizations represents organization  data. 
*	tables: organizations
*
* @author  - Alex.strigin 
* @company - Flyweb 
*/

class M_Organization extends MY_Model{


	public function __construct(){

		parent::__construct();

		/*
		*
		*	Структура модели
		*
		*/
		$this->table 	   = 'organizations';
		$this->primary_key = 'id';
		$this->fields      = array('id','name','ceo');
		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*
		*/
		$this->validate    = array();
	}

}