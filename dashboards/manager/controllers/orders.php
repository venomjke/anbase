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

		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect("");
		}

		/*
		*
		* Загрузка модели
		*
		*/
		$this->load->model("m_manager_order");

		/*
		*	
		* Настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
	}


	/**
	 * Подконтроллер, позволяет выбирать выводимый раздел
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function _remap()
	{
		$section = $this->input->get('s');

		if(!empty($section)){

			switch ($section) {
				case 'free':
					
					$this->_free_orders();
					break;
				case 'delegate':

					$this->_delegate_orders();
					break;
				default:
					
					$this->_view();
					break;
			}
		}else{

			/*
			*	
			* default
			*/
			$this->_view();
		}
	}

	/**
	 * Отображение всех заявок менеджера
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _view()
	{
		/*
		*
		*	Настройки шаблона
		*/
		$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');

		/*
		*
		*	Установка фильтров
		*
		*/
		$filter = array();
		$limit  = false;
		$offset = false;

		/*
		*
		*	Выбор всех данных менеджера
		*
		*/
		$all_manager_orders = $this->m_manager_order->get_all_orders_manager($this->manager_users->get_user_id());


		/*
		*
		*	Вывод данных
		*/

		$this->template->build('orders/view',array('orders' => $all_manager_orders));
	}


	/**
	 * Выбор всех свободных заявок
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _free_orders()
	{

		/*
		*
		* Установки шаблона
		* 
		*/	
		$this->template->set_partial("dashboard_tabs","dashboard/dashboard_tabs");


		/*
		*
		* Установки фильтра
		*
		*/
		$filter = array();
		$limit  = false;
		$offset = false;


		/*
		*
		* Выбор данных
		*
		*/
		$all_free_orders = $this->m_manager_order->get_all_free_orders($this->manager_users->get_org_id());

		/*
		*
		* Вывод данных на экран
		*
		*/
		$this->template->build("orders/free",array("orders" => $all_free_orders));
	}

	/**
	 * Заявки всех курируемых агентов
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _delegate_orders()
	{
		/*
		*
		*  Установки шаблона
		*/
		$this->template->set_partial("dashboard_tabs","dashboard/dashboard_tabs");

		/*
		*
		*	Установки фильтра
		*/
		$filter = array();
		$limit  = false;
		$offset = false;

		/*
		*
		*	Выбор данных
		*/
		$all_delegate_orders = $this->m_manager_order->get_all_delegate_orders($this->manager_users->get_user_id());


		/*
		*
		*	Вывод данных
		*
		*/
		$this->template->build("orders/delegate",array("orders" => $all_delegate_orders));

	}
} // END class Orders extends MX_Controller