/*
* Редакторы от flyweb studio для slickgrid.
* Редакторы, размещенные здесь преимущественно все для anbase
*/

(function($){

	$.extend(true,window,{
		"Slick":{
			"Editors":{
				"AnbaseAgent":AnbaseAgentEditor,
				"AnbaseCategory":AnbaseCategoryEditor,
				"AnbaseDealType":AnbaseDealTypeEditor,
				"AnbaseRegions":AnbaseRegionsEditor,
				"AnbaseMetros":AnbaseMetrosEditor,
				"AnbaseRole":AnbaseRoleEditor,
				"AnbaseUser":AnbaseUserEditor
			}
		}
	});

	function AnbaseUserEditor(args){

		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $("<SELECT tabindex='0' style=\"width:100%;\" class='editor-category'></SELECT>");
			/*
			* Менеджера можно назначить только агенту, для остальных ролей редактор не открываем.
			*/
			if(args.item.role != common.role_list['USER_ROLE_MANAGER']){
				$select.append('<option value=\"-1\">- Нету -</option>');
				for(var i in common.staff_list){
					var empl = common.staff_list[i];

					if(empl.role == common.role_list['USER_ROLE_MANAGER']){
						var $opt = $('<option value="'+empl.id+'">'+common.make_official_name(empl.name,empl.middle_name,empl.last_name)+'</option>');
						$select.append($opt);
					}
				}

				$select.appendTo(args.container);
				$select.focus();	
			}
		};

		this.destroy = function(){
			$select.remove();
		};

		this.focus = function(){
			$select.focus();
		};
		this.loadValue = function(item){
			$select.val((defaultValue = item[args.column.field]));
			$select.select();
		};

		this.serializeValue = function(){
			return $select.val();
		};

		this.applyValue = function(item,state){
			item[args.column.field] = state;
		};

		this.isValueChanged = function(){
			var val = $select.val();
			return ($select.val() != defaultValue );
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			};
		};
	
		this.init();
	}

	function AnbaseRoleEditor(args){

		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $("<SELECT tabindex='0' style=\"width:100%;\" class='editor-category'></SELECT>");
			for(var i in common.role_list){
				var $opt = '<option value="'+common.role_list[i]+'">'+common.getRoleName(common.role_list[i])+'</option>';
				$select.append($opt);
			}
			$select.appendTo(args.container);
			$select.focus();
		};

		this.destroy = function(){
			$select.remove();
		};

		this.focus = function(){
			$select.focus();
		};
		this.loadValue = function(item){
			$select.val((defaultValue = item[args.column.field]));
			$select.select();
		};

		this.serializeValue = function(){
			return $select.val();
		};

		this.applyValue = function(item,state){
			item[args.column.field] = state;
		};

		this.isValueChanged = function(){
			return ($select.val() != defaultValue );
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			};
		};

		this.init();
	}

	function AnbaseAgentEditor(args){

		var $wrapper;
		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			var $container = $('body');
				$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'>").appendTo($container);
				$apply_btn = $('<button>Применить</button>');
				$cancel_btn = $('<button>Отмена</button>');

				$apply_btn.click(scope.save);
				$cancel_btn.click(scope.cancel);

				$select = $('<select style="width:200px">');

				var $nobody_opt = $('<option value="-1"></option>');
				$select.append($nobody_opt);

				var manager_agents = {};
				
				for(var i in common.staff_list){
					var empl = common.staff_list[i];
					if(empl.role == common.role_list['USER_ROLE_MANAGER']){
						/*
						* Если список агентов менеджера уже определен, то просто до определяем менеджера.
						*/
						if(manager_agents[empl.id]){
							manager_agents[empl.id].user = empl;
						}else{
							manager_agents[empl.id] = {};
							manager_agents[empl.id].user = empl;
							manager_agents[empl.id].agents = [];
						}

					}else{

						if(empl.manager_id){
							/*
							* Присоединяем агента к менеджеру
							*/
							if(manager_agents[empl.manager_id]){
								manager_agents[empl.manager_id].agents.push(empl);
							}else{
								manager_agents[empl.manager_id]={};
								manager_agents[empl.manager_id].agents = [];
								manager_agents[empl.manager_id].agents.push(empl);
							}

						}else{
							/*
							* Задаем просто агента, который без всех работает
							*/
							manager_agents[empl.id] = {};
							manager_agents[empl.id].user = empl;
						}

					}
				}

				for(var i in manager_agents){
					var empl = manager_agents[i].user;
					if(empl.role == common.role_list['USER_ROLE_MANAGER']){
						var $opt = $('<option value="'+empl.id+'" style="font-weight:bold;">->'+empl.last_name+' '+empl.name+' '+empl.middle_name+'</option>');
						$select.append($opt);
						for(var j in manager_agents[i].agents){
							var agent = manager_agents[i].agents[j];
							var $agent_opt = $('<option value="'+agent.id+'"> &nbsp&nbsp&nbsp&nbsp&nbsp'+agent.last_name+' '+agent.name+' '+agent.middle_name+'</option>');
							$select.append($agent_opt);
						}
					}else{
						var $opt = $('<option value="'+empl.id+'"> '+empl.last_name+' '+empl.name+' '+empl.middle_name+'</option>');
						$select.append($opt);
					}
				}

				/*
				for(var i in common.staff_list){
					var employee = common.staff_list[i];
					var $opt = $('<option value="'+employee.id+'">'+employee.last_name+' '+employee.name+' '+employee.middle_name+'</option>');
					$select.append($opt);
				}*/



				$wrapper.append($select);
				$wrapper.append($apply_btn);
				$wrapper.append($cancel_btn);

				$wrapper.center();
		}

		this.save = function(){
			args.commitChanges();
		}

		this.cancel = function(){
			args.cancelChanges();
		}

		this.hide = function(){
			$wrapper.hide();
		}

		this.show = function(){
			$wrapper.show();
		}

		this.destroy = function(){
			$wrapper.remove();
		}

		this.focus = function(){
			$select.focus();
		}

		this.loadValue = function(item){
			defaultValue = !item.user_id?-1:item.user_id;

			$select.find('option[value='+defaultValue+']').attr('selected','selected');
		}

		this.serializeValue = function(){
			return $wrapper.find('option:selected').val();
		}

		this.applyValue = function(item,state){
			item.user_id = state;
			if(state != -1){
				for(var i in common.staff_list){
					if(common.staff_list[i].id == state){
						item.user_name = common.staff_list[i].name;
						item.user_middle_name = common.staff_list[i].middle_name;
						item.user_last_name = common.staff_list[i].last_name;
					}
				}	
			}	
			
		}

		this.isValueChanged = function(){
			return defaultValue != $select.find('option:selected').val();
		}

		this.validate = function(){
			return {
				valid:true,
				msg:null
			}
		}

		this.init();
	}
	function AnbaseCategoryEditor(args){

		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $("<SELECT tabindex='0' style=\"width:100%;\" class='editor-category'> <OPTION value='Жилая'>Жилая</OPTION><OPTION>Загородная</OPTION><OPTION>Коммерческая</OPTION></SELECT>");
			$select.appendTo(args.container);
			$select.focus();
		};

		this.destroy = function(){
			$select.remove();
		};

		this.focus = function(){
			$select.focus();
		};
		this.loadValue = function(item){
			$select.val((defaultValue = item[args.column.field]));
			$select.select();
		};

		this.serializeValue = function(){
			return $select.val();
		};

		this.applyValue = function(item,state){
			item[args.column.field] = state;
		};

		this.isValueChanged = function(){
			return ($select.val() != defaultValue );
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			};
		};

		this.init();
	};

	function AnbaseDealTypeEditor(args){
		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $('<SELECT tabindex="0" style=\"width:100%;\" class="deal_type"><OPTION value="Куплю">Куплю</OPTION><OPTION value="Продам">Продам</OPTION><OPTION value="Сниму">Сниму</OPTION><OPTION value="Сдам">Сдам</OPTION></SELECT>');
			$select.appendTo(args.container);
			$select.focus();
		};

		this.destroy = function(){
			$select.remove();
		};

		this.focus = function(){
			$select.select();
		};

		this.loadValue = function(item){
			$select.val((defaultValue = item[args.column.field]));
		};

		this.serializeValue = function(){
			return $select.val();
		};

		this.applyValue = function(item,state){
			item[args.column.field] = state;
		};

		this.isValueChanged = function(){
			return defaultValue != $select.val();
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			};
		};

		this.init();	
	};

	function AnbaseMetrosEditor(args){
		var $wrapper;
		var defaultValue;
		var scope = this;
		var widget;

		this.init = function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'><button id='metro-map'>По карте</button><button id='metro-list'>По списку</button></div>").appendTo($container);

			$wrapper.find('#metro-map').click(function(){
				if(!widget){
					widget = common.widgets.metro_map({metros:defaultValue,onSave:scope.save,onCancel:scope.cancel,needAnyMetro:true,any_metro:args.item.any_metro});
					widget.init();
					widget.load();
				}
			});

			$wrapper.find('#metro-list').click(function(){
				if(!widget){
					widget = common.widgets.metro_list({metros:defaultValue,onSave:scope.save,onCancel:scope.cancel,needAnyMetro:true,any_metro:args.item.any_metro});
					widget.init();
					widget.load();
				}
			});

			$wrapper.css('top',args.position.top);
			$wrapper.css('left',args.position.left);
		};

		this.show = function(){
			$wrapper.show();
			if(widget)
				widget.show();
		}

		this.hide = function(){
			$wrapper.hide();
			if(widget)
				widget.hide();
		}

		this.destroy = function(){
			$wrapper.remove();
			if(widget)
				widget.destroy();
		};

		this.focus = function(){
		};

		this.save  = function(){
			args.commitChanges();
		}

		this.cancel = function(){
			args.cancelChanges();
		}

		this.loadValue = function(item){
			defaultValue     = item.metros;	
		};

		this.serializeValue = function(){
			if(widget)
				return widget.serialize();
			return {};
		};

		this.applyValue = function(item,state){

			var selected_metros = state.selected_metros;


			delete item.metros;
			item.metros = {};
			for(var i in selected_metros){
				if(selected_metros[i].length)
					item.metros[i] = selected_metros[i].slice(0);
			}
			/*
			* [my_notice: Не самое лучшее решение на мой взгляд, нужно подумать еще]
			* В чем суть.
			* Когда мы обнуляем текущий список выбранных метро, то нужно "что-то" отправить на сервер, что бы там
			* удалить список выбранных метро, и ничего не записывать.
			*/
			if(common.isEmptyObj(item.metros)){
				item.metros = {"0":[0]};
			}


			item.any_metro = state.any_metro;
		};

		this.isValueChanged = function(){
			if(widget){
				return widget.isValueChanged();
			}
			return false;
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			}
		};

		this.init();
	};

	function AnbaseRegionsEditor(args){
		
		var defaultValue;
		var scope = this;
		var widget;
		//var $wrapper;

		this.init = function(){

			var $container = $('body');
			//$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'><button id='region-map'>По карте</button></div>").appendTo($container);

			if(!widget){
				widget = common.widgets.region_map({onSave:scope.save,onCancel:scope.cancel,needAnyRegion:true});
				widget.init();
				widget.load();
			}
			

			//$wrapper.css('top',args.position.top);
			//$wrapper.css('left',args.position.left);
		};

		this.show = function(){
			//$wrapper.show();
			if(widget)
				widget.show();
		};

		this.hide = function(){
			//$wrapper.hide();
			if(widget)
				widget.hide();
		};

		this.destroy = function(){
			//$wrapper.remove();
			if(widget)
				widget.destroy();
		};

		this.focus = function(){
		};

		this.save  = function(){
			args.commitChanges();
		}

		this.cancel = function(){
			args.cancelChanges();
		}

		this.loadValue = function(item){
			defaultValue = item.regions;
			if(widget){
				widget.load(item.regions,item.any_region);
			}
		};

		this.serializeValue = function(){
			if(widget){
				return widget.serialize();				
			}
			return {};
		};

		this.applyValue = function(item,state){
			var selected_regions = state.selected_regions;

			delete item.regions;
			item.regions = selected_regions.slice(0);

			/*
			* [my_notice: Не самое лучшее решение на мой взгляд, нужно подумать еще]
			* В чем суть.
			* Когда мы обнуляем текущий список выбранных метро, то нужно "что-то" отправить на сервер, что бы там
			* удалить список выбранных метро, и ничего не записывать.
			*/
			if(item.regions.length == 0){
				item.regions = [0];
			}				

			item.any_region = state.any_region;
		};

		this.isValueChanged = function(){
			if(widget){
				return widget.isValueChanged();
			}
			return ;		
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			};
		};

		this.init();
	}

})(jQuery)