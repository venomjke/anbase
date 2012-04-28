/*
* Редакторы от flyweb studio для slickgrid.
* Редакторы, размещенные здесь преимущественно все для anbase
*/

(function($){

	$.extend(true,window,{
		"Slick":{
			"Editors":{
				"AnbaseCategory":AnbaseCategoryEditor,
				"AnbaseDealType":AnbaseDealTypeEditor,
				"AnbaseRegions":AnbaseRegionsEditor,
				"AnbaseMetros":AnbaseMetrosEditor
			}
		}
	});


	function AnbaseCategoryEditor(args){

		var $select;
		var defaultValue;
		var scope = this;

		this.init = function(){
			$select = $("<SELECT tabindex='0' class='editor-category'> <OPTION value='Жилая'>Жилая</OPTION><OPTION>Загородная</OPTION><OPTION>Коммерческая</OPTION></SELECT>");
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
			$select = $('<SELECT tabindex="0" class="deal_type"><OPTION value="Куплю">Куплю</OPTION><OPTION value="Продам">Продам</OPTION><OPTION value="Сниму">Сниму</OPTION><OPTION value="Сдам">Сдам</OPTION></SELECT>');
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
					widget = common.widgets.metro_map({metros:defaultValue,onClose:scope.save});
					widget.init();
					widget.load();
				}
			});

			$wrapper.find('#metro-list').click(function(){
				if(!widget){
					widget = common.widgets.metro_list({metros:defaultValue,onClose:scope.save});
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
			$wrapper.focus();
			if(widget)
				widget.focus();
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
		};

		this.isValueChanged = function(){
			if(!widget){
				return false;
			}
			return widget.isValueChanged();
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

		this.init = function(){
			widget = common.widgets.region_map();
			widget.init();
		};

		this.show = function(){
			widget.show();
		};

		this.hide = function(){
			widget.hide();
		};

		this.destroy = function(){
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
			widget.load(item.regions);
		};

		this.serializeValue = function(){
			return widget.serialize();
		};

		this.applyValue = function(item,state){
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
		};

		this.isValueChanged = function(){
			return widget.isValueChanged();		
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