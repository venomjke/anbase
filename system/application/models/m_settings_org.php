<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель таблицы настроек организации
 *
 * @package default
 * @author alex.strigin
 **/
class M_Settings_org extends MY_Model
{

	public $edit_validation_rules = array(
		array('field' => 'default_category', 'label' => 'sett_default_category', 'rules'=>'valid_order_category'),
		array('field' => 'default_dealtype', 'label' => 'sett_default_category', 'rules'=>'valida_order_deal_type'),
		array('field' => 'price_col', 'label' => 'sett_price_col', 'rules' => 'is_natural|less_than[2]'),
		array('field' => 'regions_col', 'label' => 'sett_regions_col', 'rules' => 'is_natural|less_than[2]'),
		array('field' => 'metros_col', 'label' => 'sett_metros_col', 'rules' => 'is_natural|less_than[2]'),
		array('field' => 'phone_col', 'label' => 'sett_phone_col', 'rules' => 'is_natural|less_than[2]')
	);

	public function __construct()
	{
		parent::__construct();

		$this->table = 'settings_org';
		$this->primary_key = 'id';
		$this->fields  = array('id','default_category','default_dealtype','price_col','phone_col','regions_col','metros_col','org_id');
		$this->result_mode = 'object';
	}

	public function edit($org_id,$data){
		$this->db->where('org_id',$org_id);
		$this->db->update($this->table,$data);
	}

	
} // END class M_Settings_org extends MY_Model