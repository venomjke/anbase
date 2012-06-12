$(function(){
	/*
	* Настройки грида
	*/
	var options = {enableCellNavigation: true,rowHeight:25,forceFitColumns:true,enableTextSelectionOnCells:true};
	var checkboxSelector = new Slick.CheckboxSelectColumn({
  		cssClass: "slick-cell-checkboxsel"
	});
	var columns = [];
	columns.push(checkboxSelector.getColumnDefinition());
	$.merge(columns,[
		{id:"register_url",name:"Url регистрации",field:"key_id",width:400, formatter:Slick.Formatters.InviteKey },
		{id:"email", name:"Email", field:"email"},
		{id:"role", name:"Должность", field:"role",formatter:Slick.Formatters.Role},
		{id:"created",name:"Дата создания", field:"created"}	
	]);	


	/*
	* Создание грида
	*/
	var model = new Slick.Data.InviteModel({BaseUrl:admin.baseUrl+'/invites/?act=view',DeleteUrl:admin.baseUrl+'/invites/?act=del',AddUrl:admin.baseUrl+'/invites/?act=add',PageSize:200});

	/*
	* Создание грида
	*/
	var grid = new Slick.Grid("#orders_grid",model.data,columns,options);
    grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow: false}));
    grid.registerPlugin(checkboxSelector);
	common.grid = grid;

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

	/*
	* Обработчики "удалить" "добавить"
	*/
	$('#del_invites').click(function(){
		admin.invites.del_invites(grid,model);
	});
	model.onDataDeleting.subscribe(function(e,args){
		common.showAjaxIndicator()
	});
	model.onDataDeleted.subscribe(function(e,args){
		common.hideAjaxIndicator();
		grid.setSelectedRows([]);
		vp = grid.getViewport();
		model.reloadAll(vp.top,vp.bottom);
	});

	/*
	* Добавление агента,менеджера, админа
	*/
	$('#add_agent').click(function(){
		admin.invites.add_agent(grid,model);
	});

	$('#add_manager').click(function(){
		admin.invites.add_manager(grid,model);
	});

	$('#add_admin').click(function(){
		admin.invites.add_admin(grid,model);
	});

	model.onDataCreating.subscribe(function(e,args){
		common.showAjaxIndicator();
	});

	model.onDataCreated.subscribe(function(e,args){
		common.hideAjaxIndicator();
		grid.setSelectedRows([]);
		vp = grid.getViewport();
		model.reloadAll(vp.top,vp.bottom);
	});


	grid.onViewportChanged.notify();
	admin.invites.grid = grid;
});