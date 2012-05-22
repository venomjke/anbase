<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Класс, реализующий логику обработки данных таблицы manager_user
 *
 * @package default
 * @author alex.strigin 
 **/
class M_Manager_user extends MY_Model
{

	/*
	*
	* Правила валидации во время unbind manager от agent
	*/
	public $unbind_manager_validation_rules = array(
		array('field'=>'user_id','label'=>'USER ID','rules'=>'required|is_natural|is_valid_user_id')
	);
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
		$this->primary_key = 'user_id';
		$this->fields = array('user_id','manager_id');

		$this->result_mode = 'object';

		/*
		*
		* Правила валидации
		*/
		$this->validate = array(
			array('field'=>'manager_id', 'label'=>'Manager Id', 'rules' => 'required|is_natural|is_manager_org'),
			array('field'=>'user_id','label'=>'User Id','rules'=>'required|is_natural|is_valid_user_id')
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
	 * Отвязать менеджера от агента
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function unbind_manager($user_id)
	{
		return $this->delete(array('user_id'=>$user_id));
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

	public function get_manager_agents($manager_id)
	{
		$this->select("users.*");
		$this->join("managers_users","managers_users.user_id = users.id");
		$this->db->where('managers_users.manager_id',$manager_id);
		return $this->db->get("users")->result();
	}
} // END class M_Manager_user extends MY_Model