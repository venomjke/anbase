<?php
	load_partial_template($template,'dashboard_head');
?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu') ?>
	<?php load_partial_template($template,'dashboard_filter'); ?>
	<div class="content">
		<?php load_partial_template($template,'dashboard_tabs'); ?>
	    <div class="tablica" id="orders_grid" style="height:550px; border:1px #555 solid;">
	    </div>
	    <div class="nastroiki"><a href="111">Настройки таблицы</a></div>
  	</div>
  	<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
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
			  cell_content.attr('onmousemove','common.show_full_text(event,'+row+','+cell+',agent.orders.grid.getDataItem('+row+').description);');
			  cell_content.attr('onmouseout','common.hide_full_text(event,'+row+','+cell+');');
			  var wrap = $('<div>').html(cell_content);
			  return wrap.html();
			};

			/*
			* Настройки грида
			*/
			var options = {enableCellNavigation: true,rowHeight:25,forceFitColumns:true};
			var columns = [
				{id: "number", name:"Номер", field:"number"},
				{id: "create_date", name:"Дата создания", field:"create_date"},
				{id: "category", name:"Объект", field:"category"},
				{id: "deal_type", name:"Сделка", field:"deal_type"},
				{id: "regions",  name:"Район", field:"regions", formatter:Slick.Formatters.RegionsList},
				{id: "metros", name:"Метро", field:"metros",  formatter:Slick.Formatters.MetrosList},
				{id: "price", name:"Цена", field:"price",  formatter:Slick.Formatters.Rubbles},	
				{id: "description", name:"Описание", field:"description",cssClass:"cell_description", width:303, formatter:DescriptionFormatter},
			];	
			/*
			* Создание грида
			*/
			agent.orders.grid = new Slick.Grid("#orders_grid",data,columns,options);
		}

		/*
		* Загрузка и инициализация грида
		*/
	    $.ajax({
	    	url:agent.baseUrl+'?act=view&s=free',
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