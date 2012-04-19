<?php defined('BASEPATH') or die('No direct access script file');


/**
*
*	Authentication controller provides several actions:
*	
*		1. login
*		2. logout
*		3. register
*			-	In the v1.0 release registration will be able only with invite
*
*	@author  -  Alex.strigin <apstrigin@gmail.com>
*	@company -  Flyweb
*/

class Auth extends MX_Controller{


	public function __construct(){

		parent::__construct();

		/*
		*
		* Установка темы	
		*/
		$this->template->set_theme('start');


		/*
		*
		* Загрузка библиотек	
		*	[Мб вынести в autoload?]
		*/
		$this->load->library('users/users');
		$this->lang->load('users');
	}


	public function index(){
		redirect('users/auth/login');
	}
	/*
	*
	*	Yeahhhh!
	*	Действие логин не имеет собственной страницы отображения. То есть,
	*	это действие предназначено для вызова из вне. Во время вызова
	*	login проверит, залоггинен ли пользователь, и сделает redirect в случае если да.
	*	Если нет, Login попробует авторизовать пользователя, а если эта попытка не пройдет,
	*   то будет возвращена форма для повторного выполнения login. 
	*
	*/
	public function login(){

		if ($this->users->is_logged_in()) {									// logged in
			redirect($this->users->resolve_user_redirect_uri());
		} else {

			$data = array();
			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'users') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			if ($this->users->is_max_login_attempts_exceeded($login)) {
				$this->form_validation->set_rules('recaptcha_response_field', 'Код подтверждения', 'trim|xss_clean|required|callback__check_recaptcha');
			}

			$errors = array();

			if ($this->form_validation->run($this)) {								// validation ok
				if ($this->users->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'))) {
														// success
					redirect($this->users->resolve_user_redirect_uri());
				} else {
					$errors = $this->users->get_error_message();
					foreach($errors as $k => $v){
						$errors[$k] = $this->lang->line($v);
					}
				}
			}

			if ($this->users->is_max_login_attempts_exceeded($login)) {
				$this->template->set('recaptcha_html',$this->_create_recaptcha());

				$data['recaptcha_html'] = $this->_create_recaptcha();
			}

			$data['errors']            = $errors;
			return $this->load->view('users/login',$data,true);
			
		}
	}


	/*
	*
	*	Oh! No!!! Don't do it, man!
	*
	*/
	public function logout(){
		$this->users->logout();
		// редирект на главную после logout
		redirect('');
	}


	/**
	*
	*	Ok, you are in my trap=)
	*	Регистрация организации и главного пользователя.
	*	Регистрация остальных участников доступна в соотв. разделах ( Админ, Менеджер, Агент ).*   Регистрация участников разрешена только для тех, кто получил инвайт.
	*	@author Alex.strigin
	*/
	public function register(){
		if ($this->users->is_logged_in()) {									// logged in
			redirect('');
		}else {

			/*
			*
			*	Вынес правила валидации в отдельный файл, так проще.
			*/
			include APPPATH."modules/users/validation_rules/register.php";
			
			
			$data['errors'] = array();

			if ($this->form_validation->run($this)) {	// validation ok

				$register_data = array(
					'login'       => $this->input->post('r_login'),
					'password'    => $this->input->post('r_password'),
					'email'    	  => $this->input->post('r_email'),
					'name'        => $this->input->post('name'),
					'middle_name' => $this->input->post('middle_name'),
					'last_name'   => $this->input->post('last_name'),
					'phone'		  => $this->input->post('phone'),
					'org_name'    => $this->input->post('org_name'),
					'org_phone'   => $this->input->post('org_phone'),
					'org_email'   => $this->input->post('org_email')
				);
				
				if ($this->users->register($register_data)) {
					// success и отображение register_finish
					//$data['success']['register'] = lang('sucess_register'); 
					// пока редирект на главную страницу
					redirect('');
				} else {

					$errors = $this->users->get_error_message();
					foreach ($errors as $k => $v)	
						$data['errors'][$k] = $this->lang->line($v);
				}
			}

			/*
			*	
			*	Настройки шаблона
			*/
			$this->template->set_theme('start');
			$this->template->set_partial('menu','common/menu');
			$this->template->set('loginBox',$this->load->view('users/login',array(),true));
			$this->template->set('recaptcha_html',$this->_create_recaptcha());
			$this->template->build('register',$data);
		}

	}


	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('users/recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'white'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'users'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{

		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'users'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}
}