<?php defined("BASEPATH") or die("No direct access to script");



/**
 * Класс, реализующий логику обработки заявок организации
 *
 * @package default
 * @author alex.strigin
 **/
class Orders_Organization
{
	/*
	* Лимит записей по умолчанию
	*/
	const def_orders_limit = 200;

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

		// загрузкая объектов исключений
		$this->ci->load->exception('AnbaseRuntimeException');
		$this->ci->load->exception('ValidationException');
		
		// загрузка моделей
		$this->ci->load->model('m_order');
		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');
		$this->ci->load->model('m_order_comments');

	}

	/**
	 * Извлечение параметров предела и смещения
	 *
	 * @param ref
	 * @param ref
	 * @return void
	 * @author alex.strigin
	 **/
	public function fetch_limit(&$limit,&$offset)
	{
		$limit  = $this->ci->input->get('limit')?$this->ci->input->get('limit'):Orders_Organization::def_orders_limit;
		$offset = $this->ci->input->get('offset')?$this->ci->input->get('offset'):0;

		if(!is_numeric($limit) or !is_numeric($offset) or $limit < 0 or $offset < 0){
			throw new AnbaseRuntimeException(lang('common.not_legal_data'));
		}
	}

	/**
	 * Извлечение параметров фильтра
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function fetch_filter()
	{
		$this->ci->load->model('m_region');
		$this->ci->load->model('m_metro');
		/*
		* Если get array пуст, то даже и не пытаемся что-то сделать
		*/
		if( ! $this->ci->input->get()){
			return array();
		}

		/*
		* Извлекаем параметры фильтра, валидируем их, а потом засовываем в массив
		*/
		$filter_fields = array('number','number_from','number_to','number_order','phone','category','dealtype','createdate_from','createdate_to','createdate_order','price_from','price_to','price_order','description','regions','metros','description_type','user_id','agent_order','finishStatusOrder');

		/*
		* [my_notice] Не самый лучший способ проверить данные фильтра, но другого не придумал.
		*/
		$_POST = array_merge($_POST,array_intersect_key($this->ci->input->get(), array_flip($filter_fields)));

		$this->ci->form_validation->set_rules($this->ci->m_order->filter_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_order) && $this->ci->m_region->check_regions($this->ci->input->post('regions')) && $this->ci->m_metro->check_metros($this->ci->input->post('metros'))){


			/*
			* собираем фильтр и возвращаем
			*/
			return array(
				'number' => array('number'=>$this->ci->input->post('number'),'number_from'=>$this->ci->input->post('number_from'),'number_to'=>$this->ci->input->post('number_to'),'number_order'=>$this->ci->input->post('number_order')),
				'phone'  => $this->ci->input->post('phone'),
				'category' => $this->ci->input->post('category'),
				'dealtype' => $this->ci->input->post('dealtype'),
				'createdate' => array('createdate_from'=>$this->ci->input->post('createdate_from'),'createdate_to'=>$this->ci->input->post('createdate_to'),'createdate_order'=>$this->ci->input->post('createdate_order')),
				'price' => array('price_from'=>$this->ci->input->post('price_from'),'price_to'=>$this->ci->input->post('price_to'),'price_order'=>$this->ci->input->post('price_order')),
				'description' => array('words'=>$this->ci->input->post('description'),'type'=>$this->ci->input->post('description_type')),
				'regions' => $this->ci->input->post('regions'),
				'metros'  => $this->ci->input->post('metros'),
				'user_id' => $this->ci->input->post('user_id'),
				'agent_order' => $this->ci->input->post('agent_order'),
				'finishStatusOrder'=> $this->ci->input->post('finishStatusOrder')
			);
		}

		$errors_validation = array();

		if(has_errors_validation($filter_fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}

		/*
		* сюда попадаем только в том случае, если фильтр задан
		*/
		return array();
	}
	/**
	 * Привязать к заявкам регионы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_regions(&$orders)
	{
		foreach($orders as $order){
			$order->regions = $this->ci->m_order_region->get_order_regions($order->id);
		}
	}

	/**
	 * Привязать к заявкам метро
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_metros(&$orders)
	{
		foreach($orders as $order){
			$order->metros = $this->ci->m_order_metro->get_order_metros($order->id);
		}
	}

	/**
	 * Загрузка комментариев заявки
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_comments(& $orders)
	{
		foreach($orders as $order){
			$order->comments = $this->ci->m_order_comments->get_order_comments($order->id);
		}
	}

	/**
	 * Выбор всех свободных заявок
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_free_orders($org_id,$fields=array())
	{
		/*
		* 1. Выбор свободных заявок
		* 2. Привязывание regions
		* 3. Привязывание metros
		*/

		$filters = $this->fetch_filter();

		$limit = false;
		$offset = false;
		$this->fetch_limit($limit,$offset);
		
		$orders = $this->ci->m_order->get_all_free_orders($org_id,$filters,$limit,$offset,$fields);
		$this->bind_regions($orders);

		$this->bind_metros($orders);
		return $orders;
	}

	/**
	 * Подсчет всех свободных заявок агентства
	 *
	 * @return void
	 * @author 
	 **/
	public function count_all_free_orders($org_id)
	{
		$filters = $this->fetch_filter();
		return $this->ci->m_order->count_all_free_orders($org_id,$filters);
	}

	/**
	 * Выбор всех делегированных заявок
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders($org_id,$fields=array())
	{
		/*
		* 1. Выбор всех свободных заявок
		* 2. Привязывание regions
		* 3. Привязывание metros
		*/
		$filters = $this->fetch_filter();;

		$limit = false;
		$offset= false;

		$this->fetch_limit($limit,$offset);

		$orders = $this->ci->m_order->get_all_delegate_orders($org_id,$filters,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		$this->bind_comments($orders);

		return $orders;
	}

	/**
	 * Подсчет всех делегированных заявок
	 *
	 * @param  int
	 * @return int
	 * @author alex.strigin
	 **/
	public function count_all_delegate_orders($org_id)
	{
		$filters = $this->fetch_filter();
		return $this->ci->m_order->count_all_delegate_orders($org_id,$filters);
	}

	/**
	 * Выбор всех заявок организации
	 *
	 * @param int
	 * @param array
	 * @return array
	 * @author alex.trigin
	 **/
	public function get_all_orders_org($org_id,$fields=array())
	{
		$filters = $this->fetch_filter();
		
		$limit = false;
		$offset = false;

		$this->fetch_limit($limit,$offset);


		$orders = $this->ci->m_order->get_all_orders_org($org_id,$filters,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		$this->bind_comments($orders);

		return $orders;
	}

	/**
	 * Подсчет всех заявок агентства
	 *
	 * @param  int
	 * @return int
	 * @author alex.strigin
	 **/
	public function count_all_orders_org($org_id)
	{
		$filters = $this->fetch_filter();
		return $this->ci->m_order->count_all_on_orders_org($org_id,$filters);
	}

	/**
	 * Выбор всех заявок определенного пользователя
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_user_orders($user_id,$fields=array())
	{
		/*
		* 0. Установка фильтров
		* 1. Выбор всех свободных заявок
		* 2. Привязывание regions
		* 3. Привязывание metros
		*/
		$filters = $this->fetch_filter();

		$limit  = false;
		$offset = false;

		$this->fetch_limit($limit,$offset);

		$orders = $this->ci->m_order->get_all_orders_user($user_id,$filters,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		$this->bind_comments($orders);

		return $orders;
	}

	/**
	 * Подсчет всех заявок пользователя
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function count_all_user_orders($user_id)
	{
		$filters = $this->fetch_filter();
		return $this->ci->m_order->count_all_user_orders($user_id,$filters);
	}
	
	/**
	 * Выбор всех завершенных заявок агента
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_off_orders($user_id,$fields=array())
	{
		$filters = $this->fetch_filter();

		$limit  = false;
		$offset = false;

		//print_r($this->ci->input->get('regions'));
		//print_r($this->ci->input->get('metros'));

		$this->fetch_limit($limit,$offset);

		$orders = $this->ci->m_order->get_all_off_orders($user_id,$filters,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		$this->bind_comments($orders);

		return $orders;
	}

	public function count_all_off_orders($user_id)
	{
		$filters = $this->fetch_filter();
		return $this->ci->m_order->count_all_off_orders($user_id,$filters);
	}

	/**
	 * Выбор всех завершенных заявок организации
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_off_orders_org($org_id,$fields=array())
	{
		$filters = $this->fetch_filter();

		$limit  = false;
		$offset = false;

		//print_r($this->ci->input->get('regions'));
		//print_r($this->ci->input->get('metros'));

		$this->fetch_limit($limit,$offset);

		$orders = $this->ci->m_order->get_all_off_orders_org($org_id,$filters,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		$this->bind_comments($orders);

		return $orders;
	}

	public function count_all_off_orders_org($org_id)
	{
		$filters = $this->fetch_filter();
		return $this->ci->m_order->count_all_off_orders_org($org_id,$filters);
	}
} // END class OrdersOrganization