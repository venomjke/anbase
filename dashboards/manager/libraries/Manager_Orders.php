<?php defined("BASEPATH") or die("No direct access to script");


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

		/*
		* Подключение исключение
		*/
		$this->ci->load->exception('AnbaseRuntimeException');
		$this->ci->load->exception('ValidationException');
		
		/*
		* Подключение библиотек
		*/
		$this->ci->load->library('manager/Manager_Users');
		$this->ci->load->library('Orders_Organization');

		/*
		* Подключение моделей
		*/
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
		$order_fields = array('number','create_date','finish_date','finish_status','category','deal_type','description','price','phone','any_metro','any_region');
		$items        = $this->ci->orders_organization->get_all_off_orders($this->ci->manager_users->get_user_id(),$order_fields);
		return array('count' => count($items), 'total'=>$this->ci->orders_organization->count_all_off_orders($this->ci->manager_users->get_user_id()),'items'=>$items);
	}
	/**
	 * Выбор всех заявок, делегированных агентам, которых курирует админ
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders($state='on')
	{
		$orders_fields = array('number','create_date','category','deal_type','description','price','phone','any_region','any_metro','finish_status');

		/*
		* Определяем фильтры limit, offset
		*/
		$filter = $this->ci->orders_organization->fetch_filter();

		$limit  = false;
		$offset = false;

		$orders = $this->ci->orders_organization->fetch_limit($limit,$offset);
			
		$orders = $this->ci->m_manager_order->get_all_delegate_orders($this->ci->manager_users->get_user_id(),$filter,$limit,$offset,$orders_fields,($state=='on'?M_Order::ORDER_STATE_ON:M_Order::ORDER_STATE_OFF));
		$this->ci->orders_organization->bind_metros($orders);
		$this->ci->orders_organization->bind_regions($orders);

		return array('count'=>count($orders),'total'=>$this->ci->m_manager_order->count_all_delegate_orders($this->ci->manager_users->get_user_id(),$filter,($state=='on'?M_Order::ORDER_STATE_ON:M_Order::ORDER_STATE_OFF)),'items'=>$orders);
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
		$order_field = array('deal_type','category','price','description','phone','any_region','any_metro');
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

	/**
	 * Подготовка и выбор заявок на распечатку
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_print_orders()
	{
		$this->ci->load->model('m_order_user');
		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');

		$orders = $this->ci->input->get('orders');

		if(!empty($orders) && is_array($orders)){

			$valid_orders = array();

			foreach($orders as $order_id){
				if(is_numeric($order_id) and $order_id > 0 and $this->ci->m_order_user->does_order_belong_user($order_id,$this->ci->manager_users->get_user_id())){
					
					$order = $this->ci->m_order->get($order_id);
					$order->regions = $this->ci->m_order_region->get_order_regions($order_id,false,true);
					$order->metros = $this->ci->m_order_metro->get_order_metros($order_id,true,true);
					$valid_orders[] = $order;
				}
			}

			if(!empty($valid_orders)) return $valid_orders;
		}

		throw new AnbaseRuntimeException(lang("common.not_legal_data"));
	}

} // END class Manager_Orders