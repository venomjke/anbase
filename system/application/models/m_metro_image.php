<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель, реализующая API для доступа ко всем изображениям метро
 *
 * @package default
 * @author alex.strigin
 **/
class M_metro_image extends MY_Model
{

	public function __construct()
	{
		parent::__construct();

		/*
		* Структура
		*/
		$this->table = 'metros_images';
		$this->primary_key = 'id';
		$this->fields = array('id','name','image');
		$this->result_mode = 'object';

		$this->load->model('m_metro_image_element');
	}

	/**
	 * Выбор карт метро.
	 *	
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_images()
	{
		$images = $this->get_all();
		$container = array(); // сюда будем запихивать изображения

		foreach($images as $image){
			$container[$image->name]['image'] = base_url().'themes/dashboard/images/maps/'.$image->image;
			$container[$image->name]['elements']= $this->m_metro_image_element->get_all(array('metro_image_id'=>$image->id));
		}

		return json_encode($container);
	}
} // END class M_metro_image extends MY_Model