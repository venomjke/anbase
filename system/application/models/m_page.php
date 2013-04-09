<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Модель Страниц
 *
 * @package default
 * @author alex.strigin
 **/
class M_Page extends MY_Model
{
  public function __construct()
  {
    parent::__construct();

    $this->table = 'site_pages';
    $this->primary_key = 'id';
    $this->fields  = array('id', 'title', 'text', 'created', 'meta_keywords', 'meta_description');
    $this->result_mode = 'object';
  }
} // END class M_News extends MY_Model