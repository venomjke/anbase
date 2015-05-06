(function ($) {
  /***
   * A sample AJAX data store implementation.
   * Right now, it's hooked up to load all Apple-related Digg stories, but can
   * easily be extended to support and JSONP-compatible backend that accepts paging parameters.
   */
  function RemoteModel(options) {

    var def_options = {
      PageSize:100,
      BaseUrl:'',
      AddUrl:'',
      DeleteUrl:'',
      finishUrl:'',
      RestoreUrl:'',
      PrintUrl:''
    };
    options = $.extend(true,def_options,options);

    /*
    * filters
    */
    var number = '';
    var number_to = '';
    var number_from = '';
    var number_order='';
    var phone  = '' ;
    var category = '';
    var dealtype = '';
    var price_to ='';
    var price_from ='';
    var price_order = '';
    var createdate_to ='';
    var createdate_from = '';
    var createdate_order='';
    var description ='';
    var description_type ='';
    var user_id = '';
    var agent_order = '';
    var metros = {};
    var regions = [];
    var finishStatusOrder = '';

    // private
    var data = [];
    var h_request = null;
    var req = null; // ajax request

    // events
    var onDataLoading = new Slick.Event();
    var onDataLoaded = new Slick.Event();
    var onDataCreating = new Slick.Event();
    var onDataCreated = new Slick.Event();
    var onDataDeleting = new Slick.Event();
    var onDataDeleted = new Slick.Event();
    var onDataFinish  = new Slick.Event();
    var onDataFinished = new Slick.Event();
    var onDataRestore = new Slick.Event();
    var onDataRestored = new Slick.Event();


    function init() {

    }


    function setNumber(f_number){
      number = f_number;
    }
    function setNumberTo(f_number_to)
    {
      number_to = f_number_to;
    }
    function setNumberFrom(f_number_from){
      number_from = f_number_from;
    }
    function setNumberOrder(f_number_order){
      number_order = f_number_order?1:-1;
    }
    function setPhone(f_phone){
      phone = f_phone;
    }

    function setCategory(f_category){
      category = f_category;
    }

    function setDealtype(f_dealtype){
      dealtype = f_dealtype;
    }

    function setPriceTo(f_price_to){
        price_to = f_price_to;
    }
    function setPriceFrom(f_price_from){
      price_from = f_price_from;
    }
    function setPriceOrder(f_price_order){
      price_order = f_price_order?1:-1;
    }
    function setCreateDateFrom(f_createdate_from){
      createdate_from = f_createdate_from;
    }

    function setCreateDateTo(f_createdate_to){

      createdate_to = f_createdate_to;
    }
    function setCreateDateOrder(f_createdate_order){
      createdate_order = f_createdate_order?1:-1;
    }
    function setDescription(f_description){
      description = f_description;
    }

    function setDescriptionType(f_description_type){
      description_type = f_description_type;
    }

    function setMetros(f_metros){
      if(f_metros)
        metros = f_metros;
    }

    function setRegions(f_regions){
      if(f_regions)
        regions = f_regions;
    }

    function setUserId(f_user_id){
      user_id = f_user_id;
    }

    function setAgentOrder(f_agent_order){
      agent_order = f_agent_order?1:-1;
    }

    function setFinishStatusOrder(f_finishStatusOrder){
      finishStatusOrder = f_finishStatusOrder?1:-1;
    }

    function applyFilter(top,bottom){
      clear();
      ensureData(top,bottom);
    }

    function resetFilter(){
        number = '';
        number_to = '';
        number_from = '';
        number_order='';
        phone  = '' ;
        category = '';
        dealtype = '';
        price_to ='';
        price_from ='';
        price_order = '';
        createdate_to ='';
        createdate_from = '';
        createdate_order='';
        description ='';
        description_type ='';
        user_id = '';
        agent_order='';
        finishStatusOrder='';
        metros = {};
        regions = [];
    }

    function resetSortOrder(){
      number_order = '';
      createdate_order = '';
      price_order = '';
      agent_order='';
      finishStatusOrder='';
    }

    function isDataLoaded(from, to) {
      for (var i = from; i <= to; i++) {
        if (data[i] == undefined || data[i] == null) {
          return false;
        }
      }

      return true;
    }


    function clear() {
      for (var key in data) {
        delete data[key];
      }
      data.length = 0;
      // delete data;
      // data = [];
    }


    function ensureData(from, to) {
      /*
      * Небольшое пояснение этой конструкции. При прокручивании возможно такое, что загрузчик будет просто неуспевать загружать новые данные, а человек будет прокручивать все ниже и ниже. Для того, чтобы не было проблем с загрузкой и лишний запросов к серверу, будет просто return'ить такие запросы.
      */
      if (req) {
        return;
      }

      if (from < 0) {
        from = 0;
      }

      var fromPage = Math.floor(from / options.PageSize);
      var toPage = Math.floor(to / options.PageSize);
      while (data[fromPage * options.PageSize] !== undefined && fromPage < toPage)
        fromPage++;

      while (data[toPage * options.PageSize] !== undefined && fromPage < toPage)
        toPage--;

      if (fromPage > toPage || ((fromPage == toPage) && data[fromPage * options.PageSize] !== undefined)) {
        // TODO:  look-ahead
        return;
      }

      var url =  options.BaseUrl;
      var offset = fromPage*options.PageSize;
      var limit  = options.PageSize;

      var param = {
        number:number,
        number_from:number_from,
        number_to:number_to,
        number_order:number_order,
        phone:phone,
        category:category,
        dealtype:dealtype,
        price_to:price_to,
        price_from:price_from,
        price_order:price_order,
        createdate_to:createdate_to,
        createdate_from:createdate_from,
        createdate_order:createdate_order,
        description:description,
        description_type:description_type,
        metros:metros,
        regions:regions,
        user_id:user_id,
        agent_order:agent_order,
        offset:offset,
        limit:limit,
        finishStatusOrder:finishStatusOrder
      }

      
      for (var i = fromPage; i <= toPage; i++)
          data[i * options.PageSize] = null; // null indicates a 'requested but not available yet'

      onDataLoading.notify({from: from, to: to});

      req = $.ajax({
        url: url,
        dataType:'json',
        type:'GET',
        data:param,
        success: onSuccess,
        error: function () {
          common.hideAjaxIndicator();
        }
      });
      req.fromPage = fromPage;
      req.toPage = toPage;
    }
    function onSuccess(response) {
      if(response.code && response.data){
        switch(response.code){
          case 'success_load_data':
            var from = req.fromPage * options.PageSize, to = from + response.data.count;
            data.length = parseInt(response.data.total);

            for (var i = 0; i < response.data.items.length; i++) {
              data[from + i] = response.data.items[i];
            }

            req = null;
            onDataLoaded.notify({from: from, to: to});
          break;
          case 'error_load_data':
            req = null;
            console.log('Возникла ошибка во время работы');
            onDataLoaded.notify({from: 0, to: 0});
          break;
        }
      }
    }

    function reloadAll(from,to){
      clear();
      ensureData(from,to);
    }
    function reloadData(from, to) {
      for (var i = from; i <= to; i++)
        delete data[i];
      ensureData(from, to);
    }

    function printOrders(ids){
      window.open(options.PrintUrl+'&'+$.param({'orders':ids}),'_blank');
    }

    function delOrders(ids){
      onDataDeleting.notify();
      $.ajax({
        url:options.DeleteUrl,
        dataType:'json',
        data:{"orders":ids},
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case  'success_del_data':
                common.showSuccessMsg(response.data);
                onDataDeleted.notify();
              break;
              case 'error_del_data':
                common.showErrorMsg(response.data.errors[0]);
              break;
            }
          }
        }
      });
    }

    function restoreOrders(ids){
      onDataRestore.notify();
      $.ajax({
        url:options.RestoreUrl,
        dataType:'json',
        data:{"orders":ids},
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case  'success_restore_data':
                common.showSuccessMsg(response.data);
                onDataRestored.notify();
              break;
              case 'error_restore_data':
                common.showErrorMsg(response.data.errors[0]);
              break;
            }
          }
        }
      });
    }

    function finishOrders(ids){
      onDataFinish.notify();
      $.ajax({
        url:options.finishUrl,
        dataType:'json',
        data:{"orders":ids},
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case  'success_finish_data':
                common.showSuccessMsg(response.data);
                onDataFinished.notify();
              break;
              case 'error_finish_data':
                common.showErrorMsg(response.data.errors[0]);
              break;
            }
          }
        }
      });
    }

    function addOrder(data,callback){
      onDataCreating.notify();
      
      data['state'] = 'on';
      $.ajax({
        url:options.AddUrl,
        dataType:'json',
        data:data,
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case 'success_add_data':
                callback('success',response.data);
                onDataCreated.notify();
                break;
              case 'error_add_data':
                console.log(response.data);
                callback('error',response.data);
                common.hideAjaxIndicator();
                break;
            }
          }
        },
        error:function(){
          common.hideAjaxIndicator();
        }
      });
    }

    init();

    return {
      // properties
      "data": data,

      // filter methods
      "setNumber":setNumber,
      "setNumberTo":setNumberTo,
      "setNumberFrom":setNumberFrom,
      "setNumberOrder":setNumberOrder,
      "setPhone":setPhone,
      "setCategory":setCategory,
      "setDealtype":setDealtype,
      "setPriceFrom":setPriceFrom,
      "setPriceTo":setPriceTo,
      "setPriceOrder":setPriceOrder,
      "setCreateDateTo":setCreateDateTo,
      "setCreateDateFrom":setCreateDateFrom,
      "setCreateDateOrder":setCreateDateOrder,
      "setDescription":setDescription,
      "setDescriptionType":setDescriptionType,
      "setRegions":setRegions,
      "setMetros":setMetros,
      "setUserId":setUserId,
      "applyFilter":applyFilter,
      "resetFilter":resetFilter,
      "resetSortOrder":resetSortOrder,
      "addOrder":addOrder,
      "delOrders":delOrders,
      "finishOrders":finishOrders,
      "restoreOrders":restoreOrders,
      "printOrders":printOrders,
      "setAgentOrder":setAgentOrder,
      "setFinishStatusOrder":setFinishStatusOrder,
      // methods
      "clear": clear,
      "isDataLoaded": isDataLoaded,
      "ensureData": ensureData,
      "reloadData": reloadData,
      "reloadAll":reloadAll,

      // events
      "onDataLoading": onDataLoading,
      "onDataLoaded": onDataLoaded,
      "onDataCreating":onDataCreating,
      "onDataCreated":onDataCreated,
      "onDataDeleting":onDataDeleting,
      "onDataDeleted":onDataDeleted,
      "onDataFinish":onDataFinish,
      "onDataFinished":onDataFinished,
      "onDataRestore":onDataDeleting,
      "onDataRestored":onDataRestored

    };
  }

  // Slick.Data.RemoteModel
  $.extend(true, window, { Slick: { Data: { RemoteModel: RemoteModel }}});
})(jQuery);