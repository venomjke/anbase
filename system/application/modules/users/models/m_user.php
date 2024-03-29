<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model users represents user auth data. 
*	tables: users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*/
class M_User extends MY_Model{


	/*
	*	Возможные состояния пользователя
	*/
	const USER_ACTIVE	   = 1;
	const USER_NON_ACTIVE  = 0;
	
	/*
	*	Основные роли пользователей
	*/
	const USER_ROLE_ADMIN 	= 0x01;
	const USER_ROLE_MANAGER = 0x02;
	const USER_ROLE_AGENT   = 0x03;

	public function __construct(){

		parent::__construct();

		/*
		*
		*	Структура таблицы
		*
		*/
		$this->table       = 'users';
		$this->primary_key = 'id';
		$this->fields      =  array('id','login','password','email','activated','name','middle_name','last_name','phone','role','created','last_login','last_ip');

		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*/
		$this->validate = array(
			'id'=>array('field' => 'id', 'label' => 'lang:label_user_id', 'rules' => 'integer'),
			'login'=>array('field' => 'login', 'label' => 'lang:label_login', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[50]|alpha_dash|is_unique[users.login]'),
			'email'=>array('field' => 'email', 'label' => 'lang:label_email', 'rules' => 'required|trim|xss_clean|valid_email|is_unique[users.email]'),
			'password'=>array('field' => 'password', 'label' => 'lang:label_password','rules' => 'required|trim|xss_clean|min_length[6]|max_length[200]|alpha_dash'),
			'name'=>array('field' => 'name', 'label' => 'lang:label_name', 'rules' => 'required|trim|xss_clean|min_length[1]|max_length[15]'),
			'middle_name'=>array('field' => 'middle_name', 'label' => 'lang:label_middle_name', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[15]'),
			'last_name'=>array('field' => 'last_name', 'label' => 'lang:label_last_name', 'rules' => 'required|trim|xss_clean|min_length[3]|max_length[15]'),
			'phone'=>array('field' => 'phone', 'label' => 'lang:label_phone','rules'=>'trim|valid_phone'),
			'role'=>array('field' => 'role', 'label' => 'lang:label_role', 'rules'=>'required|trim|xss_clean|is_valid_user_role')
		);

		/*
		*
		*	Хуки
		*/
		$this->before_create = array("before_insert_user");
	}


	public function get_role_list()
	{
		return array(M_User::USER_ROLE_ADMIN,M_User::USER_ROLE_MANAGER,M_User::USER_ROLE_AGENT);
	}

	public function get_assoc_role_list(){
		return array('USER_ROLE_ADMIN'=>M_User::USER_ROLE_ADMIN,'USER_ROLE_MANAGER'=>M_User::USER_ROLE_MANAGER,'USER_ROLE_AGENT'=>M_User::USER_ROLE_AGENT);
	}
	/**
	 * Проверка того, что данный пользователь существует и принадлежит к текущей орг.
	 *
	 * @return void
	 * @author 
	 **/
	public function is_exists($user_id,$org_id)
	{
		$this->select("users.id");
		$this->select("users_organizations.id as user_org_id");

		$this->join("users_organizations","users.id = users_organizations.user_id");

		$this->where("users.id",$user_id);
		$this->where("users_organizations.org_id",$org_id);

		return $this->db->count_all_results("users")==0?false:true;
	}


	/**
	 * Проверка, является ли указанный id - id manager принадлежащего опр. организации
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_manager_org($manager_id,$org_id)
	{
		$this->join("users_organizations","users.id = users_organizations.user_id");
		$this->db->where("users.id",$manager_id);
		$this->db->where("users.role",M_User::USER_ROLE_MANAGER);
		$this->db->where("users_organizations.org_id",$org_id);
		$this->db->limit(1);

		return $this->count_all_results() == 0?false:true;
	}

	/**
	 * Проверка роли
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function check_role($role)
	{
		if(in_array($role,array(M_User::USER_ROLE_AGENT,M_User::USER_ROLE_MANAGER,M_User::USER_ROLE_ADMIN))){
			return true;
		}else{
			$this->form_validation->set_message('check_role',lang('error_check_role'));
			return false;
		}
	}

	/**
	 * Проверка роли, не callback как в случае с check_role.
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_valid_role($role)
	{
		return in_array($role,array(M_User::USER_ROLE_AGENT,M_User::USER_ROLE_MANAGER,M_User::USER_ROLE_ADMIN));
	}

	/**
	 * Выбор пользователя по ID
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated = 1)
	{
		$this->db->where('activated',$activated ? 1 : 0);
		return $this->get($user_id);
	}

	/**
	 * Выбор пользователя по email или login
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login_or_email($login)
	{
		$this->_build_user_select();
		$this->db->where('login',$login)
				 ->or_where('email',$login);
		$query = $this->db->get($this->table);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Выбор пользователя по id
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		$this->_build_user_select();
		return $this->get(array('login'=>$login));
	}

	/**
	 * Выбор пользователя по email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		$this->_build_user_select();
		return $this->get(array('email'=>$email));
	}

	/**
	 * Проверка, свободен ли логин
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_login_available($login)
	{
		return $this->count_all_results(array('login'=>$login)) == 0;
	}

	/**
	 * Проверка, свободен ли email
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		return $this->count_all_results(array('email'=>$email)) == 0;
	}


	 /**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 *	Обновить информацию о пользователе, такую как IP-address и время login
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table);
	}

	/**
	*
	*	Функция, для выполнения перед созданием нового пользователя
	*
	*/
	function before_insert_user($data){
		$data['last_login'] = date('Y-m-d H:i:s');
		$data['last_ip'] = $this->input->ip_address();
		$data['created']    = $data['last_login'];
		$data['activated']  = 1;
		return $data;
	}


	/**
	 * Метод проходит по списку полей применяя к каждому свой фильтр
	 *
	 * @param array
	 * @return void
	 * @author alex.strigin 
	 **/
	protected function set_filter($filter = array())
	{

		foreach($filter as $field => $value ){

			$filter_name = "set_{$field}_filter";
			if(method_exists($this,$filter_name)){
				$this->$filter_name($value);
			}else{
				throw Exception("Undifned filter_name {$filter_name} in ".__CLASS__);
			}
		}
	}

	private function _build_user_select(){
		$this->select("users.id");
		$this->select("users.login");
		$this->select("users.email");
		$this->select("users.name");
		$this->select("users.middle_name");
		$this->select("users.last_name");
		$this->select("users.phone");
		$this->select("users.role");
		$this->select("users.password");
		$this->select("users.created");
		$this->select("users.modifed");
		$this->select("users.last_ip");
		$this->select("users.activated");
		$this->select("DATE_FORMAT(users.last_login,'%d.%m.%y %H:%i:%s') as last_login",FALSE);
	}

	/**
	 * Сборка select на выбор из трех таблиц.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _build_select()
	{
		$this->select("users.id");
		$this->select("users.login");
		$this->select("users.email");
		$this->select("users.name");
		$this->select("users.middle_name");
		$this->select("users.last_name");
		$this->select("users.phone");
		$this->select("users.role");
		$this->select("managers_users.manager_id");
		$this->select("users2.name        as manager_name");
		$this->select("users2.middle_name as manager_middle_name");
		$this->select("users2.last_name   as manager_last_name");
		$this->select("DATE_FORMAT(users.last_login,'%d.%m.%y %H:%i:%s') as last_login",FALSE);
		$this->select("organizations.ceo");

		$this->from("users")
		 	 ->join("managers_users","users.id = managers_users.user_id","LEFT")
		 	 ->join("users as users2","users2.id = managers_users.manager_id","LEFT")
		 	 ->join("users_organizations","users.id = users_organizations.user_id")
		 	 ->join("organizations","organizations.id = users_organizations.org_id");

	}

	/**
	 * Выбор пользователей организации. Выбор происходит из трех таблиц: users,managers_users, organizations.
	 *
	 * @param int
	 * @param array
	 * @param int
	 * @param int
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_users_organization($org_id,$filter=array(),$limit=false,$offset=false)
	{
		/*
		*
		* Выбор всех пользователей
		*/
		$this->_build_select();

		/*
		*
		* Установка фильтров
		*/
		$this->set_filter($filter);
		/*
		*
		* Установка пределов
		*/
		$this->limit($limit,$offset);

		$this->db->where("organizations.id",$org_id);
		return $this->db->get()->result();
	}

	/**
	 * Выбор списка сотрудников компании ( Менеджер | Админ )
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_staff($org_id)
	{
		$this->where("(users.role = '".M_User::USER_ROLE_MANAGER."' OR users.role = '".M_User::USER_ROLE_AGENT."')");
		return $this->get_users_organization($org_id);
	}


	/**
	 * Выбор списка администраторов
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_admins($org_id)
	{
		$this->where("users.role",M_User::USER_ROLE_ADMIN);
		return $this->get_users_organization($org_id);
	}

	/**
	 * Выбор всех менеджеров организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_managers($org_id)
	{
		$this->where("users.role",M_User::USER_ROLE_MANAGER);
		return $this->get_users_organization($org_id);
	}

	public function delete_users($ids)
	{
		$this->db->where_in('id',$ids);
		$this->db->delete($this->table);
	}


	public function exists_email($email)
	{
		return $this->count_all_results(array('users.email'=>$email))==1;
	}
	public function send_email_forget_password($email,$key)
	{
		$this->db->where('users.email',$email);
		$this->db->update($this->table,array('forget_password_key'=>$key));
	
	}
	public function has_access_reset_password($email,$key)
	{
		return $this->count_all_results(array('email'=>$email,'forget_password_key'=>$key))==1;
	}
	public function change_password($email,$password)
	{
		$this->db->where('users.email',$email);
		$this->db->update($this->table,array('password'=>$password,'forget_password_key'=>''));
	}
}