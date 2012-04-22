<?php
	load_partial_template($template,'dashboard_head');
?>
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

		var model = new Slick.Data.RemoteModel({BaseUrl:agent.baseUrl+'?act=view&s=free',PageSize:200});	
		var grid = new Slick.Grid("#orders_grid",model.data,columns,options);

		grid.onViewportChanged.subscribe(function(e,args){
			var vp = grid.getViewport();
			model.ensureData(vp.top,vp.bottom);
		});

		model.onDataLoading.subscribe(function(){
			common.showAjaxIndicator();
		});

		model.onDataLoaded.subscribe(function(e,args){
			for (var i = args.from; i <= args.to; i++) {
		    	grid.invalidateRow(i);
		  	}
		  	grid.updateRowCount();
		  	grid.render();
			common.hideAjaxIndicator();
		});
		agent.orders.grid = grid;
		grid.onViewportChanged.notify();

	});
</script>