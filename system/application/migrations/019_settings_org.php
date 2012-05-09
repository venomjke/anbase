<?php defined("BASEPATH") or die("No direct access to script");

/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Settings_org extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'auto_increment' => true,
				'unsigned' => true
			),
			'org_id' => array(
				'type' => 'INT',
				'unsigned' => true
			),
			'default_category' => array(
				'type' => 'ENUM',
				'constraint' => "'Жилая','Коммерческая','Загородная'",
				'default' => 'Жилая'
			),
			'default_dealtype' => array(
				'type' => 'ENUM',
				'constraint' => "'Куплю','Сниму','Сдам','Продам'",
				'default' => 'Сниму'
			),
			'price_col' => array(
				'type' => 'TINYINT',
				'default' => '1'
			),
			'regions_col' => array(
				'type' => 'TINYINT',
				'default' => '1'
			),
			'metros_col' => array(
				'type' => 'TINYINT',
				'default' => '1'
			),
			'phone_col' => array(
				'type' => 'TINYINT',
				'default' => '1'
			)
		));

		$this->dbforge->add_key('id',true);

		if(!$this->dbforge->create_table('settings_org',true)){
			exit('Something goes wrong in Migration_settings_org after dbforge->create_table');			
		}


		$this->db->query('CREATE UNIQUE INDEX idx_org_id ON `settings_org`(org_id)');
		$this->db->query('ALTER TABLE `settings_org` ADD FOREIGN KEY (`org_id`) REFERENCES `organizations`(id) ON DELETE CASCADE ON UPDATE CASCADE');

	}

	public function down()
	{
		$this->drop_table('settings_org');
	}
} // END class Migration_Settings_org extends CI_Migration