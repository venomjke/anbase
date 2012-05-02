<?php defined("BASEPATH") or die("No direct access to script");


/*
* Подключение исключений
*/
if(!class_exists("ValidationException")){
	require_once APPPATH."exceptions/ValidationException.php";
}

if(!class_exists("AnbaseRuntimeException")){
	require_once APPPATH."exceptions/AnbaseRuntimeException.php";
}


/**
 * Класс, управляющий заявками админа
 *
 * @package default
 * @author alex.strigin
 **/
class Admin_Orders
{
	/*
	* Объект приложения
	*/
	private $ci;

	public function __construct()
	{
		$this->ci = get_instance();

		$this->ci->load->library("admin/Admin_Users");
		$this->ci->load->library("Orders_Organization");

		$this->ci->load->model("m_admin_order");
		$this->ci->load->model("m_order_region");
		$this->ci->load->model("m_order_metro");
	}

	/**
	 * Выбор всех заявок агентства
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_orders_org()
	{
		/*
		* Поля, которые нужно выбирать из базы
		*/
		$order_fields = array('number','create_date','category','deal_type','description','price','phone','any_metro','any_region','delegate_date','finish_date');
		$items = $this->ci->orders_organization->get_all_orders_org($this->ci->admin_users->get_org_id(),$order_fields);
		return array('count'=>count($items),'total'=>$this->ci->orders_organization->count_all_orders_org($this->ci->admin_users->get_org_id()),'items'=>$items);

	}
	/**
	 * Выбор всех свободных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders()
	{	
		$order_fields = array('number','create_date','category','deal_type','description','phone','price','any_metro','any_region');
		$items        = $this->ci->orders_organization->get_all_free_orders($this->ci->admin_users->get_org_id(),$order_fields);
		return array('count' => count($items),'total'=>$this->ci->orders_organization->count_all_free_orders($this->ci->admin_users->get_org_id()),'items' =>$items );
	}

	/**
	 * Выбор всех делегированных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders()
	{
		$order_fields = array('number','create_date','category','deal_type','description','price','phone','any_metro','any_region','delegate_date');
		$items        = $this->ci->orders_organization->get_all_delegate_orders($this->ci->admin_users->get_org_id(),$order_fields);
		return array('count' => count($items),'total'=>$this->ci->orders_organization->count_all_delegate_orders($this->ci->admin_users->get_org_id()),'items' =>$items );
	}


	/**
	 * Редактирование заявки.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_order()
	{
		/*
		* правила валидации для полей
		*/
		$order_field = array('number','create_date','deal_type','category','price','description','phone','delegate_date','finish_date','state');
		$metro_field = array('metros');
		$region_field = array('regions');

		$this->ci->form_validation->set_rules($this->ci->m_admin_order->edit_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_admin_order)){
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
				$this->ci->m_admin_order->update($this->ci->input->post('id'),$data,true);
			return;
		}

		$errors_validation = array();
		if(has_errors_validation($this->ci->m_admin_order->get_edit_validation_fields(),$errors_validation)){
			throw new ValidationException($errors_validation);
	}
}

} // END class Admin_Orders