<?php defined("BASEPATH") or die("No direct access to script");






if (!function_exists("has_errors_validation")) {
	
	/**
	 * Функция проходит по всем полям обращаясь к методу error form_validation.
	 * если будет обнаружена ошибка, то информация о ней будет занесена в errors_validation
	 * а также возвращен false
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	function has_errors_validation($fields,&$errors_validation)
	{
		$ci = get_instance();

		$has_errors_validation = false;
		foreach($fields as $field){
			$errors_validation[$field] = $ci->form_validation->error($field);
			if(!empty($errors_validation[$field]))
				$has_errors_validation = true;
		}
		return $has_errors_validation;
	}
}