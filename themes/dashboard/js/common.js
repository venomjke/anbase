/*
*
* common модуль предназначен для хранения разделяемых всеми панелями функций
* @author alex.strigin
*/
var common = {
	baseUrl:'',
	/**
	*
	* Методы уведомления о чем либо
	*
	**/
	showMsg:function(text,options){
		var def_options = {
			text:'',
			layout:'topRight',
			textAlign:'left',
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
	showNotySuccessMsg:function(text){
		common.showMsg(text);
	},
	showNotyErrorMsg:function(text){
		common.showMsg(text,{type:'error'});
	},
	showAjaxIndicator:function(){
		common.showStatusMsg();
	},
	hideAjaxIndicator:function(){
		common.hideStatusMsg();
	},
	showErrorMsg:function(msg){
		common.showStatusMsg({id:'result_msg',msg:msg,timeout:3000,bgcolor:'#92000a'});
	},
	showSuccessMsg:function(msg){
		common.showStatusMsg({id:'result_msg',msg:msg,timeout:3000,bgcolor:'#0f8434'});
	}
	,
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
			bgcolor:'#333',
			msg:'<img src="/themes/dashboard/images/load.gif"/> Загрузка...',
			timeout:0
		};
		/*
		* Сначала закрываем, если был открыт другой индикатор
		*/
		common.hideStatusMsg(options);

		options = $.extend(true,def_options,options);
		$('body').prepend('<div id="'+options.id+'" style="position:fixed;top: -20px;left: 45%;background-color:'+options.bgcolor+';z-index:99999999;padding:10px;-webkit-border-bottom-right-radius: 15px;-webkit-border-bottom-left-radius: 15px;-moz-border-radius-bottomright: 15px;-moz-border-radius-bottomleft: 15px;border-bottom-right-radius: 15px;border-bottom-left-radius: 15px;color:#fff" ><span style="padding: 3px;">'+options.msg+'</span></div>');
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
	},

	/*
	* Проверка объекта на пустотность
	*/
	isEmptyObj:function(obj){
	    for (var prop in obj) {
		    if (obj.hasOwnProperty(prop)) return false;
		}
		return true;
	},

	/*
	* Подсчет общего числа свойств
	*/
	count_props:function(obj){
		var count_props = 0;
		for(var prop in obj){
			++count_props;
		}
		return count_props;
	},

	show_full_text:function(event,row,cell,value){
		var tooltip_id = '#tooltip_'+row+'_'+cell;
		if($(tooltip_id).length>0){
		  $(tooltip_id).css('top',event.pageY-$(tooltip_id).outerHeight(true)-20);
		  $(tooltip_id).css('left',event.pageX-$(tooltip_id).outerWidth()-20);
		}else{
		  var tooltip = $('<div id="tooltip_'+row+'_'+cell+'" class="cell_tooltip" style="display:none;width:450">').html(value.replace(/(\n|\r\n)/g,'<br\/>'));
		  $('body').append(tooltip);  
		  tooltip.css('position','absolute');
		  tooltip.css('top',event.pageY-tooltip.outerHeight(true)-20);
		  tooltip.css('left',event.pageX-tooltip.outerWidth()-20);
		  tooltip.css('display','block');
		}
	},
	hide_full_text:function(event,row,cell){
	  $('#tooltip_'+row+'_'+cell).remove();
	},
	make_official_name:function(name,middle_name,last_name){
	  	name = name.charAt(0).toUpperCase();
	  	middle_name = middle_name.charAt(0).toUpperCase();
		last_name = last_name.charAt(0).toUpperCase()+last_name.substr(1,last_name.length);
		return last_name+' '+name+'.'+middle_name;
	}
}