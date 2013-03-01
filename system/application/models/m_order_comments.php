<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель комментариев к заявкам
 *
 * @package default
 * @author alex.strigin
 **/
class M_Order_comments extends MY_Model 
{
  /**
   * Конструктор
   *
   * @return void
   * @author alex.strigin
   **/
  public function __construct()
  {
    parent::__construct();

    // таблица
    $this->table = 'orders_comments';

    $this->primary_key = 'id';
    $this->fields      = array('id', 'order_id', 'user_id', 'text', 'date_created');
    $this->result_mode = 'object';

    // привила валидации
    $this->validate = array();
  }


  /**
   * Проверка comment_id
   *
   * @return boolean
   * @author alex.strigin
   **/
  public function exists($comment_id)
  {
    $this->db->where('id', $comment_id);
    return $this->db->count_all_results('orders_comments') == 0 ? false : true;
  }

  /**
   * Выбор всех комментариев связанных с заявкой
   *
   * @param int
   * @author alex.strigin
   **/
  public function get_order_comments($order_id)
  {
    $this->select("orders_comments.id");
    $this->select("orders_comments.user_id");
    $this->select("orders_comments.text");
    $this->select("orders_comments.date_created");
    $this->select("users.email");
    $this->select("users.name");
    $this->select("users.middle_name");
    $this->select('users.last_name');
    $this->select("users.role");

    $this->join("users", "orders_comments.user_id = users.id");
    $comments = $this->get_all(array("order_id" => $order_id));
    
    return $comments;
  }

  /**
   * Добавление нового комментария к заявке
   *
   * @return void
   * @author alex.strigin
   **/
  public function add_order_comment($order_id, $user_id, $text)
  {
    return $this->insert(array('order_id' => $order_id, 'user_id' => $user_id, 'text' => $text), true); 
  }

  /**
   * Удаления комментария к заявке
   *
   * @return void
   * @author alex.strigin
   **/
  public function del_order_comment($comment_id)
  {
    $this->delete(array('id' => $comment_id));
  }

  /**
   * Удаления всех комментариев заявки
   *
   * @return void
   * @author alex.strigin
   **/
  public function del_order_comments($order_id)
  {
    $this->delete(array('order_id' => $order_id));
  }
} // END class M_Order_metro 