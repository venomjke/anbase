<?php defined("BASEPATH") or die("No direct access to script");

/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Metros_transshipment extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_column('metros',array(
			'transshipment' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'null' => true
			)
		));
	}

	public function down()
	{
	}
} // END class Migration_Regions_images extends CI_Migration