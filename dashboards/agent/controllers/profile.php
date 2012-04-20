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
		$this->template->append_metadata('<script type="text/javascript">$(function(){agent.init({baseUrl:"'.base_url().'"});});</script>');
	}


	/**
	 * Редирект на маршрутизатор profile/view
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function index()
	{
		redirect('agent/profile/view');
	}


	/**
	 * Маршрутизатор профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function view()
	{
		$section = $this->input->get('s')?$this->input->get('s'):'';

		/*
		*
		*  Запуск действия
		*/
		switch ($section) {
			case 'personal':
				/*
				*
				* Персональная информация
				*
				*/
				$this->_personal_profile();
				break;
			case 'org':

				/*
				*
				*	Организационная информация
				*/
				$this->_organization_profile();
			break;
			case 'system':
				/*
				*
				*	Системная информация
				*
				*/
				$this->_system_info();
			break;
			default:
				/*
				*
				* действие по умолчанию
				*/

				$this->_personal_profile();
				break;
		}
		
	}

	/**
	 * Личная информация
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _personal_profile()
	{	
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");
		/*
		*  
		* Если данные переданы с использованием ajax, 
		* значит мы пытаемся редактировать поля, если нет
		* то просто выводим список полей
		*/
		if($this->ajax->is_ajax_request()){

			/*
			*
			* Попытаемся выполнить изменение личного профиля,
			* в случае неудачи получим *Exception
			*/
			try{

				$this->agent_users->edit_personal_profile();

				$response['code'] = 'success_edit_profile';
				$response['data'] = lang('success_edit_personal_profile');
				$this->ajax->build_json($response);
			

			}catch(ValidationException $ve){
				/*
				*
				* Обработка исключений валидации
				*
				*/
				$response['code'] = 'error_edit_profile';
				$response['data']['errors'] = $ve->get_error_messages();
				$this->ajax->build_json($response);
			}catch(AnbaseRuntimeException $re){

				/*
				*
				* Обработка исключений времени выполнения
				*/
				$response['code'] = 'error_edit_profile';
				$response['data']['errors'] = array($re->get_error_message());
			}

		}else{
			$this->template->build("profile/info");
		}
	}

	/**
	 * Организационная информация
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _organization_profile()
	{
		$this->template->build("profile/info");
	}

	/**
	 * Информация об аккаунте
	 *
	 * @return void
	 * @author 
	 **/
	private function _system_info()
	{
		$this->template->build("profile/info");
	}
}// END Profile class