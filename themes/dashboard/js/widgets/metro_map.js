$(function(){
	/*
	* Короче, это для того, чтобы можно было редактировать список метро.
	*/
	$.extend(true,window,{
		"common":{
			"widgets":{
				"metro_map":metro_map_widget				
			}
		}
	});

	/*
	* Редактор метро по карте.
	*/
	function metro_map_widget(options){


		var def_options = {
			/*
			* Показывать кнопки
			*/
			needButtons:true,

			/*
			* Список линиий и станций метро. ( Типа по ссылке передается, не копия)
			*/
			metros:{}, 

			/*
			* Функция, которую нужно выполнить при отмене результата
			*/
			onСancel:function(){

			},
			onSave:function(){

			}
		};

		options = $.extend(true,def_options,options);
		
		var $wrapper;
		var $map_wrapper;
		var $img;
		var $map;

		var metro_normal  = common.metros_images['metro-normal'];
		var selected_metros = {};

		function save_btn(event){
			$wrapper.remove();
			options.onSave();
		}

		function cancel_btn(event){
			$wrapper.remove();
			options.onCancel();
		}

		function reset_btn(event){
			
			for(var i in selected_metros){
				for(var j in selected_metros[i]){
					$('#checkpoint-'+selected_metros[i][j]).remove();
				}
			}

			selected_metros = {};
		}

		/*
		* Возвращает друзей по пересадке ( по умолчанию они обязательно должны быть)
		* или false, если они уже заданы
		*/
		function get_transshipment_friends(metro_id,transshipment){
			var transshipment_friends = [];

			for(var i in metro_normal.elements){
				var element = metro_normal.elements[i];
				
				//текущий пропускаем
				if(metro_id == element.metro_id) continue;
				
				if(element.metro_transshipment == transshipment){
					if( !selected_metros[element.metro_line] || selected_metros[element.metro_line].indexOf(element.metro_id) == -1)
						transshipment_friends.push(element.metro_id);
					else
						return false;
				}
			}
			return transshipment_friends;
		}

		function push_point(line,metro_id,x,y){

				var checkpointImg = $('<img id="checkpoint-'+metro_id+'" src="'+common.baseUrl+'themes/dashboard/images/point.gif" style="cursor:pointer; position:absolute;top:'+(y-3.2)+'px;left:'+(x-3.2)+'px">');
				checkpointImg.attr('onclick','common.widgets.metro_map.checkpoint_click(event,$(this));');
				checkpointImg.data('metro_id',metro_id);

				$map_wrapper.append(checkpointImg);
				selected_metros[line].push(metro_id);
		}

		function pop_point(line,metro_id){

			selected_metros[line].splice(selected_metros[line].indexOf(metro_id),1);
			if(!selected_metros[line].length){
				delete selected_metros[line];
			}
		}
		/*
		* Публичные методы объекта.
		*/
		common.widgets.metro_map.checkpoint_click = function(event,$checkpoint){

			var metro_id = $checkpoint.data('metro_id');
			var $area = $('#station-'+metro_id);
			pop_point($area.data('line'),metro_id);
			$checkpoint.remove();
		};

		/*
		* Функция для добавления станций метро в список отмеченных, а также удаления из этого списка
		* если станция уже добавлена
		*/
		common.widgets.metro_map.station_click = function(event,$area){

			if(!selected_metros[$area.data('line')]){
				selected_metros[$area.data('line')] = [];
			}

			if(selected_metros[$area.data('line')].indexOf($area.data('metro_id')) == -1){
				
				//добавляем checkpoint
				var coords = $area.attr('coords').split(',');
				
				push_point($area.data('line'),$area.data('metro_id'),coords[0],coords[1]);
				/*
				* Значит, если станция является пересадочной, то ищем её друзей, проверяем, заданы ли они, и если нет, то задаем 
				* и их.
				*/
				var transshipment = $area.data('transshipment');
				if(transshipment){
					transshipment_friends = get_transshipment_friends($area.data('metro_id'),transshipment); // копируем его
					if(transshipment_friends){
						var mass = transshipment_friends.slice(0);
						for(var i in mass){
							$('#station-'+mass[i]).click();
						}
					}
				}
			}else{
				$('#checkpoint-'+$area.data('metro_id')).remove();
				pop_point($area.data('line'),$area.data('metro_id'));
			}
		}

		common.widgets.metro_map.line_click = function(event,$area){

			for(var i in metro_normal.elements){
				var element = metro_normal.elements[i];
				if(element.type != 'line' && element.metro_line == $area.data('line')){
					$('#station-'+element.metro_id).click();
				}
			}
		}
		/*
		* Типа конструктор, создадим определение редактора и вернем его.
		*/
		return {
			init:function(){

				var $container = $('body');
				$wrapper = $("<div style='z-index:1000; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'>").appendTo($container);


				$closeImg = $('<img title="Закрыть без сохранения" style="position:relative; top:0; left:96%; cursor:pointer;" src="'+common.baseUrl+'themes/dashboard/images/delete.png" />');
				$closeImg.click(function(){
					cancel_btn(event);
				});
				$wrapper.append($closeImg);
				if(options.needButtons){
					$save_btn = $('<button id="save_btn">Сохранить</button>');
					$cancel_btn = $('<button id="cancel_btn">Отмена</button>"');
					$reset_btn= $('<button id="reset">Сбросить</button>');

					

					$save_btn.click(function(event){
						save_btn(event);
					});
					$cancel_btn.click(function(event){
						cancel_btn(event);
					});
					$reset_btn.click(function(event){
						reset_btn(event);
					});

					$wrapper.append($save_btn);
					$wrapper.append($cancel_btn);
					$wrapper.append($reset_btn);	
				}

				$wrapper.keydown(function(e){
					console.log(e);
				});
				
				$map_wrapper = $('<div style="position:relative">');
				$img = $('<img usemap="#metro-normal" id="metromap" src="'+metro_normal.image+'"/>');
				$map_wrapper.append($img);
				$map  = $('<map name="metro-normal">');

				for(var i in metro_normal.elements){
					var element = metro_normal.elements[i];

					if(element.type == 'station'){
						$area = $('<area href="#" id="station-'+element.metro_id+'" shape="'+element.shape+'" coords="'+element.coords+'" title="'+element.metro_name+'">');
						$area.attr('onclick',"common.widgets.metro_map.station_click(event,$(this));return false;");
						$area.data('metro_id',element.metro_id); 
						$area.data('line',element.metro_line); 
						$area.data('transshipment',element.metro_transshipment);

						$map.append($area);
					}else if(element.type == 'line'){
						$area = $('<area href="#" id="line-'+element.line+'" shape="'+element.shape+'" coords="'+element.coords+'" title="'+element.line+'">');
						$area.attr('onclick',"common.widgets.metro_map.line_click(event,$(this));return false;");
						$area.data('line',element.line);

						$map.append($area);
					}
				};

				if(options.needButtons){
					$clone_save_btn = $save_btn.clone();
					$clone_cancel_btn = $cancel_btn.clone();
					$clone_reset_btn = $reset_btn.clone();

					$clone_save_btn.click(function(event){
						save_btn(event);
					});
					$clone_cancel_btn.click(function(event){
						cancel_btn(event);
					});
					$clone_reset_btn.click(function(event){
						reset_btn(event);
					});

					$right = $('<div style="float:right;"></div>');
					$right.append($clone_save_btn);
					$right.append($clone_cancel_btn);
					$right.append($clone_reset_btn);	
				}

				$map_wrapper.append($map);
				$wrapper.append($map_wrapper);
				if(options.needButtons)
					$wrapper.append($right);
				$map_wrapper.append('<script type="text/javascript">$(function(){$("#metromap").maphilight();});</script>')
				$wrapper.center();

			},

			show:function(){
				$wrapper.show();
			},

			hide:function(){
				$wrapper.hide();
			},

			destroy:function(){
				$wrapper.remove();
			},

			focus:function(){
			},
			load:function(){
				for(var i in options.metros){
					// пустые не берем
					if(i == "0") continue;

					for(var j in options.metros[i]){

						if(!selected_metros[i]){
							selected_metros[i] = [];
						}
						$area = $('#station-'+options.metros[i][j]);
						if($area.length){
							var coords = $area.attr('coords').split(',');
							push_point(i,options.metros[i][j],coords[0],coords[1]);	
						}
					}
				}
			},
			serialize:function(){
				return selected_metros;
			},
			isValueChanged:function(){

				// #баный фиктивный пустой объект. то есть, если мы при старте получили его, и ничего не выбрали на карте, то по финишу selected_metros пуст 
				if(options.metros["0"] && common.isEmptyObj(selected_metros)){
					return false;
				}

				if(!common.isEmptyObj(options.metros) && common.isEmptyObj(selected_metros)){
					return true;
				}

				//если число линий различно, то тоже меняем
				if(common.count_props(options.metros) != common.count_props(selected_metros)){
					return true;
				}

				for(var i in selected_metros){
					if(options.metros.hasOwnProperty(i)){	
						// если мы во время работы по убирали checkpoint'ы, то линия останется живой, но будет пустой.
						if(!selected_metros[i] || selected_metros[i].length != options.metros[i].length)
							return true;

						for(var j in selected_metros[i]){
							if(options.metros[i].indexOf(selected_metros[i][j]) == -1){
								return true;
							}	
						}
					}else{
						return true;
					}
				}
				return false;
			}
		};
	}

})