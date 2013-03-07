<?php defined("BASEPATH") or die("No direct access to script");


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

		/*
		* Подключение исключений
		*/
		$this->ci->load->exception('ValidationException');
		$this->ci->load->exception('AnbaseRuntimeException');

		/*
		* Полкючение библиотек
		*/
		$this->ci->load->library('agent/Agent_Users');
		$this->ci->load->library('Orders_Organization');

		/*
		* Подключение моделей
		*/
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
		$items        = $this->ci->orders_organization->get_all_free_orders($this->ci->agent_users->get_org_id(), $order_fields);
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
		$order_fields = array('number','create_date','finish_date','finish_status','category','deal_type','description','price','phone','any_metro','any_region');
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
				if(is_numeric($order_id) and $order_id > 0 and $this->ci->m_order_user->does_order_belong_user($order_id,$this->ci->agent_users->get_user_id())){
					
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

	/**
	 * Обновление списка комментариев: добавление или удаление
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function update_comments()
	{
		$this->ci->load->model('m_order_comments');

		$order_id = $this->ci->input->post('order_id');
		$added_comments = $this->ci->input->post('added_comments');
		$del_comments   = $this->ci->input->post('del_comments');
		$comments = array();

		if($this->ci->m_order->is_exists($order_id, $this->ci->agent_users->get_org_id())){
			if(is_array($added_comments)){
				foreach($added_comments as $comment){
					$comment['text'] = trim($comment['text']);
					$comment['text'] = $this->ci->security->xss_clean($comment['text']);
					$comment['text'] = htmlspecialchars($comment['text']);
					$this->ci->m_order_comments->add_order_comment($order_id, $this->ci->agent_users->get_user_id(), $comment['text']);
				}
			}
			$comments = $this->ci->m_order_comments->get_order_comments($order_id);	
		}
		return $comments;
	}
} // END class Agent_Orders