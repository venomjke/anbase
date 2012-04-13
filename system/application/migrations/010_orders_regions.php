<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Дропаем поле id_region в orders, удаляем индекс idx_region_id из orders.
 * создаем таблицу orders_regions.
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Orders_regions extends CI_Migration
{
	/**
	 * Собственно изменения
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function up()
	{
		/*
		* Дропаем foreign key, дропаем поле index и id_region
		*/
		$this->db->query('ALTER TABLE `orders` DROP FOREIGN KEY `orders_ibfk_3`');
		$this->db->query('DROP INDEX `idx_region_id` ON orders');
		$this->dbforge->drop_column('orders','region_id');

		/*
		* Создаем таблицу orders_regions
		* В этой таблице нам не нужен id в качестве первичного ключа.
		* достаточно связки order_id, region_id
		*/
		$this->dbforge->add_field(array(
			'order_id' => array(
				'type' => 'INT',
				'unsigned' => true
			),
			'region_id' => array(
				'type' => 'INT',
				'unsigned' => true
			)
		));

		$this->dbforge->add_key('order_id',true);
		$this->dbforge->add_key('region_id',true);

		/*
		* создаем таблицу
		*/
		if(!$this->dbforge->create_table('orders_regions',true)){
			exit('Something goes wrong in Migration_orders_regions after dbforge->create_table');
		}

		/*
		* Задаем внешние ключи
		*/
		$this->db->query('ALTER TABLE `orders_regions` ADD FOREIGN KEY(`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `orders_regions` ADD FOREIGN KEY (`region_id`) REFERENCES `regions`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}

	/**
	 * откатываем изменения
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{
		/*
		* Дропаем таблицу order_regions
		*/
		$this->dbforge->drop_table('orders_regions');

		/*
		* возвращаем id_region
		*/
		$this->dbforge->add_column('orders',array(
			'region_id' => array(
				'type' => 'int',
				'unsigned' => true
			)
		));

		$this->db->query('CREATE INDEX idx_region_id ON `orders`(region_id)');
		$this->db->query('ALTER TABLE `orders` ADD FOREIGN KEY (`region_id`) REFERENCES `regions`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}
} // END class Migration_Orders_regions extends CI_Migration