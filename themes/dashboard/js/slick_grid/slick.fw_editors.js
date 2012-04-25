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
		var $img;
		var $map;
		var $metro_wrapper;

		var defaultValue;
		var scope = this;
		var metro = common.metros;
		var metros_images = common.metros_images;
		var metro_normal  = metros_images['metro-normal'];
		var selected_station = {};

		Slick.Editors.AnbaseMetros.checkpoint_click = function(event,$checkpoint){
			var metro_id = $checkpoint.data('metro_id');
			var $area = $('#station-'+metro_id);

			selected_station[$area.data('line')].splice(selected_station[$area.data('line')].indexOf($area.data('metro_id')),1);
			if(!selected_station[$area.data('line')].length){
				delete selected_station[$area.data('line')];
			}
			$checkpoint.remove();
		};

		/*
		* Функция для добавления станций метро в список отмеченных, а также удаления из этого списка
		* если станция уже добавлена
		*/
		Slick.Editors.AnbaseMetros.station_click = function(event,$area){

			if(!selected_station[$area.data('line')]){
				selected_station[$area.data('line')] = [];
			}

			if(selected_station[$area.data('line')].indexOf($area.data('metro_id')) == -1){
				//добавляем checkpoint
				var coords = $area.attr('coords').split(',');
				var checkpointImg = $('<img id="checkpoint-'+$area.data('metro_id')+'" src="'+common.baseUrl+'themes/dashboard/images/point.gif" style="cursor:pointer; position:absolute;top:'+(coords[1]-3)+'px;left:'+(coords[0]-3)+'px">');
				checkpointImg.attr('onclick','Slick.Editors.AnbaseMetros.checkpoint_click(event,$(this));');
				checkpointImg.data('metro_id',$area.data('metro_id'));
				$metro_wrapper.append(checkpointImg);
				selected_station[$area.data('line')].push($area.data('metro_id'));
			}else{
				$('#checkpoint-'+$area.data('metro_id')).remove();
				//удаляем элемент массива
				selected_station[$area.data('line')].splice(selected_station[$area.data('line')].indexOf($area.data('metro_id')),1);
				if(!selected_station[$area.data('line')].length){
					delete selected_station[$area.data('line')];
				}

			}
		}

		this.init = function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'>").appendTo($container);
			$metro_wrapper = $('<div style="position:relative">');
			$img = $('<img usemap="#metro-normal" id="metromap" src="'+metros_images['metro-normal'].image+'"/>');
			$metro_wrapper.append($img);
			$map  = $('<map name="metro-normal">');

			for(var i in metro_normal['elements']){
				var element = metro_normal['elements'][i];
				if(element.type == 'station'){
					$area = $('<area href="#" id="station-'+element.metro_id+'" shape="'+element.shape+'" coords="'+element.coords+'" title="'+element.metro_name+'">');
					$area.attr('onclick',"Slick.Editors.AnbaseMetros.station_click(event,$(this));return false;");
					$area.data('metro_id',element.metro_id); 
					$area.data('line',element.metro_line); 
					$map.append($area);
				}
			};
			$metro_wrapper.append($map);
			$wrapper.append($metro_wrapper);
			$wrapper.center();
			$metro_wrapper.append('<script type="text/javascript">$(function(){$("#metromap").maphilight();});</script>')
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
			defaultValue     = item.metros;
			for(var i in item.metros){
				for(var j in item.metros[i]){
					$('#station-'+item.metros[i][j]).trigger('click');
				}
			}	
		};

		this.serializeValue = function(){
			return selected_station;
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
			// #баный фиктивный пустой объект. то есть, если мы при старте получили его, и ничего не выбрали на карте, то по финишу selected_station пуст 
			if(defaultValue["0"] && common.isEmptyObj(selected_station)){
				return false;
			}

			if(!common.isEmptyObj(defaultValue) && common.isEmptyObj(selected_station)){
				return true;
			}

			//если число линий различно, то тоже меняем
			if(common.count_props(defaultValue) != common.count_props(selected_station)){
				return true;
			}

			for(var i in selected_station){
				if(defaultValue.hasOwnProperty(i)){	
					// если мы во время работы по убирали checkpoint'ы, то линия останется живой, но будет пустой.
					if(!selected_station[i] || selected_station[i].length != defaultValue[i].length)
						return true;

					for(var j in selected_station[i]){
						if(defaultValue[i].indexOf(selected_station[i][j]) == -1){
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

		this.init();
	};

	function AnbaseRegionsEditor(args){
		
		var $wrapper;
		var $img;
		var $map;
		var $region_wrapper;

		var defaultValue;
		var regions = common.regions;
		var region_normal = common.regions_images['regions-normal'];
		var selected_regions = [];
		var scope = this;

		/*
		* Обобщая функция для клика по area и item
		*/
		function region_click(region_id){
			var idx_metro_id = -1;
			if( (idx_metro_id = selected_regions.indexOf(region_id)) != -1){
				$('#region-item-'+region_id).css('background-color','#fff');
				selected_regions.splice(idx_metro_id,1);
			}else{
				$('#region-item-'+region_id).css('background-color','#f7f21a');
				selected_regions.push(region_id);
				$('#region-'+region_id).mouseover();
			}
		}

		Slick.Editors.AnbaseRegions.checkpoint_click = function(event,$checkpoint){

		}

		Slick.Editors.AnbaseRegions.region_click = function(event,$area){
			region_click($area.data('region_id'));
		}

		this.init = function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'></div>").appendTo($container);
			$region_wrapper = $('<div style="position:relative; width:700px"></div>');
			$img = $('<img usemap="#region-normal" id="regionmap" src="'+region_normal.image+'"/>');
			$map = $('<map name="region-normal">');
			$list= $('<ol style="float:right">');

			for(var i in region_normal.elements){
				var element = region_normal.elements[i];

				/*
				* Область на карте
				*/
				$area = $('<area href="#" id="region-'+element.region_id+'" shape="'+element.shape+'" coords="'+element.coords+'" title="'+element.region_name+'">');
				$area.attr('onclick','Slick.Editors.AnbaseRegions.region_click(event,$(this));return false;');
				$area.data('region_id',element.region_id);
				$map.append($area);
				
				/*
				* Элемент списка
				*/
				$li = $('<li id="region-item-'+element.region_id+'" style="margin:5px;"><a href="#" onclick="return false;">'+element.region_name+'</a></li>');
				$li.data('region_id',element.region_id);

				$li.hover(
					function(){
						$('#region-'+$(this).data('region_id')).trigger('mouseover');
					},
					function(){
						$('#region-'+$(this).data('region_id')).trigger('mouseout');
					}
				);

				$li.click(function(){
					region_click($(this).data('region_id'));
				});
				$list.append($li);
			};
			$region_wrapper.append($list);
			$region_wrapper.append($img);
			$region_wrapper.append($map);
			$wrapper.append($region_wrapper);
			$wrapper.center();			
			// РАБОТАЕТ ТОЛЬКО ТУТ, ХЗ ПОЧЕМУ
			// Если строчку ниже впихать где-нибудь выше, то не будет работать подсветка
			$region_wrapper.append('<script type="text/javascript">$(function(){$("#regionmap").maphilight();});</script>');

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
			for(var i in defaultValue){
				$('#region-'+defaultValue[i]).click();
			}
		};

		this.serializeValue = function(){
			return selected_regions
		};

		this.applyValue = function(item,state){
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
		};

		this.isValueChanged = function(){

			if(defaultValue.length != selected_regions.length){
				return true;
			}

			for(var i in selected_regions){
				if(defaultValue.indexOf(selected_regions[i]) == -1){
					return true;
				}
			}
			return false;		
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