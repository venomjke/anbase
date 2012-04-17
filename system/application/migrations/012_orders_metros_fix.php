<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Меняем ссылку внешнего ключа с regions на metros
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Orders_metros_fix extends CI_Migration
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
		* Дропаем foreign key
		*/
		$this->db->query('ALTER TABLE `orders_metros` DROP FOREIGN KEY `orders_metros_ibfk_2`');

		/*
		* Задаем внешние ключи
		*/
		$this->db->query('ALTER TABLE `orders_metros` ADD FOREIGN KEY (`metro_id`) REFERENCES `metros`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}

	/**
	 * Идем вниз =(
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{
		$this->db->query('ALTER TABLE `orders_metros` DROP FOREIGN KEY `orders_metros_ibfk_2`');
		$this->db->query('ALTER TABLE `orders_metros` ADD FOREIGN KEY (`metro_id`) REFERENCES `regions`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}
} // END class Migration_Orders_metros extends CI_Migration