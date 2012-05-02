<?php defined("BASEPATH") or die("No direct access to script");



/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*	@author  - Alex.strigin
*	@company - Flyweb 
*
*/
class M_Admin extends M_User{

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

	/*
	*
	* Правила валидации при смене должности
	*/
	public $change_position_employee_validation_rules = array(
		array('field'=>'id', 'label'=>'USER ID', 'rules'=>'required|is_valid_user_id'),
		array('field'=>'role', 'label'=>'USER ROLE', 'rules'=>'required|is_valid_user_role')
	);

	function __construct(){

		parent::__construct();
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


	/**
	 * Смена должности сотрудника. Правила преобразования при смене описаны в док. "Управление пользователями"
	 *
	 * @param int
	 * @param string
	 * @param string
	 * @return void
	 * @author 	alex.strigin
	 **/
	public function change_position($user_id,$old_role,$new_role)
	{
		/*
		*
		* Изменения происходят только при переходах Агент -> ( Менеджер | Админ ), а также ( Менеджер | Админ ) -> Агент
		*/
		if( ($old_role == M_User::USER_ROLE_MANAGER or $old_role == M_User::USER_ROLE_ADMIN) && $new_role == M_User::USER_ROLE_AGENT){

			$this->update($user_id,array('role' => $new_role),true);
			/*
			*
			* освободить бедных агентов из рабства =)
			*/
			$this->db->delete('managers_users',array('manager_id'=>$user_id)); 
		}else if( $old_role == M_User::USER_ROLE_AGENT and ($new_role == M_User::USER_ROLE_ADMIN or $new_role == M_User::USER_ROLE_MANAGER) ){
			$this->update($user_id,array('role' =>$new_role),true);
			/*
			*
			* высвободить агента из рабства
			*/
			$this->db->delete('managers_users',array('user_id'=>$user_id));
		}else{
			$this->update($user_id,array('role'=>$new_role),true);
		}
	}

}