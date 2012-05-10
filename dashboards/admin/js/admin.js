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
			common.load_form(admin.profile.hash_form,'personal');
			common.load_form(admin.profile.hash_form,'system');
			common.load_form(admin.profile.hash_form,'organization');
		},
		save_form:function(form){
			common.save_form(admin.profile.hash_form,form,admin.profile.save_form_callback)
		},
		save_form_callback:function(form,data){
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
	*	Модуль для управления разделом user
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
		del_users:function(grid,model){
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
					'width':300,
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
			$d = $('#add_order_dialog');
			$d.dialog({autoOpen:false,width:600});

			if(!common.category_list || !common.dealtype_list){
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

			var regions = [];
			var region_widget;
			var metros  = {};
			var metro_widget;

			for(var i in common.category_list){
				var $opt = $('<option value="'+common.category_list[i]+'">'+common.category_list[i]+'</option>');
				if(common.settings_org.default_category == common.category_list[i])
					$opt.attr('selected','selected');
				$category.append($opt);
			}

			for(var i in common.dealtype_list){
				var $opt = $('<option value="'+common.dealtype_list[i]+'">'+common.dealtype_list[i]+'</option>');
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
				$dealtype.val(common.settings_org.default_dealtype);
				$price.val('');
				$phone.val('');
				$description.val('');
				$building.val('');
				regions = [];
				metros = {};
			}

			var callback_add_order = function(add_result,data){
				switch(add_result){
					case 'success':
						common.showSuccessMsg(data);
						clear_form();
						$d.dialog('close');
						break;
					case 'error':
						if(data.errorType == "validation")
							console.debug(data.errors);
						else if(data.errorType == "runtime")
							common.showErrorMsg(data.errors[0]);
						break;	
				}
			}

			$d.dialog('option','modal',true);
			$d.dialog('option','title','Добавление заявки');
			$d.dialog('option','buttons',{
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
					data['description'] = $description.val();;

					if(region_widget){
						regionOnSave();
					}
					data['regions'] = regions;

					if(metro_widget){
						metroOnSave();
					}
					data['metros'] = metros;

					model.addOrder(data,callback_add_order);
				},
				'Отмена':function(){
					$d.dialog('close');
				}
			});
		},
		add_order:function(){
			$('#add_order_dialog').dialog('open');
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
		},
		restore_orders:function(grid,model){
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
					'title':'Вы точно желаете возобновить записи?',
					'modal':true,
					'width':400,
					'buttons':{
						'Завершить':function(){
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
					'width':300,
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
		},
		add_agent:function(grid,model){
			if(!common.staff_list){
				return false;
			}
			var callback = function(code,data){
				admin.invites.callback($d,code,data,table);
			};

			if($('#add_agent_dialog').length == 0){
				var $d = $('<div id="add_agent_dialog">').dialog({autoOpen:false,width:280,modal:true});
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

					if(empl.role=="Менеджер"){
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
				var $d = $('<div id="add_manager_dialog">').dialog({autoOpen:false,width:280,modal:true});
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

			if($('#add_manager_dialog').length == 0){
				var $d = $('<div id="add_manager_dialog">').dialog({autoOpen:false,width:280,modal:true});
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
						model.add_admin(data,callback);
					},
					'Отмена':function(){
						$d.dialog('close');
					}
				});
				$d.dialog('open');
			}else{
				$('#add_manager_dialog').dialog('open');
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
											$('#settings').find('input[name="'+i+'"]').parent().prepend('<div class="error">'+response.data.errors[i]+'</div>');
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