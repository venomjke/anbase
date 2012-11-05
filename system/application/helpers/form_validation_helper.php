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
	function has_errors_validation($fields,&$errors_validation,$error_delimiters=false)
	{
		$ci = get_instance();

		$has_errors_validation = false;
		foreach($fields as $field){
			if($error_delimiters)
				$errors_validation[$field] = $ci->form_validation->error($field);
			else
				$errors_validation[$field] = $ci->form_validation->error($field,'<span>','</span>');
			if(!empty($errors_validation[$field]))
				$has_errors_validation = true;
		}
		return $has_errors_validation;
	}
}

if(!function_exists("build_validation_array")){
	/**
	 * Функция преобразует значения полей из $target, к виду типа $prefix[значение поля]
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	function build_validation_array(&$fields,$target,$prefix)
	{
		if(is_array($target)){
			foreach($target as $field=>$field_def){
				$buff = array();
				$buff['field'] = $prefix."[".$field_def['field']."]";
				$buff['label'] = $field_def['label'];
				$buff['rules'] = $field_def['rules'];
				$fields[]=$buff;
	 		}	
		}
	}
}