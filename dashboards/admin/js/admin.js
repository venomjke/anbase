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
		admin.baseUrl = params.baseUrl || admin.baseUrl;
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

		/*
		*
		* Инициализация модуля init
		*/
		init:function(){

			/*
			* Подключение диалога "Назначить менеджера"
			*
			*/
			$('#assign_manager_dialog').dialog({ modal:true,autoOpen:false,draggable:false});
		
			/*
			* Подключение диалога "Отправить инвайт"
			*
			*/
			$('#admin_invite_dialog').dialog({modal:true,autoOpen:false,draggable:false,title:'Инвайт для админа'});
			$('#manager_invite_dialog').dialog({ modal:true,autoOpen:false,draggable:false,title:'Инвайт для менеджера'});
			$('#agent_invite_dialog').dialog({modal:true,autoOpen:false,draggable:false,title:'Инвайт для агента'});
		},
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
		},

		/**
		*
		* Назначение менеджера клиенту.
		*
		* @param object
		**/
		assign_manager:function(options){
			$("#assign_manager_dialog").dialog("option","buttons",{
				'Назначить':function(){
					/*
					*
					* Выцепить manager_id и user_id
					* отправить запрос на назначение
					* success? все ок
					* error? еще не праздник
					*/
					var manager_id = $('#assign_manager_dialog select[name=~"manager_id"] option:selected').val();
					var manager_name = $.trim($('#assign_manager_dialog select[name=~"manager_id"] option:selected').text());
					var user_id	   = options.user_id;

					$.ajax({
						url:admin.baseUrl+options.uri,
						type:"POST",
						data:{'manager_id':manager_id,'user_id':user_id},
						dataType:"JSON",
						success:function(response){
							if(response.code && response.data){
								switch(response.code){
									case 'success_assign_manager_agent':
										common.showMsg(response.data);
										$('#assign_manager_dialog').dialog('close');
										options.jObjAct.replaceWith(manager_name);
									break;
									case 'error_assign_manager_agent':
										for(var i in response.data.errors){
											if(response.data.errors[i])
												common.showMsg(response.data.errors[i],{type:'error'});
										}
									break;
									default:
										$('#assign_manager_dialog').dialog('close');
								}
							}
						}
					})

				},
				'Отмена':function(){
					$(this).dialog("close");
				}
			});
			$("#assign_manager_dialog").dialog("open");
		},
		/*
		*
		* Отсоединяем агента от менеджера
		*/
		unbind_manager:function(options){

			 noty({
			    text: options.text, 
			    buttons: [
			      {type: 'button green', text: 'Да', click: function($noty) {

			          // this = button element
			          // $noty = $noty element
			          $noty.close();
			          var post_data = {};
			          post_data['user_id'] = options.user_id;

			          $.ajax({
			          	url:admin.baseUrl+options.uri,
			          	type:'POST',
			          	dataType:'json',
			          	data:post_data,
			          	success:function(response){
			          		if(response.code && response.data){

			          			switch(response.code){
			          				case 'success_unbind_manager':
			          					common.showMsg(response.data);
			          				break;
			          				case 'error_unbind_manager':
			          					for(var i in response.data.errors){
			          						common.showMsg(response.data.errors[i],{type:'error'});
			          					}
			          				break;
			          			}
			          		}
			          	},
			          	beforeSend:function(){
			          		common.showAjaxIndicator();
			          	},
			          	complete:function(){
			          		common.hideAjaxIndicator();
			          	}
			          })

			        }
			      },
			      {type: 'button pink', text: 'Нет', click: function($noty) {
			          $noty.close();
			        }
			      }
			      ],
			    closable: false,
			    timeout: false,
			    layout:'center',
			    theme:'noty_theme_mitgux'
			  });
		},
		admin_invite:function(){
			admin.user.invite('#admin_invite_dialog');
		},
		manager_invite:function(){
			admin.user.invite('#manager_invite_dialog');
		},
		agent_invite:function(){
			admin.user.invite('#agent_invite_dialog');
		},

		/*
		*
		* Сериализация формы, отправка инвайта
		*
		*/
		invite:function(dialog_id){
			$(dialog_id).dialog('option','buttons',{

				'Отправить':function(){

					/*
					* Сериализация формы
					* Отправки формы
					* Обработка ответа
					*/
					var post_data = $(dialog_id+' form').serialize();
					$.ajax({
						url:$(dialog_id+' form').attr('action'),
						type:"POST",
						data:post_data,
						dataType:'json',
						success:function(response){

							if(response.code && response.data){
								switch(response.code){

									case 'success_send_invite':
										$(dialog_id).dialog('close');
										common.showMsg(response.data);
									break;
									case 'error_send_invite':
										for(var i in response.data.errors)
											if(response.data.errors[i])
												common.showMsg(response.data.errors[i],{type:'error'});
									break;
								}
							}
						}
					})
				},
				'Отмена':function(){
					$(this).dialog('close');
				}
			});
			$(dialog_id).dialog('open');
		},
		del_invite:function(options){

			var def_options = {
 				jObjAct:{},
 				id:'',
 				uri:''
			};
			options = $.extend(true,def_options,options);
			/*
			* Упаковываем ID в массив и передаем del_invites со всем вытекающим
			*/
			var del_invites = [];
			del_invites.push(options.id);
			
			 noty({
			    text: options.text, 
			    buttons: [
			      {type: 'button green', text: 'Да', click: function($noty) {
			      		$noty.close();
						admin.user.del_invites({jObjTable:options.jObjAct.parent().parent().parent(),uri:options.uri,ids:del_invites});	
			        }
			      },
			      {type: 'button pink', text: 'Нет', click: function($noty) {
			          $noty.close();
			        }
			      }
			      ],
			    closable: false,
			    timeout: false,
			    layout:'center',
			    theme:'noty_theme_mitgux'
			  });
		},
		del_invites:function(options){
			var def_options = {
				jObjTable:{},
				ids:[],
				uri:''
			};

			options = $.extend(true,def_options,options);
			var post_data = {};
			post_data['ids_invites'] = options.ids; 
			$.ajax({
				url:admin.baseUrl+options.uri,
				type:'POST',
				data:post_data,
				dataType:'json',
				success:function(response){

					if(response.code && response.data){
						switch(response.code){
							case 'success_del_user_invites':
								for(i in options.ids){
									$('#invite_'+options.ids[i]).remove();
								}
								common.showMsg(response.data);
							break;
							case 'error_del_user_invites':
								for(var i in response.data.errors){
									if(response.data.errors[i])
										common.showMsg(response.data.errors[i],{type:'error'});
								}
							break;
						}
					}
				}
			});
		},
		check_all:function(options){

			var i_checked = options.jObjAction.attr('checked');
			if(i_checked == "checked"){
				$('.user_table input[type="checkbox"]').attr('checked','checked');
			}else{
				$('.user_table input[type="checkbox"]').removeAttr('checked','checked');
			}
		},
		del_user:function(options){

		},
		del_users:function(options){
			/*
			* 1. формируем пачку id для отправки
			* 2. отправляем
			* 3. все ок? удаляем их из таблицы
			*/
			var users_ids = [];
			var all_checkbox = $('.user_table td input:checked');
			all_checkbox.each(function(){
				users_ids.push($(this).attr('value'));
			});

			var post_data = {};
			post_data['ids_users'] = users_ids;

			$.ajax({
				url:admin.baseUrl+options.uri,
				type:'POST',
				dataType:'json',
				data:post_data,
				success:function(response){
					if(response.code && response.data){
						switch(response.code){
							case 'success_del_users':
								common.showResultMsg(response.data);
								all_checkbox.each(function(){
									$('#user_'+$(this).val()).remove();
								});
							break;
							case 'error_del_users':
								common.showResultMsg('Удалить не удалось');
							break;
						}
					}
				},
				beforeSend:function(){
					common.showAjaxIndicator();
				},
				complete:function(){
					common.hideAjaxIndicator();
				}
			})
		}
	},

	orders:{
		def_options:{
			jObjAction:{},
			name:''
		},
		dialog_options:{
			autoOpen:false,
			modal:true,
			draggable:false
		},
		init:function(){
			$('#dialog_add_order').dialog(admin.orders.dialog_options);
			$('#dialog_edit_number').dialog(admin.orders.dialog_options);
			$('#dialog_edit_create_date').dialog(admin.orders.dialog_options);
			$('#dialog_edit_category').dialog(admin.orders.dialog_options);
			$('#dialog_edit_deal_type').dialog(admin.orders.dialog_options);
			$('#dialog_edit_price').dialog(admin.orders.dialog_options);
			$('#dialog_edit_description').dialog(admin.orders.dialog_options);
			$('#dialog_edit_delegate_date').dialog(admin.orders.dialog_options);
			$('#dialog_edit_phone').dialog(admin.orders.dialog_options);
			$('#dialog_delegate_order').dialog(admin.orders.dialog_options);
		},
		add:function(options){
			var dialog=$('#dialog_add_order');
			dialog.dialog('option','buttons',{
				'Добавить':function(){
					var form = dialog.find('form');
					var form_data = form.serialize();
					$.ajax({
						url:admin.baseUrl+options.uri,
						type:'POST',
						dataType:'json',
						data:form_data,
						success:function(response){
							if(response.code && response.data){
								switch(response.code){
									case 'success_add_order':
										common.showResultMsg(response.data.msg);
										/*
										* добавить строку в таблицу
										*/
										var row = $('<tr>');
										var checkbox_td = $('<td><input type="checkbox" name="orders_ids" value="'+response.data.id+'"/></td>');
										var number   = $('<td>'+form.find('input[name="number"]').val()+'</td>');
										var create_date = $('<td>'+form.find('input[name="create_date"]').val()+'</td>');
										var category    = $('<td>'+form.find('select[name="category"] option:selected').val()+'</td>');
										var deal_type   = $('<td>'+form.find('select[name="deal_type"] option:selected').val()+'</td>');
										var region      = $('<td></td>');
										var metro       = $('<td></td>');  
										var price       = $('<td>'+form.find('input[name="price"]').val()+'</td>');
										var description = $('<td>'+form.find('textarea[name="description"]').val()+'</td>');
										var user = $('<td></td>');
										var delegate_date = $('<td></td>');
										var phone = $('<td>'+form.find('input[name="phone"]').val()+'</td>');
										row.append(checkbox_td)
										   .append(number)
										   .append(create_date)
										   .append(category)
										   .append(deal_type)
										   .append(region)
										   .append(metro)
										   .append(price)
										   .append(description)
										   .append(user)
										   .append(delegate_date)
										   .append(phone);
										 $('#dashboard_table tbody').prepend(row);
										 dialog.dialog('close');
									break;
									case 'error_add_order':
										common.showResultMsg("Не удалось сохранить");
										dialog.dialog('close');
									break;
								}
							}
						},
						beforeSend:function(){
							common.showAjaxIndicator();
						},
						complete:function(){
							common.hideAjaxIndicator();
						}
					})
				},
				'Отмена':function(){

				}
			});

			dialog.dialog('open');
		},
		check_all:function(options){

			var i_checked = options.jObjAction.attr('checked');
			if(i_checked == "checked"){
				$('#dashboard_table input[type="checkbox"]').attr('checked','checked');
			}else{
				$('#dashboard_table input[type="checkbox"]').removeAttr('checked','checked');
			}
		},
		del:function(options){
			/*
			* 1. формируем пачку id для отправки
			* 2. отправляем
			* 3. все ок? удаляем их из таблицы
			*/
			var orders_ids = [];
			var all_checkbox = $('#dashboard_table td input:checked');
			all_checkbox.each(function(){
				orders_ids.push($(this).attr('value'));
			});

			var post_data = {};
			post_data['orders_ids'] = orders_ids;

			$.ajax({
				url:admin.baseUrl+options.uri,
				type:'POST',
				dataType:'json',
				data:post_data,
				success:function(response){
					if(response.code && response.data){
						switch(response.code){
							case 'success_del_order':
								common.showResultMsg(response.data);
								all_checkbox.each(function(){
									$('#order_'+$(this).val()).remove();
								});
							break;
							case 'error_del_order':
								common.showResultMsg('Удалить не удалось');
							break;
						}
					}
				},
				beforeSend:function(){
					common.showStatusMsg();
				},
				complete:function(){
					common.hideStatusMsg();
				}
			})
		},
		edit:function(options){
			/*
			* 1. выдергиваем поле
			* 2. отправлялем изменение
			* 3. все в поряде
			*/
			var form_data = options.form.serialize();
			form_data += "&id="+options.id;
			$.ajax({
				url:admin.baseUrl+options.uri,
				type:'POST',
				dataType:'json',
				data:form_data,
				success:function(response){
					if(response.code && response.data){
						switch(response.code){
							case 'success_edit_order':
								options.callback();
								common.showStatusMsg({id:'response_msg',msg:response.data,timeout:5000});
							break;
							case 'error_edit_order':
								common.showStatusMsg({id:'response_msg',msg:'Сохранить не удалось...',timeout:5000});
							break;
						}
					}
				},
				beforeSend:function(){
					common.showStatusMsg();
				},
				complete:function(){
					common.hideStatusMsg();
				}
			});
			return true;
		},
		edit_text:function(options){
			/*
			* выцепить из формы текст
			* прицепить его соотв. диалогу
			* создать соотв. диалог
			*/
			var txt = $.trim(options.jObjAction.text());
			var dialog = $('#dialog_edit_'+options.name);
			var form = dialog.find('form');
			form.find('input[type="text"]').val(txt);
			dialog.dialog('option','buttons',{
				'Сохранить':function(event){
					options['form'] = form;
					// callback нужна для того, чтобы после отправки запроса можно было сохранить результат в таблице пользователя
					options['callback'] = function(){
						options.jObjAction.text(form.find('input').val());
					};
					admin.orders.edit(options);
					dialog.dialog('close');
				},
				'Отмена':function(event){
					dialog.dialog('close');
				}
			});
			dialog.dialog('open');
		},
		edit_bigtext:function(options){
			
			var txt = $.trim(options.jObjAction.text());
			var dialog = $('#dialog_edit_'+options.name);
			var form = dialog.find('form');
			form.find('textarea').val(txt);
			dialog.dialog('option','buttons',{
				'Сохранить':function(event){
					options['form'] = form;
					// callback нужна для того, чтобы после отправки запроса можно было сохранить результат в таблице пользователя
					options['callback'] = function(){
						options.jObjAction.text(form.find('textarea').val());
					};
					admin.orders.edit(options);
					dialog.dialog('close');
				},
				'Отмена':function(event){
					dialog.dialog('close');
				}
			});
			dialog.dialog('open');
		},
		edit_select:function(options){
			var text   = $.trim(options.jObjAction.text());
			var dialog = $('#dialog_edit_'+options.name);
			var form = dialog.find('form');
			form.find('option').each(function(){
				if($.trim($(this).text()) == text){
					$(this).attr('selected','selected');
				}
			});

			dialog.dialog('option','buttons',{
				'Сохранить':function(event){
					options['form'] = form;
					// callback нужна для того, чтобы после отправки запроса можно было сохранить результат в таблице пользователя
					options['callback'] = function(){
						options.jObjAction.text(form.find('option:selected').text());
					};
					admin.orders.edit(options);
					dialog.dialog('close');
				},
				'Отмена':function(event){
					dialog.dialog('close');
				}
			});
			dialog.dialog('open');
		},
		/*
		*
		* Передать заявку под управление агента
		* структура options:
		* 	jObjAction - dom елемент, который мы использовали для вызова функции
		*   uri - ссылка на операцию
		*   id  - id Заявки
		*   user_id - id пользователя, владеющего заявкой
		*/
		delegate_order:function(options){
			/*
			* Загружаем диалог со списком агентов - менеджеров
			*/
			var dialog = $('#dialog_delegate_order');
			var form   = dialog.find('form');
			if(options.user_id){
				form.find('option').each(function(){
					if($(this).val() == options.user_id){
						$(this).attr('selected','selected');
					}
				})
			}
			dialog.dialog('option','buttons',{
				'Назначить':function(){

					var post_data = form.serialize();
					post_data += '&order_id='+options.id;
					$.ajax({
						url:admin.baseUrl+options.uri,
						type:'POST',
						data:post_data,
						dataType:'json',
						success:function(response){
							if(response.code && response.data){
								switch(response.code){
									case 'success_delegate_order':
										options.jObjAction.parent().html($.trim(form.find('option:selected').text()));
										common.showResultMsg(response.data);
									break;
									case 'error_delegate_order':
										common.showResultMsg('Не удалось делегировать заявку');
									break;
								}
							}
						},
						beforeSend:function(){
							common.showAjaxIndicator();
						},
						complete:function(){
							common.hideAjaxIndicator();
						}
					})
					dialog.dialog('close');
				},
				'Отмена':function(){
					dialog.dialog('close');
				}
			});
			dialog.dialog('open');
		}	
	}
}