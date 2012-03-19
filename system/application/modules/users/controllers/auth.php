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


	}


	/*
	*
	*	Ok, you are in my trap=)
	*
	*/
	public function register(){


	}
}