<?php

/**
* Класс, реализующий логику контроллера profile, отвечающего за обработку действий над профилем
*
* @package agent
* @author alex.strigin
*/
class Profile extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();

		/*
		*
		*	
		*	загрузка либ
		*
		*/
		$this->load->library("manager/Manager_Users");
		$this->load->library("Ajax");

		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect('');
		}
		/*
		*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language("manager/messages");

		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');

		/*
		*
		* Загрузка главного скрипта панели manager, а также его инициализация
		*
		*
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/manager/js/manager.js").'"> </script>');

		$this->template->append_metadata('<script type="text/javascript">manager.init({baseUrl:"'.base_url().'"});</script>');
	}



	/**
	 * Индексный action,redirect
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		redirect('manager/profile/view');
	}


	/**
	 * Маршрутизатор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function view()
	{
		$section = $this->input->get('s');

		if (!empty($section)) {
			/*
			*
			* обработка действий
			*
			*/

			switch ($section) {
				case 'personal':
					/*
					*
					* личное инфо
					*
					*/
					$this->_personal_profile();
					break;
				case 'org':
					/*
					*
					*	организационное ифно ( менеджер меня его не может )
					*
					*/
					$this->_organization_profile();
				break;
				case 'system':
					/*
					*
					* инфо об аккаунте
					*
					*/
					$this->_system_info();
				break;
				default:
					/*
					*
					* по умолчанию показывает личное инфо
					*/
					$this->_personal_profile();
					break;
			}
		} else {
			/*
			*
			* действие по умолчанию
			*
			*/
			$this->_personal_profile();
		}
		
	}


	/**
	 * Вывод личной инфы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _personal_profile()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/profile/tabs');
		/*
		*
		*	Если данные переданы с использованием ajax, значи мы 
		* пытаемся редактировать поля, если нет,  то просто 
		*  выводим спсикок полей
		*
		*/
		if($this->ajax->is_ajax_request()){

			/*
			*
			* Пытаемся изменить личный профиль
			*
			*/
			try{

				$this->manager_users->edit_personal_profile();
				
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
			return;
		}
		$this->template->build('profile/personal');
	}


	/**
	 * Орг. инфа
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _organization_profile()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/profile/tabs');
		$this->template->build('profile/organization');
	}


	/**
	 * системное инфо
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _system_info()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/profile/tabs');
		$this->template->build('profile/system_info');
	}
}// END Profile class