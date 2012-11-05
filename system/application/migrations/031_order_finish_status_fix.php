<?php defined("BASEPATH") or die("No direct access to script");


class Migration_Order_finish_status_fix extends CI_Migration{

	public function up()
	{
		/*
		* Меняем значение по умолчанию у поля finish_status
		*/
		$this->dbforge->modify_column('orders',array(
			'finish_status' => array(
				'name' => 'finish_status',
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x00
			)
		));
	}

	public function down()
	{
	}
}