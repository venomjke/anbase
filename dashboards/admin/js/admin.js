var admin = {

	/*
	*	Базовый URL для выполнения запросов
	*/
	baseUrl:'',

	init:function(params){
	
		/*
		*	Установка базового URL
		*/
		admin.baseUrl = params.baseUrl || admin.baseUrl;
	},
	/*
	* Модуль, для управления разделом profile
	*/
	profile:{
		init:function(){
			admin.profile.hash_form = {};
			common.load_form(admin.profile.hash_form, 'personal');
			common.load_form(admin.profile.hash_form, 'system');
			common.load_form(admin.profile.hash_form, 'organization');
		},
		save_form:function(form){
			common.save_form(admin.profile.hash_form, form, admin.profile.save_form_callback)
		},
		save_form_callback:function(form, data){
			// отправка формы
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
	*	Модуль для управления разделом user
	*/
	user:{
		/*
		* Инициализация модуля init
		*/
		init:function(){
		},

		/**
		*
		* Назначение менеджера клиенту.
		*
		* @param object
		**/
		assign_manager:function(options){
		},
		/*
		* Отсоединяем агента от менеджера
		*/
		unbind_manager:function(options){
		},
		del_users:function(grid, model){
			var ids = [];
			var selectedRows = grid.getSelectedRows();

			for(var i in selectedRows){
				if(grid.getDataItem(selectedRows[i]) && grid.getDataItem(selectedRows[i]).id){
					ids.push(grid.getDataItem(selectedRows[i]).id);
				}
			}
			if(ids.length){
				$d = $('<div>');
				$d.dialog({
					'title': lang['admin.user.confirm_delete_users'],
					'modal':true,
					'width':'auto',
					'buttons':{
						'Удалить':function(){
							model.delUsers(ids);
							$d.dialog('close');
						},
						'Отмена':function(){
							$d.dialog('close');
						}
					}
				});
			}
		}
	},

	orders:{
		init:function(){
		},
		init_add_order_dialog:function(model){

			/*
			* [my_notice] Временное решение, перенести надо будет в базу
			*/
			var buildings = {};
			buildings[common.category_list['ORDER_CATEGORY_RESIDENTAL_REAL_ESTATE']] = ['комната','1к.кв',"2к.кв","3к.кв","4к.кв","5к.кв и более"];
			buildings[common.category_list['ORDER_CATEGORY_COMMERCIAL_REAL_ESTATE']] = ['Офис',"Общепит","Магазин","Производство","Склад"];
			buildings[common.category_list['ORDER_CATEGORY_COUNTRY_REAL_ESTATE']] = ['Коттедж',"Дом","Участок","Часть дома"]; 

			$d = $('#add_order_dialog');
			$d.dialog({
				autoOpen:false, 
				width:600, 
				closeOnEscape:false
			});

			if( ! common.category_list || ! common.dealtype_list){
				common.debug('');
				return false;
			}


			var $d;
			var $category = $('#add_order_category');
			var $dealtype = $('#add_order_dealtype');
			var $regions  = $('#add_order_regions');
			var $metros   = $('#add_order_metros');
			var $price    = $('#add_order_price');
			var $phone    = $('#add_order_phone');
			var $description= $('#add_order_description');
			var $category = $('#add_order_category');
			var $dealtype = $('#add_order_dealtype');
			var $building = $('#add_order_building');
			var $source   = $('#add_order_source');


			var choose_buildings = function(category){
				$building.empty();
				for(var i in buildings[category]){
					var $opt = $('<option value="'+buildings[category][i]+'">'+buildings[category][i]+'</option>');
					$building.append($opt);
				}
			}

			var regions = [];
			var region_widget;
			var metros  = {};
			var metro_widget;

			for(var i in common.category_list){
				var $opt = $('<option value="'+common.category_list[i]+'">'+common.getCategoryName(common.category_list[i])+'</option>');
				if(common.settings_org.default_category == common.category_list[i]){
					$opt.attr('selected','selected');
					choose_buildings(common.category_list[i]);
				}
				$category.append($opt);
				$category.change(function(){choose_buildings($(this).val())});
			}

			for(var i in common.dealtype_list){
				var $opt = $('<option value="'+common.dealtype_list[i]+'">'+common.getDealtypeName(common.dealtype_list[i])+'</option>');
				if(common.settings_org.default_dealtype == common.dealtype_list[i])
					$opt.attr('selected','selected');
				$dealtype.append($opt);
			}

			/*
			* Виджеты  района
			*/

			function regionOnSave(){
				if(region_widget.isValueChanged()){
					regions = region_widget.serialize().splice(0);
				}
				region_widget = undefined;
			}
			function regionOnCancel(){
				region_widget = undefined;
			}

			$regions.click(function(){
				if(!region_widget){
					region_widget = common.widgets.region_map({onSave:regionOnSave,onCancel:regionOnCancel});
					region_widget.init();
					region_widget.load(regions);
				}else{
					region_widget.destroy();
					region_widget = undefined;
				}
			});

			/*
			* Виджет метро
			*/
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
			$metros.click(function(){
				if(!metro_widget){
					metro_widget = common.widgets.metro_map({metros:metros,onSave:metroOnSave,onCancel:regionOnSave});
					metro_widget.init();
					metro_widget.load();
				}else{
					metro_widget.destroy();
					metro_widget = undefined;
				}
			});

			function clear_form(){
				$category.val(common.settings_org.default_category);
				$category.trigger('change');
				$dealtype.val(common.settings_org.default_dealtype);
				$price.val('');
				$phone.val('');
				$description.val('');
				$building.val('');
				$source.val('');
				regions = [];
				metros = {};
			}

			var callback_add_order = function(add_result, data){
				switch(add_result){
					case 'success':
						common.showSuccessMsg(data);
						clear_form();
						$d.dialog('close');
						break;
					case 'error':
						if(data.errorType == "validation")
							for(var i in data.errors)
								common.showNotyErrorMsg(data.errors[i])
						else if(data.errorType == "runtime")
							common.showNotyErrorMsg(data.errors[0]);
						break;	
				}
			}

			$d.dialog('option','modal', true);
			$d.dialog('option','title', 'Добавление заявки');
			$d.dialog('option','buttons', {
				'Добавить':function(){
					var data = {};

					var txt = $description.val();
					var buildings = $building.val();
					var s = '';
					for(var i in buildings) s += buildings[i]+' ';
					$description.val(s+' '+txt); 

					data['category'] = $category.val();
					data['deal_type']= $dealtype.val();;
					data['price']    = $price.val();
					data['phone']    = $phone.val();
					data['description'] = $description.val();
					data['source']   = $source.val();

					if(region_widget){
						regionOnSave();
					}
					data['regions'] = regions;

					if(metro_widget){
						metroOnSave();
					}
					data['metros'] = metros;

					model.addOrder(data, callback_add_order);
				},
				'Отмена':function(){
					$d.dialog('close');
				}
			});
		},

		add_order:function(){
			$('#add_order_dialog').dialog('open');
		},

		print_orders:function(grid,model){
			var ids = [];
			var selectedRows = grid.getSelectedRows();

			for(var i in selectedRows){
				if(grid.getDataItem(selectedRows[i]) && grid.getDataItem(selectedRows[i]).id){
					ids.push(grid.getDataItem(selectedRows[i]).id);
				}
			}
			if(ids.length){
				model.printOrders(ids);
			}
		},

		del_orders:function(grid,model){
			var ids = [];
			var selectedRows = grid.getSelectedRows();

			for(var i in selectedRows){
				if(grid.getDataItem(selectedRows[i]) && grid.getDataItem(selectedRows[i]).id){
					ids.push(grid.getDataItem(selectedRows[i]).id);
				}
			};

			if(ids.length){
				$d = $('<div>');
				$d.dialog({
					'title': lang['admin.orders.confirm_delete_orders'],
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

		finish_orders:function(grid, model){
			var ids = {};
			var cnt = 0;
			var selectedRows = grid.getSelectedRows();
			var $promptDialog = $('<div></div>');

			function show_prompt(){
				if(selectedRows.length != cnt){
					var item = selectedRows[cnt];
					var itemData = grid.getDataItem(item);

					if(itemData && itemData.id){
						$promptDialog.dialog({
							'title': lang['prompt_finish_order_set_status'] + ' #:' + itemData.id,
							'modal':true,
							'width':'auto',
							'autoOpen':false,
							'buttons': {
								'Сдал': function(){
									ids[itemData.id] = common.finishstatus_list['ORDER_FINISH_STATUS_SUCCESS'];
									$promptDialog.dialog('close');
									show_prompt();
								},
								'Не сдал': function(){
									ids[itemData.id] = common.finishstatus_list['ORDER_FINISH_STATUS_FAILURE'];
									$promptDialog.dialog('close');
									show_prompt();
								}
							}
						});
						$promptDialog.dialog('open');
					}
					++cnt;
				}else{
					finish();
				}
			}

			function finish(){
				ids.length = cnt;

				if(ids.length){
					$d = $('<div></div>');
					$d.dialog({
						'title':lang['prompt_finish_orders'],
						'modal':true,
						'width':400,
						'buttons':{
							'Завершить': function(){
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

			show_prompt();	
		},
		restore_orders:function(grid,model){
			var ids = [];
			var selectedRows = grid.getSelectedRows();

			for(var i in selectedRows){
				if(grid.getDataItem(selectedRows[i]) && grid.getDataItem(selectedRows[i]).id){
					ids.push(grid.getDataItem(selectedRows[i]).id);
				}
			}
			if(ids.length){
				$d = $('<div>');
				$d.dialog({
					'title':'Вы точно желаете возобновить записи?',
					'modal':true,
					'width':'auto',
					'buttons':{
						'Возобновить':function(){
							model.restoreOrders(ids);
							$d.dialog('close');
						},
						'Отмена':function(){
							$d.dialog('close');
						}
					}
				});		
			}	
		}	
	},
	invites:{
		init:function(){

		},
		del_invites:function(grid,model){
			var ids = [];
			var selectedRows = grid.getSelectedRows();

			for(var i in selectedRows){
				if(grid.getDataItem(selectedRows[i]) && grid.getDataItem(selectedRows[i]).id){
					ids.push(grid.getDataItem(selectedRows[i]).id);
				}
			}
			if(ids.length){
				$d = $('<div>');
				$d.dialog({
					'title':'Вы точно желаете удалить записи?',
					'modal':true,
					'width':'auto',
					'buttons':{
						'Удалить':function(){
							model.delInvites(ids);
							$d.dialog('close');
						},
						'Отмена':function(){
							$d.dialog('close');
						}
					}
				});		
			}
		},
		fill_table:function(table,body){
			for(var i in body){
				$tr = $('<tr>');
				$td = $('<td>');
				$td.append(body[i].label).append('<br/>').append(body[i].input);
				table.append($tr.append($td));
			}
		},
		reset_table:function(table){
			for(var i in table){
				table[i].input.val('');
			}
		},
		callback:function($d,code,data,table){
			switch(code){
				case 'success':
					$d.dialog('close');
					common.showSuccessMsg(data);
					admin.invites.reset_table(table);
					break;
				case 'error':
					if(data.errorType == "runtime"){
						if(data.errors[0])
							common.showNotyErrorMsg(data.errors[0]);
					}else if(data.errorType == "validation"){
						for(var i in data.errors){
							if(data.errors[i])
								common.showNotyErrorMsg(data.errors[i]);
						}
					}
					break;
			}
			$(".ui-dialog-buttonpane button").button("enable");
		},
		add_agent:function(grid,model){
			if(!common.staff_list){
				return false;
			}
			var callback = function(code,data){
				admin.invites.callback($d,code,data,table);
			};

			if($('#add_agent_dialog').length == 0){
				var $d = $('<div id="add_agent_dialog">').dialog({autoOpen:false,width:'auto',modal:true});
				var $form = $('<table cellpadding="0" align="center" cellspacing="0"></table>');
				var table = {};

				table['managers'] = {
					'label':$('<label>Менеджер</label>'),
					'input':$('<select></select>')
				};

				table['email'] = {
					'label':$('<label>Email</label>'),
					'input':$('<input type="text" />')
				};

				admin.invites.fill_table($form,table);

				table['managers'].input.append('<option value="">- Нету -</option>');
				for(var i in common.staff_list){
					var empl = common.staff_list[i];

					if(empl.role==common.role_list['USER_ROLE_MANAGER']){
						var $opt = $('<option value="'+empl.id+'">'+empl.last_name+' '+empl.name+' '+empl.middle_name+'</option>');
						table['managers'].input.append($opt);
					}
				}

				$d.append($form);
				$d.dialog('option','title','Инвайт для агента');
				$d.dialog('option','buttons',{
					'Добавить':function(){
						var data = {};
						data['manager_id'] = table['managers'].input.val();
						data['email']	   = table['email'].input.val();
						$(".ui-dialog-buttonpane button").button("disable");
						model.add_agent(data,callback);
					},
					'Отмена':function(){
						$d.dialog('close');
					}
				});
				$d.dialog('open');
			}else{
				$('#add_agent_dialog').dialog('open');
			}
		},
		add_manager:function(grid,model){
			if(!common.staff_list){
				return false;
			}
			var callback = function(code,data){
				admin.invites.callback($d,code,data,table);
			};

			if($('#add_manager_dialog').length == 0){
				var $d = $('<div id="add_manager_dialog">').dialog({autoOpen:false,width:'auto',modal:true});
				var $form = $('<table cellpadding="0" align="center" cellspacing="0"></table>');
				var table = {};

				table['email'] = {
					'label':$('<label>Email</label>'),
					'input':$('<input type="text" />')
				};

				admin.invites.fill_table($form,table);

				$d.append($form);
				$d.dialog('option','title','Инвайт для менеджера');
				$d.dialog('option','buttons',{
					'Добавить':function(){
						var data = {};
						data['email']	   = table['email'].input.val();
						$(".ui-dialog-buttonpane button").button("disable");
						model.add_manager(data,callback);
					},
					'Отмена':function(){
						$d.dialog('close');
					}
				});
				$d.dialog('open');
			}else{
				$('#add_manager_dialog').dialog('open');
			}
		},
		add_admin:function(grid,model){
			if(!common.staff_list){
				return false;
			}
			var callback = function(code,data){
				admin.invites.callback($d,code,data,table);
			};

			if($('#add_admin_dialog').length == 0){
				var $d = $('<div id="add_admin_dialog">').dialog({autoOpen:false,width:'auto',modal:true});
				var $form = $('<table cellpadding="0" align="center" cellspacing="0"></table>');
				var table = {};

				table['email'] = {
					'label':$('<label>Email</label>'),
					'input':$('<input type="text" />')
				};

				admin.invites.fill_table($form,table);

				$d.append($form);
				$d.dialog('option','title','Инвайт для админа');
				$d.dialog('option','buttons',{
					'Добавить':function(){
						var data = {};
						data['email']	   = table['email'].input.val();
						$(".ui-dialog-buttonpane button").button("disable");
						model.add_admin(data,callback);
					},
					'Отмена':function(){
						$d.dialog('close');
					}
				});
				$d.dialog('open');
			}else{
				$('#add_admin_dialog').dialog('open');
			}
		}
	},
	settings:{
		init:function(){
			admin.settings.hash_form = {};
			common.load_form(admin.settings.hash_form,'cols');
			common.load_form(admin.settings.hash_form,'default');
		},
		save_form:function(form){
			common.save_form(admin.settings.hash_form,form,admin.settings.save_form_callback)
		},
		save_form_callback:function(form,data){
			/*
			* Отправляем форму.
			*/
			$.ajax({
				url:admin.baseUrl+'/edit',
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
							case 'success_edit_settings':
								common.showSuccessMsg(response.data);
								for(var i in data){
									admin.settings.hash_form[form][i] = data[i];
								}
							break;
							case 'error_edit_profile':
								if(response.data.errors && response.data.errorType){
									if(response.data.errorType == 'validation'){
										for(var i in response.data.errors){
											$('#settings').find('input[name="' + i + '"]').parent().prepend('<div class="error">' + response.data.errors[i] + '</div>');
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
		},
		switch:function(jinput){
			common.switch(jinput,1,0);
		}
	}
}