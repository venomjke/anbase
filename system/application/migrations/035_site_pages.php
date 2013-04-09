<?php defined("BASEPATH") or die("No direct access to script");


class Migration_Site_Pages extends CI_Migration{

  public function up()
  {
    $fields = array(
      'id' => array(
        'type' => 'INT',
        'unsigned' => true,
        'auto_increment' => true
      ),
      'title' => array(
        'type' => 'VARCHAR',
        'constraint' => '256',
      ),
      'text' => array(
        'type' => 'TEXT'
      ),
      'meta_keywords' => array(
        'type' => 'MEDIUMTEXT'
      ),
      'meta_description' => array(
        'type' => 'MEDIUMTEXT'
      )
    );

    $this->dbforge->add_field($fields);
    $this->dbforge->add_field('`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL');
    $this->dbforge->add_key('id', true);
    if( ! $this->dbforge->create_table('site_pages')){
      exit('Something goes wrong in Migration_site_pages after dbforge->create_table');
    }
  }

  public function down()
  {
    $this->dbforge->drop_table('site_pages');
  }
}