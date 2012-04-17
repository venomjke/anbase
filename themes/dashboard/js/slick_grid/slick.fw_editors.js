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
		var metro = common.metros;

		this.init = function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'><button id=\"save_metros\">Сохранить</button><button id=\"cancel_metros\">Отмена</button>").appendTo($container);
			var $first_line  = scope.buildMetroLineWrap(metro["1"],1);
			var $second_line = scope.buildMetroLineWrap(metro["2"],2);
			var $third_line  = scope.buildMetroLineWrap(metro["3"],3);
			var $fourth_line = scope.buildMetroLineWrap(metro["4"],4);
			var $fith_line   = scope.buildMetroLineWrap(metro["5"],5);

			var $second_row = $('<div style="overflow:auto; margin:5px" />');
			$second_row.append($fourth_line);
			$second_row.append($fith_line);
	
			var $first_row = $('<div style="overflow:auto; margin:5px" />');
			$first_row.append($first_line);
			$first_row.append($second_line);
			$first_row.append($third_line);
		
			$wrapper.prepend($second_row);
			$wrapper.prepend($first_row);

			$wrapper.find('#save_metros').on('click',scope.save);
			$wrapper.find('#cancel_metros').on('click',scope.cancel);

			scope.position(args.position);
		};

		this.show = function(){
			$wrapper.show();
		}

		this.hide = function(){
			$wrapper.hide();
		}

		this.destroy = function(){
			$wrapper.remove();
		};

		this.focus = function(){
			$wrapper.focus();
		};

		this.save  = function(){
			args.commitChanges();
		}

		this.cancel = function(){
			args.cancelChanges();
		}

		this.loadValue = function(item){
			defaultValue = item.metros;

			for(var i in item.metros){
				for(var j in item.metros[i]){	
					$wrapper.find('input:checkbox').each(function(){
						if(item.metros[i][j] == $(this).val()){
							$(this).attr('checked','checked');
						}
					});	
				}
			}	
		};

		this.serializeValue = function(){
			var all_checked_metros = {};
			$wrapper.find('input:checkbox:checked').each(function(){
				var line = $(this).attr('rel');	

				if(!all_checked_metros[line])
					all_checked_metros[line] = [];

				all_checked_metros[line].push($(this).val());
					
			});
			return all_checked_metros;
		};

		this.applyValue = function(item,state){
			item.metros = state;
		};

		this.isValueChanged = function(){
			var all_checked_metros = scope.serializeValue();
			for(var i in all_checked_metros){
				if(defaultValue.hasOwnProperty(i)){	
					for(var j in all_checked_metros[i]){
						if(defaultValue[i].indexOf(all_checked_metros[i][j]) == -1){
							return true;
						}
					}
				}else{
					return true;
				}
			}
			return false;
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			}
		};
		this.position = function(position){
			$wrapper
				.css("top",position.top+10)
				.css("left",position.left+15);
		};

		this.buildMetroLineWrap = function(Line,LineNumber){
			var $metro_line_wrap = $('<div class="metro-line" style="float:left;oveflow:auto;margin:5px"/>');
			for(var i in Line){
				var $line_point_wrap = $('<div>');
				var $line_point = $('<input type="checkbox" value="'+i+'" rel="'+LineNumber+'">');
				var $line_point_label = $('<label for="">'+Line[i]+'</label>');
				$line_point_wrap.append($line_point);
				$line_point_wrap.append($line_point_label);
				$line_point_wrap.appendTo($metro_line_wrap);
			}
			return $metro_line_wrap;
		}

		this.init();
	};

	function AnbaseRegionsEditor(args){
		var defaultValue;
		var regions = common.regions;
		var scope = this;

		var $wrapper;
		var $all_checkboxes;

		this.init = function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'><button id=\"save_choose\">Сохранить</button><button id=\"cancel_choose\"> Отмена </button> </div>").appendTo($container);
			var region_list = scope.buildRegionList(regions);
			$wrapper.prepend(region_list);
			$wrapper.find('#save_choose').on('click',scope.save);
			$wrapper.find('#cancel_choose').on('click',scope.cancel);
			$all_checkboxes = $wrapper.find('input:checkbox');
			scope.position(args.position);

		};

		this.show = function(){
			$wrapper.show();
		};

		this.hide = function(){
			$wrapper.hide();
		};

		this.destroy = function(){
			$wrapper.remove();
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
			for(var i in item.regions){
				$all_checkboxes.each(function(){
					if($(this).val() == item.regions[i]) 
						$(this).attr('checked','checked');
				});
			}
		};

		this.serializeValue = function(){
			var all_checked_regions = [];
			$wrapper.find('input:checkbox:checked').each(function(){
				all_checked_regions.push($(this).val());
			});
			return all_checked_regions;
		};

		this.applyValue = function(item,state){
			item.regions = state;
		};

		this.isValueChanged = function(){
			return !common.compareArrays(scope.serializeValue(),defaultValue,"eq");	
		};

		this.validate = function(){
			return {
				valid:true,
				msg:null
			};
		};

		this.buildRegionList = function(regions){
			var $region_list = $('<table>');
			for(var i in regions){
				var $region_wrap = $('<tr>');
				var $region_label    = $('<td><label for="">'+regions[i]+'</label></td>').appendTo($region_wrap);
				var $region_checkbox = $('<td><input type="checkbox" value="'+i+'"/></td>').appendTo($region_wrap);
				$region_list.append($region_wrap);
			}
			return $region_list;
		};

		this.position = function(position){
			$wrapper
				.css('top',position.top+10)
				.css('left',position.left+20);
		};

		this.init();
	}

})(jQuery)