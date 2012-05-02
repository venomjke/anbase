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


	public $edit_validation_rules = array(
		array('field'=>'name','label'=>'lang:label_org_name','rules'=>'trim|xss_clean|min_length[3]|max_length[150]'),
		array('field'=>'email','label'=>'lang:label_org_email','rules'=>'trim|xss_clean|min_length[5]|max_length[100]'),
		array('field'=>'phone','label'=>'lang:label_org_phone','rules'=>'trim|valid_phone')
	);

	public function __construct(){

		parent::__construct();

		/*
		*
		*	Структура модели
		*
		*/
		$this->table 	   = 'organizations';
		$this->primary_key = 'id';
		$this->fields      = array('id','name','ceo','email','phone');
		$this->result_mode = 'object';
		
		/*
		*
		*	Правила валидации
		*
		*/
		$this->validate    = array(
			array('field'=>'name','label'=>'lang:label_org_name','rules'=>'trim|xss_clean|min_length[3]|max_length[150]'),
			array('field'=>'email','label'=>'lang:label_org_email','rules'=>'trim|xss_clean|min_length[5]|max_length[100]'),
			array('field'=>'phone','label'=>'lang:label_org_phone','rules'=>'trim|valid_phone')
		);
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

}