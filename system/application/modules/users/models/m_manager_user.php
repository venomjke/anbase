<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Класс, реализующий логику обработки данных таблицы manager_user
 *
 * @package default
 * @author alex.strigin 
 **/
class M_Manager_user extends MY_Model
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
		*
		*
		* Структура модели
		*
		*/
		$this->table = 'managers_users';
		$this->primary_key = 'id';
		$this->fields = array('id','user_id','manager_id');

		$this->result_mode = 'object';

		/*
		*
		* Правила валидации
		*/
		$this->validate = array(
			array('field'=>'manager_id', 'label'=>'Manager Id', 'rules' => 'required|is_natural'),
			array('field'=>'user_id','label'=>'User Id','rules'=>'required|is_natural')
		);
	}


	/**
	 * Проверка, есть ли у юзера менеджер
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function has_manager($user_id)		
	{
		return $this->count_all_results(array('user_id'=>$user_id)) == 0?false:true;	
	}
	

	/**
	 * Возвращаем правила валидации
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_validation_rules()
	{
		return $this->validate;
	}
} // END class M_Manager_user extends MY_Model