<?php defined("BASEPATH") or die("No direct access to script");


/**
*
*	Модель для манипуляций над заявками
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*
*/
class M_Order extends MY_Model{

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
		return $this->get_all(array('organizations.id' => $id));

	}



	/**
	 * Метод, позволяющий выбрать заявки все пользователей или одного
	 *
	 * @param - int ids список идентификаторов пользователей
	 * @param - array
	 * @param - int
	 * @param - int
	 * @return array
	 * @author Alex.strigin
	 **/
	public function get_all_orders_users($user_ids,$filter=array(),$limit=false,$limit = false)
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