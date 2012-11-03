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
		* Установка темы	
		*/
		$this->template->set_theme('start');


		/*
		* Загрузка библиотек	
		*	[Мб вынести в autoload?]
		*/
		$this->load->library('users/users');
		$this->load->library('ajax');
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

			$login_fields = $this->get_login_fields();

			$this->form_validation->set_rules($login_fields);

			$errors = array();

			if ($this->form_validation->run($this)) {	// validation ok
				$login_data = $this->get_login_data();

				if ($this->users->login($login_data['login'],$login_data['password'],$login_data['remember'])) {// success
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

	public function get_login_fields()
	{
		$fields = $this->users->get_login_validation_rules();

		// Get login for counting attempts to login
		if ($this->config->item('login_count_attempts', 'users') AND
				($login = $this->input->post('login'))) {
			$login = $this->security->xss_clean($login);
		} else {
			$login = '';
		}

		if ($this->users->is_max_login_attempts_exceeded($login)) {
			$fields[] = array('field'=>'recaptcha_response_field','label'=>'Код подтверждения','rules'=>'trim|xss_clean|required|callback__check_recaptcha');
		}
		return $fields;
	}

	public function get_login_data()
	{
		return $this->input->post('user_login');
	}

	/*
	*
	*	Oh! No!!! Don't do it, man!
	*
	*/
	public function logout(){
		$this->users->logout();
		// редирект на главную после logout
		redirect();
	}


	/**
	*	Ok, you are in my trap=)
	*
	*	Регистрация организации и главного пользователя.
	*	Регистрация остальных участников доступна в соотв. разделах ( Админ, Менеджер, Агент ).
	*	@author Alex.strigin
	*/
	public function register(){
		if ($this->users->is_logged_in()) {									// logged in
			redirect();
		}else {
			$register_fields = $this->get_register_fields(); // получаем преобразованный список полей с правилами валидации для m_user+m_organization

			$this->form_validation->set_rules($register_fields);

			$data['errors'] = array();

			if ($this->form_validation->run($this)) {	// validation ok

				$register_data = $this->get_register_data(); // получаем поля для выполнения регистрации
				
				if ($this->users->register($register_data)) {
					// пока редирект на главную страницу
					$this->session->set_flashdata('redirect',true);
					redirect('redirect');
				} else {
					/*
					* Ошибки для каждой секции выводятся отдельно: ошибки регистрации аккаунта, ошибки регистрации организации
					*/
					$errors = $this->users->get_error_message();
					foreach ($errors as $k => $v)	
						$data['errors'][$k] = $this->lang->line($v);
				}
			}

			/*
			*	Настройки шаблона
			*/
			$this->template->set_theme('start');
			$this->template->set_partial('menu','common/menu');
			$this->template->set('loginBox',$this->load->view('users/login',array(),true));
			$this->template->set('recaptcha_html',$this->_create_recaptcha());
			$this->template->build('register',$data);
		}

	}

	private function get_register_fields()
	{
		$f = $this->users->get_register_validation_rules();
		$f[] = array('field'=>'recaptcha_response_field','label'=>'Код подтверждения','rules'=>'trim|xss_clean|required|callback__check_recaptcha');
		return $f;
	}


	private function get_register_data()
	{
		$user_fields = $this->input->post('user');
		$org_fields  = $this->input->post('org');
		return array(
					'login'       => $user_fields['login'],
					'password'    => $user_fields['password'],
					'email'    	  => $user_fields['email'],
					'name'        => $user_fields['name'],
					'middle_name' => $user_fields['middle_name'],
					'last_name'   => $user_fields['last_name'],
					'phone'		  => isset($user_fields['phone'])?$user_fields['phone']:'',
					'org_name'    => $org_fields['name'],
					'org_phone'   => $org_fields['phone'],
					'org_email'   => $org_fields['email']
				);
	}
	/**
	 * Общая проверка полей необходимых при регистрации пользователя
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function register_ajax_validation()
	{
		/*
		* Подготавливаем полный список полей для валидации
		*/
		$fields_validation_rules = $this->get_register_fields();
		/*
		* Находим соответствие между списком заданных полей и переданных клиентом
		*/
		$fields = array();
		$search_field = function($validationFields,$field,&$matchField){
			foreach($validationFields as $validationField){
				if($validationField['field'] == $field){
					$matchField = $validationField;
					return true;
				}
			}
			return false;
		};

		if($this->input->post('fields') && is_array($this->input->post('fields'))){

			foreach($this->input->post('fields') as $k){
				$validField = null;
				if($search_field($fields_validation_rules,$k,$validField)){
					$fields[] = $validField;
				}
			}
			$this->form_validation->set_rules($fields);

			if($this->form_validation->run()){
				$response = array();
				$response['code'] = 'success';
				$response['fields'] = array();
				foreach($fields as $field){
					$response['fields'][$field['field']] = 'success'; 
				}
				$this->ajax->build_json($response);
			}else{
				$response['code'] = 'error';
				$response['fields'] = array();
				foreach($fields as $field){
					$response['fields'][$field['field']] = $this->form_validation->error($field['field'],'<span class="error">','</span>');
				}
				$this->ajax->build_json($response);
			}
		}
	}

	public function redirect()
	{
		if($this->session->flashdata('redirect')){
			$this->template->set_theme('start');
			$this->template->set_partial('menu','common/menu');
			$this->template->set('loginBox',$this->load->view('users/login',array(),true));
			$this->template->build('redirect');
		}else{
			redirect('');
		}
	}

	public function forget_password($act = 'view')
	{
		if($this->users->is_logged_in()) redirect('');

		/*
		*	Настройки шаблона
		*/
		$this->template->set_theme('start');
		$this->template->set_partial('menu','common/menu');
		$this->template->set('loginBox',$this->load->view('users/login',array(),true));

		switch($act){
			case 'view':
				/*
				* Определяем, можно ли сбросить пароль, и оптравляем ссылку сброса пароля на почту.
				*/
				$this->_send_email_forget_password();
				break;
			case 'reset':
				/*
				* Определяем возможность сброса пароля
				*/
				$this->_reset_forget_password();
				break;
		}
	}

	private function _send_email_forget_password()
	{
		try{
			if($this->users->send_email_forget_password()){
				$this->template->build('forget_password/send_mail',array('success_send'=>true));
			}else{
				$this->template->build('forget_password/send_mail');
			}
		}catch(AnbaseRuntimeException $re){
			$this->template->build('forget_password/send_mail',array('runtime_error'=>$re->get_error_message()));
		}catch(ValidationException $ve){
			$this->template->build('forget_password/send_mail');
		}
	}

	private function _reset_forget_password()
	{
		/*
		* Проверк ключа "key" и "email".
		*/
		if(!$this->users->has_access_reset_password()){
			redirect();
		}

		$data['email'] = $this->input->get('email');
		$data['key']   = $this->input->get('key');
		try{
			if($this->users->reset_forget_password($this->input->get('email'))){
				$data['success_reset'] = true;
				$this->template->build('forget_password/reset',$data);
			}else{
				$this->template->build('forget_password/reset',$data);
			}
		}catch(AnbaseRuntimeException $re){
			$data['runtime_error'] = $re->get_error_message();
			$this->template->build('forget_password/reset',$data);
		}catch(ValidationException $ve){
			$this->template->build('forget_password/reset',$data);
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