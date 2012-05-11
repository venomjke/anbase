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


	public function get_all_off_orders()
	{
		$order_fields = array('number','create_date','finish_date','category','deal_type','description','price','phone','any_metro','any_region');
		$items        = $this->ci->orders_organization->get_all_off_orders_org($this->ci->admin_users->get_org_id(),$order_fields);
		return array('count' => count($items),'total'=>$this->ci->orders_organization->count_all_off_orders_org($this->ci->admin_users->get_org_id()),'items' =>$items );	
	}

	/**
	 * Выбор следующего за последним номера
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_next_number()
	{
		$max_number = $this->ci->m_order->get_max_number($this->ci->admin_users->get_org_id());
	return $max_number + 1;
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
		$order_field = array('number','create_date','deal_type','category','price','description','phone','delegate_date','finish_date','state','any_metro','any_region');
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

	/**
	 * Добавление заявки.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function add_order()
	{
		/*
		* Устанавливаем правила валидации	
		*/
		$insert_fields = array('number','create_date','category','deal_type','price','description','delegate_date','finish_date','phone','state');

		$this->ci->form_validation->set_rules($this->ci->m_admin_order->add_order_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_admin_order)){
			/*
			* Валидацию прошли, теперь можем наши данные собрать воедино вместе с пред. определенными
			*/
			$insert_data = array_intersect_key($this->ci->input->post(),array_flip($insert_fields));
			$insert_data['org_id'] = $this->ci->admin_users->get_org_id();
			$insert_data['number'] = $this->ci->admin_orders->get_next_number();
			if( ($org_id = $this->ci->m_admin_order->insert($insert_data,true))){

				/*
				* Добавляем метро, если указано
				*/
				if($this->ci->input->post('metros')){
					/*
					* обращаемся к orders_metros
					*/
					$this->ci->m_order_metro->bind_order_metros($org_id,$this->ci->input->post('metros'));
				}
				/*
				* Добавляем районы если указаны
				*/
				if($this->ci->input->post('regions')){
					/*
					* обращаемся к orders_regions
					*/
					$this->ci->m_order_region->bind_order_regions($org_id,$this->ci->input->post('regions'));
				}
				return $org_id;
			}

			throw new AnbaseRuntimeException(lang('common.insert_error'));
		}

		$errors_validation = array();

		if(has_errors_validation($insert_fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}
		return false;
	}

	
	/**
	 * Удаление заказов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function del_orders()
	{
		$orders_ids = $this->ci->input->post('orders');
		$valid_ids = array();
		if(is_array($orders_ids)){
			foreach($orders_ids as $order_id){

				if(is_numeric($order_id) && $this->ci->m_order->is_exists($order_id,$this->ci->admin_users->get_org_id())) {
					$valid_ids[] = $order_id;
				}
			}

			/*
			* Будем просто удалять, а получилось или нет, это уже не наша забота. 
			* P.S Если пользователь не хакер, то все получится.
			* P.P.S База должна позаботиться о том, чтобы все связанные с заявкой записи были тоже удалены
			*/
			if(!empty($valid_ids))
				$this->ci->m_order->delete_orders($valid_ids);
			return;
		}
		throw new AnbaseRuntimeException(lang('common.not_legal_data'));
	}

	public function restore_orders()
	{
		$orders_ids = $this->ci->input->post('orders');
		$valid_ids = array();
		if(is_array($orders_ids)){
			foreach($orders_ids as $order_id){

				if(is_numeric($order_id) && $this->ci->m_order->is_exists($order_id,$this->ci->admin_users->get_org_id())) {
					$valid_ids[] = $order_id;
				}
			}

			$this->ci->m_order->restore_orders($valid_ids);
			return;
		}
		throw new AnbaseRuntimeException(lang('common.not_legal_data'));
	}
	/**
	 * Выключение заявок
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function finish_orders()
	{
		$orders_ids = $this->ci->input->post('orders');
		$valid_ids = array();
		if(is_array($orders_ids)){
			foreach($orders_ids as $order_id){

				if(is_numeric($order_id) && $this->ci->m_order->is_exists($order_id,$this->ci->admin_users->get_org_id())) {
					$valid_ids[] = $order_id;
				}
			}
			$this->ci->m_order->finish_orders($valid_ids);
			return;
		}
		throw new AnbaseRuntimeException(lang('common.not_legal_data'));
	}

	/**
	 * Назначение заявки члену персонала
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function delegate_order()
	{
		$this->ci->load->model('m_order_user');
		/*
		* Правила валидации прикрепляем
		*/
		$this->ci->form_validation->set_rules($this->ci->m_order_user->delegate_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_order_user)){

			/*
			*
			* Т.к к одной заявке может быть привязан только один человек, то для удаления записи в таблице orders_users
			* достаточно знать order_id
			*/
			$order_id = $this->ci->input->post('order_id');
			$user_id  = $this->ci->input->post('user_id');
			if($this->ci->m_order_user->delegate_order($order_id,$user_id)){
				return true;
			}
			throw new AnbaseRuntimeException(lang("common.insert_error"));
		}

		$errors_validation = array();

		if(has_errors_validation(array('order_id','user_id'),$errors_validation)){
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
		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');

		$orders = $this->ci->input->get('orders');

		if(!empty($orders) && is_array($orders)){

			$valid_orders = array();

			foreach($orders as $order_id){
				if(is_numeric($order_id) and $order_id > 0 and $this->ci->m_order->is_exists($order_id,$this->ci->admin_users->get_org_id())){
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

} // END class Admin_Orders