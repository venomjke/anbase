<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель комментариев к заявкам
 *
 * @package default
 * @author alex.strigin
 **/
class M_Order_comments extends MY_Model 
{
  public $inser_validation_rules = array(
    'text' => array('field'=>'text','label'=>'lang:order.label_comment.text','rules'=>'trim|xss_clean|html_escape')
  );

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
    $this->select("DATE_FORMAT(orders_comments.date_created, '%d/%m/%Y %H:%i:%s') as date_created", FALSE);
    $this->select("users.email");
    $this->select("users.name");
    $this->select("users.middle_name");
    $this->select('users.last_name');
    $this->select("users.role");

    $this->join("users", "orders_comments.user_id = users.id");
    $this->order_by('orders_comments.date_created', "DESC");
    $comments = $this->get_all(array("order_id" => $order_id));
    
    return $comments;
  }

  /**
   * Проверка валидности переданного id комментария org_id
   *
   * @return bool
   * @author alex.strigin
   **/
  public function exists($comment_id, $org_id)
  {
    $this->select('COUNT(*)');

    $this->join("orders", "orders.id = orders_comments.order_id");
    $comments = $this->get_all(array("orders_comments.id" => $comment_id, "orders.org_id" => $org_id));
    return count($comments);
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