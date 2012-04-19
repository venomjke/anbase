<?php defined("BASEPATH") or die("No direct access to script");


/*
*	
*	Загрузка на всякий случай M_Order в тупую
*/
if(!class_exists("M_Order")){
	require APPPATH."models/m_order.php";
}

/**
 * Расширение модели M_Order специально для панели администратоор
 *
 * @package anbase
 * @author 
 **/
class M_Admin_order extends M_Order
{

	public $edit_validation_rules = array(
		array('field'=>'id', 'label'=>'ORDER ID','rules'=>'required|valid_order_id'),
		array('field'=>'number', 'label'=>'lang:order.label_number','rules'=>'is_natural|less_than[4294967295]'),
		array('field'=>'create_date', 'label'=>'lang:order.label_create_date', 'rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'category', 'label'=>'lang:order.label_category','rules'=>'valid_order_category'),
		array('field'=>'deal_type', 'label'=>'lang:order.label_deal_type', 'rules'=>'valid_order_deal_type'),
		array('field'=>'price', 'label'=>'lang:order.label_price','rules'=>'numeric|greater_than[-1]|max_length[15]'),
		array('field'=>'description', 'label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules' =>'trim|valid_phone'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]')
	);

	/**
	 * конструктор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Метод обращается к get_all_orders_org, предварительно установив фильтр NOT NULL
	 * на поле user_id
	 *
	 * @param int
	 * @param array
	 * @param int
	 * @param int
	 * @return void
	 * @author Alex.strigin
	 **/
	function get_all_delegate_orders($org_id,$filter=array(),$limit=FALSE,$offset=FALSE)
	{
		$this->where('orders_users.user_id IS NOT NULL');
		return $this->get_all_orders_org($org_id,$filter,$limit,$offset);
	}


	public function get_edit_validation_fields()
	{
		$edit_validation_fields = array();
		foreach($this->edit_validation_rules as $rule) array_push($edit_validation_fields,$rule['field']);
		return $edit_validation_fields;
	}


} // END class M_Admin_order extends M_Order