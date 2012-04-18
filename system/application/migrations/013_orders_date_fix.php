<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Меняем структуру дат в таблице orders. Убираем у текущих create_date,finish_date,delegate_date приставку
 * time, и создаем записи created,delegated,finished, которые будут хранить реальную информацию об создании,делегировании, окончании.
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Orders_date_fix extends CI_Migration
{
	/**
	 * Выполняем подъем!
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function up()
	{
		$this->dbforge->modify_column('orders',array(
			'create_date' => array(
				'TYPE' => 'DATE',
				'default' => '00.00.00'
				),
			'delegate_date' => array(
				'TYPE' => 'DATE',
				'default' => '00.00.00'
				),
			'finish_date' => array(
				'TYPE' => 'DATE',
				'default' => '00.00.00'
				)
		));

		$this->dbforge->add_column('orders',array(
			'created' => array(
				'TYPE' => 'DATETIME',
				'default' => '00.00.00 00:00:00'
			),
			'delegated' => array(
				'TYPE' => 'DATETIME',
				'default' => '00.00.00 00:00:00'
			),
			'finished' => array(
				'TYPE' => 'DATETIME',
				'default' => '00.00.00 00:00:00'				
			)
		));
	}

	/**
	 * Идем вниз =(
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{
	}
} // END class Migration_Orders_metros extends CI_Migration