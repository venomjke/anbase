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
					return;
			  var cell_content = $('<div id="cell_description" style="height:40px;overflow:hidden;">').html(value);
			  cell_content.attr('onmousemove','common.show_full_text(event,'+row+','+cell+',agent.orders.grid_data['+row+'].description);');
			  cell_content.attr('onmouseout','common.hide_full_text(event,'+row+','+cell+');');
			  var wrap = $('<div>').html(cell_content);
			  return wrap.html();
			};

			/*
			* Настройки грида
			*/
			var options = {enableCellNavigation: true,editable:true,autoEdit:false,rowHeight:33,enableAddRow:true};
			var columns = [
				{id: "number", name:"Номер", field:"number", width:70, height:100, editor:Slick.Editors.Text },
				{id: "create_date", name:"Дата создания", field:"create_date", width:118, editor:Slick.Editors.Date},
				{id: "category", name:"Объект", field:"category", width:75, editor:Slick.Editors.AnbaseCategory},
				{id: "deal_type", name:"Сделка", field:"deal_type", width:80, editor:Slick.Editors.AnbaseDealType},
				{id: "regions",  name:"Район", field:"regions", width:141, editor:Slick.Editors.AnbaseRegions,formatter:Slick.Formatters.RegionsList},
				{id: "metros", name:"Метро", field:"metros", width:140, editor:Slick.Editors.AnbaseMetros,formatter:Slick.Formatters.MetrosList},
				{id: "price", name:"Цена", field:"price", width:100, formatter:Slick.Formatters.Rubbles,editor:Slick.Editors.Text},	
				{id: "description", name:"Описание", field:"description",cssClass:"cell_description", width:400, formatter:DescriptionFormatter, editor:Slick.Editors.LongText},
				{id: "phone", name:"Телефон", field:"phone", width:160, editor:Slick.Editors.Text}
			];	
			/*
			* Создание грида
			*/
			agent.orders.grid = new Slick.Grid("#orders_grid",data,columns,options);
			agent.orders.grid_data = data;

		}

		/*
		* Загрузка и инициализация грида
		*/
	    $.ajax({
	    	url:agent.baseUrl+'orders/?act=view&s=my',
	    	type:'POST',
	    	dataType:'json',
	    	success:function(response){
	    		if(response.code && response.data){
	    			agent.orders.grid_data = response.data;
	    			switch(response.code){
	    				case 'success_view_order':
	    					load_grid(response.data);
	    				break;
	    				case 'error_view_order':
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