$(function(){
	/*
	* Короче, это для того, чтобы можно было редактировать список райнов
	*/
	$.extend(true,window,{
		"common":{
			"widgets":{
				"region_map":region_map_widget				
			}
		}
	});

	/*
	* Редактор районов по карте
	*/
	function region_map_widget(options){


		var def_options = {
			/*
			* Список районов
			*/
			regions:{}, 

			/*
			* Функция, которую нужно выполнить при закрытии редактора
			*/
			onClose:function(){

			}
		};

		options = $.extend(true,def_options,options);

		var $wrapper;
		var $img;
		var $map;
		var $region_wrapper;

		var region_normal = common.regions_images['regions-normal'];
		var selected_regions = [];

		/*
		* Обобщая функция для клика по area и item
		*/
		function region_click(region_id){
			var idx_region_id = -1;
			if( (idx_region_id = selected_regions.indexOf(region_id)) != -1){
				$('#region-item-'+region_id).css('background-color','#fff');
				selected_regions.splice(idx_region_id,1);
			}else{
				$('#region-item-'+region_id).css('background-color','#f7f21a');
				selected_regions.push(region_id);
				$('#region-'+region_id).mouseover();
			}
		}

		common.widgets.region_map.region_click = function(event,$area){
			region_click($area.data('region_id'));
		}


		/*
		* Типа конструктор, создадим определение редактора и вернем его.
		*/
		return {
			init:function(){


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
				$area.attr('onclick','common.widgets.region_map.region_click(event,$(this));return false;');
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
			// РАБОТАЕТ ТОЛЬКО ТУТ, ХЗ ПОЧЕМУ
			// Если строчку ниже впихать где-нибудь выше, то не будет работать подсветка
			$region_wrapper.append('<script type="text/javascript">$(function(){$("#regionmap").maphilight();});</script>');
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
			load:function(regions){
				options.regions = regions;
				for(var i in options.regions){
					$('#region-'+options.regions[i]).click();
				}
			},
			serialize:function(){
				return selected_regions;
			}
		};
	}

})