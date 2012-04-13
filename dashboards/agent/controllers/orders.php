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
		parent::__construct();
		/*
		*
		*	Загрузка либ
		*
		*/
		$this->load->library("agent/Agent_Users");
		$this->load->library("agent/Agent_Orders");
		$this->load->library("Ajax");

		if(!$this->agent_users->is_logged_in_as_agent()){
			redirect('');
		}

		/*
		*
		*	Загрузка доп. моделей
		*/
		$this->load->model('m_region');
		$this->load->model('m_metro');

		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');

		/*
		* Не знаю, как лучше обыграть эту задачу, но пока так.
		*/
		$regions = $this->m_region->get_region_list("json");
		$metros  = $this->m_metro->get_metro_list("json");
		/*
		* Подключение скриптов
		*/
		$this->template->append_metadata('<script type="text/javascript"> common.regions='.$regions.'; common.metros='.$metros.'</script>');

		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/agent/js/agent.js").'"> 
			agent.init({baseUrl:"'.base_url().'"});
			agent.orders.init(); 
		</script>');

	}


	/**
	 * Редирект на orders/view
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function index()
	{
		redirect('agent/orders');
	}


	/**
	 * Маленьки маршрутизатор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function _remap()
	{
		$action = $this->input->get('act')?$this->input->get('act'):'view';

		/*
		* Обрабатываем действие
		*/
		switch ($action) {
			case 'view':
			default:

				/*
				* Обработка действия view: выбираем section, и отображаем её
				*/
				$section = $this->input->get('s')?$this->input->get('s'):'my';

				switch ($section) {
					case 'my':
					default:
						/*
						* Отображение заявок агента
						*/
						$this->_view();
						break;
					case 'free':
						/*
						* Отображение свободных
						*/
						$this->_free_orders();
						break;		
				}
				break;
			case 'edit':
			/*
			* Редактирование заявок
			*/
				break;
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