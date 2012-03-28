/*
*
* common модуль предназначен для хранения разделяемых всеми панелями функций
* @author alex.strigin
*/
var common = {

	/**
	*
	* Функция принимает строку вида key1=val1;key2=val2 e.t.c
	* парзит её на части, и возвращает ассоциативный контейнер 
	* arr[key1] = val1 e.t.c
	* @return array
	* @author alex.strigin
	*
	**/
	parse_string_params:function(string_params){

		string_params = string_params.split(';');

		params = [];
		for (var i in string_params){
			key_value = string_params[i].split('=');
			params[key_value[0]] = key_value[1];
		}

		return params;
	}
}