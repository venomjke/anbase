<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель, реализующая API для доступа ко всем изображениям районов
 *
 * @package default
 * @author alex.strigin
 **/
class M_region_image extends MY_Model
{

	public function __construct()
	{
		parent::__construct();

		/*
		* Структура
		*/
		$this->table = 'regions_images';
		$this->primary_key = 'id';
		$this->fields = array('id','name','image');
		$this->result_mode = 'object';

		$this->load->model('m_region_image_element');
	}

	/**
	 * Выбор карт районов.
	 *	
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_images()
	{
		$images = $this->get_all();
		$container = array(); // сюда будем запихивать изображения

		foreach($images as $image){
			$container[$image->name]['image'] = base_url().'themes/dashboard/images/regions/'.$image->image;
			$container[$image->name]['elements']= $this->m_region_image_element->get_all(array('region_image_id'=>$image->id));
		}

		return json_encode($container);
	}
} // END class M_metro_image extends MY_Model