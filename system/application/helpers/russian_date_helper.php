<?php defined("BASEPATH") or die("No direct access to script");


/*
* Форматирование даты на русский лад.
*/
if(!function_exists("russian_week_day")){
	function russian_week_day($day){
		switch($day){
			case 1:
				return "Понедельник";
			break;
			case 2:
				return "Вторник";
			break;
			case 3:
				return "Среда";
			break;
			case 4:
				return "Четверг";
			break;
			case 5:
				return "Пятница";
			break;
			case 6:
				return "Суббота";
			break;
			case 0:
				return "Воскресенье";
			break;
		}
	}
}