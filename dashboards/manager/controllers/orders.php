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
		/*
		*
		*	Загрузка либ
		*
		*/
		$this->load->library("Manager_Users");
		$this->load->library("Manager_Orders");
		$this->load->library("Ajax");

		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect("");
		}

		/*
		* Загрузка доп.моделей
		*/
		$this->load->model('m_region');
		$this->load->model('m_metro');

		/*
		*	
		* Настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");

		/*
		* Подключение скриптов
		*/
		$regions = $this->m_region->get_region_list("json");
		$metros  = $this->m_metro->get_metro_list("json");

		$this->template->append_metadata('<script type="text/javascript"> common.regions='.$regions.'; common.metros='.$metros.'</script>');

		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/manager/js/manager.js").'"> 
			manager.init({baseUrl:"'.site_url('manager/orders').'"});
			manager.orders.init(); 
		</script>');
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
				$section = $this->input->get('sct')?$this->input->get('sct'):'my';
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
				}
				break;
			case 'edit':
				/*
				* редактирование своих заявок
				*/
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
				$response['code'] = 'success_view_orders';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_orders';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*	Настройки шаблона
			*/
			$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');
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
				$response['code'] = 'success_view_orders';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_orders';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*
			* Установки шаблона
			* 
			*/	
			$this->template->set_partial("dashboard_tabs","dashboard/dashboard_tabs");
			/*
			*
			* Вывод данных на экран
			*
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
				$response['code'] = 'success_view_orders';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_orders';
				$response['data']['errors'] = array($orders);
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*  Установки шаблона
			*/
			$this->template->set_partial("dashboard_tabs","dashboard/dashboard_tabs");
			/*
			*	Вывод данных
			*/
			$this->template->build("orders/delegate");
		}
	}
} // END class Orders extends MX_Controller