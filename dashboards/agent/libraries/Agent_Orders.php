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
		$order_fields = array('number','create_date','category','deal_type','description','price','phone');
		return $this->ci->orders_organization->get_all_user_orders($this->ci->agent_users->get_user_id(),$order_fields);
	}

	/**
	 * Выбор всех свободных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders()
	{
		$order_fields = array('number','create_date','category','deal_type','description','price');
		return $this->ci->orders_organization->get_all_free_orders($this->ci->agent_users->get_org_id(),$order_fields);
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
		$order_field = array('number','create_date','deal_type','category','price','description','phone');
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
				*/
				$this->ci->m_order_metro->bind_order_metros($this->ci->input->post('id'),$this->ci->input->post('metros'));
			}else if($this->ci->input->post('regions')){
				/*
				* обращаемся к orders_regions
				*/
				$this->ci->m_order_region->bind_order_regions($this->ci->input->post('id'),$this->ci->input->post('regions'));
			}else{
				/*
				* стандартное редактирование
				*/
				$data = array_intersect_key($this->ci->input->post(), array_flip($order_field));
				if(!empty($data))
					$this->ci->m_agent_order->update($this->ci->input->post('id'),$data,true);
				else
					throw new AnbaseRuntimeException(lang('common.not_legal_data'));
			}
			return;
		}

		$errors_validation = array();
		if(has_errors_validation($this->ci->m_agent_order->get_edit_validation_fields(),$errors_validation)){
			throw new ValidationException($errors_validation);
		}
	}

} // END class Agent_Orders