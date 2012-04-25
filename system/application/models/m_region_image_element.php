<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель, для работы со список элементов изображения
 *
 * @package default
 * @author alex.strigin
 **/
class M_region_image_element extends MY_Model
{
	public function __construct()
	{
		parent::__construct();

		/*
		* Структура
		*/
		$this->table = 'regions_images_elements';
		$this->primary_key = 'id';
		$this->fields = array('id','region_id','region_image_id','coords','shape');
		$this->result_mode = 'object';

	}

	private function build_select()
	{
		$this->select("regions_images_elements.id");
		$this->select("regions_images_elements.region_id");
		$this->select("regions_images_elements.coords");
		$this->select("regions_images_elements.shape");

		$this->select("regions.name as region_name");

		$this->join("regions","regions.id = regions_images_elements.region_id");
		$this->order_by("regions.name");
	}
	public function get_all($where)
	{
		$this->build_select();
		return parent::get_all($where);
	}
} // END class M_metro_image_element