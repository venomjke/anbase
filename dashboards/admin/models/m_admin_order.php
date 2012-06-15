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
		array('field'=>'price', 'label'=>'lang:order.label_price','rules'=>'trim|numeric|greater_than[-1]|max_length[15]'),
		array('field'=>'description', 'label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules' =>'trim|valid_phone'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'any_metro','label'=>'lang:order.label_any_metro','rules'=>'callback_valid_any_metro'),
		array('field'=>'any_region','label'=>'lang:order.label_any_region','rules'=>'callback_valid_any_region'),
		array('field'=>'finish_status','label'=>'lang:order.label_finish_status', 'rules' => 'valid_order_finish_status')
	);

	public $add_order_validation_rules = array(
		array('field'=>'number','label'=>'lang:order.label_number', 'rules'=>'is_natural|max_length[9]'),
		array('field'=>'create_date','label'=>'lang:order.label_create_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'category','label'=>'lang:order.label_category','rules'=>'valid_order_category'),
		array('field'=>'deal_type','label'=>'lang:order.label_deal_type','rules'=>'valid_order_deal_type'),
		array('field'=>'price','label'=>'lang:order.label_price','rules'=>'trim|numeric|greater_than[-1]|max_length[12]'),
		array('field'=>'description','label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'delegate_date','label'=>'lang:order.label_delegate_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'finish_date','label'=>'lang:order.label_finish_date','rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules'=>'trim|valid_phone'),
		array('field'=>'state','label'=>'lang:order.label_state','rules'=>'callback_valid_state')
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
		foreach($this->edit_validation_rules as $rule) array_push($edit_validation_fields,$rule['field']);
		return $edit_validation_fields;
	}


} // END class M_Admin_order extends M_Order