<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Класс контроллера заявок панели управления менеджера
 *
 * @package anbase
 * @author 
 **/
class Orders extends MX_Controller
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();

		/*
		* Загрузка драйверов
		*/
		$this->load->driver('Minify');
		/*
		*	Загрузка либ
		*/
		$this->load->library("Manager_Users");
		$this->load->library("Manager_Orders");
		$this->load->library("Settings_Organization");
		$this->load->library("Ajax");

		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect("");
		}

		/*
		* Загрузка доп.моделей
		*/
		$this->load->model('m_region');
		$this->load->model('m_metro');
		$this->load->model('m_metro_image');
		$this->load->model('m_region_image');

		/*
		* Подключение сообщений
		*/
		$this->load->language('manager/messages');
		/*
		*	
		* Настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
		$this->template->set_partial("dashboard_user","dashboard/dashboard_user");
		$this->template->set_partial("dashboard_menu","dashboard/dashboard_menu");


		$this->_load_app_assets();
		

		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/manager/js/manager.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){manager.init({baseUrl:"'.site_url('manager/orders').'"});manager.orders.init(); });</script>');
	}

	private function _load_app_assets()
	{
		$this->settings_org = $this->settings_organization->get_settings_org();
		/*
		* [my_notice: Не знаю, как лучше обыграть задачу загрузки ресурсов, но пока так.]
		*/
		$settings_org = json_encode($this->settings_org);
		$assets[] = "common.settings_org=".$settings_org;

		$regions = $this->m_region->get_region_list("json");
		$assets[] = "common.regions=".$regions;

		$metros  = $this->m_metro->get_metro_list("json");
		$assets[] = "common.metros=".$metros;

		$metros_images = $this->m_metro_image->get_images();
		$assets[] = "common.metros_images=".$metros_images;

		$regions_images = $this->m_region_image->get_images();
		$assets[] = "common.regions_images=".$regions_images;

		/*
		* это дополнительно, чтобы в конце был знак ;
		*/
		$assets[]="";

		$this->template->append_metadata('<script type="text/javascript" src="'.base_url().'themes/dashboard/js/slick_grid/slick.remotemodel.js"></script>');
		$this->template->append_metadata('<script type="text/javascript">'.implode(';',$assets).'</script>');

	}
	/**
	 * Маленький маршрутизатор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function _remap()
	{
		$action = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($action) {
			case 'view':
			default:
				/*
				* отображение заявок
				*/
				$section = $this->input->get('s')?$this->input->get('s'):'my';
				$this->template->set('current',$section);
				$this->template->set('settings_org',$this->settings_org);
				$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');
				$this->template->set_partial('dashboard_filter','dashboard/dashboard_filter',array('manager_agents'=>$this->manager_users->get_manager_agents()));
				switch ($section) {
					case 'my':
					default:
						$this->_my_orders();
						break;
					case 'free':
						$this->_free_orders();
						break;
					case 'delegate':
						$this->_delegate_orders();
						break;
					case 'off':
						$this->_off_orders();
					break;
				}
				break;
			case 'edit':
				/*
				* редактирование своих заявок
				*/
				$this->_edit_order();
				break;
			case 'print':
				/*
				* Распечатка заявок
				*/
				$this->_print_orders();
				break;
		}
	}

	/**
	 * Отображение всех заявок менеджера
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _my_orders()
	{
		/*
		* Если мы передаем данные исп. ajax, то загружаем данные,
		* если нет, то загружаем страницу, которая загрузит данные=)
		*/
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->manager_orders->get_all_orders_manager();
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['validation'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*	Вывод данных
			*/
			$this->template->build('orders/view');
		}
	}


	/**
	 * Выбор всех свободных заявок
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _free_orders()
	{

		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->manager_orders->get_all_free_orders();
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			* Вывод данных на экран
			*/
			$this->template->build("orders/free");
		}
	}

	/**
	 * Заявки всех курируемых агентов
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _delegate_orders()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->manager_orders->get_all_delegate_orders();	
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*	Вывод данных
			*/
			$this->template->build("orders/delegate");
		}
	}

	private function _off_orders()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->manager_orders->get_all_off_orders();
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			* Вывод страницы и скрипта загрузки таблицы
			*/
			$this->template->build('orders/off');
		}
	}
	
	/**
	 * Редактирование своих заявок
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_order()
	{
		/*
		* Редактирование возможно только с использованием ajax
		*/
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$this->manager_orders->edit_order();
				$response['code'] = 'success_edit_order';
				$response['data'] = lang('success_edit_order');
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			redirect($this->manager_users->get_home_page());
		}
	}

	private function _print_orders()
	{
		$this->load->library('table');
		try{
			$orders = $this->manager_orders->get_print_orders();
			$this->template->build('orders/print',array('orders'=>$orders));
		}catch(AnbaseRuntimeException $re){
			redirect($this->admin_users->get_home_page());
		}catch(ValidationException $ve){
			redirect($this->admin_users->get_home_page());
		}
	}
} // END class Orders extends MX_Controller