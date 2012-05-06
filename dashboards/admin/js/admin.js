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
	}
}