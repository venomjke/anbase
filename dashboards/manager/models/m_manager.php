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
		array('field' => 'login', 'label' => 'lang:label_login', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[50]|alpha_dash|is_unique[users.login]'),
		array('field' => 'password', 'label' => 'lang:label_password','rules' => 'required|trim|xss_clean|min_length[6]|max_length[200]|alpha_dash'),
		array('field' => 're_password', 'label' => 'lang:label_password','rules'=>'required|trim|xss_clean|matches[password]'),
		array('field' => 'name', 'label' => 'lang:label_name', 'rules' => 'required|min_length[3]|max_length[15]|trim|xss_clean'),
		array('field' => 'middle_name', 'label' => 'lang:label_middle_name', 'rules' => 'required|min_length[3]|max_length[15]|trim|xss_clean'),
		array('field' => 'last_name', 'label' => 'lang:label_last_name', 'rules' => 'required|min_length[3]|max_length[15]|trim|xss_clean'),
		array('field' => 'phone', 'label' => 'lang:label_phone','rules'=>'required|trim|xss_clean|min_length[9]|max_length[20]')
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