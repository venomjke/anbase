<?php defined("BASEPATH") or die("No direct access to script");



/**
 * Контроллер заявок админа
 *
 * @package Anbase
 * @author Alex.strigin 
 **/
class Orders extends MX_Controller
{

	public function __construct(){

		parent::__construct();

		$this->load->library("admin/Admin_Users");
		if( !$this->admin_users->is_logged_in_as_admin() ){
			redirect("");
		}

		/*
		*
		*	загрузка моделей
		*
		*/
		$this->load->model("admin/m_admin_order");


		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
	}


	/*
	*
	*	Подконтроллер, определяющий по типу раздела какой метод выводить
	*/
	public function _remap(){

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
				/*
				*
				*	Действие по умолчанию
				*/
					$this->view();
					break;
			}
		}else{
			/*
			*
			*	Действие по умолчанию
			*/
			$this->view();
		}

	}

	/**
	*
	*	Действие, отвечающее за отображение всех заявок
	*	
	*	@author Alex.strigin
	*	@company Flyweb	
	*
	*/
	public function view(){

		/*
		*	
		*	Базовые установки шаблона
		*/
		$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');


		/* 
		*
		*	Установки фильтров
		*
		*/

		$filter = array();

		$limit = FALSE;
		$offset = FALSE;
		/**
		*
		*
		*	Выбор и подготовка данных
		*
		*/
		$all_orders_org = $this->m_admin_order->get_all_orders_org($this->admin_users->get_org_id(),$filter,$limit,$offset);

		/*
		*
		* Вывод
		*
		*/
		$this->template->build('orders/view',array('orders'=>$all_orders_org));
	}


	/**
	*
	*	"Закрытый" метод, выводящий список свободных заявок
	*
	*	@author Alex.strigin
	*	@company Flyweb
	*/
	public function _free_orders(){

		/*
		*
		*	Базовые установки шаблона
		*/
		$this->template->set_partial('dashboard_tabs','dashboard_tabs');

		/*
		*
		*	Установка фильтров
		*
		*/
		$filter = array();
		$limit  = FALSE;
		$offset = FALSE;

		/*
		*
		*	Выбор данных их подготовка для вывода
		*
		*/
		$all_free_orders = $this->m_admin_order->get_all_free_orders($this->admin_users->get_org_id(),$filter,$limit,$offset);

		/*
		*
		*	Вывод данных
		*
		*/
		$this->template->build('orders/view',array('orders' => $all_free_orders));
	}

	/**
	*
	*	"Закрытый" метод, выводящий делегированные заявки
	*	@author Alex.strigin
	*	@company Flyweb
	*/
	public function _delegate_orders(){
		/*
		*	Базовые установки шаблона
		*/
		$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');

		/*
		*
		*	Установка фильтра
		*
		*/

		$filter = array();

		$limit = FALSE;
		$offset = FALSE;
		/*
		*
		*	Выбор данных и их подгтовка для вывода
		*
		*/
		$all_delegate_orders = $this->m_admin_order->get_all_delegate_orders($this->admin_users->get_org_id());
		/*
		*
		*	Вывод данных
		*
		*/
		$this->template->build('orders/view',array('orders' => $all_delegate_orders));

	}
} // END class Orders extends MX_Controller