<?php defined("BASEPATH") or die("No direct access to script");


/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Order_price_decimal extends CI_Migration
{

	public function up()
	{
		$this->dbforge->modify_column('orders',array(
			'price' => array(
				'name' => 'price',
				'type' => 'DECIMAL',
				'constraint' => "12,0",
				'unsigned' => true
			)
		));
	}

	public function down()
	{
	}
} // END class Migration_Settings_org extends CI_Migration