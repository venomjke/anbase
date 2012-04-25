<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Добавляем таблицу для хранения списка изображений метро, и информации об элементах на этом изображении
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Metros_images extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '64',
				'default' => ''
			),
			'image' => array(
				'type' => 'VARCHAR',
				'constraint' => '64',
				'default' => ''
			)
		));

		$this->dbforge->add_key('id',true);

		if(!$this->dbforge->create_table('metros_images',true)){
			exit('Something goes wrong in Migration_metros_images after dbforge->create_table');
		}


		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'metro_image_id' => array(
				'type' => 'INT',
				'unsigned' => true
			),
			'metro_id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'null' => TRUE
			),
			'type' => array(
				'type' => 'ENUM',
				'constraint' => "'station','line'",
				'default' => 'station'
			),
			'line' => array(
				'type' => 'TINYINT',
				'null' => true,
				'unsigned' => true
			),
			'coords' => array(
				'type' => 'VARCHAR',
				'constraint' => '512',
				'default' => ''
			),
			'shape' => array(
				'type' => 'ENUM',
				'constraint' => "'circle','default','poly','rect'",
				'default' => 'default'
			)
		));

		$this->dbforge->add_key('id',true);

		if(!$this->dbforge->create_table('metros_images_elements',true)){
			exit('Something goes wrong in Migration_metros_images after dbforge->create_table');
		}

		$this->db->query("CREATE INDEX idx_metro_image_id ON `metros_images_elements`(metro_image_id)");
		$this->db->query("CREATE INDEX idx_metro_id ON `metros_images_elements`(metro_id)");

		$this->db->query("ALTER TABLE  `metros_images_elements` ADD FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION");
		$this->db->query("ALTER TABLE `metros_images_elements` ADD FOREIGN KEY (`metro_image_id`) REFERENCES `metros_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE");
	}

	public function down()
	{
		$this->dbforge->drop_table('metros_images_elements');
		$this->dbforge->drop_table('metros_images');	
	}

} // END class Migration_Metros_images