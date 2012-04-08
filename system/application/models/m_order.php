<?php defined("BASEPATH") or die("No direct access to script");


/**
*
*	Модель для манипуляций над заявками
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*
*/
class M_Order extends MY_Model{


	/*
	*
	*  Категории
	*/
	const ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE = 'Коммерческая недвижимость';
	const ORDER_CATEGORY_COUNTRY_REAL_ESTATE    = 'Загородная недвижимость';
	const ORDER_CATEGORY_LIVING_REAL_ESTATE		= 'Жилая недвижимость';

	/*
	*
	* Типы сделки
	*/	
	const ORDER_DEAL_TYPE_RENT    = 'Сдам';
	const ORDER_DEAL_TYPE_TAKEOFF = 'Сниму';
	const ORDER_DEAL_TYPE_BUY     = 'Куплю';
	const ORDER_DEAL_TYPE_SELL    = 'Продам';

	/*
	*
	* Состояния
	*/
	const ORDER_STATE_ON	= 'on';
	const ORDER_STATE_OFF	= 'off';

	/*
	*
	* Правила валидации "создание" записи
	*/
	public $insert_validation_rules = array(
		array('field'=>'number','label'=>'lang:order.label_number', 'rules'=>'is_natural|max_length[9]'),
		array('field'=>'create_date','label'=>'lang:order.label_create_date','rules'=>'valid_datetime[yyyy/mm/dd h:m:s]'),
		array('field'=>'category','label'=>'lang:order.label_category','rules'=>'callback_valid_category'),
		array('field'=>'deal_type','label'=>'lang:order.label_deal_type','rules'=>'callback_valid_dealtype'),
		array('field'=>'region_id','label'=>'lang:order.label_region_id','rules'=>'is_natural|valid_region_id'),
		array('field'=>'metro_id','label'=>'lang:order.label_metro_id','rules'=>'is_natural|valid_metro_id'),
		array('field'=>'price','label'=>'lang:order.label_price','rules'=>'greater_than[0]|max_length[12]'),
		array('field'=>'description','label'=>'lang:order.label_description','rules'=>'xss_clean'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_datetime[yyyy/mm/dd h:m:s]'),
		array('field'=>'finish_date','label'=>'lang:order.label_finish_date','rules'=>'valid_datetime[yyyy/mm/dd h:m:s]'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules'=>'max_length[20]'),
		array('field'=>'state','label'=>'lang:order.label_state','rules'=>'callback_valid_state')
	);

	/*
	*
	* Правила валидации "изменение записи
	*/
	public $update_validation_rules = array(
		array('field'=>'id','label'=>'ORDER_ID','rules'=>'required|is_natural_no_zero'),
		array('field'=>'number','label'=>'lang:order.label_number', 'rules'=>'is_natural|max_length[9]'),
		array('field'=>'create_date','label'=>'lang:order.label_create_date','rules'=>'valid_datetime[yyyy/mm/dd h:m:s]'),
		array('field'=>'category','label'=>'lang:order.label_category','rules'=>'callback_valid_category'),
		array('field'=>'deal_type','label'=>'lang:order.label_deal_type','rules'=>'callback_valid_dealtype'),
		array('field'=>'region_id','label'=>'lang:order.label_region_id','rules'=>'is_natural|valid_region_id'),
		array('field'=>'metro_id','label'=>'lang:order.label_metro_id','rules'=>'is_natural|valid_metro_id'),
		array('field'=>'price','label'=>'lang:order.label_price','rules'=>'greater_than[0]|max_length[12]'),
		array('field'=>'description','label'=>'lang:order.label_description','rules'=>'xss_clean'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_datetime[yyyy/mm/dd h:m:s]'),
		array('field'=>'finish_date','label'=>'lang:order.label_finish_date','rules'=>'valid_datetime[yyyy/mm/dd h:m:s]'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules'=>'max_length[20]'),
		array('field'=>'state','label'=>'lang:order.label_state','rules'=>'callback_valid_state')
	);

	/**
	 * construct
	 *
	 * @return void
	 * @author 
	 **/
	function __construct()
	{
		parent::__construct();
		/*
		*
		*	Определение структура модели
		*
		*/
		$this->table       = 'orders';
		$this->primary_key = 'id';
		$this->fileds      = array('number','create_date','category','deal_type','region_id','metro_id','price','description','delegate_date','finish_date','phone','state','org_id');
		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*/
		$this->validate = array();

		/*
		* Хуки
		*/
		$this->before_insert = array('fill_empty_fields');
	}

	/**
	 * Проверка заявки на существование
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_exists($order_id,$org_id)
	{
	 	return $this->count_all_results(array('id'=>$order_id,'org_id'=>$org_id))==0?false:true;
	}
	/**
	 * Получить список категорий
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_category_list()
	{
		return array(M_Order::ORDER_CATEGORY_LIVING_REAL_ESTATE,M_Order::ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE,M_Order::ORDER_CATEGORY_COUNTRY_REAL_ESTATE);
	}

	/**
	 * Получить список типов сделок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_dealtype_list()
	{
		return array(M_Order::ORDER_DEAL_TYPE_SELL,M_Order::ORDER_DEAL_TYPE_BUY,M_Order::ORDER_DEAL_TYPE_TAKEOFF,M_Order::ORDER_DEAL_TYPE_RENT);
	}
	/**
	 * Заполнить поля по умолчанию, если они не заданы
	 *
	 * @return void
	 * @author 
	 **/
	protected function fill_empty_fields($data = array())
	{
		if(empty($data['create_date'])){
			$data['create_date'] = date('Y/m/d H:i:s');
		}

		/*
		* Можно задать по умолчанию id района и как-то по другому...
		*/
		if(empty($data['region_id'])){
			$data['region_id'] = 1;
		}

		/*
		*
		* Можно задать по умолчанию id метро и как-то по другому
		*/
		if(empty($data['metro_id'])){
			$data['metro_id'] = 1;
		}

	}
	/**
	 * Проверка состояния
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_state($state='')
	{
		/*
		* Т.к есть состояние по умолчанию, то можно пропустить "пустое" состояние
		*/
		if(empty($state)){
			return true;
		}else{
			if(in_array($state,array(M_Order::ORDER_STATE_ON,M_Order::ORDER_STATE_OFF))){
				return true;
			}else{
				$this->form_validation->set_message('valid_state',lang('order.validation.valid_state'));
				return false;
			}
		}
	}

	/**
	 * Проверка типа сделки
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_dealtype($deal_type='')
	{
		/*
		* Т.к есть сделка по умолчанию, то можно пропустить "пустую" сделку
		*/
		if(empty($deal_type)){
			return true;
		}else{
			if(in_array($deal_type,array(M_Order::ORDER_DEAL_TYPE_SELL,M_Order::ORDER_DEAL_TYPE_BUY,M_Order::ORDER_DEAL_TYPE_TAKEOFF,M_Order::ORDER_DEAL_TYPE_RENT))){
				return true;
			}else{
				$this->form_validation->set_message('valid_dealtype',lang('order.validation.valid_dealtype'));
				return false;
			}
		}
	}

	/**
	 * Проверка категории
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_category($category='')
	{	
		/*
		* Т.к есть категории по умолчанию, то можно пропустить пустую категорию
		*/
		if(empty($category)){
			return true;
		}else{
			if(in_array($category,array(M_Order::ORDER_CATEGORY_LIVING_REAL_ESTATE,M_Order::ORDER_CATEGORY_COUNTRY_REAL_ESTATE,M_Order::ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE))){
				return true;
			}else{
				$this->form_validation->set_message('valid_category',lang('order.validation.valid_category'));
				return false;
			}
		}
	}

	/**
	*
	*	Фильтр для поля Price
	*
	*/
	protected function set_price_filter($value = array()){

		$price_from = $value['price_from'];
		$price_to   = $value['price_to'];

		if(is_numeric($price_from)){
			$this->db->where('orders.price >=',$price_from);
		}

		if(is_numeric($price_to)){
			$this->db->where('orders.price <=',$price_to);
		}
	}


	/**
	*
	*	фильтр для поля description
	*
	*/
	protected function set_description_filter($value = ''){

		$this->db->like('orders.description',$value);
	}

	/**
	 * Метод проходит по списку полей применяя к каждому свой фильтр
	 *
	 * @param array
	 * @return void
	 * @author 
	 **/
	protected function set_filter($filter = array())
	{

		foreach($filter as $field => $value ){

			$filter_name = "set_{$field}_filter";
			if(method_exists($this,$filter_name)){
				$this->$filter_name($value);
			}else{
				throw Exception("Undifned filter_name {$filter_name} in ".__CLASS__);
			}
		}
	}
	/**
	* Метод определяет правила присоединения и список выбора
	* @return void
	* @author - Alex.strigin
	*
	*/
	protected function build_select(){

		$this->select('orders.id');
		$this->select('orders.number');
		$this->select('orders.create_date');
		$this->select('orders.category');
		$this->select('orders.deal_type');
		$this->select('orders.price');
		$this->select('orders.description');
		$this->select('orders.delegate_date');
		$this->select('orders.finish_date');
		$this->select('orders.phone');
		$this->select('orders.state');

		$this->select('users.id as user_id');
		$this->select('users.name as user_name');
		$this->select('users.middle_name as user_middle_name');
		$this->select('users.last_name as user_last_name');

		$this->select('regions.name as region_name');
		$this->select('metros.name as metro_name');

		$this->join('orders_users','orders_users.order_id = orders.id','LEFT')
			 ->join('users','orders_users.user_id = users.id','LEFT')
			 ->join('organizations','orders.org_id = organizations.id')
			 ->join('regions','regions.id = orders.region_id')
			 ->join('metros','metros.id = orders.metro_id');
	}


	/**
	*
	*
	*	Метод, позволяющий выбрать все заявки организации
	*	@author - Alex.strigin
	*	@param  int   - id организации
	*	@param  array - filter 
	* 	@param  int   - limit
	*	@param  int   - offset 
	*	@return array 
	*
	*
	*/
	public function get_all_orders_org($id,$filter = array(),$limit = false,$offset = false){

		/*
		*	
		* Выбор данных
		*/
		$this->build_select();

		/*
		*
		* Применение фильтров
		*/
		$this->set_filter($filter);


		/*
		*
		*	Установка ограничений
		*/
		$this->limit($limit,$offset);

		/*
		*
		*	Порядок сортировки
		*
		*/
		$this->order_by('orders.id','DESC');
		return $this->get_all(array('organizations.id' => $id));

	}



	/**
	 * Метод, позволяющий выбрать заявки всех пользователей или одного
	 *
	 * @param - int ids список идентификаторов пользователей
	 * @param - array
	 * @param - int
	 * @param - int
	 * @return array
	 * @author Alex.strigin
	 **/
	public function get_all_orders_users($user_ids,$filter=array(),$limit=false,$offset = false)
	{
		/*
		*
		*	Выбор данных
		*/
		$this->build_select();

		/*
		*	Применение фильтров
		*/
		$this->set_filter($filter);

		/*
		*	
		* Установка ограничений
		*/
		$this->limit($limit,$offset);

		/*
		*
		* Установка порядка сортировки
		*/
		$this->order_by('orders.id','DESC');
		$this->where_in('users.id',$user_ids);

		return $this->get_all();
	}


	/**
	 * Надстройка над get_all_orders_users
	 *
	 * @param int
	 * @param array
	 * @return array
	 * @author Alex.strigin
	 **/
	public function get_all_orders_user ($user_id,$filter = array(),$limit = false,$offset = false)
	{
		return $this->get_all_orders_users(array($user_id),$filter,$limit,$offset);
	}


}