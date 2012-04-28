$(function(){
	/*
	* Короче, это для того, чтобы можно было редактировать список метро.
	*/
	$.extend(true,window,{
		"common":{
			"widgets":{
				"metro_list":metro_list_widget				
			}
		}
	});

	/*
	* Редактор метро по карте.
	*/
	function metro_list_widget(options){


		var def_options = {
			/*
			* Список линиий и станций метро. ( Типа по ссылке передается, не копия)
			*/
			metros:{}, 

			/*
			* Функция, которую нужно выполнить отмене работы
			*/
			onCancel:function(){

			},
			onSave:function(){

			}
		};
	
		options = $.extend(true,def_options,options);
		
		var $wrapper;
		var $map_wrapper;
		var $img;
		var $map;
		var $list;
		var $list_td;
		var $list_wrapper;

		var metro_list  = common.metros_images['metro-list'];
		var selected_metros = {};

		function save_btn(event){
			$wrapper.hide();
			options.onSave();
		}

		function cancel_btn(event){
			$wrapper.hide();
			options.onCancel();
		}

		function reset_btn(event){
			for(var i in selected_metros[i]){
				for(var j in selected_metros[j]){
					var $area = $('#station-'+selected_metros[i][j]);
					pop_point_map($area);
					pop_point_list($area);
				}
			}

			selected_metros = {};
		}

		function get_transshipement_neighbors(transshipment){
			var transshipment_neighbors = [];

			for(var i in metro_list.elements){
				var element = metro_list.elements[i];
				
				if(element.metro_transshipment == transshipment){
					transshipment_neighbors.push(element.metro_id);
				}
			}
			return transshipment_neighbors;
		}
		/*
		* Возвращает друзей по пересадке ( по умолчанию они обязательно должны быть)
		* или false, если они уже заданы
		*/
		function get_transshipment_friends(metro_id,transshipment){
			var transshipment_friends = [];

			for(var i in metro_list.elements){
				var element = metro_list.elements[i];
				
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

		function pop_transshipment_point($checkpoint){
			var transshipment = $checkpoint.data('transshipment');
			/*
			* 1. Ищу всех друзей
			* 2. считаю число выбранных
			* 3. считаю общее число
			* 4. если число выбранных равно числу общему, то удаляю всех
			* 5. если число выбранных равно 1 то удаляю одного его и маячок
			* 6. если число выбранных иное, то ничего не делаю
			*/
			var transshipment_neighbors = get_transshipement_neighbors(transshipment);
			var selected_neighbors=[];
			for(var i in selected_metros){
				for(var j in selected_metros[i]){
					if(transshipment_neighbors.indexOf(selected_metros[i][j]) != -1){
						selected_neighbors.push(selected_metros[i][j]);
					}						
				}
			}

			if(transshipment_neighbors.length == selected_neighbors.length){
				for(var i in selected_neighbors){
					pop_point_map($('#station-'+selected_neighbors[i]));
					pop_point_list($('#station-'+selected_neighbors[i]));
				}
				$checkpoint.remove();
				return true;
			}else if(selected_neighbors.length == 1){
				pop_point_map($('#station-'+selected_neighbors[0]));
				pop_point_list($('#station-'+selected_neighbors[0]));
				$checkpoint.remove();
				return true;
			}
			/*
			* Иначе ничего не делаем, так как никакое метро не привязано
			*/
			return false;
		}

		function push_point_list($area){
			var metro_id = $area.data('metro_id');

			$('#station-list-item-'+metro_id).css('color','#FFFFFF');
			$('#station-list-item-'+metro_id).css('text-decoration','none');
			$('#station-list-item-'+metro_id).css('background-color','#666666');
		}
		function pop_point_list($area){
			var metro_id = $area.data('metro_id');

			$('#station-list-item-'+metro_id).css('color','#333333');
			$('#station-list-item-'+metro_id).css('background-color','#fff');
		}

		function push_point_map($area){
			var line = $area.data('line');
			var metro_id= $area.data('metro_id');
			var transshipment= $area.data('transshipment');
			var coords = $area.attr('coords').split(',');

			if(!selected_metros[line]) selected_metros[line] = [];

			if(transshipment){
				transshipment_friends = get_transshipment_friends(metro_id,transshipment);
				if(transshipment_friends){
					var checkpointImg = $('<img id="checkpoint-transshipment-'+transshipment+'" src="'+common.baseUrl+'themes/dashboard/images/point.gif" style="cursor:pointer; position:absolute;top:'+(coords[1]-4)+'px;left:'+(coords[0]-4)+'px">');
					checkpointImg.attr('onclick','common.widgets.metro_list.checkpoint_click(event,$(this));');
					checkpointImg.data('transshipment',transshipment);
					$map_wrapper.append(checkpointImg);
				}
			}else{
				var checkpointImg = $('<img id="checkpoint-'+metro_id+'" src="'+common.baseUrl+'themes/dashboard/images/point.gif" style="cursor:pointer; position:absolute;top:'+(coords[1]-4)+'px;left:'+(coords[0]-4)+'px">');
				checkpointImg.attr('onclick','common.widgets.metro_list.checkpoint_click(event,$(this));');
				checkpointImg.data('metro_id',metro_id);
				checkpointImg.data('transshipment',transshipment);

				$map_wrapper.append(checkpointImg);
			}	
			selected_metros[line].push(metro_id);
		}

		function pop_point_map($area){
			var line = $area.data('line');
			var metro_id = $area.data('metro_id');

			selected_metros[line].splice(selected_metros[line].indexOf(metro_id),1);
			if(!selected_metros[line].length){
				delete selected_metros[line];
			}
		}

		function isSelected($area){
			return selected_metros[$area.data('line')] && selected_metros[$area.data('line')].indexOf($area.data('metro_id')) != -1;
		}
		/*
		* Публичные методы объекта.
		*/
		common.widgets.metro_list.checkpoint_click = function(event,$checkpoint){
			var transshipment = $checkpoint.data('transshipment');
			var metro_id      = $checkpoint.data('metro_id');

			if(transshipment){
				pop_transshipment_point($checkpoint);
			}else{
				pop_point_map($('#station-'+metro_id));
				pop_point_list($('#station-'+metro_id));
				$checkpoint.remove();
			}
		};

		/*
		* Функция для добавления станций метро в список отмеченных, а также удаления из этого списка
		* если станция уже добавлена
		*/
		common.widgets.metro_list.station_click = function(event,$area){
			var metro_id = $area.data('metro_id');
			var transshipment = $area.data('transshipment');

			if(isSelected($area)){
				/*
				* если пересадка, то
				* 1. ищу всех друзей
				* 2. 
				*/
				pop_point_map($area);
				pop_point_list($area);
			}else{
				/*
				* ищу всех друзей по пересадке
				* 1. если они уже в списке, то добавляю только себя
				* 2. если их в списке нету, то я прохожу через них и их добавляю
				*/
				push_point_map($area);
				push_point_list($area);
				/*
				* Добавляем друзей по пересадке, если они есть
				*/
				if(transshipment){
					var transshipment_friends = get_transshipment_friends(metro_id,transshipment);
					if(transshipment_friends){
						var mass = transshipment_friends.splice(0);
						for(var i in mass){
							push_point_map($('#station-'+mass[i]));
							push_point_list($('#station-'+mass[i]));
						}
					}				
				}
			}

		}

		common.widgets.metro_list.line_click = function(event,$area){
			var line = $area.data('line');

			for(var i in metro_list.elements){
				var element = metro_list.elements[i];

				if(element.type == 'station' && element.metro_line == line){
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
				$wrapper = $('<div style="z-index:9999;opacity:0.95;width: 850px;-moz-border-radius: 10px 10px 10px 10px;-webkit-border-radius: 10px 10px 10px 10px;border-radius: 10px 10px 10px 10px;border: 1px solid #666666;background-color: #FFFFFF;position: absolute;font-family: Arial,Helvetica, sans-serif;font-size: 11px;">').appendTo($container);
				$save_btn = $('<button id="save_btn">Сохранить</button>');
				$cancel_btn = $('<button id="cancel_btn">Отмена</button>"');
				$reset_btn= $('<button id="reset">Сбросить</button>')


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

				$map_wrapper = $('<div style="position:relative;">');
				$img = $('<img usemap="#metro-list" style="float:left" id="metromap" src="'+metro_list.image+'"/>');
				$list_wrapper = $('<div style="padding: 10px;float:right"><div style="height: 28px;padding-left: 42px;background-image: url('+common.baseUrl+'themes/dashboard/images/mlogo.png);background-repeat: no-repeat;background-position: left top;font-size: 14px;line-height: 14px;padding-top: 2px;font-weight: bold;color: #00569F;margin-bottom: 8px;">СХЕМА ЛИНИЙ САНКТ-ПЕТЕРБУРГСКОГО МЕТРОПОЛИТЕНА</div></div>');
				$list = $('<table class="spisok" style="width:100%" border="0" cellspacing="0" cellpadding="3"></table>');
				$list_td = $('<tr>');
				$map  = $('<map name="metro-list">');

				var cnt=0;
				var $td;
				for(var i in metro_list.elements){
					var element = metro_list.elements[i];

					if(element.type == 'station'){
						/*
						* Area
						*/	
						$area = $('<area href="#" id="station-'+element.metro_id+'" shape="'+element.shape+'" coords="'+element.coords+'" title="'+element.metro_name+'">');
						$area.attr('onclick',"common.widgets.metro_list.station_click(event,$(this));return false;");
						$area.data('metro_id',element.metro_id); 
						$area.data('line',element.metro_line); 
						$area.data('transshipment',element.metro_transshipment);
						$map.append($area);

						if(cnt % 21 == 0){
							$td  = $('<td valign="top">');
							$td.appendTo($list_td);
						}
						/*
						* List
						*/
						var $a = $('<a href="#" id="station-list-item-'+element.metro_id+'" onclick="return false" style="display: block;margin: 0px;padding-top: 1px;padding-right: 0px;padding-bottom: 1px; margin:2px 0px; padding-left: 4px;color: #333333;  text-decoration: none;">'+element.metro_name+'</a>');

						$a.data('metro_id',element.metro_id);
						$a.data('transshipment',element.metro_transshipment);

						$a.hover(
							function(){
								var metro_id = $(this).data('metro_id');
								if(!isSelected($('#station-'+metro_id))){
									$('#station-list-item-'+metro_id).css('color','#FFFFFF');
									$('#station-list-item-'+metro_id).css('text-decoration','none');
									$('#station-list-item-'+metro_id).css('background-color','#666666');
									$('#station-'+metro_id).mouseover();
								}
							},
							function(){
								var metro_id = $(this).data('metro_id');
								if(!isSelected($('#station-'+metro_id))){
									$('#station-list-item-'+metro_id).css('color','#333333');
									$('#station-list-item-'+metro_id).css('background-color','#fff');
									$('#station-'+metro_id).mouseout();
								}
							}
						);

						$a.click(function(){
							var transshipment = $(this).data('transshipment');
							var metro_id = $(this).data('metro_id');
							var $area = $('#station-'+metro_id);

							if(isSelected($area)){
								if(transshipment){
									// либо удаляем все сразу либо только одну точку на пересадке
									if(!pop_transshipment_point($('#checkpoint-transshipment-'+transshipment))){
										pop_point_map($area);
										pop_point_list($area)
										$('#checkpoint-transshipment-'+metro_id).remove();
									}
								}else{
									pop_point_map($area);
									pop_point_list($area);
									$('#checkpoint-'+metro_id).remove();
								}
							}else{
								push_point_map($area);
								push_point_list($area);

								if(transshipment){
									transshipment_friends = get_transshipment_friends(metro_id,transshipment);
									if(transshipment_friends){
										// Из-за того, что объекты передаются по ссылке приходится делать копии
										var mass = transshipment_friends.splice(0);
										for(var i in mass){
											push_point_map($('#station-'+mass[i]));
											push_point_list($('#station-'+mass[i]));
										}
									}
								}
							}

						});

						$td.append($a);
						++cnt;
					}
				};

				var td_width = 100/$list_td.find('td').length;

				$list_td.find('td').each(function(){
					$(this).attr('width',td_width+"%");
				});
				
				$clone_save_btn = $save_btn.clone();
				$clone_cancel_btn = $cancel_btn.clone();
				$clone_reset_btn= $reset_btn.clone();
				
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

				$list.append($list_td);
				$list_wrapper.append($list);
				$map_wrapper.append($list_wrapper);
				$map_wrapper.append($img);
				$map_wrapper.append($map);
				$wrapper.append($map_wrapper);
				$wrapper.append($right);
				// РАБОТАЕТ ТОЛЬКО ТУТ, ХЗ ПОЧЕМУ
				// Если строчку ниже впихать где-нибудь выше, то не будет работать подсветка
				$map_wrapper.append('<script type="text/javascript">$(function(){$("#metromap").maphilight();});</script>');
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
				$wrapper.focus();
			},
			load:function(){
				for(var i in options.metros){
					for(var j in options.metros[i]){
						if(!selected_metros[i]){
							selected_metros[i] = [];
						}
						var metro_id = options.metros[i][j];
						var $area = $('#station-'+metro_id);

						push_point_map($area);
						push_point_list($area);
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