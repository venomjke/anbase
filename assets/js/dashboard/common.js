// common methods, objects etc.
var common = {
	baseUrl:'',

	// вывод текстового уведомления
	notify: function(text, options){
		var settings = {
			text:'',
			typeNoty: '' 
		};
		settings = $.extend({}, settings, options, {text: text});

		if(settings.text){
			switch(settings.typeNoty){
				case 'popup':
					noty($.extend({
							layout: 'topRight',
							textAlign:'left',
							animateOpen:{
								opacity:'show'
							},
							animateClose:{
								opacity:'hide'
							},
							type: 'success',
						}, settings)
					);
					break;
				case 'status':
					if(settings.show){
						common.showStatusMsg(settings)
					} else {
						common.hideStatusMsg(settings);
					}
					break;
			};	
		}
	},

	showNotySuccessMsg:function(text){
		common.notify(text, {
			typeNoty: 'popup',
		});
	},
	

	showNotyErrorMsg:function(text){
		common.notify(text, {
			typeNoty: 'popup',
			type: 'error'
		})
	},
	
	showAjaxIndicator:function(){
		common.showStatusMsg();
	},
	
	hideAjaxIndicator:function(){
		common.hideStatusMsg();
	},
	
	showErrorMsg:function(msg){
		common.notify({
			id:'result_msg',
			msg: msg,
			timeout: 3000,
			bgcolor: '#92000a',
			show: true,
			typeNoty: 'status'
		});
	},
	
	showSuccessMsg:function(msg){
		common.notify({
			id: 'result_msg',
			msg: msg,
			timeout: 3000,
			bgcolor: '#0f8434',
			show: true,
			typeNoty: 'status'
		});
	},

	showResultMsg:function(msg){
		common.notify({
			id:'result_msg',
			msg: msg,
			timeout: 3000,
			typeNoty: 'status',
			show: true
		});
	},

	/*
	* Показать статусное сообщение
	*/
	showStatusMsg:function(options){
		var settings = {
			id:'status_msg',
			type: 'msg',
			bgcolor: '#333',
			msg: '<img src="/assets/images/dashboard/load.gif"/> ' + lang['common.loading'] ,
			timeout: 0
		};

		settings = $.extend(true, settings, options);

		// прячем открытый индикатор
		common.hideStatusMsg(settings);

		$('body').prepend('<div id="' + settings.id + '" style="position:fixed;top: -20px;left: 45%;background-color:' +settings.bgcolor + ';z-index:99999999;padding:10px;-webkit-border-bottom-right-radius: 15px;-webkit-border-bottom-left-radius: 15px;-moz-border-radius-bottomright: 15px;-moz-border-radius-bottomleft: 15px;border-bottom-right-radius: 15px;border-bottom-left-radius: 15px;color:#fff" ><span style="padding: 3px;">' + settings.msg + '</span></div>');
		$('#' + settings.id).animate({"top":"+=20px"}, "fast");

		// закрываем по timeout
		if(settings.timeout){
			setTimeout(function(){ common.hideStatusMsg(settings); }, settings.timeout);
		}
	},

	hideStatusMsg:function(options){
		var settings = {
			id:'status_msg'
		};

		settings = $.extend(true, settings, options);

		$('#' + settings.id).animate(
				{ 
					"top": "-=20px"
				},

				"fast",

				function(){
					$(this).remove();
				}
		);
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
	* Проверка имеет объект свойства или нет
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
		common.showCellTooltip(event, row, cell, value);
	},
	hide_full_text:function(event,row,cell){
		common.hideCellTooltip(event, row, cell);
	},
	make_official_name:function(name,middle_name,last_name){
	  	name = name.charAt(0).toUpperCase();
	  	middle_name = middle_name.charAt(0).toUpperCase();
		last_name = last_name.charAt(0).toUpperCase()+last_name.substr(1,last_name.length);
		return last_name+' '+name+'.'+middle_name;
	},

	/*
	* Отображение подсказки
	*/
	showCellTooltip: function(event, row, cell, value){
		var tooltipId = '#tooltip_'+row+'_'+cell;
		if($(tooltipId).length > 0){
		  $(tooltipId).css('top',event.pageY-$(tooltipId).outerHeight(true)-20);
		  $(tooltipId).css('left',event.pageX-$(tooltipId).outerWidth()-20);
		}else{
		  var tooltip = $('<div id="tooltip_'+row+'_'+cell+'" class="cell_tooltip" style="display:none;width:450">').html(common.fromText2Html(value));
		  $('body').append(tooltip);  
		  tooltip.css('position','absolute');
		  tooltip.css('top',event.pageY-tooltip.outerHeight(true)-20);
		  tooltip.css('left',event.pageX-tooltip.outerWidth()-20);
		  tooltip.css('display','block');
		}
	},

	hideCellTooltip: function(event, row, cell){
	  $('#tooltip_'+row+'_'+cell).remove();
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
	turn: function($input, first, second){
		if($input.val() == first){
			$input.val(second);
		}else{
			$input.val(first);
		}
	},

	switch:function($input,first,second){
		common.turn($input, first, second);
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
	fromText2Html: function(text){
		return text.replace(/(\n|\r\n)/g,'<br\/>');
	},

	/*
	* Текущая дата
	*/
	localDate: function(d){
		d = !d? new Date(): d;

		return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
	}
}