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
	},

	/**
	*
	* Методы уведомления о чем либо
	*
	**/
	showMsg:function(text,options){
		var def_options = {
			text:'',
			layout:'topRight',
			animateOpen:{opacity:'show'},
			animateClose:{opacity:'hide'},
			type:'success',
			theme:'noty_theme_mitgux'
		}
		options = $.extend(def_options,options);
		if(text){
			noty($.extend(options,{text:text}));
		}
	},
	showAjaxIndicator:function(){
		common.showStatusMsg();
	},
	hideAjaxIndicator:function(){
		common.hideStatusMsg();
	},
	showResultMsg:function(msg){
		common.showStatusMsg({id:'result_msg',msg:msg,timeout:3000});
	},
	/*
	*  
	* Показать статусное сообщение
	* 
	*/
	showStatusMsg:function(options){


		var def_options = {
			id:'status_msg',
			type:'msg',
			msg:'Загрузка...',
			timeout:0
		};

		/*
		* Сначала закрываем, если был открыт другой индикатор
		*/
		common.hideStatusMsg(options);

		options = $.extend(true,def_options,options);
		$('body').prepend('<div id="'+options.id+'" style="position:fixed;top: -20px;left: 50%;background-color: #B4B4B4;" ><span style="padding: 3px;">'+options.msg+'</span></div>');
		$('#'+options.id).animate({"top":"+=20px"},"fast");

		/*
		* если был задан timeout, то закрываем наше сообщение через определенный timeout
		*/
		if(options.timeout){
			setTimeout(function(){common.hideStatusMsg(options);},options.timeout);
		}
	},

	/*
	* Отключить Ajax индикатор загрузки
	*/
	hideStatusMsg:function(options){
		var def_options = {
			id:'status_msg'
		}
		options = $.extend(true,def_options,options);
		$('#'+options.id).animate({"top":"-=20px"},"fast",function(){$(this).remove();})
	}

}