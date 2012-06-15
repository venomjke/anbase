<?php defined("BASEPATH") or die("No direct access to script");


class Migration_Order_finish_status extends CI_Migration{

	public function up()
	{
		/*
		* Добавляем поле finish_status к orders
		*/
		$this->dbforge->add_column('orders',array(
			'finish_status' => array(
				'type'     => 'TINYINT',
				'unsigned' => true,
				'default' => 0x01
			)
		));	
	}

	public function down()
	{
	}
}