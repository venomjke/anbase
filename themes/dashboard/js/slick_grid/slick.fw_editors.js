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
		if(!common.staff_list){
			this.cancel();
		}

		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $("<SELECT tabindex='0' style=\"width:100%;\" class='editor-category'><option value=\"-1\">- Нету -</option></SELECT>");
			for(var i in common.staff_list){
				var empl = common.staff_list[i];

				if(empl.role == common.role_list['USER_ROLE_MANAGER']){
					var $opt = $('<option value="'+empl.id+'">'+common.make_official_name(empl.name,empl.middle_name,empl.last_name)+'</option>');
					$select.append($opt);
				}
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

	function AnbaseRoleEditor(args){
		if(!common.role_list){
			this.cancel();
		}

		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $("<SELECT tabindex='0' style=\"width:100%;\" class='editor-category'></SELECT>");
			for(var i in common.role_list){
				var $opt = '<option value="'+common.role_list[i]+'">'+common.role_list[i]+'</option>';
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
		if(!common.staff_list){
			this.cancel();
			console.log('Ошибка: список сотрудников не задан');
		}

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

				for(var i in common.staff_list){
					var employee = common.staff_list[i];
					var $opt = $('<option value="'+employee.id+'">'+employee.last_name+' '+employee.name+' '+employee.middle_name+'</option>');
					$select.append($opt);
				}


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
		var $any_metro_checkbox;
		var defaultValue;
		var scope = this;
		var widget;

		var isChangedAnyMetro = false;
		var isChangedMetroList = false;

		this.init = function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'><button id='metro-map'>По карте</button><button id='metro-list'>По списку</button></div>").appendTo($container);

			$any_metro_label    = $('<label for="any_metro_checkbox">Любое</label>')
			$any_metro_checkbox = $('<input id="any_metro_checkbox" type="checkbox" value="'+args.item.any_metro+'"/>');
			if(args.item.any_metro == "1"){
				$any_metro_checkbox.attr('checked','checked');
			}

			$any_metro_checkbox.click(function(){
				if($(this).val()=="1"){
					$(this).val("0");
				}else{
					$(this).val("1");
				}
			});

			$wrapper.append($any_metro_checkbox);
			$wrapper.append($any_metro_label);

			$wrapper.find('#metro-map').click(function(){
				if(!widget){
					widget = common.widgets.metro_map({metros:defaultValue,onSave:scope.save,onCancel:scope.cancel});
					widget.init();
					widget.load();
				}
			});

			$wrapper.find('#metro-list').click(function(){
				if(!widget){
					widget = common.widgets.metro_list({metros:defaultValue,onSave:scope.save,onCancel:scope.cancel});
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

			if(isChangedMetroList){
				delete item.metros;
				item.metros = {};
				for(var i in state){
					if(state[i].length)
						item.metros[i] = state[i].slice(0);
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
			}

			if(isChangedAnyMetro){
				item.any_metro = $any_metro_checkbox.val();				
			}
		};

		this.isValueChanged = function(){
			if($any_metro_checkbox.val() != args.item.any_metro){
				isChangedAnyMetro = true;
			}

			if(widget && widget.isValueChanged()){
				isChangedMetroList = true;
			}

			return isChangedAnyMetro || isChangedMetroList;
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
		var $wrapper;
		var $any_region_checkbox;

		var isChangedAnyRegion = false;
		var isChangedRegionList = false;

		this.init = function(){

			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'><button id='region-map'>По карте</button></div>").appendTo($container);

			$any_region_label    = $('<label for="any_region_checkbox">Любой</label>')
			$any_region_checkbox = $('<input id="any_region_checkbox" type="checkbox" value="'+args.item.any_region+'"/>');
			if(args.item.any_region == "1"){
				$any_region_checkbox.attr('checked','checked');
			}

			$any_region_checkbox.click(function(){
				if($(this).val()=="1"){
					$(this).val("0");
				}else{
					$(this).val("1");
				}
			});

			$wrapper.append($any_region_checkbox);
			$wrapper.append($any_region_label);

			$wrapper.find('#region-map').click(function(){
				if(!widget){
					widget = common.widgets.region_map({onSave:scope.save,onCancel:scope.cancel});
					widget.init();
					widget.load(args.item.regions);
				}
			});

			$wrapper.css('top',args.position.top);
			$wrapper.css('left',args.position.left);
		};

		this.show = function(){
			$wrapper.show();
			if(widget)
				widget.show();
		};

		this.hide = function(){
			$wrapper.hide();
			if(widget)
				widget.hide();
		};

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
			defaultValue = item.regions;
		};

		this.serializeValue = function(){
			if(widget){
				return widget.serialize();				
			}
			return {};
		};

		this.applyValue = function(item,state){
			if(isChangedRegionList){
				delete item.regions;
				item.regions = widget.serialize().slice(0);
				/*
				* [my_notice: Не самое лучшее решение на мой взгляд, нужно подумать еще]
				* В чем суть.
				* Когда мы обнуляем текущий список выбранных метро, то нужно "что-то" отправить на сервер, что бы там
				* удалить список выбранных метро, и ничего не записывать.
				*/
				if(item.regions.length == 0){
					item.regions = [0];
				}				
			}

			if(isChangedAnyRegion){
				item.any_region = $any_region_checkbox.val();
			}
		};

		this.isValueChanged = function(){
			if($any_region_checkbox.val() != args.item.any_region){
				isChangedAnyRegion = true;
			}

			if(widget && widget.isValueChanged()){
				isChangedRegionList = true
			}
			return isChangedAnyRegion || isChangedRegionList;		
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