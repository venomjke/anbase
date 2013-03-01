<?php defined("BASEPATH") or die("No direct access to script");


class Migration_Orders_Comments extends CI_Migration{

  public function up()
  {
    $fields = array(
      'id' => array(
        'type' => 'INT',
        'unsigned' => true,
        'auto_increment' => true
      ),
      'order_id' => array(
        'type' => 'BIGINT',
        'unsigned' => true,
      ),
      'user_id' => array(
        'type' => 'INT',
        'constraint' => '9',
        'unsigned' => true,
      ),
      'text' => array(
        'type' => 'MEDIUMTEXT',
      )
    );

    $this->dbforge->add_field($fields);
    $this->dbforge->add_field('`date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->add_key('id', true);

    if( ! $this->dbforge->create_table("orders_comments", true)){
      exit('Something goes wrong in Migration_Orders_Comments after dbforge->create_table');
    }

    // добавление индексов и внешних ключей
    $this->db->query('CREATE INDEX user_id_idx ON `orders_comments`(user_id)');
    $this->db->query('CREATE INDEX order_id_idx ON `orders_comments`(order_id)');

    $this->db->query('ALTER TABLE `orders_comments` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE');
    $this->db->query('ALTER TABLE `orders_comments` ADD FOREIGN KEY (`order_id`) REFERENCES `orders`(id) ON DELETE CASCADE ON UPDATE CASCADE');

  }

  public function down()
  {

  }
}