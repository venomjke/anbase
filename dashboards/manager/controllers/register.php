<?php defined("BASEPATH") or die("No direct access to script");



/**
* Класс, реализующий логику контроллера, отвечающего за регистрацию пользователей
*
* @package default
* @author alex.strigin
*/
class Register extends MX_Controller
{
	
	function __construct()
	{
		/*
		*
		* Загрузка либ
		*
		*/
		$this->load->library('manager/Manager_Users');
		$this->load->library('Ajax');

		/*
		* К регистрации допускаются только "обычные" люди
		*/
		if($this->manager_users->is_logged_in() or !$this->manager_users->has_invite()){
			redirect('');
		}

		/*
		* Загрузка пакета сообщений
		*/
		$this->load->language('manager/messages');

		/*
		*
		* Настройка шаблона
		*/

		/*
		*
		* Настройка шаблона
		*/
		$this->template->set_theme('start');
		$this->template->set_partial('menu','common/menu',array('current' =>''));

		/*
		* Загрузка мета инфы всякой
		*
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/manager/js/register.js").'"> </script>');
		$this->template->append_metadata('<script type="text/javascript"> register.init({baseUrl:"'.base_url().'"}); </script>');
	}

	/**
	 * Метод отвечает за регистрацию пользователей
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function _remap()
	{
		/*
		* Загрузка инвайта
		*/
		$invite = $this->manager_users->load_invite($this->input->get('key'),$this->input->get('email'));

		/*
		* данные шаблона
		*/
		$data['invite'] = $invite;

		$this->template->set('loginBox',$this->load->view('users/login',array(),true));

		/*
		* Контейнер данных для ajax ответа
		*/
		$response = array();
		try{

			/*
			* Если пользователь только попал на страницу, то он получит форму для регистрации
			*/
			if($this->manager_users->register($invite)){

				if($this->ajax->is_ajax_request()){
					$response['code'] = 'success_register_manager';
					$response['data'] = lang('success_register_manager');
					$this->ajax->build_json($response);
				}else{
					redirect('');
				}
			}else{
				$this->template->build('register',$data);
			}
		}catch(ValidationException $ve){
			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_register_manager';
				$response['data']['errors'] = $ve->get_error_messages();
				$this->ajax->build_json($response);
			}else{
				$this->template->build('register',$data);
			}
		}catch(RuntimeException $re){
			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_register_manager';
				$response['data']['errors'] = array($re->get_error_message());
				$this->ajax->build_json($response);
			}else{
				$data['runtime_error'] = $re->get_error_message();
				$this->template->build('register',$data);
			}
		}
	
	}
}// END Register class 