<?php defined("BASEPATH") or die("No direct access to script");



/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";


/**
* @author Alex.strigin <apstrigin@gmail.com>
*/
class M_Manager extends M_User
{

	
	/*
	* 
	* Правила валидации при регистрации.
	*/
	public $register_validation_rules = array(
		array('field' => 'r_login', 'label' => 'lang:label_login', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[50]|alpha_dash|is_unique[users.login]'),
		array('field' => 'r_password', 'label' => 'lang:label_password','rules' => 'required|trim|xss_clean|min_length[6]|max_length[200]|alpha_dash'),
		array('field' => 'r_re_password', 'label' => 'lang:label_password','rules'=>'required|trim|xss_clean|matches[r_password]'),
		array('field' => 'name', 'label' => 'lang:label_name', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[15]'),
		array('field' => 'middle_name', 'label' => 'lang:label_middle_name', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[15]'),
		array('field' => 'last_name', 'label' => 'lang:label_last_name', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[15]|'),
		array('field' => 'phone', 'label' => 'lang:label_phone','rules'=>'required|valid_phone')
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
	
	function __construct()
	{
		parent::__construct();
	}


	/**
	 * getter validation array
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_validation_rules()
	{
		return $this->validate;
	}

	
}