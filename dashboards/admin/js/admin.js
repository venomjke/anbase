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
		init:function(){
			admin.profile.hash_form = {};
			admin.profile.load_form('personal');
			admin.profile.load_form('system');
			admin.profile.load_form('organization');
		},
		/*
		* Загрузка формы
		*/
		load_form:function(form){
			if(!form || typeof form != "string")
				return false;			
			admin.profile.hash_form[form] = {};

			$('#'+form+' input').each(function(){
				if($(this).attr('type') != 'text' && $(this).attr('type') != 'password')
					return false;
				/*
				* Т.к все элементы формы у нас либо просто text,либо password, 
				* то считываем name и value и сохраняем их в hash_form для последующей обработки
				*/
				var name  = $(this).attr('name');
				var value = $(this).attr('value');
				admin.profile.hash_form[form][name] = value;
			})
		},
		/*
		* Сохранение формы
		*/
		save_form:function(form){
			if( !form || typeof form != "string")
				return false;
			var data = {};
			var cnt  = 0; // кол-во полей для изменения.
			$('#'+form+' input').each(function(){
				if($(this).attr('type') != 'text' && $(this).attr('type') != 'password')
					return false;

				var name = $(this).attr('name');
				var value = $(this).attr('value');

				if(admin.profile.hash_form[form][name] != value){
					data[name] = value;
					++cnt;

				}
			});

			/*
			* если сохранять нечего, то выходим
			*/
			if(cnt == 0){
				return false;
			}

			/*
			* Отправляем форму.
			*/
			$.ajax({
				url:admin.baseUrl+'/edit/?sct='+form,
				type:'POST',
				dataType:'json',
				data:data,
				beforeSend:function(){
					common.showAjaxIndicator();
				},
				complete:function(){
					common.hideAjaxIndicator();
				},
				success:function(response){
					if(response.code && response.data){
						switch(response.code){
							case 'success_edit_profile':
								common.showSuccessMsg(response.data);
								for(var i in data){
									admin.profile.hash_form[form][i] = data[i];
								}
							break;
							case 'error_edit_profile':
								if(response.data.errors && response.data.errorType){
									if(response.data.errorType == 'validation'){
										for(var i in response.data.errors){
											$('#profile').find('input[name="'+i+'"]').parent().prepend('<div class="error">'+response.data.errors[i]+'</div>');
										}
										setTimeout(function(){
											$('#profile .error').remove();
										},5000);
									}else{
										common.showErrorMsg(response.data.errors[0]);
									}
								}
							break;
						}
					}
				}
			});
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
									case 'success_assign_manager_admin':
										common.showMsg(response.data);
										$('#assign_manager_dialog').dialog('close');
										options.jObjAct.replaceWith(manager_name);
									break;
									case 'error_assign_manager_admin':
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
		admin_invite:function(){
			admin.user.invite('#admin_invite_dialog');
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
		init:function(){
		},
		add_order:function(grid,model){
			if(!common.category_list || !common.dealtype_list){
				return false;
			}

			function metroOnSave(){
				if(metro_widget.isValueChanged()){
					serializeValue = metro_widget.serialize();
					if(common.isEmptyObj(serializeValue)){
						metros = serializeValue;
					}else{
						metros = {};
						for(var i in serializeValue){
							if(!metros[i]) metros[i] = [];
							metros[i] = serializeValue[i].splice(0);
						}
					}
				}
				metro_widget = undefined;
			}
			function metroOnCancel(){
				metro_widget = undefined;
			}

			function regionOnSave(){
				if(region_widget.isValueChanged()){
					regions = region_widget.serialize().splice(0);
				}
				region_widget = undefined;
			}
			function regionOnCancel(){
				region_widget = undefined;
			}

			function fill_table(table,body){
				for(var i in body){
					$tr = $('<tr>');
					$td = $('<td>');
					$td.append(body[i].label).append('<br/>').append(body[i].input);
					table.append($tr.append($td));
				}
			}

			var $d;
			var left_table = {};
			
			left_table['category'] = { 
					label:$('<label>Тип объекта</label>'),
					input:$('<select style="width:170px"></select>')
			},

			left_table['dealtype'] = {
				label:$('<label>Тип сделки</label>'),
				input:$('<select style="width:170px"></select>')
			}

			left_table['regions'] = {
				label:$('<label>Районы</label>'),
				input:$('<a href="#" onclick="return false;">Выбрать</a>')
			};

			left_table['metros']  = {
				label:$('<label>Метро</label>'),
				input:$('<a href="#" onclick="return false;">Выбрать</a>')
			} 

			left_table['price'] = {
				label:$('<label>Цена</label>'),
				input:$('<input type="text"/>')
			}

			left_table['phone'] = {
				label:$('<label>Телефон</label>'),
				input:$('<input type="text"/>')
			}
			/*
			* Виджеты метро и региона
			*/
			left_table['regions'].input.click(function(){
				if(!region_widget){
					region_widget = common.widgets.region_map({onSave:regionOnSave,onCancel:regionOnCancel});
					region_widget.init();
					region_widget.load(regions);
				}else{
					region_widget.destroy();
					region_widget = undefined;
				}
			});

			left_table['metros'].input.click(function(){
				if(!metro_widget){
					metro_widget = common.widgets.metro_map({metros:metros,onSave:metroOnSave,onCancel:regionOnSave});
					metro_widget.init();
					metro_widget.load();
				}else{
					metro_widget.destroy();
					metro_widget = undefined;
				}
			});

			var right_table = {};

			right_table['description'] = {
				label:$('<label>Описание</label>'),
				input:$('<textarea style="width:350px; height:90px"></textarea>')
			}

			if($('#add_order_dialog').length == 0){
				$d = $('<div id="add_order_dialog" style="width:400px">').dialog({autoOpen:false,width:600});
				var $left = $('<table cellspacing="0" cellpadding="0" style="float:left">');
				var $right = $('<table cellspacing="0" cellpadding="0">');

				for(var i in common.category_list){
					var $opt = $('<option value="'+common.category_list[i]+'">'+common.category_list[i]+'</option>');
					left_table['category'].input.append($opt);
				}

				for(var i in common.dealtype_list){
					var $opt = $('<option value="'+common.dealtype_list[i]+'">'+common.dealtype_list[i]+'</option>');
					left_table['dealtype'].input.append($opt);
				}

				fill_table($right,right_table);
				fill_table($left,left_table);

				var regions = [];
				var region_widget;
				var metros  = {};
				var metro_widget;

				var callback_add_order = function(add_result,data){
					switch(add_result){
						case 'success':
							common.showSuccessMsg(data);
							$d.dialog('close');
							break;
						case 'error':
							if(data.errorType == "validation")
								console.debug(data.errors);
							else if(data.errorType == "runtime")
								common.showErrorMsg(data.errors[0]);
							break;	
					}
				};


				$d.append($left);
				$d.append($right);

				$d.dialog('option','modal',true);
				$d.dialog('option','title','Добавление заявки');
				$d.dialog('option','buttons',{
					'Добавить':function(){
						var data = {};

						data['category'] = left_table['category'].input.val();
						data['deal_type']= left_table['dealtype'].input.val();
						data['price']    = left_table['price'].input.val();
						data['phone']    = left_table['phone'].input.val();
						data['description'] = right_table['description'].input.val();

						if(region_widget){
							metroOnSave();
						}
						data['regions'] = regions;

						if(metro_widget){
							regionOnSave();
						}
						data['metros'] = metros;

						model.addOrder(data,callback_add_order);
					},
					'Отмена':function(){
						$d.dialog('close');
					}
				});
				$d.dialog('open');
			}else{
				$('#add_order_dialog').dialog('open');
			}

		},
		del_orders:function(grid,model){
			var ids = [];
			var SelectedRows = grid.getSelectedRows();

			for(var i in SelectedRows){
				if(grid.getDataItem(SelectedRows[i]) && grid.getDataItem(SelectedRows[i]).id){
					ids.push(grid.getDataItem(SelectedRows[i]).id);
				}
			}
			if(ids.length){
				$d = $('<div>');
				$d.dialog({
					'title':'Вы точно желаете удалить записи?',
					'modal':true,
					'width':400,
					'buttons':{
						'Удалить':function(){
							model.delOrders(ids);
							$d.dialog('close');
						},
						'Отмена':function(){
							$d.dialog('close');
						}
					}
				});		
			}
		},
		finish_orders:function(grid,model){
			var ids = [];
			var SelectedRows = grid.getSelectedRows();

			for(var i in SelectedRows){
				if(grid.getDataItem(SelectedRows[i]) && grid.getDataItem(SelectedRows[i]).id){
					ids.push(grid.getDataItem(SelectedRows[i]).id);
				}
			}
			if(ids.length){
				$d = $('<div>');
				$d.dialog({
					'title':'Вы точно желаете завершить записи?',
					'modal':true,
					'width':400,
					'buttons':{
						'Завершить':function(){
							model.finishOrders(ids);
							$d.dialog('close');
						},
						'Отмена':function(){
							$d.dialog('close');
						}
					}
				});		
			}	
		}	
	}
}