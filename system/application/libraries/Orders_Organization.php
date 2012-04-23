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
		/*
		* Загрузка модели m_order
		*/
		$this->ci->load->model('m_order');

		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');

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
		/*
		* Извлекаем параметры фильтра, валидируем их, а потом засовываем в массив
		*/
		$filter_fields = array('number','phone','category','dealtype','createdate_from','createdate_to','price_from','price_to','description');

		/*
		* [my_notice] Не самый лучший способ проверить данные фильтра, но другого не придумал.
		*/
		$_POST = array_merge($_POST,array_intersect_key($this->ci->input->get(), array_flip($filter_fields)));

		$this->ci->form_validation->set_rules($this->ci->m_order->filter_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_order)){
			/*
			* собираем фильтр и возвращаем
			*/
			return array(
				'number' => $this->ci->input->post('number'),
				'phone'  => $this->ci->input->post('phone'),
				'category' => $this->ci->input->post('category'),
				'dealtype' => $this->ci->input->post('dealtype'),
				'createdate' => array('createdate_from'=>$this->ci->input->post('createdate_from'),'createdate_to'=>$this->ci->input->post('createdate_to')),
				'price' => array('price_from'=>$this->ci->input->post('price_from'),'price_to'=>$this->ci->input->post('price_to')),
				'description' => $this->ci->input->post('description')
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

		$filter = $this->fetch_filter();

		$limit = false;
		$offset = false;
		$this->fetch_limit($limit,$offset);
		
		$orders = $this->ci->m_order->get_all_free_orders($org_id,$filter,$limit,$offset,$fields);
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
		$filter = $this->fetch_filter();
		return $this->ci->m_order->count_all_free_orders($org_id,$filter);
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
		$filter = array();

		$limit = false;
		$offset= false;

		$this->fetch_limit($limit,$offset);

		$orders = $this->ci->m_order->get_all_delegate_orders($org_id,$filter,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);

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
		return $this->ci->m_order->count_all_delegate_orders($org_id);
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
		$filter = array();
		
		$limit = false;
		$offset = false;

		$this->fetch_limit($limit,$offset);

		$orders = $this->ci->m_order->get_all_orders_org($org_id,$filter,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);

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
		return $this->ci->m_order->count_all_orders_org($org_id);
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
		$filters = array();
		
		$limit  = false;
		$offset = false;
		$this->fetch_limit($limit,$offset);


		$orders = $this->ci->m_order->get_all_orders_user($user_id,$filters,$limit,$offset,$fields);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
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
		return $this->ci->m_order->count_all_user_orders($user_id);
	}
	
} // END class OrdersOrganization