<?php defined("BASEPATH") or die("No direct access to script");


class Migration_Order_source extends CI_Migration{

  public function up()
  {
    $fields = array(
      'source' => array(
        'type' => 'VARCHAR',
        'constraint' => '512'
      )
    );
    $this->dbforge->add_column('orders', $fields);
  }

  public function down()
  {
    $this->dbforge->drop_column('orders', 'source');
  }
}