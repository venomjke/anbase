<?php defined("BASEPATH") or die("No direct access to script");


/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*
*/
class M_Agent extends M_User{


	/**
	 * Правила валидации данных при регистрации агента.
	 *
	 * Валидируем только те данные, которые передаем.
	 * @var array
	 **/
	public $register_validation_rules = array(
		array('field' => 'login', 'label' => 'lang:label_login', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[50]|alpha_dash|is_unique[users.login]'),
		array('field' => 'password', 'label' => 'lang:label_password','rules' => 'required|trim|xss_clean|min_length[6]|max_length[200]|alpha_dash'),
		array('field' => 're_password', 'label' => 'lang:label_password','rules'=>'required|trim|xss_clean|matches[password]'),
		array('field' => 'name', 'label' => 'lang:label_name', 'rules' => 'required|min_length[3]|max_length[15]|trim|xss_clean'),
		array('field' => 'middle_name', 'label' => 'lang:label_middle_name', 'rules' => 'required|min_length[3]|max_length[15]|trim|xss_clean'),
		array('field' => 'last_name', 'label' => 'lang:label_last_name', 'rules' => 'required|min_length[3]|max_length[15]|trim|xss_clean'),
		array('field' => 'phone', 'label' => 'lang:label_phone','rules'=>'required|trim|xss_clean|min_length[9]|max_length[20]')
	);

	public $personal_profile_validation_rules = array(
		array('field' => 'name', 'label' => 'lang:label_name', 'rules' => 'trim|xss_clean|min_length[3]|max_length[15]'),
		array('field' => 'middle_name', 'label' => 'lang:label_middle_name', 'rules' => 'trim|xss_clean|min_length[3]|max_length[15]'),
		array('field' => 'last_name', 'label' => 'lang:label_last_name', 'rules' => 'trim|xss_clean|min_length[3]|max_length[15]'),
		array('field' => 'phone', 'label' => 'lang:label_phone','rules'=>'trim|valid_phone')
	);

	public $system_profile_validation_rules  = array(
		array( 'field' => 'password', 'label' => 'Пароль', 'rules' => 'required|min_length[6]|max_length[20]'),
		array( 'field' => 'new_password', 'label' => 'Новый пароль', 'rules'=>'required|min_length[6]|max_length[20]'),
		array( 'field' => 're_new_password', 'label' => 'Копия пароля','rules' => 'required|matches[new_password]|min_length[6]|max_length[20]')
	);

	public function __construct(){
		parent::__construct();

	}
	/**
	 * Подготовка запроса к выбору данных об менеджере
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _build_manager_query()
	{
		$this->select("users.id");
		$this->select("users.name");
		$this->select("users.middle_name");
		$this->select("users.last_name");
		$this->select("users.phone");


		$this->from("managers_users");
		$this->join("users","managers_users.manager_id = users.id");
	}

	/**
	 * Метод, определяющй есть ли у агента менеджер
	 *
	 * @return bool
	 * @author Alex.strigin
	 **/
	public function has_manager($user_id)
	{
		$this->_build_manager_query();
		$this->where("user_id =",$user_id);
		return $this->db->count_all_results() == 1?TRUE:FALSE;
	}

	/**
	 * Метод, возвращающий объект менеджера
	 *
	 * @return object
	 * @author Alex.strigin
	 **/
	public function get_manager_agent($user_id)
	{
		$this->_build_manager_query();
		$this->where("user_id =",$user_id);
		$this->limit(1);
		$manager = $this->db->get();

		if($manager->num_rows() == 1){
			return $manager->row();
		}
		return NULL;
	}
}