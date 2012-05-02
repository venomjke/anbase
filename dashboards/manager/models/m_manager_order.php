<?php defined("BASEPATH") or die("No direct access to script");



if(!class_exists("M_Order")){
	require APPPATH."models/m_order.php";
}


/**
 * Модель заявок менеджера, и управляемая из под менеджера
 *
 * @package anbase
 * @author 
 **/
class M_Manager_order extends M_Order
{

	public $edit_validation_rules = array(
		array('field'=>'id', 'label'=>'ORDER ID','rules'=>'required|valid_agent_order_id'),
		array('field'=>'number', 'label'=>'lang:order.label_number','rules'=>'is_natural|less_than[4294967295]'),
		array('field'=>'create_date', 'label'=>'lang:order.label_create_date', 'rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'category', 'label'=>'lang:order.label_category','rules'=>'valid_order_category'),
		array('field'=>'deal_type', 'label'=>'lang:order.label_deal_type', 'rules'=>'valid_order_deal_type'),
		array('field'=>'price', 'label'=>'lang:order.label_price','rules'=>'numeric|greater_than[-1]|max_length[15]'),
		array('field'=>'description', 'label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules' =>'trim|valid_phone'),
		array('field'=>'any_metro','label'=>'lang:order.label_any_metro','rules'=>'callback_valid_any_metro'),
		array('field'=>'any_region','label'=>'lang:order.label_any_region','rules'=>'callback_valid_any_region')
	);
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Выбор всех заявок агентов, которых курирует данный менеджер
	 *
	 * @param int manager_id
	 * @param int org_id
	 * @param array filter
	 * @param int limit
	 * @param int offset
	 * @return array
	 * @author Alex.strigin
	 **/
	public function get_all_delegate_orders($user_id,$filter=array(),$limit=false,$offset=false,$fields=array())
	{
		/*
		*
		* метод build_select для  формирования селекта на выбор всех заявок
		* + join на выбор заявок из managers_users
		*/
		$this->build_select($fields);
		$this->join("managers_users","managers_users.user_id = orders_users.user_id");
		/*
		*
		* Установка фильтров 
		*
		*/
		$this->set_filter($filter);

		/*
		*
		*	Установка ограничений
		*
		*/
		$this->limit($limit,$offset);

		/*
		*	Выборка.
		*	Указываем только manager_id, т.к org_id подразумевается верным потому, что manager_id не может быть связан с теми user_id, которые не принадлежат к текущей организации.
		*/
		return $this->get_all(array("managers_users.manager_id" => $user_id));
	}

	public function count_all_delegate_orders($user_id,$filter=array())
	{
		$this->build_count_select($filter);
		$this->join("managers_users","managers_users.user_id = orders_users.user_id");
		$this->db->where("managers_users.manager_id",$user_id);
		return $this->get_count_result();	
	}

	public function valid_any_metro($any_metro)
	{
		return $this->check_any_metro($any_metro);
	}

	public function valid_any_region($any_region){
		return $this->check_any_region($any_region);
	}

	public function get_edit_validation_fields()
	{
		$edit_validation_fields = array();
		foreach($this->edit_validation_rules as $rule)$edit_validation_fields[] = $rule['field'];
		return $edit_validation_fields;
	}

} // END class M_Manager_order extends M_Order