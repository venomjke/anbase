<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Модификация таблицы orders
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Orders_category extends CI_Migration
{
	/**
	 * 	модификация таблицы oders
	 *
	 * Меняем содержимое поля category
	 * @return void
	 * @author alex.strigin
	 **/
	public function up()
	{
		$this->dbforge->drop_column('orders','category');
		$this->dbforge->add_column('orders',array(
			'category' => array(
				'type'       => 'ENUM',
				'constraint' => "'Жилая','Коммерческая','Загородная'",
				'default'    => 'Жилая'
			)
		));
	}


	/**
	 * 
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{
	}
} // END class Migration_Invties extends CI_Migration