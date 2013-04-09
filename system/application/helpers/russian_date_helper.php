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

/*
* Форматирование название месяца
*/
if( ! function_exists("russian_month")){
	function russian_month($code = '')
	{
		switch($code){
			default:
				return 'Неопределено';
			case '1':
				return 'Январь';
			case '2':
				return 'Февраль';
			case '3':
				return 'Март';
			case '4':
				return 'Апрель';
			case '5':
				return 'Май';
			case '6':
				return 'Июнь';
			case '7':
				return 'Июль';
			case '8':
				return 'Август';
			case '9':
				return 'Сентябрь';
			case '10':
				return 'Октябрь';
			case '11':
				return 'Ноябрь';
			case '12':
				return 'Декабрь';
		}
	}
}