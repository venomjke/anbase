<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap1">
	<div id="orders_grid" style="height:550px;border:1px #555 solid">

	</div>
</div>
<script type="text/javascript">
	$(function(){

		/*
		* Настройки грида
		*/

		var options = {
			enableCellNavigation: true,
			editable:true,
			autoEdit:false,
			rowHeight:45,
			enableAddRow:true
		};

		var data = [];

		/*
		* Хелперы.
		*/
		agent.orders.show_full_text = function(event,row,cell){
			var tooltip_id = '#tooltip_'+row+'_'+cell;
			if($(tooltip_id).length>0){
				$(tooltip_id).css('top',event.pageY-$(tooltip_id).outerHeight(true)-20);
				$(tooltip_id).css('left',event.pageX-$(tooltip_id).outerWidth()-20);
			}else{
				var tooltip = $('<div>').html(data[row].description.replace(/\n/g,'<br\/>'));
				tooltip.attr('id','tooltip_'+row+'_'+cell);
				tooltip.attr('class','cell_tooltip');
				tooltip.css('display','none');

				tooltip.css('width',450);
				$('body').append(tooltip);	
				tooltip.css('position','absolute');
				tooltip.css('top',event.pageY-tooltip.outerHeight(true)-20);
				tooltip.css('left',event.pageX-tooltip.outerWidth()-20);
				tooltip.css('display','block');
			}
		}

		agent.orders.hide_full_text = function(event,row,cell){
			$('#tooltip_'+row+'_'+cell).remove();
		}

		/*
		* Мои форматтеры
		*/
		function descriptionFormatter(row,cell,value,columnDef,dataContext){
			var cell_content = $('<div>').html(value);
			cell_content.css('height','40');
			cell_content.css('overflow','hidden');
			cell_content.attr('onmousemove','agent.orders.show_full_text(event,'+row+','+cell+');');
			cell_content.attr('onmouseout','agent.orders.hide_full_text(event,'+row+','+cell+');');
			var wrap = $('<div>').html(cell_content);
			return wrap.html();
		}

		var columns = [
			{id: "number", name:"Номер", field:"number", width:70, height:100, editor:Slick.Editors.Text },
			{id: "create_date", name:"Дата создания", field:"create_date", width:118, editor:Slick.Editors.Date},
			{id: "category", name:"Объект", field:"category", width:75, editor:Slick.Editors.AnbaseCategory },
			{id: "deal_type", name:"Сделка", field:"deal_type", width:80, editor:Slick.Editors.AnbaseDealType },
			{id: "regions",  name:"Район", field:"regions", width:141, editor:Slick.Editors.AnbaseRegions, formatter:Slick.Formatters.RegionsList },
			{id: "metro", name:"Метро", field:"metro", width:140, editor:Slick.Editors.AnbaseMetro, formatter:Slick.Formatters.MetroList },
			{id: "price", name:"Цена", field:"price", width:100, formatter:Slick.Formatters.Rubbles, editor:Slick.Editors.Text },	
			{id: "description", name:"Описание", field:"description", width:400, formatter:descriptionFormatter, editor:Slick.Editors.LongText},
			{id: "phone", name:"Телефон", field:"phone", width:160, editor:Slick.Editors.Text }
		];


		for (var i = 0; i < 100; i++) {
	      data[i] = {
	        number: i,
	        create_date: "05.04.2012",
	        category:'Жилая',
	        deal_type:'Сниму',
	        regions:[17,1,3],
	        metro:[36,19,26],
	        price:'1000',
	        description:i+" Коммунальные Услуги, Сдается Впервые, Развитая Инфраструктура, \n Гражданство РФ, Для 1 Женщины, Для 1 Мужчины, Для 1-3 Человек, Для Пары, Для Семьи, Есть Все необходимое Оборудование и Мебель!, Фото Объекта Предоставлю по Запросу, Зеленый Уютный Благоустроенный Квартал, Транспорт в Зоне Пешеходной Доступности, Парковая Зона, Рядом ст.Метро, Фитнес-Центр с Бассейном, Рядом:, 1-й Елагин мост",
	        phone:"+7 (921) 984-40-40"
	      };
	    };
		agent.orders.grid = new Slick.Grid("#orders_grid", data, columns, options);

		/*
		* Обработчики событий
		*/
		agent.orders.grid.onClick.subscribe(function(e){
			cellHandle = agent.orders.grid.getCellFromEvent(e);
			column = agent.orders.grid.getColumns()[cellHandle.cell];
			if(column.id == "description"){
			}
		});1

		agent.orders.grid.onMouseEnter.subscribe(function(e){
			cellHandle = agent.orders.grid.getCellFromEvent(e);
			column = agent.orders.grid.getColumns()[cellHandle.cell];

			if(column.id == "description"){
			}
		});

		agent.orders.grid.onMouseLeave.subscribe(function(e){
			cellHandle = agent.orders.grid.getCellFromEvent(e);
			column     = agent.orders.grid.getColumns()[cellHandle.cell];

			if(column.id == "description"){}
		})

	});
</script>