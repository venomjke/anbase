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
			{id: "role", name:"Должность", field:"role"}				
		];	
		/*
		* Создание грида
		*/
		var grid = new Slick.Grid("#orders_grid",data,columns,options);
		admin.user.staff = grid;
	}
	/*
	* Загрузка и инициализация грида
	*/
    $.ajax({
    	url:admin.baseUrl+'/staff/?act=view',
    	type:'POST',
    	dataType:'json',
    	success:function(response){
    		if(response.code && response.data){
    			admin.orders.grid_data = response.data;
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