<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Добавляем таблицы для хранения инфы об изображениях, а также об элементах на этих изображениях
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Regions_images extends CI_Migration
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

		if(!$this->dbforge->create_table('regions_images',true)){
			exit('Something goes wrong in Migration_regions_images after dbforge->create_table');
		}


		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'region_image_id' => array(
				'type' => 'INT',
				'unsigned' => true
			),
			'region_id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'null' => TRUE
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

		if(!$this->dbforge->create_table('regions_images_elements',true)){
			exit('Something goes wrong in Migration_regions_images after dbforge->create_table');
		}

		$this->db->query("CREATE INDEX idx_region_image_id ON `regions_images_elements`(region_image_id)");
		$this->db->query("CREATE INDEX idx_region_id ON `regions_images_elements`(region_id)");

		$this->db->query("ALTER TABLE  `regions_images_elements` ADD FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION");
		$this->db->query("ALTER TABLE `regions_images_elements` ADD FOREIGN KEY (`region_image_id`) REFERENCES `regions_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE");
	}

	public function down()
	{
		$this->dbforge->drop_table('regions_images_elements');
		$this->dbforge->drop_table('regions_images');
	}
} // END class Migration_Regions_images extends CI_Migration