$(function(){

  $.extend(true,window,{
    "common":{
      "widgets":{
        "order_comments": order_comments        
      }
    }
  });

  function order_comments(options){


    var settings = {
      uri: '',
      // добавление комментариев
      addComments: true,
      // удаление комментариев
      delComments: true,
      // Список комментариев
      comments:[], 
      // callbacks
      onSave:function(){},
      onCancel:function(){}
    };

    options = $.extend(true, settings, options);

    // обертка
    var $wrapper;
    // раздел для просмотра комментариев
    var $comments;
    // форма добавления комментария
    var $form;
    
    // buttons для управления окном
    var $saveBtn;
    var $cancelBtn;
    var $resetBtn;

    var added_comments = [];
    var del_comments   = [];

    function save_btn(event){
      // попытка сохранения
      $.ajax({
        url: common.baseUrl + options.uri,
        type: 'POST',
        dataType:'json',
        data:{
          added_comments: added_comments,
          del_comments: del_comments,
          order_id: options.order_id
        },
        success: function(response){
          if(response.code == 'success_comments'){
            options.comments = response.data;
            $wrapper.remove();
            options.onSave(); 
          } else {
            console.log(response);
          }
        }
      })
    }

    function cancel_btn(event){
      $wrapper.remove();
      options.onCancel();
    }

    function reset_btn(event){
      added_comments = [];
      del_comments   = [];
    }


    function is_order_comments_changed () {
      return added_comments.length || del_comments.length;
    }

    /*
    * Добавление нового комментария.
    */
    //TODO: код частично пересекается с add_comment, подумать над объединением.
    function add_new_comment (text) {
      var comment = {
        text: text
      };
      var now = new Date();
      var $comment = $('<div><table style="width:100%"><tbody><tr><td style="width:40%"> - </td><td style="width:55%"> Вы </td><td><img title="удалить" src="'+ common.baseUrl +'themes/dashboard/images/delete.png"> </td></tr><tr><td colspan="3">' + text + '</td></tr></tbody></table></div>');
      var $delBtn  = $comment.find('img');
      // Сохраняем dom и обычный объект
      $delBtn.data('$comment', $comment);
      $delBtn.data('comment', comment);

      $delBtn.click(function(){
        var $comment = $(this).data('$comment');
        var comment = $(this).data('comment');
        $comment.remove();
        added_comments.splice(added_comments.indexOf(comment), 1);
      });

      added_comments.push({text: text});
      $comments.append($comment);
    }

    /*
    * Вывод комментария
    */
    function add_comment (comment) {
     var $comment = $('<div><table style="width:100%"><tbody><tr><td> ' + comment.date_created + ' </td><td> ' + comment.last_name + ' ' + comment.middle_name + ' ' + comment.name + ' </td><td><img title="удалить" src="'+ common.baseUrl +'themes/dashboard/images/delete.png"> </td></tr><tr><td colspan="3">' + comment.text + '</td></tr></tbody></table></div>');
     var $delBtn = $comment.find('img');

     // сохраняем dom и обычный объект
     $delBtn.data('$comment', $comment);
     $delBtn.data('comment', comment);

     $delBtn.click(function(){
        var $comment = $(this).data('$comment');
        var comment  = $(this).data('comment');
        $comment.remove();
        del_comments.push(comment.id);
     });

     $comments.append($comment);
    }

    return {
      init:function(){
        var $container = $('body');
        $wrapper = $("<div style='z-index:99999; position:absolute; background-color:#fff; opacity:.95; padding:5px; border:1px #b4b4b4 solid;'></div>").appendTo($container);

        // список комментариев
        $comments = $('<div style="width:450px"></div>');

        // форма добавления комментария
        $form = $('<div><textarea style="width:98%;" cols="10" rows="5" id="comment_text"></textarea><br/><button id="add_new_comment">Добавить</button></div>');
        $form.find('#add_new_comment').click(function(){
          var text = $('#comment_text').val();
          add_new_comment(text);
        });

        $closeImg = $('<img title="Закрыть без сохранения" style="position:relative; top:0; left:96%; cursor:pointer;" src="' + common.baseUrl + 'themes/dashboard/images/delete.png" />');
        $closeImg.click(function(event){
          cancel_btn(event);
        });

        $wrapper.append($closeImg);
        $wrapper.append($comments);
        $wrapper.append($form);
        
        var $right = $('<div style="float:right;"></div>');

        $saveBtn = $('<button id="save_btn">Сохранить</button>');
        $cancelBtn = $('<button id="cancel_btn">Отмена</button>"');
        $resetBtn= $('<button id="reset_btn">Сбросить</button>');
        $saveBtn.click(function(event){
          save_btn(event);
        });
        $cancelBtn.click(function(event){
          cancel_btn(event);
        });
        $resetBtn.click(function(event){
          reset_btn(event);
        });

        $right.append($saveBtn);
        $right.append($cancelBtn);
        $right.append($resetBtn);  
        $wrapper.append($right);  

        $wrapper.center();  
      },

      show:function(){
        $wrapper.show();
      },

      hide:function(){
        $wrapper.hide();
      },

      destroy:function(){
        $wrapper.remove();
      },

      focus:function(){

      },

      load:function(comments, order_id){
        options.comments = comments;
        options.order_id = order_id;
        for(var i in comments){
          add_comment(comments[i]);
        }
      },

      serialize:function(){
        return {
          comments: options.comments
        }
      },

      isValueChanged:function(){
        return is_order_comments_changed();
      }
    };
  }

})