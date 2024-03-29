/*
*
* common модуль предназначен для хранения разделяемых всеми панелями функций
* @author alex.strigin
*/
var common = {
	baseUrl:'',

	/**
	* Методы уведомления о чем либо
	**/
	showMsg:function(text,options){
		var def_options = {
			text:'',
			layout:'topRight',
			textAlign:'left',
			animateOpen:{opacity:'show'},
			animateClose:{opacity:'hide'},
			type:'success'
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
	* Показать статусное сообщение
	*/
	showStatusMsg:function(options){


		var def_options = {
			id:'status_msg',
			type:'msg',
			bgcolor:'#333',
			msg:'<img src="/assets/images/dashboard/load.gif"/> Загрузка...',
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

	show_full_text:function(event, row, cell, value){
		var tooltip_id = '#tooltip_'+row+'_'+cell;
		if($(tooltip_id).length > 0){
		  $(tooltip_id).css('top',event.pageY-$(tooltip_id).outerHeight(true)-20);
		  $(tooltip_id).css('left',event.pageX-$(tooltip_id).outerWidth()-20);
		}else{
		  var tooltip = $('<div id="tooltip_'+row+'_'+cell+'" class="cell_tooltip" style="display:none;width:450">').html(common.fromText2Html(value));
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
	},

	/*
	* Загрузка формы
	*/
	load_form:function(hash, form){
		if( !hash || typeof hash != "object" || !form || typeof form != "string")
			return false;			

		hash[form] = {};

		$('#'+form+' input').each(function(){

			/*
			* Т.к все элементы формы у нас либо просто text,либо password, 
			* то считываем name и value и сохраняем их в hash_form для последующей обработки
			*/
			var name  = $(this).attr('name');
			var value = $(this).attr('value');
			hash[form][name] = value;
		});

		$('#'+form+' select').each(function(){
			var name = $(this).attr('name');
			var value = $(this).val();
			hash[form][name] = value;
		});
	},
	/*
	* Сохранение формы, если произошли изменения внутри формы
	*/
	save_form:function(hash, form, callback){
		if( !hash || typeof hash !="object" || !form || typeof form != "string")
				return false;

		var data = {};
		var cnt  = 0; // кол-во полей для изменения.
		$('#'+form+' input').each(function(){

			var name = $(this).attr('name');
			var value = $(this).attr('value');

			if(hash[form][name] != value){
				data[name] = value;
				++cnt;
			}
		});

		$('#'+form+' select').each(function(){
			var name = $(this).attr('name');
			var value = $(this).val();

			if(hash[form][name] != value){
				data[name] = value;
				++cnt;
			}
		})

		/*
		* если сохранять нечего, то выходим
		*/
		if(cnt == 0){
			return false;
		}
		callback(form,data);
	},

	/*
	* Переключатель с одного значения на другое
	*/
	switch:function($input,first,second){
		if($input.val() == first){
			$input.val(second);
		}else{
			$input.val(first);
		}
	},

	/*
	* Получение имени роли по её идентификатору
	*/
	getRoleName:function(role){
		if(role == common.role_list['USER_ROLE_ADMIN']) return lang['user.user_role_admin'];
		else if(role == common.role_list['USER_ROLE_MANAGER']) return lang['user.user_role_manager'];
		else if(role == common.role_list['USER_ROLE_AGENT']) return lang['user.user_role_agent'];

		return lang['user.undefined_role'];
	},

	/*
	* Выбор значения категории
	*/
	getCategoryName:function(category){
		if(category == common.category_list['ORDER_CATEGORY_RESIDENTAL_REAL_ESTATE']) return lang['order.order_category_residental_real_estate'];
        else if(category == common.category_list['ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE']) return lang['order.order_category_commercial_real_estate'];
        else if(category == common.category_list['ORDER_CATEGORY_COUNTRY_REAL_ESTATE']) return lang['order.order_category_country_real_estate'];

        return lang['order.undefined_cateogry'];
	},

	/*
	* Получение названия типа сделки по её идентификатору
	*/
	getDealtypeName:function(dealtype){
		if(dealtype == common.dealtype_list['ORDER_DEAL_TYPE_RENT']) return lang['order.order_deal_type_rent'];
		else if(dealtype == common.dealtype_list['ORDER_DEAL_TYPE_GET']) return lang['order.order_deal_type_get'];
		else if(dealtype == common.dealtype_list['ORDER_DEAL_TYPE_SELL']) return lang['order.order_deal_type_sell'];
		else if(dealtype == common.dealtype_list['ORDER_DEAL_TYPE_BUY']) return lang['order.order_deal_type_buy'];

		return lang['order.undefined_deal_type'];
	},

	/*
	* Выбор значения Статуса завершения
	*/
	getFinishStatusName:function(finish_status,deal_type){

	    function check_status(finish_status,success_text,failure_text){
	      if(finish_status == common.finishstatus_list['ORDER_FINISH_STATUS_SUCCESS']){
	        return success_text;
	      }else if(finish_status == common.finishstatus_list['ORDER_FINISH_STATUS_FAILURE']){
					return failure_text;
	      }
	      return ''; // в том случае, если заявка еще не завершена
	    }
	    //Выводим текст статуса заявки в зависимости от типа сделки
      switch(parseInt(deal_type)){
        case common.dealtype_list['ORDER_DEAL_TYPE_RENT']: // Сдать
            return check_status(finish_status,lang['finish_status.rent.success'],lang['finish_status.rent.failure']);
          break;
        case common.dealtype_list['ORDER_DEAL_TYPE_SELL']: // Продать
            return check_status(finish_status,lang['finish_status.sell.success'],lang['finish_status.sell.failure']);
          break;
        case common.dealtype_list['ORDER_DEAL_TYPE_GET']: // Снять
            return check_status(finish_status,lang['finish_status.get.success'],lang['finish_status.get.failure']);
          break;
        case common.dealtype_list['ORDER_DEAL_TYPE_BUY']: // Купить
            return check_status(finish_status,lang['finish_status.buy.success'],lang['finish_status.buy.failure']);
          break;
      }
	},

	/*
	* Преобразование спец. символов: переходы на новую строку, и.т.п в спец. символы html  
	*/
	fromText2Html:function(text){
		return text.replace(/(\n|\r\n)/g,'<br\/>');
	}
}