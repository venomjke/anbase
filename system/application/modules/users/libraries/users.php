<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');


/**
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb
* 
*	Base authentication library
*	Based on Tank_Auth
*/
class Users {

	/* Экземпляр приложения */
	protected $ci;

	/* Контейнер ошибок */
	protected $error = array();

	public function __construct(){
		$this->ci = get_instance();

		/*
		* Загрузка исключение
		*/
		$this->ci->load->exception('AnbaseRuntimeException');
		$this->ci->load->exception('ValidationException');

		/*
		*	Загрузка config
		*/
		$this->ci->load->config('users/users',TRUE);

		/*
		*	Загрузка основных моделей.
		*
		* [ Мб вынести их в autoload? ]
		*/
		$this->ci->load->model('users/m_user');
		$this->ci->load->model('users/m_attempt_login_user');
		$this->ci->load->model('users/m_autologin_user');
		$this->ci->load->model('users/m_user_organization');
		$this->ci->load->model('users/m_manager_user');
		$this->ci->load->helper("users/users");
		/*
		* Загрузка языка
		*/
		$this->ci->load->language('users/users');
		// Try to autologin
		$this->autologin();
	}

	/**
	*
	*	Login в систему. Возвращает TRUE при успешном логин.
	*	(Пользователь существует, активирован, пароль корректен)
	*
	*	@param string ( login или email)
	*	@param string ( пароль )
	*	@return bool
	*
	*/
	public function login($login,$password,$remember){

		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			if (!is_null($user = $this->ci->m_user->get_user_by_login_or_email($login))) {	// login ok
				// Does password match hash in database?
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'users'),
						$this->ci->config->item('phpass_hash_portable', 'users'));

				
				if ($hasher->CheckPassword($password, $user->password)) {		// password ok

					/*
					*	Если пользователь существует, и все впорядке, 
					*	 то выбираем информацию об организации
					*/
					if(!is_null($user_as_org = $this->ci->m_user_organization->get(array('user_id' => $user->id)))){


						$this->save_user_session_data($user,$user_as_org);

						if ($user->activated == 0) {							// fail - not activated
							$this->error = array('not_activated' => '');

						} else {												// success
							if ($remember) {
								$this->create_autologin($user->id);
							}

							$this->clear_login_attempts($login);

							$this->ci->m_user->update_login_info(
									$user->id,
									$this->ci->config->item('login_record_ip', 'users'),
									$this->ci->config->item('login_record_time', 'users'));
							

							return TRUE;
						}

					}

				} else {														// fail - wrong password
					$this->increase_login_attempt($login);
					$this->error = array('password' => 'auth_incorrect_password');
				}
			} else {															// fail - wrong login
				$this->increase_login_attempt($login);
				$this->error = array('login' => 'auth_incorrect_login');
			}
		}
		return FALSE;
			
	}

	/**
	 * Функция возвращает список всех правил для первичной регистрации в системе
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_register_validation_rules($org=true)
	{
		$this->ci->load->model('users/m_organization');
		$this->ci->load->model('users/m_user');

		$fields = array();

		$user_valid_rules = $this->ci->m_user->get_validation_fields();
		$user_valid_rules['re_password'] =array('field'=>'re_password', 'label'=>'lang:label_re_password','rules'=>($user_valid_rules['password']['rules'].'|matches2[user[password]]')); // в форме регистрации появляется доп. поле "повторить пароль"
		unset($user_valid_rules['id']); //id задавать не надо
		unset($user_valid_rules['role']); // роль устанавливается автоматически


		build_validation_array($fields, $user_valid_rules, 'user');

		if($org){
			$org_valid_rules  = $this->ci->m_organization->get_validation_fields();
			build_validation_array($fields,$org_valid_rules,'org');	
		}
		
		return $fields;
	}

	/**
	 * Функция возвращает список всех правил, для реализации входа в систему
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_login_validation_rules()
	{
		$this->ci->load->model('users/m_user');

		$fields = array();

		/*
		* Для входа в систему достаточно всего два поля login и пароль, при этом у login не должно быть правила is_unique
		*/
		$user = $this->ci->m_user->get_validation_fields();
		$user_fields = array();
		$user_fields['login']['rules'] = 'required|trim|xss_clean'; // в форме "входа" логин может быть в форме emai.
		$user_fields['password'] = $user['password'];
		$user_fields['remember'] = array('field'=>'remember','label'=>'lang:label_remember','rules'=>'integer');

		build_validation_array($fields,$user_fields,'user_login');
		return $fields;
	}

	/**
	 * Функция, выполняющая регистрацию пользователя ( администратора/директора ) и его организации.
	 *
	 * @return void
	 * @author 
	 **/
	function register($register_data = array())
	{
		$this->ci->load->model('users/m_organization');
		$this->ci->load->model('m_settings_org');

		// Hash password using phpass
		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'users'),
				$this->ci->config->item('phpass_hash_portable', 'users'));

		/*
		*	Составляем пачку данных для регистрации пользователя
		*/
		$register_data['password'] = $hasher->HashPassword($register_data['password']);
		$register_data['role']	   = M_User::USER_ROLE_ADMIN; // делаем его админом

		if (($res = $this->ci->m_user->insert($register_data,true))) {

			/*
			*	Составляем пачку данных для регистрации организации
			*/
			$register_data['user_id'] = $res;

			$org_data['name'] = $register_data['org_name'];
			$org_data['ceo']  = $register_data['user_id'];
			$org_data['email'] = $register_data['org_email'];
			$org_data['phone'] = $register_data['org_phone'];


			if(($res = $this->ci->m_organization->insert($org_data,true))) {

				/**
				*	Добавляем юзера и org_id в таблицу user_orgs, чтобы юзер мог свободно заходить в систему
				*/
				$this->ci->m_user_organization->insert(array('user_id'=>$register_data['user_id'], 'org_id' => $res),true);				

				$default_settings = array();
				$default_settings['org_id'] = $res;

				if($this->ci->m_settings_org->insert($default_settings)){
					return $register_data;
				}
			}
			$this->ci->m_user->delete($register_data['user_id']);

			$this->error = array('register_org_error' => 'register_org_error');
			return NULL;
		}

		$this->error = array('register_user_error' => 'register_user_error');
		return NULL;

	}

	/**
	 * Простая регистрация пользователя
	 *
	 * @return 
	 * @author alex.strigin
	 **/
	protected function simple_register($register_data =array())
	{

		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'users'),
				$this->ci->config->item('phpass_hash_portable', 'users'));

		/*
		*	Составляем пачку данных для регистрации пользователя
		*/
		$register_data['password'] = $hasher->HashPassword($register_data['password']);

		if (($res = $this->ci->m_user->insert($register_data,true))) {
			$this->ci->m_user_organization->insert(array('user_id'=>$res, 'org_id' => $register_data['org_id']));
			return $res;
		}

		$this->error = array('register_user_error');
		return NULL;
	}
	/**
	 * Logout user from the site
	 *
	 * @return	void
	 */
	function logout()
	{
		$this->delete_autologin();

	
		$this->ci->session->set_userdata(array('user_id' => '', 'username' => '', 'status' => '','role'));

		$this->ci->session->sess_destroy();
	}

	/**
	 * Отправка письма с ссылкой о сбросе пароля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function send_email_forget_password()
	{
		$validation_rules = array(
			array('field'=>'email','label'=>'Адрес электронной почты','rules'=>'required|valid_email|exists_email')
		);

		$this->ci->form_validation->set_rules($validation_rules);

		if($this->ci->form_validation->run()){

			/*
			* [my_notice]Я думаю, что стоит придумать более эффективный алгоритм для генерации ключа
			*/
			$key = substr(md5(uniqid(rand())), 0, 24); // generate key;
			$this->ci->m_user->send_email_forget_password($this->ci->input->post('email'),$key);
			$url = site_url("forget_password/reset/?key=$key&email=".$this->ci->input->post('email'));

			$messageTxt = "Уважаемый, пользователь!<br/><br/>Кто-то, возможно Вы, запросил восстановление пароля к аккаунту на сайте anbase.ru <br/><br/> Если Это были Вы, то для восстановления пароля перейдите пожалуйста по ссылке $url <br/><br/>Если это были не Вы, то ничего не делайте и проигнорируйте это письмо.<br/><br/>C уважением,<br/>anbase.ru<br/>---------------------------<br/>Это письмо создано роботом и не требует ответа!<br/>Адрес службы технической поддержки - support@anbase.ru";

			$this->ci->load->library('email');
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from('no-reply@anbase.ru', 'anbase project');
			$this->ci->email->to($this->ci->input->post('email')); 
			$this->ci->email->subject('Восстановление пароля для входа на сайт - http://anbase.ru/');
			$this->ci->email->message($messageTxt);	
			// Set to, from, message, etc.

			$result = $this->ci->email->send();
			return true;
		}

		$errors_validation = array();

		if(has_errors_validation(array('email'),$errors_validation)){
			throw new ValidationException($errors_validation);
		}

		return false;
	}

	/**
	 * Проверка того, что пользователь имеет доступ к смене пароля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function has_access_reset_password()
	{
		$key = $this->ci->input->get('key');
		$email = $this->ci->input->get('email');

		if(!empty($key) and strlen($key)==24 && !empty($email)){
			return $this->ci->m_user->has_access_reset_password($email,$key);
		}
		return false;
	}

	/**
	 * Сброс пароля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function reset_forget_password($email)
	{
		$validation_rules = array(
			array('field'=>'f_password','label'=>'Новый пароль','rules'=>'trim|required|xss_clean|min_length[6]|max_length[200]|alpha_dash'),
			array('field'=>'f_re_password','label'=>'Копия пароля','rules'=>'trim|required|xss_clean|matches[f_password]')
		);

		$this->ci->form_validation->set_rules($validation_rules);

		if($this->ci->form_validation->run()){
			$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'users'),
				$this->ci->config->item('phpass_hash_portable', 'users'));

			/*
			*	Составляем пачку данных для регистрации пользователя
			*/
			$password = $hasher->HashPassword($this->ci->input->post('f_password'));

			$this->ci->m_user->change_password($email,$password);
			return true;
		}

		$errors_validation = array();

		if(has_errors_validation(array('f_password','f_re_password'),$errors_validation)){
			throw new ValidationException($errors_validation);
		}

		return false;
	}

	/**
	 * Сохранение данных  - user's autologin
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_autologin($user_id)
	{
		$this->ci->load->helper('cookie');

		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);


	
		$this->ci->m_autologin_user->purge($user_id);
		if ($this->ci->m_autologin_user->set($user_id, md5($key))) {
			
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name', 'users'),
					'value'		=> serialize(array('user_id' => $user_id, 'key' => $key)),
					'expire'	=> $this->ci->config->item('autologin_cookie_life', 'users'),
			));
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * Удаление данных об Autologin
	 *
	 * @return	void
	 */
	private function delete_autologin()
	{
		$this->ci->load->helper('cookie');
		if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'users'), TRUE)) {

			$data = unserialize($cookie);
			$this->ci->m_autologin_user->remove($data['user_id'], md5($data['key']));

			delete_cookie($this->ci->config->item('autologin_cookie_name', 'users'));
		}
	}

	/**
	 *  Автоматический логин если он/она обеспечат корректный autologin verification
	 *
	 * @return	void
	 */
	private function autologin()
	{
		if (!$this->is_logged_in()) {			// not logged in (as any user)

			$this->ci->load->helper('cookie');
			if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'users'), TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) AND isset($data['user_id'])) {

					/*
					*
					*	Выдергиваем инфу о юзере из autologin
					*
					*/
					if (!is_null($user = $this->ci->m_autologin_user->get($data['user_id'], md5($data['key'])))) {

						

						/*
						*
						*	Выдергиваем инфу об организации юзера из user_as_org 
						*/
						if(!is_null($user_as_org = $this->ci->m_user_organization->get(array('user_id' => $user->id)))) {

							$this->save_user_session_data($user,$user_as_org);
							// Renew users cookie to prevent it from expiring
							set_cookie(array(
									'name' 		=> $this->ci->config->item('autologin_cookie_name', 'users'),
									'value'		=> $cookie,
									'expire'	=> $this->ci->config->item('autologin_cookie_life', 'users'),
							));

							$this->ci->m_user->update_login_info(
									$user->id,
									$this->ci->config->item('login_record_ip', 'users'),
									$this->ci->config->item('login_record_time', 'users'));
							return TRUE;

						}
						return FALSE;

					}
				}
			}
		}
		return FALSE;
	}

	/**
	 *	Увеличение числа попыток логин для заданного ip-address и Login
	 * 
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login){
		if ($this->ci->config->item('login_count_attempts', 'users')) {
				if (!$this->is_max_login_attempts_exceeded($login)) {
					$this->ci->m_attempt_login_user->increase_attempt($this->ci->input->ip_address(), $login);
				}
		}
	}

	/**
	 * Проверка, превысило ли число попыток допустимое число ( указано в конфиге )
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_max_login_attempts_exceeded($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'users')) {
			return $this->ci->m_attempt_login_user->get_attempts_num($this->ci->input->ip_address(), $login)
					>= $this->ci->config->item('login_max_attempts', 'users');
		}
		return FALSE;
	}

	/**
	 * Очистить список логин-попыток для заданного UP
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function clear_login_attempts($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'users')) {
			$this->ci->m_attempt_login_user->clear_attempts(
					$this->ci->input->ip_address(),
					$login,
					$this->ci->config->item('login_attempt_expire', 'users'));
		}
	}

	/**
	 * Смена пароля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function change_password($user_id,$data=array())
	{
		// Does password match hash in database?
		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'users'),
				$this->ci->config->item('phpass_hash_portable', 'users'));

		$user = $this->ci->m_user->get($this->get_user_id());
		if ($hasher->CheckPassword($data['password'], $user->password)) {		// password ok
				
			$data['new_password'] = $hasher->HashPassword($data['new_password']);
			$this->ci->m_user->update($user_id,array('password'=>$data['new_password']),true);
			return true;
		}
		throw new AnbaseRuntimeException(lang('users.error_change_password'));
	}
	/**
	*	Проверка залогинен ли пользователь
	*
	*	@return bool
	*/
	public function is_logged_in($activated = M_User::USER_ACTIVE){
		return $this->ci->session->userdata('status') === ($activated ? M_User::USER_ACTIVE : M_User::USER_NON_ACTIVE);
	}



	/**
	 * Получить сообщение об ошибке
	 * 
	 *
	 * @return	string
	 */
	function get_error_message()
	{
		return $this->error;
	}


	/*
	*
	*	Определение родного uri для роли.
	*
	*/
	public function resolve_user_redirect_uri(){

		if($this->is_admin()){
			return "admin";
		}else if($this->is_manager()){
			return "manager";
		}else if($this->is_agent()){
			return "agent";
		}
		return "/";
	}

	/*
	*
	*	Определение роли пользователя, является ли админом
	*
	*/

	public function is_admin($user_id = ''){
		if(empty($user_id)){
			if($this->ci->session->userdata('role') == M_User::USER_ROLE_ADMIN)return true;
		}else{
			$user = $this->ci->m_user->get($user_id);
			if($user && $user->role == M_User::USER_ROLE_ADMIN)return true;
			return false;
		}
		return false;
	}

	/*
	*
	*	Определение роли пользователя, является ли менеджером
	*
	*/
	public function is_manager($user_id = ''){

		if(empty($user_id)){
			if($this->ci->session->userdata('role') == M_User::USER_ROLE_MANAGER)return true;
		}else{
			$user = $this->ci->m_user->get($user_id);
			if($user && $user->role == M_User::USER_ROLE_MANAGER)return true;
			return false;
		}
		return false;
	}

	/**
	 * Проверка, есть ли у юзера manager
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function has_manager($user_id)
	{
		return $this->ci->m_manager_user->has_manager($user_id);
	}
	/*
	*
	*	Определение роли пользователя, является ли агентом
	*
	*/
	public function is_agent($user_id = ''){

		if(empty($user_id)){
			if($this->ci->session->userdata('role') == M_User::USER_ROLE_AGENT)return true;
		}else{
			$user = $this->ci->m_user->get($user_id);
			if($user && $user->role == M_User::USER_ROLE_AGENT)return true;
			return false;
		}
		return false;
	}


	/**
	 * Определение того, является ли пользователь директором
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_ceo($user_id)
	{
		if($user_id == $this->ci->session->userdata('ceo')) return true;
		return false;
	}
	
	/**
	*
	*	Составление полного оффициального имени
	*
	*/
	public function get_official_name(){
		$name = $this->ci->session->userdata('name');
		$middle_name = $this->ci->session->userdata('middle_name');
		$last_name = $this->ci->session->userdata('last_name');

		return make_official_name($name,$middle_name,$last_name);
	}


	/**
	 * Выбор id пользователя
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function get_user_id()
	{
		return $this->ci->session->userdata('user_id');	
	}

	/**
	*
	*
	*	Выбор id организации
	*
	*/
	public function get_org_id(){
		return $this->ci->session->userdata('org_id');
	}

	/**
	 * Выбор login пользователя
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_user_login()
	{
		return $this->ci->session->userdata('login');
	}

	/**
	 * Выбор email пользователя
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	public function get_user_email()
	{
		return $this->ci->session->userdata('email');
	}
	/**
	 * Выбор имени пользователя
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_user_name($user_id='')
	{
		if(empty($user_id)){
			return $this->ci->session->userdata('name');
		}else{
			$user = $this->ci->m_user->get($user_id);
			return $user->name;
		}
	}

	/**
	 * Выбор отчества пользователя
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_user_middle_name()
	{
		return $this->ci->session->userdata('middle_name');
	}

	/**
	 * Выбор фамилии пользователя
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_user_last_name()
	{
		return $this->ci->session->userdata('last_name');
	}

	/**
	 * Выбор телефона пользователя
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_user_phone()
	{
		return $this->ci->session->userdata('phone');
	}

	/**
	 * Выбор даты последнего визита
	 *
	 * @return void
	 * @author 
	 **/
	public function get_last_visit()
	{
		return $this->ci->session->userdata('last_login');
	}
	/**
	 * Выбор роли текущего пользователя
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	public function get_user_role($user_id='')
	{
		if(empty($user_id)){
			return $this->ci->session->userdata('role');
		}else{
			$user = $this->ci->m_user->get($user_id);
			return $user->role;
		}
	}
	/**
	 * Метод, возвращающий имя организации
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_org_name()
	{
		$this->ci->load->model("users/m_organization");
		$org = $this->ci->m_organization->get($this->get_org_id());
		return $org->name;
	}


	/**
	 * Метод, позволяющий получить телефон call manager
	 *
	 * @return string
	 * @author 
	 **/
	public function get_callmanager_phone()
	{
		$this->ci->load->model('users/m_organization');
		$org = $this->ci->m_organization->get($this->get_org_id());
		return $org->phone;
	}


	/**
	 * Метод, возвращающий email организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_org_email()
	{
		$this->ci->load->model('users/m_organization');
		$org = $this->ci->m_organization->get($this->get_org_id());
		return $org->email;
	}
	
	/**
	*
	*	Обновление сохранение данных о пользователе в сессии
	*
	*	@param object (user)	- объект таблицы users
	*	@param object (user_as_org) - объект таблицы users_organizations
	*/
	protected function save_user_session_data($user,$user_as_org){
		// Login user
		$this->ci->session->set_userdata(array(
				'user_id'	=> $user->id,
				'login'	    => $user->login,
				'email'     => $user->email,
				'status'	=> M_User::USER_ACTIVE,
				'role'		=> $user->role,
				'name'      => $user->name,
				'last_name' => $user->last_name,
				'middle_name'=> $user->middle_name,
				'phone' 	=> $user->phone,
				'org_id'	=> $user_as_org->org_id,
				'ceo'		=> $user_as_org->ceo,
				'last_login' => $user->last_login
		));
	}



}