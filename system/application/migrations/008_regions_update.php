<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Добавление новых записей в таблицу regions
 *
 * @package default
 * @author alex.strigin
 **/
class Migration_Regions_update extends CI_Migration
{
	/**
	 * Добавляем недостающие записи в таблицу regions
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function up()
	{
		$new_regions = array(
			"Василеостровский",
			"Выборгский",
			"Калининский",
			"Кировский",
			"Колпинский",
			"Красногвардейский",
			"Красносельский",
			"Кронштадский",
			"Курортный",
			"Московский",
			"Невский",
			"Петроградский",
			"Петродворцовый",
			"Приморский",
			"Пушкинский",
			"Центральный");

		foreach($new_regions as $new_region){
			$this->db->insert('regions',array('name'=>$new_region));
		}
	}

	/**
	 * Ничего не делаем, таблицу всеравно потом удалять будем
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{

	}
} // END class Migration_Regions_update extends CI_Migration