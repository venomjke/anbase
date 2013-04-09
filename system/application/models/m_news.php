<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Модель новостей
 *
 * @package default
 * @author alex.strigin
 **/
class M_News extends MY_Model
{
  public function __construct()
  {
    parent::__construct();

    $this->table = 'site_news';
    $this->primary_key = 'id';
    $this->fields  = array('id', 'title', 'anons', 'text', 'created');
    $this->result_mode = 'object';
  }

  public function get_last_news($limit = 0, $offset = 0)
  {
    $this->db->select('*');
    if($limit && $offset){
      $this->db->limit($limit, $offset);
    } else if($limit){
      $this->db->limit($limit, $offset);
    }
    $this->db->order_by('created', 'DESC');
    return $this->get_all();
  }

  public function total_news()
  {
    $this->db->select('COUNT(*) as _cnt');
    $result = $this->db->get($this->table);
    if($result->num_rows() == 1){
      $cnt_res = $result->row();
      return $cnt_res->_cnt;
    }
    return 0;
  }
} // END class M_News extends MY_Model