<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu') ?>
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
		function load_grid(data){		
			/*
			* Настройки грида
			*/
			var options = {enableCellNavigation: true,rowHeight:25,forceFitColumns:true};
			var columns = [
				{id: "last_name", name:"Фамилия", field:"last_name"},
				{id: "name", name:"Имя", field:"name"},
				{id: "middle_name", name:"Отчество", field:"middle_name"},
				{id: "phone", name:"Телефон", field:"phone", formatter:Slick.Formatters.Phone},
				{id: "email", name:"Email", field:"email"},
				{id: "role", name:"Роль", field:"role"}				
			];	
			/*
			* Создание грида
			*/
			var grid = new Slick.Grid("#orders_grid",data,columns,options);
			manager.user.staff = grid;
		}
		/*
		* Загрузка и инициализация грида
		*/
	    $.ajax({
	    	url:manager.baseUrl+'/staff/?act=view',
	    	type:'POST',
	    	dataType:'json',
	    	success:function(response){
	    		if(response.code && response.data){
	    			manager.orders.grid_data = response.data;
	    			switch(response.code){
	    				case 'success_view_user':
	    					load_grid(response.data);
	    				break;
	    				case 'error_view_user':
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