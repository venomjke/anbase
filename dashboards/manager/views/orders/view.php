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
			* [my_notice: нужно как-то отсюда убрать форматтер, мне не нравится что он здесь находится]
			*/
			function DescriptionFormatter(row,cell,value,columnDef,dataContext){
				if(!value)
					return;
			  var cell_content = $('<div id="cell_description" style="height:40px;overflow:hidden;">').html(value);
			  cell_content.attr('onmousemove','common.show_full_text(event,'+row+','+cell+',manager.orders.grid.getDataItem('+row+').description);');
			  cell_content.attr('onmouseout','common.hide_full_text(event,'+row+','+cell+');');
			  var wrap = $('<div>').html(cell_content);
			  return wrap.html();
			};

			/*
			* Настройки грида
			*/
			var options = {enableCellNavigation: true,editable:true,autoEdit:false,rowHeight:35,forceFitColumns:true};
			var columns = [
				{id: "number", name:"Номер", field:"number", editor:Slick.Editors.Text },
				{id: "create_date", name:"Дата создания", field:"create_date",  editor:Slick.Editors.Date},
				{id: "category", name:"Объект", field:"category", editor:Slick.Editors.AnbaseCategory},
				{id: "deal_type", name:"Сделка", field:"deal_type", editor:Slick.Editors.AnbaseDealType},
				{id: "regions",  name:"Район", field:"regions",  editor:Slick.Editors.AnbaseRegions,formatter:Slick.Formatters.RegionsList},
				{id: "metros", name:"Метро", field:"metros",  editor:Slick.Editors.AnbaseMetros,formatter:Slick.Formatters.MetrosList},
				{id: "price", name:"Цена", field:"price",  formatter:Slick.Formatters.Rubbles,editor:Slick.Editors.Text},	
				{id: "description", name:"Описание", field:"description",cssClass:"cell_description", width:303, formatter:DescriptionFormatter, editor:Slick.Editors.LongText},
				{id: "phone", name:"Телефон", field:"phone",  editor:Slick.Editors.Text}
			];	
			/*
			* Создание грида
			*/
			manager.orders.grid = new Slick.Grid("#orders_grid",data,columns,options);
		}

		/*
		* Загрузка и инициализация грида
		*/
	    $.ajax({
	    	url:manager.baseUrl+'?act=view&sct=my',
	    	type:'POST',
	    	dataType:'json',
	    	success:function(response){
	    		if(response.code && response.data){
	    			manager.orders.grid_data = response.data;
	    			switch(response.code){
	    				case 'success_view_orders':
	    					load_grid(response.data);
	    				break;
	    				case 'error_view_orders':
	    					common.showResultMsg("Загрузка не удалась, обновите страницу");
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