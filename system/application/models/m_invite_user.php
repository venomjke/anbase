<?php defined("BASEPATH") or die("No direct access to script");



/**
 * Модель таблицы invites_users
 *
 * @package default
 * @author alex.strigin
 **/
class M_Invite_user extends MY_Model
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();

		/*
		* Определение свойств
		*
		*/
		$this->table = "invites_users";
		$this->primary_key = "id";
		$this->fields = array('id','key_id','email','role','create_date','manager_id','org_id');
		$this->result_mode = 'object';
		/*
		*
		* Правила валидации
		*/
		$this->validate = array(
			array('field'=>'email', 'label'=>'lang:label_email', 'rules'=>'xss_clean|valid_email'),
			array('field'=>'manager_id', 'label'=>'Manager Id', 'rules'=>'is_natural')
		);
	}

	/**
	 * Получение правил валидации
	 *
	 * @return void
	 * @author 
	 **/
	public function get_validation_rules()
	{
		return $this->validate;
	}
} // END class M_Invite_user extends MY_Model