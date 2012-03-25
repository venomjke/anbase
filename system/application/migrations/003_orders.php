<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
*
*	@author Alex.strigin
*	@company Flyweb
*
*/
class Migration_Orders extends CI_Migration{


	/*
	*
	*	@author Alex.strigin
	*	
	*		Добавление таблицы orders,metro,region
	*/
	public function up(){

		$this->dbforge->add_field(array(
			'id' => array(
				'type'     => 'int',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type'       => 'VARCHAR',
				'constraint' => '30'
			),
		));
		$this->dbforge->add_key('id',TRUE);
		if(!$this->dbforge->create_table('regions',true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}

		$this->dbforge->add_field(array(
			'id' => array(
				'type'     => 'int',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type'       => 'VARCHAR',
				'constraint' => '30'
			)
		));

		$this->dbforge->add_key('id',TRUE);

		if(!$this->dbforge->create_table('metros',true)){

			exit('Something goes wrong in Migration_orders after dbforge->create_table');
		}

		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'int',
				'unsigned' => true,
				'auto_increment' => true
			),
			'number' => array(
				'type'    => 'int',
				'unsigned'=> true,
				'default' => '0'
			),
			'create_date' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00'
			),
			'category' => array(
				'type' => 'ENUM',
				'constraint' => "'Жилая недвижимость','Коммерческая недвижимость','Загородная недвижимость'",
				'default' => 'Жилая недвижимость'
			),
			'deal_type' => array(
				'type' => 'ENUM',
				'constraint' => "'Куплю','Сниму','Сдам','Продам'",
				'default' => 'Сдам'
			),
			'region_id' => array(
				'type'     => 'int',
				'unsigned' => true
			),
			'metro_id' => array(
				'type' => 'int',
				'unsigned' => true
			),
			'price' => array(
				'type' => 'float',
				'unsigned' => true,
				'default' => '0.0'
			),
			'description' => array(
				'type' => 'MEDIUMTEXT'
			),
			'delegate_date' => array(
  				'type' => 'datetime',
  				'default' => '0000-00-00 00:00:00'
			),
			'finish_date'  => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00'
			),
			'phone' => array(
				'type' => 'varchar',
				'constraint' => '20'
			),
			'state' => array(
				'type' 		 => 'ENUM',
				'constraint' => "'on','off'",
				'default'	 => 'on' 
			),
			'org_id' => array(
				'type'     => 'int',
				'unsigned' => true,
				'default'  => '0'
			)
		));
		$this->dbforge->add_key('id',TRUE);
		if(!$this->dbforge->create_table('orders',true)){
			exit('Something goes wrong in Migration_orders after dbforge->create_table');
		}
		/*
		*
		*	
		*	Добавление ключей для таблицы orders
		*
		*/
		$this->db->query('CREATE INDEX idx_number ON `orders`(number)');
		$this->db->query('CREATE INDEX idx_metro_id ON `orders`(metro_id)');
		$this->db->query('CREATE INDEX idx_region_id ON `orders`(region_id)');
		$this->db->query('CREATE INDEX idx_org_id	ON `orders`(org_id)');


		$this->db->query('ALTER TABLE `orders` ADD FOREIGN KEY (`org_id`) REFERENCES `organizations`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `orders` ADD FOREIGN KEY (`metro_id`) REFERENCES `metros`(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
		$this->db->query('ALTER TABLE `orders` ADD FOREIGN KEY (`region_id`) REFERENCES `regions`(id) ON DELETE RESTRICT ON UPDATE RESTRICT');


		/*
		*
		*	 Добавление таблицы orders_users
		*
		*/
		$this->dbforge->add_field(array(
			'id' => array(
				'type'     => 'int',
				'unsigned' => true,
				'auto_increment' => true
			),
			'user_id' => array(
				'type'     => 'int',
				'unsigned' => true
			),
			'order_id' => array(
				'type'     => 'int',
				'unsigned' => true
			) 
		));

		$this->dbforge->add_key('id',TRUE);
		if(!$this->dbforge->create_table('orders_users',true)){
			exit('Something goes wrong in Migration_orders after dbforge->create_table');
		}

		/*
		*
		*	Создание индексов и внешних ключей для таблицы orders_users 
		*	
		*/
		$this->db->query('CREATE INDEX idx_user_id ON `orders_users`(user_id)');
		$this->db->query('ALTER TABLE `orders_users` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `orders_users` ADD FOREIGN KEY (`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}
	public function down(){
		$this->dbforge->drop_table('orders_users');
		$this->dbforge->drop_table('orders');
		$this->dbforge->drop_table('regions');
		$this->dbforge->drop_table('metros');
	}
}