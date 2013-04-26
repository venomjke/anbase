(function ($) {
  /***
   * A sample AJAX data store implementation.
   * Right now, it's hooked up to load all Apple-related Digg stories, but can
   * easily be extended to support and JSONP-compatible backend that accepts paging parameters.
   */
  function InviteModel(options) {

    var def_options = {
      PageSize:100,
      BaseUrl:'',
      DeleteUrl:'',
    };
    options = $.extend(true,def_options,options);

    // private
    var data = [];
    var h_request = null;
    var req = null; // ajax request

    // events
    var onDataLoading = new Slick.Event();
    var onDataLoaded = new Slick.Event();
    var onDataDeleting = new Slick.Event();
    var onDataDeleted = new Slick.Event();
    var onDataCreating = new Slick.Event();
    var onDataCreated = new Slick.Event()


    function init() {

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
        offset:offset,
        limit:limit
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

    function add_agent(data,callback){
      onDataCreating.notify();
      $.ajax({
        url:options.AddUrl+'&for=agents',
        dataType:'json',
        data:data,
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case 'success_send_invite':
                onDataCreated.notify();
                callback('success',response.data);
                break;
              case 'error_send_invite':
                common.hideAjaxIndicator();
                callback('error',response.data);
                break;
            }
          }
        }
      });
    }

    function add_manager(data,callback){
      onDataCreating.notify();
      $.ajax({
        url:options.AddUrl+'&for=managers',
        dataType:'json',
        data:data,
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case 'success_send_invite':
                onDataCreated.notify();
                callback('success',response.data);
                break;
              case 'error_send_invite':
                common.hideAjaxIndicator();
                callback('error',response.data);
                break;
            }
          }
        }
      });
    }

    function add_admin(data,callback){
      onDataCreating.notify();
      $.ajax({
        url:options.AddUrl+'&for=admins',
        dataType:'json',
        data:data,
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case 'success_send_invite':
                onDataCreated.notify();
                callback('success',response.data);
                break;
              case 'error_send_invite':
                common.hideAjaxIndicator();
                callback('error',response.data);
                break;
            }
          }
        }
      });
    }

    function delInvites(ids){
      onDataDeleting.notify();
      $.ajax({
        url:options.DeleteUrl,
        dataType:'json',
        data:{"ids_invites":ids},
        type:'POST',
        success:function(response){
          if(response.code && response.data){
            switch(response.code){
              case  'success_del_data':
                common.showSuccessMsg(response.data);
                onDataDeleted.notify();
              break;
              case 'error_del_data':
                common.hideAjaxIndicator();
                common.showErrorMsg(response.data.errors[0]);
              break;
            }
          }
        }
      });
    }

    init();

    return {
      // properties
      "data": data,

      "delInvites":delInvites,
      "add_agent":add_agent,
      "add_manager":add_manager,
      "add_admin":add_admin,
      // methods
      "clear": clear,
      "isDataLoaded": isDataLoaded,
      "ensureData": ensureData,
      "reloadData": reloadData,
      "reloadAll":reloadAll,

      // events
      "onDataLoading": onDataLoading,
      "onDataLoaded": onDataLoaded,
      "onDataDeleting":onDataDeleting,
      "onDataDeleted":onDataDeleted,
      "onDataCreating":onDataCreating,
      "onDataCreated":onDataCreated

    };
  }

  // Slick.Data.RemoteModel
  $.extend(true, window, { Slick: { Data: { InviteModel: InviteModel }}});
})(jQuery);