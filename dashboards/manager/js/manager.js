/*
*
* логина обработки админ части на стороне клиента
*
*/
var manager = {

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
		manager.baseUrl = params.baseUrl || '';


	},

	profile:{

		/*
		* 
		* объект настроект по умолчанию
		*/
		def_options:{
			jField:{},
			jObjAct:{},
			type:'text',
			name:'name',
			uri:'manager/profile/view/'
		},
		/*
		*
		* переключение поля в режим редактирования поля
		*
		* @param object
		*/
		edit_field:function(options){

			/*
			*
			* 1. Выбрать поле profile_col_label
			* 2. Выбрать поле profile_col_action
			* 3. Создать поля profile_col_input и profile_col_action
			* 4. Заменить одни на другие.
			*/

			var profile_col_text  = options.jField.find('.profile_col_text');
			var profile_col_action = options.jField.find('.profile_col_action');
			var profile_col_input = $("<input type=\"text\" onkeypress=\"manager.profile.keypressed_edit_field({ jObjAct:$(this),name:'"+options.name+"',uri:'"+options.uri+"', type:'"+options.type+"'},event);\"  class=\"profile_col profile_col_input\"  />");
			var text = $.trim(profile_col_text.text());
			profile_col_input.val(text);
		    profile_col_text.replaceWith(profile_col_input); 
		
			profile_col_action.replaceWith("<span class=\"profile_col profile_col_action\"><a href=\"#\" onclick=\"manager.profile.click_edit_field({ jObjAct:$(this),name:'"+options.name+"', uri:'"+options.uri+"', type:'"+options.type+"' });return false;\"> Сохранить </a> </span>")
		},

		/*
		*
		* Обработчики событий клик и keypressed во время редактирования поля
		*
		*/
		click_edit_field:function(options){


			/*
			* 1. считать параметры 
			* 2. переключиться в режим просмотра
			*/
			options = $.extend(true,this.def_options,options);
			
			if(options.jObjAct){

				options.jField = options.jObjAct.parent().parent();
				this.show_field(options);	
			}
		},
		
		keypressed_edit_field:function(options,e){
			e = e || event;
			if(e.keyCode == 13){
				/*
				* 1. считать параметры 
				* 2. переключиться в режим просмотра
				*/
				options = $.extend(true,this.def_options,options);
				
				if(options.jObjAct){

					options.jField = options.jObjAct.parent();
					this.show_field(options);	
				}
			}	
		},



		/*
		*
		* переключение поля в режим только просмотра поля
		*
		* @param object
		*/
		show_field:function(options){

			/*
			* Попытка сохранить данные
			* Выбрать profile_col_input
			* Выбрать profile_col_action и заменить на profile_col_text
			*
			*/
			var profile_col_input  = options.jField.find('.profile_col_input');
			var profile_col_action = options.jField.find('.profile_col_action');

			var postData = {};
			postData[options.name] = profile_col_input.attr('value');

			$.ajax({

				type:'POST',
				url:manager.baseUrl+options.uri,
				data:postData,
				success:function(response){
					/*
					*
					* проверка ответа и вывод результата
					*/
					if(response.code && response.data){

						switch (response.code){
							case "success_edit_profile":
								profile_col_input.replaceWith("<span class=\"profile_col profile_col_text\">"+profile_col_input.attr('value')+"</span>");
								profile_col_action.replaceWith("<span class=\"profile_col profile_col_action\"> <a href=\"#\" onclick=\"manager.profile.click_show_field({jObjAct:$(this),uri:'"+options.uri+"',name:'"+options.name+"', type:'"+options.type+"'});return false;\">Изменить</a></span>"); 	
								common.showMsg(response.data);
							break;
							case "error_edit_profile":
								for (var i in response.data.errors){
									common.showMsg(response.data.errors[i],{type:"alert"});
								}
							break;
						}
					}
					
				},
				beforeSend:function(){
					options.jField.append('<span class="profile_col preloader"> <img src="'+manager.baseUrl+'themes/dashboard/images/preloader.gif" /> </span>')
				},
				complete:function(){
					options.jField.find('.preloader').remove();
				}
			})

		},

		/*
		* Обработчики событий click во время просмотра поля
		* @param object
		*/
		click_show_field:function(options){

			/*
			* 1. считать параметры 
			* 2. переключиться в режим редактирования
			*/

			options = $.extend(true,this.def_options,options);
			
			if(options.jObjAct){

				options.jField = options.jObjAct.parent().parent();
				this.edit_field(options);	
			}
		}
	}
}