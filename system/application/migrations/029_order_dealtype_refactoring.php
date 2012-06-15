<?php defined("BASEPATH") or die("No direct access to script");

/**
 * @package default
 * @author alex.strigin
 **/
class Migration_Order_dealtype_refactoring extends CI_Migration{

	public function up()
	{
		/*
		* Заменяем категории в таблице orders
		*/
		$this->dbforge->add_column('orders',array(
			'deal_type_new' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));

		/*
		* Заменяем "Сдам" на 0x01, "Сниму" на 0x02, "Куплю" на 0x03, "Продам" на 0x04
		*/
		$this->db->where('deal_type','Сдам');
		$this->db->update('orders',array(
			'deal_type_new'=>0x01
		));

		$this->db->where('deal_type','Сниму');
		$this->db->update('orders',array(
			'deal_type_new'=>0x02
		));

		$this->db->where('deal_type','Куплю');
		$this->db->update('orders',array(
			'deal_type_new'=>0x03
		));

		$this->db->where('deal_type','Продам');
		$this->db->update('orders',array(
			'deal_type_new'=>0x04
		));

		$this->dbforge->drop_column('orders','deal_type');
		$this->dbforge->modify_column('orders',array(
			'deal_type_new' => array(
				'name' => 'deal_type',
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x02
			)
		));

		/*
		* Меняем тип сделки в таблице settings_org
		*/
		$this->dbforge->add_column('settings_org',array(
			'dealtype_new' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));

		$this->db->where('default_dealtype','Сдам');
		$this->db->update('settings_org',array(
			'dealtype_new' => 0x01
		));

		$this->db->where('default_dealtype','Сниму');
		$this->db->update('settings_org',array(
			'dealtype_new' => 0x02
		));

		$this->db->where('default_dealtype','Куплю');
		$this->db->update('settings_org',array(
			'dealtype_new' => 0x03
		));

		$this->db->where('default_dealtype','Продам');
		$this->db->update('settings_org',array(
			'dealtype_new' => 0x04
		));

		$this->dbforge->drop_column('settings_org','default_dealtype');
		$this->dbforge->modify_column('settings_org',array(
			'dealtype_new' => array(
				'name' => 'default_dealtype',
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x02
			)
		));

	}

	public function down()
	{

	}
}