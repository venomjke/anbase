<?php

/**
* Класс, реализующий логику контроллера, отвечающего за действия над профилем
*
* @package anbase
* @author Alex.strigin
*/
class Profile extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		/*
		*
		*	Загрузка либ
		*
		*/
		$this->load->library('agent/Agent_Users');
		$this->load->library('Ajax');
		/*
		*
		* Контроль входа	
		*
		*/
		if(!$this->agent_users->is_logged_in_as_agent()){
			redirect('');
		}

		/*
		*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language("agent/messages");

		$this->template->set_theme('dashboard');
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
		$this->template->set_partial('dashboard_user','dashboard/dashboard_user');
		$this->template->set_partial('dashboard_menu','dashboard/dashboard_menu');

		/*
		*
		* Загрузка главного скрипта панели admin, а также
		* его инициализация
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/agent/js/agent.js").'"> </script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){agent.init({baseUrl:"'.site_url('agent/profile').'"}); agent.profile.init();});</script>');
	}


	/**
	 *	Отображение профиля
	 * 
	 * @return void
	 * @author Alex.strigin
	 **/
	public function index()
	{
		$this->template->build("profile/info");
	}


	/**
	 * Редактирование разделов профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit()
	{
		/*
		* Редактирование доступно только с использованием ajax
		*/
		if($this->ajax->is_ajax_request()){
			$section = $this->input->get('sct')?$this->input->get('sct'):'';

			switch ($section) {
				case 'personal':
					$this->_edit_personal_profile();
					break;
				case 'system':
					$this->_edit_system_profile();
					break;
				default:
					/*
					* Ничего не делаем, пускай помучаются
					*/
					return;
					break;
			}
		}else{
			redirect('agent/profile');
		}
	}
	/**
	 * Личная информация
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_personal_profile()
	{	
		/*
		*  
		* Если данные переданы с использованием ajax, 
		* значит мы пытаемся редактировать поля, если нет
		* то просто выводим список полей
		*/
		if($this->ajax->is_ajax_request()){
			try{
				$this->agent_users->edit_personal_profile();
				$response['code'] = 'success_edit_profile';
				$response['data'] = lang('success_edit_personal_profile');
			}catch(ValidationException $ve){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect("agent/profile");
		}
	}


	/**
	 * Редактирование системный инфы 
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_system_profile()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$this->agent_users->edit_system_profile();
				$response['code'] = 'success_edit_profile';
				$response['data'] = lang('success_edit_system_profile');
			}catch(ValidationException $ve){

				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect("agent/profile");
		}
	}
}// END Profile class