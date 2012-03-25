<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Класс, реализующий логику контроллера заявок агента
 *
 * @package anbase
 * @author Alex.strigin
 **/
class Orders extends MX_Controller
{
	/**
	 * конструктор
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
		$this->load->library("agent/Agent_Users");

		if(!$this->agent_users->is_logged_in_as_agent()){
			redirect('');
		}

		/*
		*
		*	Загрузка основной модели
		*/
		$this->load->model("agent/m_agent_order");

		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
	}


	/**
	 * Редирект на orders/view
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function index()
	{
		redirect('agent/orders/view');
	}


	/**
	 * Подконтроллер, выполняющий переадресацию на нужную функцию
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
				default:
					$this->_view();
					break;
			}
		}else{

			$this->_view();
		}
	}

	/**
	 * Отображение заявок агента
	 *
	 * @return void
	 * @author 
	 **/
	private function _view(){

		/*
		*
		*	Установка шаблона
		*
		*/
		$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');


		/*
		*
		*	Установки фильтров
		*
		*/
		$filter = array();
		$limit  = false;
		$offset = false;


		/*
		*
		*	Выбор данных
		*
		*/
		$all_agent_orders = $this->m_agent_order->get_all_orders_agent($this->agent_users->get_user_id(),$filter,$limit,$offset);

		/*
		*
		*	Вывод данных
		*
		*/
		$this->template->build('orders/view',array('orders' => $all_agent_orders));

	}



	private function _free_orders(){
		/*
		*
		*	Установки шаблона
		*
		*/
		$this->template->set_partial("dashboard_tabs","dashboard/dashboard_tabs");

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
		*	Выбор данных
		*
		*/
		$all_free_orders = $this->m_agent_order->get_all_free_orders($this->agent_users->get_org_id());

		/*
		*
		*	Вывод данных
		*
		*/
		$this->template->build('orders/free',array('orders' => $all_free_orders));
	}

} // END class Orders extends MX_Controller