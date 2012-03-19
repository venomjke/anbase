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
	*
	*
	*/
	public function login(){

		if ($this->users->is_logged_in()) {									// logged in
			redirect('');
		} else {


			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			if ($this->users->is_max_login_attempts_exceeded($login)) {
				$this->form_validation->set_rules('recaptcha_response_field', 'Код подтверждения', 'trim|xss_clean|required|callback__check_recaptcha');
			}

			$errors = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->users->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'))) {								// success
					redirect('');

				} else {
					$errors = $this->users->get_error_message();
					foreach($errors as $k => $v){
						$errors[$k] = $this->lang->line($v);
					}
				}
			}


			if ($this->users->is_max_login_attempts_exceeded($login)) {
				$this->template->set('recaptcha_html',$this->_create_recaptcha);
			}

			$this->template
			     ->set('errors',$errors)
			     ->build('login');
		}
	}


	/*
	*
	*	Oh! No!!! Don't do it, man!
	*
	*/
	public function logout(){
		$this->users->logout();
	}


	/*
	*
	*	Ok, you are in my trap=)
	*
	*/
	public function register(){


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
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

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