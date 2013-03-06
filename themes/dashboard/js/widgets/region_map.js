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
			// Показывать кнопки или нет
			needButtons:true,
			// Выбор любого района или нет
			needAnyRegion:false,
			// Список районов
			regions:[], 
			// callbacks
			onSave:function(){},
			onCancel:function(){}
		};

		options = $.extend(true,def_options,options);

		var $wrapper;
		var $img;
		var $map;
		var $region_wrapper;
		var $any_region_checkbox;

		var region_normal = common.regions_images['regions-normal'];
		var selected_regions = [];

		function save_btn(event){
			$wrapper.remove();
			options.onSave();
		}

		function cancel_btn(event){
			$wrapper.remove();
			options.onCancel();
		}

		function push_item_list(region_id){
			$('#region-item-'+region_id).find('a').css('color','#FFFFFF');
			$('#region-item-'+region_id).css('background-color','#666666');	
		}

		function pop_item_list(region_id){
			$('#region-item-'+region_id).find('a').css('color','#333333');
			$('#region-item-'+region_id).css('background-color','#fff');
		}

		function reset_btn(event){
			while(selected_regions.length>0){
				$('#region-'+selected_regions[(selected_regions.length-1)]).click();
			}
			selected_regions = [];
		}

		function selected(region_id){
			return selected_regions.indexOf(region_id) != -1;
		}

		/*
		* Обобщая функция для клика по area и item
		*/
		function region_click(region_id){
			var idx_region_id = -1;
			if( (idx_region_id = selected_regions.indexOf(region_id)) != -1){
				selected_regions.splice(idx_region_id,1);
				pop_item_list(region_id);
			}else{
				selected_regions.push(region_id);
				$('#region-'+region_id).mouseover();
				push_item_list(region_id);
			}
		}

		common.widgets.region_map.region_click = function(event,$area){
			region_click($area.data('region_id'));
		}

		function isRegionListChanged(){
			if(options.regions.length != selected_regions.length){
				return true;
			}

			for(var i in selected_regions){
				if(options.regions.indexOf(selected_regions[i]) == -1){
					return true;
				}
			}
		}

		function isAnyRegionChanged(){
			return $any_region_checkbox.val() != options.any_region;
		}

		/*
		* Типа конструктор, создадим определение редактора и вернем его.
		*/
		return {
			init:function(){
			var $container = $('body');
			$wrapper = $("<div style='z-index:99999; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'></div>").appendTo($container);
			$region_wrapper = $('<div style="position:relative; width:700px"></div>');
			$img = $('<img usemap="#region-normal" id="regionmap" src="'+region_normal.image+'"/>');
			$map = $('<map name="region-normal">');
			$list= $('<ol style="float:right">');

			$closeImg = $('<img title="Закрыть без сохранения" style="position:relative; top:0; left:96%; cursor:pointer;" src="'+common.baseUrl+'themes/dashboard/images/delete.png" />');
			$closeImg.click(function(){
				cancel_btn(event);
			});

			$any_region_label    = $('<label for="any_region_checkbox">Любой</label>')
			$any_region_checkbox = $('<input id="any_region_checkbox" type="checkbox" value=""/>');

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

			if(options.needAnyRegion){
				$wrapper.append($any_region_checkbox);
				$wrapper.append($any_region_label);


				$any_region_checkbox.click(function(){
					if($(this).val()=="1"){
						$(this).val("0");
					}else{
						$(this).val("1");
					}
				});
			}
			

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
				$li = $('<li id="region-item-'+element.region_id+'" style="padding-top: 1px;padding-bottom: 1px;margin-top:2px;margin-bottom:2px;padding-left: 4px;" onclick="return false;"><a href="#" style="color:#333;">'+element.region_name+'</a></li>');
				$li.data('region_id',element.region_id);

				$li.hover(
					function(){
						var region_id = $(this).data('region_id');

						$(this).find('a').css('text-decoration','none');

						if(!selected(region_id)){
							$(this).find('a').css('color','#FFFFFF');
							$(this).css('background-color','#666666');	
						}

						$('#region-'+$(this).data('region_id')).trigger('mouseover');
					},
					function(){
						var region_id = $(this).data('region_id');

						if(!selected(region_id)){
							$(this).find('a').css('color','#333333');
							$(this).css('background-color','#fff');
						}

						$('#region-'+$(this).data('region_id')).trigger('mouseout');
					}
				);

				$li.click(function(){
					region_click($(this).data('region_id'));
				});
				$list.append($li);
			};

			if(options.needButtons){
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
			}
			

			$region_wrapper.append($list);
			$region_wrapper.append($img);
			$region_wrapper.append($map);
			$wrapper.append($region_wrapper);	
			if(options.needButtons)
				$wrapper.append($right);	
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

			},

			load:function(regions, any_region){
				options.regions = regions;
				options.any_region = any_region;
				
				for(var i in options.regions){
					$('#region-' + options.regions[i]).click();
				}

				$any_region_checkbox.val(any_region);
				if(any_region == "1") $any_region_checkbox.attr('checked','checked');
			},

			serialize:function(){
				if(options.needAnyRegion)
					return {"selected_regions":selected_regions,"any_region":$any_region_checkbox.val()};
				else
					return selected_regions;
			},

			isValueChanged:function(){
				return isAnyRegionChanged() || isRegionListChanged();
			}
		};
	}

})