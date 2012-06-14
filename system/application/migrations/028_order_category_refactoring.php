<?php defined("BASEPATH") or die("No direct access to script");

/**
 * @package default
 * @author alex.strigin
 **/
class Migration_Order_category_refactoring extends CI_Migration{

	public function up()
	{
		/*
		* Заменяем категории в таблице orders
		*/
		$this->dbforge->add_column('orders',array(
			'category_new' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));

		/*
		* Заменяем "Жилая" на 0x01, "Коммерческая" на 0x02, "Загородная" на 0x03
		*/
		$this->db->where('category','Жилая');
		$this->db->update('orders',array(
			'category_new'=>0x01
		));

		$this->db->where('category','Коммерческая');
		$this->db->update('orders',array(
			'category_new'=>0x02
		));

		$this->db->where('category','Загородная');
		$this->db->update('orders',array(
			'category_new'=>0x03
		));

		$this->dbforge->drop_column('orders','category');
		$this->dbforge->modify_column('orders',array(
			'category_new' => array(
				'name' => 'category',
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));

		/*
		* Меняем категории в таблице settings_org
		*/
		$this->dbforge->add_column('settings_org',array(
			'category_new' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));

		$this->db->where('default_category','Жилая');
		$this->db->update('settings_org',array(
			'category_new' => 0x01
		));

		$this->db->where('default_category','Коммерческая');
		$this->db->update('settings_org',array(
			'category_new' => 0x02
		));

		$this->db->where('default_category','Загородная');
		$this->db->update('settings_org',array(
			'category_new' => 0x03
		));

		$this->dbforge->drop_column('settings_org','default_category');
		$this->dbforge->modify_column('settings_org',array(
			'category_new' => array(
				'name' => 'default_category',
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));

	}

	public function down()
	{

	}
}