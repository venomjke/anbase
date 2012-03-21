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
		*
		*	Загрузка config
		*
		*/
		$this->ci->load->config('users/users',TRUE);

		/*
		*
		*	Загрузка основных моделей.
		*
		* [ Мб вынести их в autoload? ]
		*/
		$this->ci->load->model('users/m_user');
		$this->ci->load->model('users/m_attempt_login_user');
		$this->ci->load->model('users/m_autologin_user');


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
					$this->ci->session->set_userdata(array(
						'user_id'	=> $user->id,
						'login'	    => $user->login,
						'status'	=> ($user->activated == 1) ? M_User::USER_ACTIVE  : M_User::USER_NON_ACTIVE,
						'role'		=>  $user->role
					));

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

					if (!is_null($user = $this->ci->m_autologin_user->get($data['user_id'], md5($data['key'])))) {

						// Login user
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'status'	=> M_User::USER_ACTIVE,
								'role'		=> $user->role
						));

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

	public function is_admin(){

		if($this->ci->session->userdata('role') == M_User::USER_ROLE_ADMIN)return true;
		return false;
	}

	/*
	*
	*	Определение роли пользователя, является ли менеджером
	*
	*/

	public function is_manager(){

		if($this->ci->session->userdata('role') == M_User::USER_ROLE_MANAGER)return true;

		return false;
	}

	/*
	*
	*	Определение роли пользователя, является ли агентом
	*
	*/
	public function is_agent(){

		if($this->ci->session->userdata('role') == M_User::USER_ROLE_AGENT)return true;
		return false;
	}



}