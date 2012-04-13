/*
*
* common модуль предназначен для хранения разделяемых всеми панелями функций
* @author alex.strigin
*/
var common = {
	metros:{
		"first_line":[
			"Проспект ветеранов",
			"Ленинский проспект",
			"Автово",
			"Кировский завод",
			"Нарвская",
			"Балтийская",
			"Пушкинская",
			"Владимирская",
			"Площадь восстания",
			"Чернышевская",
			"Площадь ленина",
			"Выборгская",
			"Лесная",
			"Площадь мужества",
			"Политехническая",
			"Академическая",
			"Гражданский проспект",
			"Девяткино"
		],
		/*
		* 2-я линия
		*/
		"second_line":[
			"Купчино",
			"Звездная",
			"Московская",
			"Парк победы",
			"Электросила",
			"Московские ворота",
			"Фрунзенская",
			"Технологический институт",
			"Сенная площадь",
			"Невский проспект",
			"Горьковская",
			"Петроградская",
			"Черная речка",
			"Пионерская",
			"Удельная",
			"Озерки",
			"Проспект просвещения",
			"Парнас"
		],
		/*
		* 3-я линия
		*/
		"third_line":[
			"Рыбацкое",
			"Обухово",
			"Пролетарская",
			"Ломоносовская",
			"Елизаровская",
			"Площадь Александра Невского I",
			"Маяковская",
			"Гостинный двор",
			"Василеостровская",
			"Приморская"
		],
		/*
		* 4-я линия
		*/
		"fourth_line":[
			"Улица дыбенко",
			"Проспект большевиков",
			"Ладожская",
			"Новочеркасская",
			"Площадь Александра Невского II",
			"Лиговский проспект",
			"Спасская"
		],
		/*
		* 5-я линия
		*/
		"fith_line":[
			"Волковская",
			"Обводный канал",
			"Звенигородская",
			"Садовая площадь",
			"Спортивная",
			"Чкаловская",
			"Крестовский остров",
			"Старая деревня",
			"Комендантский проспект"
		]
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
	},
	/*
	* Сравнение массивов
	*/
	compareArrays:function(array1,array2,operation){
		if( !array1 || !array2 || array1.length != array2.length)
			return false;

		for(i = 0; i < array1.length; ++i){
			switch(operation){
				case 'eq':
					if( array1[i] != array2[i] ){
						return false;
					}
				break;
			}
		}
		return true;
	}
}