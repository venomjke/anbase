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
        {id: "last_name", name:"Фамилия", field:"last_name"},
        {id: "name", name:"Имя", field:"name"},
        {id: "middle_name", name:"Отчество", field:"middle_name"},
        {id: "phone", name:"Телефон", field:"phone", formatter:Slick.Formatters.Phone},
        {id: "email", name:"Email", field:"email"},
        {id: "role", name:"Должность", field:"role"}                
    ]); 

    /*
    * Создание грида
    */
    var modelInvite = new Slick.Data.InviteModel({AddUrl:admin.baseUrl+'/invites/?act=add'});
    var modelUser = new Slick.Data.UserModel({BaseUrl:admin.baseUrl+'/staff/?act=view',DeleteUrl:admin.baseUrl+'/staff/?act=del',PageSize:1000});

    /*
    * Создание грида
    */
    var grid = new Slick.Grid("#orders_grid",modelUser.data,columns,options);
    grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow: false}));
    grid.registerPlugin(checkboxSelector);
    common.grid = grid;

    grid.onViewportChanged.subscribe(function(e,args){
        var vp = grid.getViewport();
        modelUser.ensureData(vp.top,vp.bottom);
    });

    modelUser.onDataLoading.subscribe(function(){
        common.showAjaxIndicator();
    });
    modelUser.onDataLoaded.subscribe(function(e,args){
        for (var i = args.from; i <= args.to; i++) {
            grid.invalidateRow(i);
        }
        grid.updateRowCount();
        grid.render();
        common.hideAjaxIndicator();
    });

    /*
    * Удаление пользователей
    */

    $('#del_users').click(function(){
        admin.user.del_users(grid,modelUser);
    });

    modelUser.onDataDeleting.subscribe(function(e,args){
        common.showAjaxIndicator()

    });

    modelUser.onDataDeleted.subscribe(function(e,args){
        common.hideAjaxIndicator();
        grid.setSelectedRows([]);
        vp = grid.getViewport();
        modelUser.reloadAll(vp.top,vp.bottom);
    });
    /*
    * Добавление агента,менеджера, админа
    */
    $('#add_agent').click(function(){
        admin.invites.add_agent(grid,modelInvite);
    });

    $('#add_manager').click(function(){
        admin.invites.add_manager(grid,modelInvite);
    });

    $('#add_admin').click(function(){
        admin.invites.add_admin(grid,modelInvite);
    });

    modelInvite.onDataCreating.subscribe(function(e,args){
        common.showAjaxIndicator();
    });

    modelInvite.onDataCreated.subscribe(function(e,args){
        common.hideAjaxIndicator();
    });

    grid.onViewportChanged.notify();
    admin.user.grid = grid;
});