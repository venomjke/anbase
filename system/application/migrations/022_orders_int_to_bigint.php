<?php defined("BASEPATH") or die("No direct access to script");


/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Orders_int_to_bigint extends CI_Migration
{

	public function up()
	{
		/*
		* Сносим все внешние ключи на orders
		*/
		
		$this->db->query('ALTER TABLE orders_users DROP FOREIGN KEY `orders_users_ibfk_2`');

		/*
		* Т.к при удалении и новом добавлении внешнего ключа изменится идентификация, то будем удалять все поля
		*/
		$this->db->query('ALTER TABLE orders_regions DROP FOREIGN KEY `orders_regions_ibfk_1`');
		$this->db->query('ALTER TABLE orders_regions DROP FOREIGN KEY `orders_regions_ibfk_2`');

		$this->db->query('ALTER TABLE orders_metros DROP FOREIGN KEY `orders_metros_ibfk_1`');
		$this->db->query('ALTER TABLE orders_metros DROP FOREIGN KEY `orders_metros_ibfk_2`');
		
		/*
		* Меняем тип с Int на Bigint
		*/
		$this->dbforge->modify_column('orders',array(
			'id' => array(
				'name' => 'id',
				'type' => 'BIGINT',
				'constraint' => '20',
				'auto_increment' => true,
				'unsigned' => true
			)
		));

		$this->dbforge->modify_column('orders_users',array(
			'order_id' => array(
				'name' => 'order_id',
				'type' => 'BIGINT',
				'constraint' => '20',
				'unsigned'=>true
			)
		));

		$this->dbforge->modify_column('orders_regions',array(
			'order_id' => array(
				'name' => 'order_id',
				'type' => 'BIGINT',
				'constraint' => '20',
				'unsigned' => true
			)
		));

		$this->dbforge->modify_column('orders_metros',array(
			'order_id' => array(
				'name' => 'order_id',
				'type' => 'BIGINT',
				'constraint' => '20',
				'unsigned' => true
			)
		));

		$this->db->query('ALTER TABLE `orders_users` ADD FOREIGN KEY (`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');

		$this->db->query('ALTER TABLE `orders_regions` ADD FOREIGN KEY (`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `orders_regions` ADD FOREIGN KEY (`region_id`) REFERENCES `regions`(id) ON DELETE CASCADE ON UPDATE CASCADE');

		$this->db->query('ALTER TABLE `orders_metros` ADD FOREIGN KEY (`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `orders_metros` ADD FOREIGN KEY (`metro_id`) REFERENCES `metros`(id) ON DELETE CASCADE ON UPDATE CASCADE');

	}

	public function down()
	{
	}
} // END class Migration_Settings_org extends CI_Migration