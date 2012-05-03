$(function(){
		/*
		* Мои форматтеры
		*/
		function DescriptionFormatter(row,cell,value,columnDef,dataContext){
			if(!value)
				return "";
		  var cell_content = $('<div id="cell_description" style="height:40px;overflow:hidden;">').html(value);
		  cell_content.attr('onmousemove','common.show_full_text(event,'+row+','+cell+',agent.orders.grid.getDataItem('+row+').description);');
		  cell_content.attr('onmouseout','common.hide_full_text(event,'+row+','+cell+');');
		  var wrap = $('<div>').html(cell_content);
		  return wrap.html();
		};
		/*
		* Настройки грида
		*/
		var options = {enableCellNavigation:true,editable:true,autoEdit:false,rowHeight:25,forceFitColumns:true};
		var columns = [
			{id: "number", name:"№", field:"number",width:40, editor:Slick.Editors.Integer,sortable:true},
			{id: "create_date", name:"Дата создания", field:"create_date",  editor:Slick.Editors.Date, sortable:true},
			{id: "category", name:"Тип объекта", field:"category", editor:Slick.Editors.AnbaseCategory},
			{id: "deal_type", name:"Сделка", field:"deal_type", editor:Slick.Editors.AnbaseDealType},
			{id: "regions",  name:"Район", field:"regions",  editor:Slick.Editors.AnbaseRegions,formatter:Slick.Formatters.RegionsList},
			{id: "metros", name:"Метро", field:"metros",  editor:Slick.Editors.AnbaseMetros,formatter:Slick.Formatters.MetrosList},
			{id: "price", name:"Цена", field:"price",  formatter:Slick.Formatters.Rubbles,editor:Slick.Editors.Integer, sortable:true},	
			{id: "description", name:"Описание", field:"description",cssClass:"cell_description", width:303, formatter:DescriptionFormatter, editor:Slick.Editors.LongText},
			{id: "phone", name:"Телефон", field:"phone", width:115, formatter:Slick.Formatters.Phone, editor:Slick.Editors.Integer}
		];

		/*
		* некоторые данные
		*/
		var region_widget;
		var metro_widget;
		var regions = [];
		var metros  = {};

		/*
		* Создание грида
		*/
		var model = new Slick.Data.RemoteModel({BaseUrl:agent.baseUrl+'?act=view&s=my',PageSize:200});	
		var grid = new Slick.Grid("#orders_grid",model.data,columns,options);
		common.grid = grid;

		/*
		* Событие сортировки
		*/
		grid.onSort.subscribe(function(e,sortHandle){
			if(sortHandle){
				model.resetSortOrder();
				switch(sortHandle.sortCol.field){
					case 'price':
						model.setPriceOrder(sortHandle.sortAsc);
						vp = grid.getViewport();
						model.applyFilter(vp.top,vp.bottom);
						break;
					case 'number':
						model.setNumberOrder(sortHandle.sortAsc);
						vp = grid.getViewport();
						model.applyFilter(vp.top,vp.bottom);
					break;
					case 'create_date':
						model.setCreateDateOrder(sortHandle.sortAsc);
						vp = grid.getViewport();
						model.applyFilter(vp.top,vp.bottom);
					break;
				}
			}
		});

		/*
		* Сохраняем backup значение
		*/
		grid.onBeforeEditCell.subscribe(function(e,handle){
			handle.item.backupFieldValue = handle.item[handle.column.field]; 
		});
		
		/*
		[my_notice:] Комментирую тебя бро до наилучших времен, я обязательно тебя включу
		grid.onHeaderClick.subscribe(function(e,columnHandle){
			if(columnHandle){
				switch(columnHandle.column.field){
					case 'metros':
						if(!metro_widget){
							metro_widget = common.widgets.metro_map({metros:metros,onSave:metroOnSave,onCancel:metroOnCancel});
							metro_widget.init();
							metro_widget.load();
						}else{
							metroOnSave();
						}
					break;
					case 'regions':
						if(!region_widget){
							region_widget = common.widgets.region_map({onSave:regionOnSave,onCancel:regionOnCancel});
							region_widget.init();
							region_widget.load(regions);
						}else{
							regionOnCancel();
						}
					break;
				}
			}
		});
		*/

		/*
		* Обработка события изменения ячейки
		*/
		grid.onCellChange.subscribe(function(e,handle){
			var data = {};
			var item = handle.item;
			var cell = handle.cell;
			var field = grid.getColumns()[cell].field;

			data['id']  = item.id;
			data[field] = item[field];

			/*
			* [my_notice]Наверно, стоит подумать над тем как нормально сохранять эти данные
			*/
			if(field == "metros"){
				data["any_metro"] = item["any_metro"];
			}

			if(field == "regions"){
				data["any_region"] = item["any_region"];
			}

			$.ajax({
				url:agent.baseUrl+'/?act=edit',
				type:'POST',
				dataType:'json',
				data:data,
				success:function(response){
					if(response.code && response.data){
						switch(response.code){
							case 'success_edit_order':
								common.showSuccessMsg(response.data);
							break;
							case 'error_edit_order':
								/*
								* Восстанавливаем старое значение
								*/
								item[field] = item.backupFieldValue;
								grid.updateRow(handle.row);
								
								/*
								* Выводим сообщение об ошибке.
								* Если ошибка уровня валидации, то дя поля выводим ошибку. Если ошибка уровня системы, то просто выводим ошибку
								*/
								if(response.data.errors[field] && typeof response.data.errors[field] == "string"){
									common.showErrorMsg(response.data.errors[field]);
								}else{
									common.showErrorMsg(response.data.errors[0]);
								}
								return false;
							break;
						}
					}
				},
				beforeSend:function(){
					common.showAjaxIndicator();
				},
				complete:function(){
					common.hideAjaxIndicator();
				}
			});
		});
		/*
		* Обработка события неверного редактирования ячейки
		*/
		grid.onValidationError.subscribe(function(e,handle){
			var column = handle.column;
			var validationResults = handle.validationResults;
			common.showResultMsg(validationResults.msg);
		});

		grid.onViewportChanged.subscribe(function(e,args){
			var vp = grid.getViewport();
			model.ensureData(vp.top,vp.bottom);
		});

		model.onDataLoading.subscribe(function(){
			common.showAjaxIndicator();
		});

		/*
		* Обработчики фильтра
		*/
		$('#f_category').change(function(){
			model.setCategory($(this).val());
			vp = grid.getViewport();
			model.applyFilter(vp.top,vp.bottom);
		});

		$('#f_dealtype').change(function(){
			model.setDealtype($(this).val());
			vp = grid.getViewport();
			model.applyFilter(vp.top,vp.bottom)
		});

		$('#f_number').keyfilter(/[\d]/);
		$('#f_number').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setNumber($(this).val());
				model.setPhone($('#f_phone').val());
				model.applyFilter(vp.top,vp.bottom);
			}
		});
		
		$('#f_phone').keyfilter(/[\+\d]/);
		$('#f_phone').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setPhone($(this).val());
				model.setNumber($('#f_number').val());
				model.applyFilter(vp.top,vp.bottom);
			}
		});

		$('#f_price_to').keyfilter(/[\d\.]/);
		$('#f_price_to').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setPriceTo($(this).val());
				model.setPriceFrom($('#f_price_from').val());
				model.applyFilter(vp.top,vp.bottom);
			}
		});

		$('#f_price_from').keyfilter(/[\d\.]/);
		$('#f_price_from').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setPriceFrom($(this).val());
				model.setPriceTo($('#f_price_to').val())
				model.applyFilter(vp.top,vp.bottom);
			}else if(!(event.which >= 48 && event.which <= 57 || event.which == 8 || event.which == 9)){
				event.preventDefault();
			}
		});
		$('#f_createdate_to').change(function(event){
			vp = grid.getViewport();
			model.setCreateDateTo($(this).val());
			model.setCreateDateFrom($('#f_createdate_from').val());
			model.applyFilter(vp.top,vp.bottom);
		});
		$('#f_createdate_from').change(function(event){
			vp = grid.getViewport();
			model.setCreateDateFrom($(this).val());
			model.setCreateDateTo($('#f_createdate_to').val());
			model.applyFilter(vp.top,vp.bottom);
		});


		$('#description').keydown(function(event){
		});

		function regionOnSave(event){
			region_widget.destroy();
			if(region_widget.isValueChanged()){
				regions = region_widget.serialize().splice(0);

				vp = grid.getViewport();
				model.setRegions(regions);
				model.applyFilter(vp.top,vp.bottom);
			}
			region_widget = undefined;
		}

		function regionOnCancel(event){
			region_widget.destroy();
			region_widget = undefined;

		}

		$('#region_btn').click(function(event){
			if(!region_widget){
				region_widget = common.widgets.region_map({onSave:regionOnSave,onCancel:regionOnCancel});
				region_widget.init();
				region_widget.load(regions);
			}else{
				regionOnSave();
			}
		});

		function metroOnSave(event){
			metro_widget.destroy();
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
				vp = grid.getViewport();
				model.setMetros(metros);
				model.applyFilter(vp.top,vp.bottom);
			}
			metro_widget = undefined;
		}

		function metroOnCancel(event){
			metro_widget.destroy();
			metro_widget = undefined;
		}

		$('#metro_btn').click(function(event){
			if(!metro_widget){
				metro_widget = common.widgets.metro_map({metros:metros, onSave:metroOnSave, onCancel:metroOnCancel });
				metro_widget.init();
				metro_widget.load();
			}else{
				metroOnSave();	
			}
		});

		$('#search_btn').click(function(event){

			model.setNumberTo($('#f_number_to').val());
			model.setNumberFrom($('#f_number_from').val());
			model.setCategory($('#f_category').val());
			model.setDealtype($('#f_dealtype').val());
			model.setPriceFrom($('#f_price_from').val());
			model.setPriceTo($('#f_price_to').val());
			model.setCreateDateTo($('#f_createdate_to').val());
			model.setCreateDateFrom($('#f_createdate_from').val());
			model.setDescription($('#f_description').val());

			model.setDescriptionType($('input[name=f_description_type]:checked').val());
			if(metros){
				model.setMetros(metros)
			}

			if(regions){
				model.setRegions(regions);
			}

			vp = grid.getViewport();
			model.applyFilter(vp.top,vp.bottom);
		});

		$('#reset_filter_btn').click(function(){
			model.resetFilter();
			$('#f_phone').val('');
			$('#f_number').val('');
			$('#f_number_to').val('');
			$('#f_number_from').val('');
			$('#f_category').val('');
			$('#f_dealtype').val('');
			$('#f_price_from').val('');
			$('#f_price_to').val('');
			$('#f_createdate_to').val('');
			$('#f_createdate_from').val('');
			$('#f_description').val('');
			metros = {};
			regions = {};
			
			vp = grid.getViewport();
			model.applyFilter(vp.top,vp.bottom);

		});

		model.onDataLoaded.subscribe(function(e,args){
			for (var i = args.from; i <= args.to; i++) {
		    	grid.invalidateRow(i);
		  	}
		  	grid.updateRowCount();
		  	grid.render();
			common.hideAjaxIndicator();
		});
		grid.onViewportChanged.notify();
		agent.orders.grid = grid;
	});