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
 * Класс, отвечающий за реализацию операций над заявками в панели manager
 *
 * @package default
 * @author alex.strigin 
 **/
class Manager_Orders
{
	/*
	* Объект приложения
	*/
	private $ci;
	
	public function __construct()
	{
		$this->ci = get_instance();

		$this->ci->load->library('manager/Manager_Users');
		$this->ci->load->library('Orders_Organization');

		$this->ci->load->model('m_manager_order');	
		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');
	}

	/**
	 * Выбор всех заявок менеджера
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_orders_manager()
	{
		/*
		* Необходимые поля
		*/
		$orders_fields = array('number','create_date','category','deal_type','description','price','phone','any_metro','any_region');
		$items = $this->ci->orders_organization->get_all_user_orders($this->ci->manager_users->get_user_id(),$orders_fields);
		return array('count'=>count($items),'total'=>$this->ci->orders_organization->count_all_user_orders($this->ci->manager_users->get_user_id()),'items'=>$items);
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
		$items        = $this->ci->orders_organization->get_all_free_orders($this->ci->manager_users->get_org_id(),$order_fields);
		return array('count' => count($items),'total'=>$this->ci->orders_organization->count_all_free_orders($this->ci->manager_users->get_org_id()),'items' =>$items );
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
		return array('count' => count($items), 'total'=>$this->ci->orders_organization->count_all_off_orders($this->ci->manager_users->get_user_id()),'items'=>$items);
	}
	/**
	 * Выбор всех заявок, делегированных агентам, которых курирует админ
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders()
	{
		$orders_fields = array('number','create_date','category','deal_type','description','price','phone','any_region','any_metro');

		/*
		* Определяем фильтры, limit, offset
		*/
		$filter = $this->ci->orders_organization->fetch_filter();

		$limit  = false;
		$offset = false;

		$orders = $this->ci->orders_organization->fetch_limit($limit,$offset);
			
		$orders = $this->ci->m_manager_order->get_all_delegate_orders($this->ci->manager_users->get_user_id(),$filter,$limit,$offset,$orders_fields);
		$this->ci->orders_organization->bind_metros($orders);
		$this->ci->orders_organization->bind_regions($orders);

		return array('count'=>count($orders),'total'=>$this->ci->m_manager_order->count_all_delegate_orders($this->ci->manager_users->get_user_id(),$filter),'items'=>$orders);
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
		$order_field = array('number','create_date','deal_type','category','price','description','phone','any_region','any_metro');
		$metro_field = array('metros');
		$region_field = array('regions');

		$this->ci->form_validation->set_rules($this->ci->m_manager_order->edit_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_manager_order)){
			/*
			* Решаем, что редактировать
			*/
			if($this->ci->input->post('metros')){
				/*
				* обращаемся к orders_metros
				*/
				$this->ci->m_order_metro->bind_order_metros($this->ci->input->post('id'),$this->ci->input->post('metros'));
			}

			if($this->ci->input->post('regions')){
				/*
				* обращаемся к orders_regions
				*/
				$this->ci->m_order_region->bind_order_regions($this->ci->input->post('id'),$this->ci->input->post('regions'));
			}

			/*
			* стандартное редактирование
			*/
			$data = array_intersect_key($this->ci->input->post(), array_flip($order_field));
			if(!empty($data))
				$this->ci->m_manager_order->update($this->ci->input->post('id'),$data,true);

			return;
		}

		$errors_validation = array();
		if(has_errors_validation($this->ci->m_manager_order->get_edit_validation_fields(),$errors_validation)){
			throw new ValidationException($errors_validation);
		}
	
	}

} // END class Manager_Orders