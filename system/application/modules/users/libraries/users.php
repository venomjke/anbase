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
		$this->ci->load->config('users',TRUE);

		/*
		*
		*	Загрузка основных моделей.
		*
		* [ Мб вынести их в autoload? ]
		*/
		$this->ci->load->model('users/m_user');
		$this->ci->load->model('users/m_attempt_login_user');
		$this->ci->load->model('users/m_autologin_user');
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
	public function login($login,$password){

		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			if (!is_null($user = $this->ci->m_user->get_user_by_login_or_email($login))) {	// login ok

				// Does password match hash in database?
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'users'),
						$this->ci->config->item('phpass_hash_portable', 'users'));
				if ($hasher->CheckPassword($password, $user->password)) {		// password ok
						return TRUE;
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
	 *	Увеличение числа попыток логин для заданного ip-address и Login
	 * 
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login){

	}

	/**
	*	Проверка залогинен ли пользователь
	*
	*	@return bool
	*/
	public function is_logged_in(){
		return FALSE;
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
}