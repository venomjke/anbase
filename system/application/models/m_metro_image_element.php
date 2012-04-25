<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель, для работы со список элементов изображения
 *
 * @package default
 * @author alex.strigin
 **/
class M_metro_image_element extends MY_Model
{
	public function __construct()
	{
		parent::__construct();

		/*
		* Структура
		*/
		$this->table = 'metros_images_elements';
		$this->primary_key = 'id';
		$this->fields = array('id','metro_id','metro_image_id','type','line','coords','shape');
		$this->result_mode = 'object';

	}

	private function build_select()
	{
		$this->select("metros_images_elements.id");
		$this->select("metros_images_elements.metro_id");
		$this->select("metros_images_elements.type");
		$this->select("metros_images_elements.line");
		$this->select("metros_images_elements.coords");
		$this->select("metros_images_elements.shape");

		$this->select("metros.name as metro_name");
		$this->select("metros.line as metro_line");

		$this->join("metros","metros.id = metros_images_elements.metro_id","LEFT");
		$this->order_by("metros.name");
	}
	public function get_all($where)
	{
		$this->build_select();
		return parent::get_all($where);
	}
} // END class M_metro_image_element