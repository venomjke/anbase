<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap1">
	<div id="orders_grid" style="height:550px;border:1px #555 solid">
	</div>
</div>
<script type="text/javascript">
	$(function(){
		function load_grid(data){		
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

			function AgentFormatter(row,cell,value,columnDef,dataContext){
				if(!value)
					return '<a href="#"> Назначить агента </a>';
				var agent_name = dataContext.user_name.charAt(0).toUpperCase();
				var agent_middle_name = dataContext.user_middle_name.charAt(0).toUpperCase();
				var agent_last_name = dataContext.user_last_name.charAt(0).toUpperCase()+dataContext.user_last_name.substr(1,dataContext.user_last_name.length);
				return '<a href="#">'+agent_last_name+' '+agent_name+'.'+agent_middle_name+'</a>';
			}
			/*
			* Настройки грида
			*/
			var options = {enableCellNavigation: true,editable:true,autoEdit:false,rowHeight:35,forceFitColumns:true};
			var columns = [
				{id: "number", name:"Номер", field:"number", editor:Slick.Editors.Integer },
				{id: "create_date", name:"Дата создания", field:"create_date",  editor:Slick.Editors.Date},
				{id: "category", name:"Объект", field:"category", editor:Slick.Editors.AnbaseCategory},
				{id: "deal_type", name:"Сделка", field:"deal_type", editor:Slick.Editors.AnbaseDealType},
				{id: "regions",  name:"Район", field:"regions",  editor:Slick.Editors.AnbaseRegions,formatter:Slick.Formatters.RegionsList},
				{id: "metros", name:"Метро", field:"metros",  editor:Slick.Editors.AnbaseMetros,formatter:Slick.Formatters.MetrosList},
				{id: "price", name:"Цена", field:"price",  formatter:Slick.Formatters.Rubbles,editor:Slick.Editors.Integer},	
				{id: "description", name:"Описание", field:"description",cssClass:"cell_description", width:200, formatter:DescriptionFormatter, editor:Slick.Editors.LongText},
				{id: "phone", name:"Телефон", field:"phone",  editor:Slick.Editors.Integer, formatter:Slick.Formatters.Phone },
				{id: "agent", name:"Агент", field:"user_id", formatter:AgentFormatter},
				{id:"delegate_date", name:"Дата делегирования", field:"delegate_date", editor:Slick.Editors.Date}
			];	
			/*
			* Создание грида
			*/
			var grid = new Slick.Grid("#orders_grid",data,columns,options);

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
					url:admin.baseUrl+'/?act=edit',
					type:'POST',
					dataType:'json',
					data:data,
					success:function(response){
						if(response.code && response.data){
							switch(response.code){
								case 'success_edit_order':
									common.showResultMsg(response.data);
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
										common.showResultMsg(response.data.errors[field]);
									}else{
										common.showResultMsg(response.data.errors[0]);
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
			admin.orders.grid = grid;
		}

		/*
		* Загрузка и инициализация грида
		*/
	    $.ajax({
	    	url:admin.baseUrl+'/?act=view&s=<?php echo $section ?>',
	    	type:'POST',
	    	dataType:'json',
	    	success:function(response){
	    		if(response.code && response.data){
	    			admin.orders.grid_data = response.data;
	    			switch(response.code){
	    				case 'success_view_orders':
	    					load_grid(response.data);
	    				break;
	    				case 'error_view_orders':
	    					common.showResultMsg("Загрузка не удалась")
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
</script>