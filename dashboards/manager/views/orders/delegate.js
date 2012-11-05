$(function(){
		/*
		* Настройки грида
		*/
		var options = {enableCellNavigation: true,rowHeight:25,forceFitColumns:true};
		/*
		* Добавляем поля из настроек
		*/
		var columns = [
			{id: "number", name:lang['grid.title.number'], field:"number",width:30,sortable:true},
			{id: "create_date", name:lang['grid.title.create_date'], width:55, field:"create_date",sortable:true},
			{id: "category", name:lang['grid.title.category'],width:40, field:"category", formatter:Slick.Formatters.Category},
			{id: "deal_type", name:lang['grid.title.deal_type'], field:"deal_type",width:40, formatter:Slick.Formatters.Dealtype}
		];

		if(common.settings_org.regions_col == "1"){
			var region_widget;
			var regions = [];
			$.merge(columns,[{id: "regions",  name:lang['grid.title.regions'],width:60, field:"regions", formatter:Slick.Formatters.RegionsList}]);
		}

		if(common.settings_org.metros_col == "1"){
			var metro_widget;
			var metros  = {};
			$.merge(columns,[{id: "metros", name:lang['grid.title.metros'], width:60, field:"metros",formatter:Slick.Formatters.MetrosList}]);
		}

		if(common.settings_org.price_col == "1"){
			$.merge(columns,[{id: "price", name:lang['grid.title.price'],width:60, field:"price",  formatter:Slick.Formatters.Rubbles, sortable:true}]);
		}

		$.merge(columns,[{id: "description", name:lang['grid.title.description'], field:"description",cssClass:"cell_description", width:303, formatter:Slick.Formatters.Description}
		]);

		$.merge(columns,[{id: "agent", name:lang['grid.title.agent'], field:"user_id",formatter:Slick.Formatters.Agent, sortable:true}]);

		if(common.settings_org.phone_col == "1"){
			$.merge(columns,[{id: "phone", name:lang['grid.title.phone'], field:"phone", width:115, formatter:Slick.Formatters.Phone}]);
		}

		$.merge(columns,[{id:"finish_status",name:lang['grid.title.finish_status'],field:"finish_status",formatter:Slick.Formatters.FinishStatus,sortable:true}]);



		/*
		* Создание грида
		*/
		var model = new Slick.Data.RemoteModel({BaseUrl:manager.baseUrl+'?act=view&s=delegate',PageSize:200});	
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
					case 'user_id':
						model.setAgentOrder(sortHandle.sortAsc);
						vp = grid.getViewport();
						model.applyFilter(vp.top,vp.bottom);
					break;
					case 'finish_status':
						model.setFinishStatusOrder(sortHandle.sortAsc);
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

		$('#f_user_id').change(function(event){
			vp = grid.getViewport();
			model.setUserId($(this).val());
			model.applyFilter(vp.top,vp.bottom);
		})

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


		$('#f_number_from').keyfilter(/[\d]/);
		$('#f_number_from').keydown(function(event){
			if(event.which == 13){
				vp = grid.getViewport();
				model.setNumberFrom($(this).val());
				model.setNumberTo($('#f_number_to').val());
				model.applyFilter(vp.top,vp.bottom);
			}
		});

		$('#f_number_to').keyfilter(/[\d]/);
		$('#f_number_to').keydown(function(event){
			if(event.which == 13){
				vp = grid.getViewport();
				model.setNumberFrom($('#f_number_from').val());
				model.setNumberTo($(this).val());
				model.applyFilter(vp.top,vp.bottom);
			}
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
			$('#f_user_id').val('');
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
		manager.orders.grid = grid;
	});