<?php defined("BASEPATH") or die("No direct access to script");


if(!class_exists("M_Order")){
	require APPPATH."models/m_order.php";
}

/**
 * Класс модели заявок агента
 *
 * @package anbase
 * @author Alex.strigin
 **/
class M_Agent_order extends M_Order
{

	public $edit_validation_rules = array(

		array('field'=>'id', 'label'=>'ORDER ID','rules'=>'required|valid_agent_order_id'),
		array('field'=>'number', 'label'=>'lang:order.label_number','rules'=>'is_natural|less_than[4294967295]'),
		array('field'=>'create_date', 'label'=>'lang:order.label_create_date', 'rules'=>'valid_date[dd/mm/yyyy]|convert_valid_date[dd/mm/yyyy]'),
		array('field'=>'category', 'label'=>'lang:order.label_category','rules'=>'valid_order_category'),
		array('field'=>'deal_type', 'label'=>'lang:order.label_deal_type', 'rules'=>'valid_order_deal_type'),
		array('field'=>'price', 'label'=>'lang:order.label_price','rules'=>'numeric|greater_than[-1]|max_length[15]'),
		array('field'=>'description', 'label'=>'lang:order.label_description','rules'=>'trim|xss_clean|html_escape'),
		array('field'=>'phone','label'=>'lang:order.label_phone','rules' =>'trim|valid_phone')
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function get_edit_validation_fields()
	{
		$edit_validation_fields = array();
		foreach($this->edit_validation_rules as $rule) array_push($edit_validation_fields,$rule['field']);
		return $edit_validation_fields;
	}

} // END class M_Agent_order extends M_Order