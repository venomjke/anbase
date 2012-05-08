<?php defined("BASEPATH") or die("No direct access to script");



/*
*
* Подключение исключений
*
*/
if(!class_exists("ValidationException")){
	require_once APPPATH."exceptions/ValidationException.php";
}

if(!class_exists("AnbaseRuntimeException")){
	require_once APPPATH."exceptions/AnbaseRuntimeException.php";
}


/**
 * Класс для управления заявками агента.
 *
 * @package default
 * @author alex.strigin 
 **/
class Agent_Orders
{
	/*
	* Объект приложения
	*/
	private $ci;

	/**
	 * Конструктор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function __construct()
	{	
		$this->ci = get_instance();

		$this->ci->load->library('agent/Agent_Users');
		$this->ci->load->library('Orders_Organization');

		$this->ci->load->model('m_agent_order');
		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');
	}


	/**
	 * Выбор всех заявок агента
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_agent_orders()
	{
		/*
		* Поля, которые нужно выбрать
		*/
		$order_fields = array('number','create_date','category','deal_type','description','price','phone','any_metro','any_region');
		$items        = $this->ci->orders_organization->get_all_user_orders($this->ci->agent_users->get_user_id(),$order_fields);
		return array('count'=>count($items),'total'=>$this->ci->orders_organization->count_all_user_orders($this->ci->agent_users->get_user_id()),'items'=>$items);
	}

	/**
	 * Выбор всех свободных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders()
	{
		$order_fields = array('number','create_date','category','deal_type','description','price','any_metro','any_region');
		$items        = $this->ci->orders_organization->get_all_free_orders($this->ci->agent_users->get_org_id(),$order_fields);
		return array('count' => count($items),'total'=>$this->ci->orders_organization->count_all_free_orders($this->ci->agent_users->get_org_id()),'items' =>$items );
	}

	/**
	 * Выбор всех завершенных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_off_orders()
	{
		$order_fields = array('number','create_date','finish_date','category','deal_type','description','price','phone','any_metro','any_region');
		$items        = $this->ci->orders_organization->get_all_off_orders($this->ci->agent_users->get_user_id(),$order_fields);
		return array('count' => count($items), 'total'=>$this->ci->orders_organization->count_all_off_orders($this->ci->agent_users->get_user_id()),'items'=>$items);
	}

	/**
	 * Редактирование заявки
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_order()
	{
		/*
		* правила валидации для полей
		*/
		$order_field = array('deal_type','category','price','description','phone','any_metro','any_region');
		$metro_field = array('metros');
		$region_field = array('regions');

		$this->ci->form_validation->set_rules($this->ci->m_agent_order->edit_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_agent_order)){
			/*
			* Решаем, что редактировать
			*/
			if($this->ci->input->post('metros')){
				/*
				* обращаемся к orders_metros
				* т.к во время передачи metros передается еще и any_metro флаг
				*/
				$metros = $this->ci->input->post('metros');
				$this->ci->m_order_metro->bind_order_metros($this->ci->input->post('id'),$metros);
			}

			if($this->ci->input->post('regions')){
				/*
				* обращаемся к orders_regions
				*/
				$regions = $this->ci->input->post('regions');
				$this->ci->m_order_region->bind_order_regions($this->ci->input->post('id'),$regions);
			}

			/*
			* стандартное редактирование
			*/
			$data = array_intersect_key($this->ci->input->post(), array_flip($order_field));
			if(!empty($data))
				$this->ci->m_agent_order->update($this->ci->input->post('id'),$data,true);
			return;
		}
		$errors_validation = array();
		if(has_errors_validation($this->ci->m_agent_order->get_edit_validation_fields(),$errors_validation)){
			throw new ValidationException($errors_validation);
		}
	}

} // END class Agent_Orders