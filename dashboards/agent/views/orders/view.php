<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu') ?>
	<?php load_partial_template($template,'dashboard_filter'); ?>
	<div class="content">
		<?php load_partial_template($template,'dashboard_tabs'); ?>
	    <div class="tablica" id="orders_grid" style="height:550px; border:1px #8AA1BC solid;">
	    </div>
	    <div class="nastroiki"><a href="111">Настройки таблицы</a></div>
  	</div>
  	<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
</div>

<script type="text/javascript">
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
		var options = {enableCellNavigation: true,editable:true,autoEdit:false,rowHeight:25,forceFitColumns:true};
		var columns = [
			{id: "number", name:"Номер", field:"number", editor:Slick.Editors.Integer },
			{id: "create_date", name:"Дата создания", field:"create_date",  editor:Slick.Editors.Date},
			{id: "category", name:"Тип объекта", field:"category", editor:Slick.Editors.AnbaseCategory},
			{id: "deal_type", name:"Сделка", field:"deal_type", editor:Slick.Editors.AnbaseDealType},
			{id: "regions",  name:"Район", field:"regions",  editor:Slick.Editors.AnbaseRegions,formatter:Slick.Formatters.RegionsList},
			{id: "metros", name:"Метро", field:"metros",  editor:Slick.Editors.AnbaseMetros,formatter:Slick.Formatters.MetrosList},
			{id: "price", name:"Цена", field:"price",  formatter:Slick.Formatters.Rubbles,editor:Slick.Editors.Integer},	
			{id: "description", name:"Описание", field:"description",cssClass:"cell_description", width:303, formatter:DescriptionFormatter, editor:Slick.Editors.LongText},
			{id: "phone", name:"Телефон", field:"phone", width:115, formatter:Slick.Formatters.Phone, editor:Slick.Editors.Integer}
		];	
		/*
		* Создание грида
		*/
		var model = new Slick.Data.RemoteModel({BaseUrl:agent.baseUrl+'?act=view&s=my',PageSize:200});	
		var grid = new Slick.Grid("#orders_grid",model.data,columns,options);

		/*
		* Сохраняем backup значение
		*/
		grid.onBeforeEditCell.subscribe(function(e,handle){
			handle.item.backupFieldValue = handle.item[handle.column.field]; 
		});
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

		$('#f_number').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setNumber($(this).val());
				model.applyFilter(vp.top,vp.bottom);
			}else if(!(event.which >= 48 && event.which <= 57 || event.which == 8 || event.which == 9)){
				event.preventDefault();
			}
		});

		$('#f_phone').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setPhone($(this).val());
				model.applyFilter(vp.top,vp.bottom);
			}else if(!(event.which >= 48 && event.which <= 57 || event.which == 8 || event.which == 9)){
				event.preventDefault();
			}
		});

		$('#f_price_to').keydown(function(event){
			if(event.which == 13){
				event.preventDefault();
				vp = grid.getViewport();
				model.setPriceTo($(this).val());
				model.setPriceFrom($('#f_price_from').val());
				model.applyFilter(vp.top,vp.bottom);
			}else if(!(event.which >= 48 && event.which <= 57 || event.which == 8 || event.which == 9)){
				event.preventDefault();
			}
		});


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
</script>