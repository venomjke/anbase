<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Дропаем все внешние ключи, индексы, и саму колонку metro_id. 
 * Создаем таблицу orders_metros и соотв. индексы к ней.
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Orders_metros extends CI_Migration
{
	/**
	 * Выполняем подъем!
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function up()
	{

		/*
		* Дропаем foreign key, дропаем поле index и id_region
		*/
		$this->db->query('ALTER TABLE `orders` DROP FOREIGN KEY `orders_ibfk_2`');
		$this->db->query('DROP INDEX `idx_metro_id` ON orders');
		$this->dbforge->drop_column('orders','metro_id');

		/*
		* Создаем таблицу orders_metros
		* В этой таблице нам не нужен id в качестве первичного ключа.
		* достаточно связки order_id, metro_id
		*/
		$this->dbforge->add_field(array(
			'order_id' => array(
				'type' => 'INT',
				'unsigned' => true
			),
			'metro_id' => array(
				'type' => 'INT',
				'unsigned' => true
			)
		));

		$this->dbforge->add_key('order_id',true);
		$this->dbforge->add_key('metro_id',true);

		/*
		* создаем таблицу
		*/
		if(!$this->dbforge->create_table('orders_metros',true)){
			exit('Something goes wrong in Migration_orders_metros after dbforge->create_table');
		}

		/*
		* Задаем внешние ключи
		*/
		$this->db->query('ALTER TABLE `orders_metros` ADD FOREIGN KEY(`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `orders_metros` ADD FOREIGN KEY (`metro_id`) REFERENCES `regions`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}

	/**
	 * Идем вниз =(
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{
		/*
		* Дропаем таблицу order_regions
		*/
		$this->dbforge->drop_table('orders_metros');

		/*
		* возвращаем id_region
		*/
		$this->dbforge->add_column('orders',array(
			'metro_id' => array(
				'type' => 'int',
				'unsigned' => true
			)
		));

		$this->db->query('CREATE INDEX idx_metro_id ON `orders`(metro_id)');
		$this->db->query('ALTER TABLE `orders` ADD FOREIGN KEY (`metro_id`) REFERENCES `metros`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	
	}
} // END class Migration_Orders_metros extends CI_Migration