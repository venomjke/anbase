/*
*
* логина обработки админ части на стороне клиента
*
*/
var admin = {

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
		admin.baseUrl = params.baseUrl || '';


	},
	/*
	*
	* Модуль, для управления разделом profile
	*
	*/
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
			uri:'admin/profile/view/'
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
			var profile_col_input = $("<input type=\"text\" onkeypress=\"admin.profile.keypressed_edit_field({ jObjAct:$(this),name:'"+options.name+"',uri:'"+options.uri+"', type:'"+options.type+"'},event);\"  class=\"profile_col profile_col_input\"  />");
			var text = $.trim(profile_col_text.text());
			profile_col_input.val(text);
		    profile_col_text.replaceWith(profile_col_input); 
		
			profile_col_action.replaceWith("<span class=\"profile_col profile_col_action\"><a href=\"#\" onclick=\"admin.profile.click_edit_field({ jObjAct:$(this),name:'"+options.name+"', uri:'"+options.uri+"', type:'"+options.type+"' });return false;\"> Сохранить </a> </span>");
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
				url:admin.baseUrl+options.uri,
				data:postData,
				dataType:'json',
				success:function(response){
					/*
					*
					* проверка ответа и вывод результата
					*/
					if(response.code && response.data){

						switch (response.code){
							case "success_edit_profile":
								profile_col_input.replaceWith("<span class=\"profile_col profile_col_text\">"+profile_col_input.attr('value')+"</span>");
								profile_col_action.replaceWith("<span class=\"profile_col profile_col_action\"> <a href=\"#\" onclick=\"admin.profile.click_show_field({jObjAct:$(this),uri:'"+options.uri+"',name:'"+options.name+"', type:'"+options.type+"'});return false;\">Изменить</a></span>"); 	
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
					options.jField.append('<span class="profile_col preloader"> <img src="'+admin.baseUrl+'themes/dashboard/images/preloader.gif" /> </span>')
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
	},

	/*
	*
	*	Модуль для управления разделом user
	*
	*
	*/
	user:{

		/**
		*
		*	Объект настроект по умолчанию
		*
		**/
		def_options:{
			jCol:{},
			jObjAct:{},
			id:'',
			name:'role',
			uri:'admin/user/admins/?act=change_position',
			role:['Админ','Менеджер','Агент'],
			text:'',
			last_role:''
		},

		/*
		* 
		*	Переключение поля в режим редактирования
		*
		*/
		edit_col:function(options){

			/*
			*
			* В ячейку добавить select для выбора нового значения
			* 
			*
			*/
			role_string = "['";
			role_string += options.role.join("','");
			role_string += "']";
			
			if(options){
				options.last_role = $.trim(options.jCol.text());
				var col_select = $('<select name="'+options.name+'">');
				col_select.attr('onchange',"admin.user.change_edit_col({jObjAct:$(this),uri:'"+options.uri+"',name:'"+options.name+"',role:"+role_string+",last_role:'"+options.last_role+"',user_id:'"+options.user_id+"'})");

				for(var i in options.role){
					var select_option = $('<option value="'+options.role[i]+'">'+options.role[i]+'</option>');
					if(options.role[i] == options.last_role)
						select_option.attr('selected','');
					select_option.appendTo(col_select);
				}
				options.jCol.html(col_select);
			}
		},

		/*
		*
		*	Обработчик события changed во время редактирования ячейки
		*
		*/
		change_edit_col:function(options){

			/*
			*
			* 1. считать параметры
			* 2. переключиться в режим просмотра
			*
			*/
			options = $.extend(true,this.def_options,options);

			if(options.jObjAct){
				options.jCol = options.jObjAct.parent();
				this.show_col(options);
			}
		},

		/*
		*
		* Переключение в режим просмотра поля
		*
		*/
		show_col:function(options){

			/*
			* 1.Подтвердить изменение должности
			* 2.Сохранить изменения
			*/
			 noty({
			    text: options.text, 
			    buttons: [
			      {type: 'button green', text: 'Да', click: function($noty) {

			          // this = button element
			          // $noty = $noty element
			          $noty.close();
			          var col_select = options.jObjAct;
			          var checked_option = col_select.find('option:checked');
			          var data = {};
			          /*
			          * передаваемые данные
			          *
			          */
			          data[options.name] = checked_option.val();
			          data['id']		 = options.user_id;
			          $.ajax({
			          	url:admin.baseUrl+options.uri,
			          	data:data,
			          	type:'POST',
			          	dataType:'json',
			          	success:function(response){

			          		/*
			          		*
			          		* проверка ответа и вывод результата
			          		*/
			          		if(response.code && response.data){
			          			switch(response.code){
				          			case 'success_change_position_employee':
				          				options.jCol.html(checked_option.val());
				          				common.showMsg(response.data);
				          			break;
				          			case 'error_change_position_employee':
				          				for(var i in response.data.errors)
				          					common.showMsg(response.data.errors[i],{type:'error'});
				          				options.jCol.html(options.last_role);
				          			break;
			          			}
			          		}
			          	},
			          	beforeSend:function(){

			          	},
			          	complete:function(){

			          	}
			          });
			        }
			      },
			      {type: 'button pink', text: 'Нет', click: function($noty) {
			          $noty.close();
			          options.jCol.html(options.last_role);
			        }
			      }
			      ],
			    closable: false,
			    timeout: false,
			    layout:'center',
			    theme:'noty_theme_mitgux'
			  });
		},

		/*
		*
		*	Обработчик события dblclick во время просмотра колонки
		*
		*/
		dblclick_show_col:function(options){
			/*
			*
			* 1. Считать параметры
			* 2. переключиться в режим редактирования
			*
			*/
			options = $.extend(true,this.def_options,options);

			if(options && options.jObjAct){
				options.jCol = options.jObjAct;
				this.edit_col(options);
			}
		}
	}
}