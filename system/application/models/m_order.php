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
	*  Категории
	*/
	const ORDER_CATEGORY_COUNTRY_REAL_ESTATE    = 0x03;
	const ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE = 0x02;
	const ORDER_CATEGORY_RESIDENTAL_REAL_ESTATE	= 0x01;

	/*
	* Типы сделки
	*/	
	const ORDER_DEAL_TYPE_RENT    = 0x01;
	const ORDER_DEAL_TYPE_GET     = 0x02;
	const ORDER_DEAL_TYPE_BUY     = 0x03;
	const ORDER_DEAL_TYPE_SELL    = 0x04;

	/*
	* Состояния
	*/
	const ORDER_STATE_ON	= 'on';
	const ORDER_STATE_OFF	= 'off';

	/*
	* Состояния завершения заявки
	*/
	const ORDER_FINISH_STATUS_SUCCESS = 0x01;
	const ORDER_FINISH_STATUS_FAILURE = 0x02;

	/*
	* Правила валидации "создание" записи
	* чисто формальные, фактически для каждой панели должны будут быть свои правила
	*/
	public $insert_validation_rules = array(
		array('field'=>'number','label'=>'lang:order.label_number', 'rules'=>'is_natural|max_length[9]'),
		array('field'=>'create_date','label'=>'lang:order.label_create_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'category','label'=>'lang:order.label_category','rules'=>'callback_valid_category'),
		array('field'=>'deal_type','label'=>'lang:order.label_deal_type','rules'=>'callback_valid_dealtype'),
		array('field'=>'price','label'=>'lang:order.label_price','rules'=>'greater_than[-1]|max_length[12]'),
		array('field'=>'description','label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'finish_date','label'=>'lang:order.label_finish_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules'=>'trim|valid_phone'),
		array('field'=>'state','label'=>'lang:order.label_state','rules'=>'callback_valid_state'),
		array('field' => 'source', 'label' => 'lang:order.label_source', 'rules' => 'trim|max_length[512]|xss_clean|html_escape')
	);

	/*
	*
	* Правила валидации "изменение записи
	* чисто формальные, фактически для каждой панели должны будут быть свои правила
	*/
	public $update_validation_rules = array(
		array('field'=>'id','label'=>'ORDER_ID','rules'=>'required|is_natural_no_zero|valid_order_id'),
		array('field'=>'number','label'=>'lang:order.label_number', 'rules'=>'is_natural|max_length[9]'),
		array('field'=>'create_date','label'=>'lang:order.label_create_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'category','label'=>'lang:order.label_category','rules'=>'callback_valid_category'),
		array('field'=>'deal_type','label'=>'lang:order.label_deal_type','rules'=>'callback_valid_dealtype'),
		array('field'=>'price','label'=>'lang:order.label_price','rules'=>'greater_than[-1]|max_length[12]'),
		array('field'=>'description','label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'finish_date','label'=>'lang:order.label_finish_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules'=>'trim|valid_phone'),
		array('field'=>'state','label'=>'lang:order.label_state','rules'=>'callback_valid_state'),
		array('field' => 'source', 'label' => 'lang:order.label_source', 'rules' => 'trim|max_length[512]|xss_clean|html_escape')
	);
	
	/*
	* Правила валидации фильтра
	*/
	public $filter_validation_rules = array(
		array('field'=>'number_from','label'=>'lang:order.label_number','rules'=>'is_natural|max_length[9]'),
		array('field'=>'number_to','label'=>'lang:order.label_number','rules'=>'is_natural|max_length[9]'),
		array('field'=>'number','label'=>'lang:order.label_number','rules'=>'is_natural|max_length[9]'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules'=>'trim|valid_phone'),
		array('field'=>'category','label'=>'lang:order.label_category','rules'=>'valid_order_category'),
		array('field'=>'dealtype','label'=>'lang:order.label_deal_type','rules'=>'valid_order_deal_type'),
		array('field'=>'createdate_from','label'=>'lang:order.label_create_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'createdate_to','label'=>'lang:order.label_create_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'price_from','label'=>'lang:order.label_price','rules'=>'greater_than[-1]|max_length[12]'),
		array('field'=>'price_to','label'=>'lang:order.label_price','rules'=>'greater_than[-1]|max_length[12]'),
		array('field'=>'price_order','label'=>'price order','rules'=>'numeric|max_length[2]'),
		array('field'=>'description','label'=>'lang:order.label_description','rules'=>'trim|xss_clean'),
		array('field'=>'description_type','label'=>'description_type','rules'=>'alpha'),
		array('field'=>'user_id','label'=>'user id','rules'=>'is_valid_user_id')
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
		*	Определение структура модели
		*/
		$this->table       = 'orders';
		$this->primary_key = 'id';
		$this->fields      = array('id','number','create_date','category','deal_type','price','description','delegate_date','finish_date','phone','state','org_id','any_metro','any_region','created','delegated','finished','finish_status', 'source');
		$this->result_mode = 'object';
		/*
		*	Правила валидации
		*/
		$this->validate = array();

		/*
		* Хуки
		*/
		$this->before_create = array('fill_empty_fields');
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
		return array('ORDER_CATEGORY_RESIDENTAL_REAL_ESTATE'=>M_Order::ORDER_CATEGORY_RESIDENTAL_REAL_ESTATE,
			         'ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE'=>M_Order::ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE,
			         'ORDER_CATEGORY_COUNTRY_REAL_ESTATE'=>M_Order::ORDER_CATEGORY_COUNTRY_REAL_ESTATE);
	}

	/**
	* Выбор обозначения категории по её значению
	* @return string
	* @author alex.strigin
	*/
	public function get_category_name($category)
	{
		if($category == M_Order::ORDER_CATEGORY_RESIDENTAL_REAL_ESTATE) return lang('order.order_category_residental_real_estate');
		else if ($category == M_Order::ORDER_CATEGORY_COUNTRY_REAL_ESTATE) return lang('order.order_category_country_real_estate');
		else if ($category == M_Order::ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE) return lang('order.order_category_commercial_real_estate');

		return $lang['order.order_category_undefined'];
	}
	/**
	 * Получить список типов сделок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_dealtype_list()
	{
		return array( 'ORDER_DEAL_TYPE_GET'  => M_Order::ORDER_DEAL_TYPE_GET,
			          'ORDER_DEAL_TYPE_RENT' => M_Order::ORDER_DEAL_TYPE_RENT,
			          'ORDER_DEAL_TYPE_SELL' => M_Order::ORDER_DEAL_TYPE_SELL,
			          'ORDER_DEAL_TYPE_BUY'  => M_Order::ORDER_DEAL_TYPE_BUY);
	}

	/**
	 * Выбор обозначения "Типа сделки" по его значению
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	public function get_dealtype_name($dealtype)
	{
		if($dealtype == M_Order::ORDER_DEAL_TYPE_RENT) return lang('order.order_deal_type_rent');
		else if($dealtype == M_Order::ORDER_DEAL_TYPE_BUY) return lang('order.order_deal_type_buy');
		else if($dealtype == M_Order::ORDER_DEAL_TYPE_SELL) return lang('order.order_deal_type_sell');
		else if($dealtype == M_Order::ORDER_DEAL_TYPE_GET) return lang('order.order_deal_type_get');

		return $lang['order.undefined_deal_type'];
	}

	/**
	 * Получить список статусов завершения заявки
	 *
	 * @return number
	 * @author alex.strigin
	 **/
	public function get_finishstatus_list()
	{
		return array( 'ORDER_FINISH_STATUS_SUCCESS' => M_Order::ORDER_FINISH_STATUS_SUCCESS,
			          'ORDER_FINISH_STATUS_FAILURE' => M_Order::ORDER_FINISH_STATUS_FAILURE
		);
	}

	public function get_max_number($org_id)
	{
		$this->db->select("MAX(number) as max_number");
		$this->db->where('org_id',$org_id);
		$this->db->limit(1);
		$result = $this->db->get($this->table);
		if($result->num_rows() == 1)
			return $result->row()->max_number;
		return 0;

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
			$data['create_date'] = date('Y/m/d');
			$data['created']	 = date('Y-m-d H:i:s');
		}
		return $data;
	}

	public function check_state($state)	
	{
		if(in_array($state,array(M_Order::ORDER_STATE_ON,M_Order::ORDER_STATE_OFF))){
			return true;
		}
		return false;
			
	}

	public function check_category($category)
	{
		if(in_array($category,$this->get_category_list())){
			return true;
		}
		return false;
	}

	public function check_deal_type($deal_type)
	{
		if(in_array($deal_type,$this->get_dealtype_list())){
			return true;
		}
		return false;
	}

	public function check_finish_status($finish_status)
	{
		if(in_array($finish_status,$this->get_finishstatus_list())){
			return true;
		}
		return false;
	}

	public function check_any_metro($any_metro)
	{
		if(!empty($any_metro) && $any_metro == 1){
			return true;
		}else{
			return 0;
		}
	}

	public function check_any_region($any_region)
	{
		if(!empty($any_region) && $any_region == 1){
			return true;
		}else{
			return 0;
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
			if($this->check_state($state)) {
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
			if($this->check_deal_type($deal_type)){
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
			if($this->check_category($category)){
				return true;
			}else{
				$this->form_validation->set_message('valid_category',lang('order.validation.valid_category'));
				return false;
			}
		}
	}

	/**
	 * Фильтр для поля номер
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	protected function set_number_filter($value)
	{
		$number = $value['number'];
		$number_to = $value['number_to'];
		$number_from = $value['number_from'];
		$number_order = $value['number_order'];

		if(!empty($number) && is_numeric($number) && $number >= 0)
		{
			$this->db->where('orders.number',$number);
			return;
		}

		if(!empty($number_to) && is_numeric($number_to) && $number_to >= 0){
			$this->db->where('orders.number <=',$number_to);
		}

		if(!empty($number_from) && is_numeric($number_from) && $number_from >= 0){
			$this->db->where('orders.number >=',$number_from);
		}

		if(!empty($number_order) && is_numeric($number_order) ){
			if($number_order >= 1) $this->db->order_by('orders.number','ASC');
			else $this->db->order_by('orders.number','DESC');
		}
	}

	/**
	 * Фильтр для поля телефон
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	protected function set_phone_filter($value)
	{
		if(!empty($value) && is_numeric($value)){
			$this->db->like('orders.phone',(float)$value);			
		}
	}
	/**
	*	Фильтр для поля Price
	*/
	protected function set_price_filter($value = array()){

		$price_from = $value['price_from'];
		$price_to   = $value['price_to'];
		$price_order = $value['price_order'];

		if(is_numeric($price_from) && $price_from >= 0 ){
			$this->db->where('orders.price >=',$price_from);
		}

		if(is_numeric($price_to) && $price_to >= 0){
			$this->db->where('orders.price <=',$price_to);
		}

		if(!empty($price_order) && is_numeric($price_order)){
			if($price_order >= 1) $this->db->order_by('orders.price','ASC');
			else $this->db->order_by('orders.price','DESC');
		}
	}


	/**
	*	фильтр для поля description
	*/
	protected function set_description_filter($value = ''){
		$words = $value['words'];
		$type  = $value['type']?$value['type']:'full';

		if(!empty($words)){
			$search_words = preg_split('/[\s,;]/',$words);
			switch ($type) {
				default:
				case 'full':
					if(is_array($search_words)){
						foreach($search_words as $search_word){
							$this->db->like('orders.description',$search_word);
						}
					}
					break;
				case 'each':
				if(is_array($search_words)){
					$where_string = '(';
					foreach($search_words as $search_word){
						$where_string .= "orders.description LIKE '%".$search_word."%'";
						$where_string .=" OR ";
					};
					$where_string = rtrim($where_string,"OR ");
					$where_string .=')';
					$this->db->where($where_string);
				}
					break;
			}
		}
	}


	/**
	 * Фильтр для поля create_date
	 *
	 * @return void
	 * @author 
	 **/
	protected function set_createdate_filter($value = array())
	{
		$createdate_from = $value['createdate_from'];
		$createdate_to   = $value['createdate_to'];
		$createdate_order = $value['createdate_order'];

		if(!empty($createdate_from)){
			$this->db->where('orders.create_date >=',$createdate_from);
		}
		if(!empty($createdate_to)){
			$this->db->where('orders.create_date <=',$createdate_to);
		}
		if(!empty($createdate_order) && is_numeric($createdate_order)){
			if($createdate_order >= 1)$this->db->order_by('orders.create_date','ASC');
			else $this->db->order_by('orders.create_date','DESC');
		}
	}

	/**
	 * Фильтр для поля категория
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	protected function set_category_filter($value)
	{
		if(!empty($value))
			$this->db->where('orders.category =',$value);
	}

	/**
	 * Фильтр для поля тип сделки
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	protected function set_dealtype_filter($value)
	{
		if(!empty($value))
			$this->db->where('orders.deal_type =',$value);
	}

	protected function set_metros_filter($value)
	{
		if(!empty($value) && is_array($value)){
			$metro_ids = array();
			foreach($value as $metro_line){
				foreach($metro_line as $metro_id){
						$metro_ids[]= $metro_id;							
					}
			}
			$this->db->join("orders_metros","orders.id = orders_metros.order_id","LEFT");
			$this->db->where("(`orders_metros`.`metro_id` IN (".implode(',',$metro_ids).") OR `orders`.`any_metro`=1)");
		}
	}

	protected function set_regions_filter($value)
	{
		if(!empty($value) && is_array($value)){
			$region_ids = array();
			foreach($value as $region_id){
				$region_ids[]=$region_id;
			}
			$this->db->join("orders_regions","orders.id = orders_regions.order_id","LEFT");
			$this->db->where("(`orders_regions`.`region_id` IN (".implode(',',$region_ids).") OR `orders`.`any_region`=1)");
		}
	}

	public function set_user_id_filter($value)
	{
		if(!empty($value) && is_numeric($value)){
			$this->db->where("users.id",$value);
		}
	}

	public function set_agent_order_filter($value)
	{
		if(!empty($value) && is_numeric($value)){
			if($value >= 1)	$this->db->order_by('users.id','ASC');
			else $this->db->order_by('users.id','DESC');
		} 
	}

	public function set_finishStatusOrder_filter($value)
	{
		if(!empty($value) && is_numeric($value)){
			$value >= 1? $this->db->order_by('orders.finish_status','ASC'):$this->db->order_by('orders.finish_status','DESC');
		}
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
				throw new Exception("Undifned filter_name {$filter_name} in ".__CLASS__);
			}
		}
	}
	/**
	* Метод определяет правила присоединения и список выбора
	* @return void
	* @author - Alex.strigin
	*
	*/
	protected function build_select($fields = array()){
		/*
		* Можно указывать какие поля нужно выбрать. По умолчанию выбираются все.
		*/
		$allow_fields = array('number','create_date','category','deal_type','price','description','delegate_date','finish_date','phone','state','any_metro','any_region','finish_status', 'source');
		$fields = array_flip(array_intersect_key(array_flip($fields),array_flip($allow_fields)));

		if(empty($fields)) $fields = $allow_fields;

		$this->select('orders.id');
		foreach($fields as $field){
			if($field == 'create_date' or $field == 'delegate_date' or $field == 'finish_date'){
				$this->select("DATE_FORMAT(orders.$field,'%d.%m.%y') as $field",FALSE);	
			}else{
				$this->select("orders.$field");
			}
		}



		$this->select('users.id as user_id');
		$this->select('users.name as user_name');
		$this->select('users.middle_name as user_middle_name');
		$this->select('users.last_name as user_last_name');

		$this->join('orders_users','orders_users.order_id = orders.id','LEFT')
			 ->join('users','orders_users.user_id = users.id','LEFT')
			 ->join('organizations','orders.org_id = organizations.id');
	}


	protected function build_count_select($filter)
	{
		/*
		* делаем подсчет своими руками
		*/
		$this->select('COUNT(DISTINCT orders.id) as _cnt');
		$this->build_select();
		$this->set_filter($filter);
		$this->limit(1);
	}

	protected function get_count_result()
	{
		$result = $this->db->get($this->table);
		if($result->num_rows() == 1){
			$cnt_res = $result->row();
			return $cnt_res->_cnt;
		}
		return 0;
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
	public function get_all_orders_org($id,$filter = array(),$limit = false,$offset = false,$fields=array()){

		/*
		* Выбор данных
		*/
		$this->build_select($fields);

		/*
		* Применение фильтров
		*/
		$this->set_filter($filter);


		/*
		*	Установка ограничений
		*/
		$this->limit($limit,$offset);

		/*
		*	Порядок сортировки
		*/
		$this->order_by('orders.id','DESC');
		$this->group_by('orders.id');	
		return $this->get_all(array('organizations.id' => $id));

	}

	public function count_all_orders_org($org_id,$filter=array())
	{
		$this->build_count_select($filter);
		$this->db->where('organizations.id',$org_id);
		return $this->get_count_result();	
	}

	public function get_all_on_orders_org($org_id,$filter=array(),$limit=false,$offset=false,$fields=array()){
		$this->where(array('state'=>M_Order::ORDER_STATE_ON));
		return $this->get_all_orders_org($org_id,$filter,$limit,$offset,$fields);
	}

	public function count_all_on_orders_org($org_id,$filter=array())
	{
		$this->where(array('state'=>M_Order::ORDER_STATE_ON));
		return $this->count_all_orders_org($org_id,$filter);	
	}

	public function get_all_off_orders_org($org_id,$filter=array(),$limit=false,$offset=false,$fields=array())
	{
		$this->where('orders.state',M_Order::ORDER_STATE_OFF);
		return $this->get_all_orders_org($org_id,$filter,$limit,$offset,$fields);
	}

	public function count_all_off_orders_org($org_id,$filter=array())
	{
		
		$this->build_count_select($filter);
		$this->where('orders.state',M_Order::ORDER_STATE_OFF);
		$this->db->where('organizations.id',$org_id);
		return $this->get_count_result();	
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
	public function get_all_orders_users($user_ids,$filter=array(),$limit=false,$offset = false,$fields=array())
	{
		/*
		*	Выбор данных
		*/
		$this->build_select($fields);

		/*
		*	Применение фильтров
		*/
		$this->set_filter($filter);

		/*
		* Установка ограничений
		*/
		$this->limit($limit,$offset);

		/*
		* Установка порядка сортировки
		*/
		$this->where_in('users.id',$user_ids);
		$this->order_by('orders.id');
		$this->group_by('orders.id');
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
	public function get_all_orders_user ($user_id,$filter = array(),$limit = false,$offset = false,$fields = array())
	{
		$this->where('orders.state',M_Order::ORDER_STATE_ON);
		return $this->get_all_orders_users(array($user_id),$filter,$limit,$offset,$fields);
	}

	public function count_all_user_orders($user_id,$filter=array())	
	{
		$this->build_count_select($filter);
		$this->where_in('users.id',array($user_id));
		$this->where('orders.state',M_Order::ORDER_STATE_ON);
		return $this->get_count_result();	
	}

	public function get_all_off_orders($user_id,$filter=array(),$limit=false,$offset=false,$fields=array())
	{
		$this->where('orders.state',M_Order::ORDER_STATE_OFF);
		return $this->get_all_orders_users(array($user_id),$filter,$limit,$offset,$fields);
	}

	public function count_all_off_orders($user_id,$filter=array())
	{
		
		$this->build_count_select($filter);
		$this->where_in('users.id',array($user_id));
		$this->where('orders.state',M_Order::ORDER_STATE_OFF);
		return $this->get_count_result();	
	}

	/**
	 * Выбор всех свободных заявок организации
	 *
	 * @param int
	 * @param array
	 * @param bool
	 * @param bool
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders($org_id,$filter = array(),$limit = false,$offset = false,$fields=array())
	{
		/*
		* Т.к при присоединении oders_users к orders используется LEFT JOIN, то свободные заявки мы
		* выбираем просто проверяя user_id на NULL
		*/
		$this->db->where("orders_users.user_id IS NULL");
		return $this->get_all_on_orders_org($org_id,$filter,$limit,$offset,$fields);
	}

	/**
	 * Подсчет всех пустых заявок
	 *
	 * @param  int
	 * @return array
	 * @author alex.strigin
	 **/
	public function count_all_free_orders($org_id,$filter=array())
	{
		$this->db->where("orders_users.user_id IS NULL");
		return $this->count_all_on_orders_org($org_id,$filter);
	}

	/**
	 * Выбор всех занятых заявок
	 *
	 * @param int
	 * @param array
	 * @param bool
	 * @param bool
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders($org_id,$filter=array(),$limit=false,$offset=false,$fields=array())
	{
		$this->db->where("orders_users.user_id IS NOT NULL");
		return $this->get_all_on_orders_org($org_id,$filter,$limit,$offset,$fields);
	}

	public function count_all_delegate_orders($org_id,$filter=array())	
	{
		$this->db->where('orders_users.user_id IS NOT NULL');
		return $this->count_all_on_orders_org($org_id,$filter);
	}

	/**
	* Функция удаления списка заявок
	* @param ids - список заявок
	*/
	public function delete_orders($ids)
	{
		$this->where_in('id',$ids);
		$this->db->delete($this->table);
	}

	/**
	* Функция завершения заявок
	* @param ids - список заявок
	*/
	public function finish_orders($ids)
	{
		foreach($ids as $id=>$status){
			$this->db->where('id',$id);
			$this->db->update($this->table,array('state'=>M_Order::ORDER_STATE_OFF,'finish_status'=>$status,'finish_date'=>date('Y-m-d'),'finished'=>date('Y-m-d H:i:s')));
		}
	}

	/**
	* Функция восстановления заявок.
	* @param ids - список заявок
	*/
	public function restore_orders($ids){
		$this->where_in('id',$ids);
		$this->db->update($this->table,array('state'=>M_Order::ORDER_STATE_ON,'finish_status'=>0));
	}

	/**
	* Функция для подсчета числа всех заявок организации, сгруппированных по дате
	* @param org_id идентификатор организации
	*/
	public function count_orders_org_group_by_date($org_id)
	{
		$this->db->select("orders.create_date");
		$this->db->select("COUNT(*) as cnt_date");
		$this->db->group_by("orders.create_date");
		$this->db->where('orders.org_id',$org_id);
		return $this->db->get($this->table)->result();
	}
}