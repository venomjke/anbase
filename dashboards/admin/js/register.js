/*
*
* Js регистрация для агента
*
*/
var register = {

	/*
	*
	*	Базовый URL для выполнения запросов
	*
	*/
	baseUrl:'',

	init:function(params){
	
		/*
		*	
		*	Установка базового URL
		*/
		register.baseUrl = params.baseUrl || '';
	},

	submit:function(options){

		var def_options = {
			jObjForm:{}
		};
	}
}