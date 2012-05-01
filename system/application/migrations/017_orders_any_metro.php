<?php defined("BASEPATH") or die("No direct access to script");

/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Orders_any_metro extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_column('orders',array(
			'any_metro' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('orders','any_metro');
	}
} // END class Migration_Orders_any_metro extends CI_Migration