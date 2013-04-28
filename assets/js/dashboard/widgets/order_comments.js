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


    function is_order_comments_changed () {
      return added_comments.length || del_comments.length;
    }

    /*
    * Добавление нового комментария.
    */
    //TODO: код частично пересекается с add_comment, подумать над объединением.
    function add_new_comment (text) {

      if( ! text){
        return false;
      }

      var comment = {
        text: text
      };

      // тело комментария
      var $comment = $('<table class="widget-comments-comment" cellspacing="0"><tr><td colspan="3">' + common.fromText2Html(comment.text) + '</td></tr><tr><td><a href="#" class="widget-button widget-button-small widget-button-red widget-button-delete">' + lang['widgets.buttons.delete'] + '</a></td><td class="widget-comments-user">' + common.userName + '</td><td class="widget-comments-date">' + common.localDate() + '</td></tr></table>');

      var $delBtn  = $comment.find('.widget-button-delete');
      // Сохраняем dom и обычный объект
      $delBtn.data('$comment', $comment);
      $delBtn.data('comment', comment);

      $delBtn.click(function(event){
        event.preventDefault();
        var $comment = $(this).data('$comment');
        var comment = $(this).data('comment');
        $comment.remove();
        added_comments.splice(added_comments.indexOf(comment), 1);
      });

      added_comments.push({text: text});
      $comments.prepend($comment);
    }

    /*
    * Вывод комментария
    */
    function add_comment (comment) {
      // клавиша удаления комментария
      var delPointer = options.delComments ? '<td><a href="#" class="widget-button widget-button-small widget-button-red widget-button-delete">' + lang['widgets.buttons.delete'] + '</a></td>': '';

      // тело комментария
      var $comment = $('<table class="widget-comments-comment" cellspacing="0"><tr><td colspan="3">' + common.fromText2Html(comment.text) + '</td></tr><tr>' + delPointer + '<td class="widget-comments-user">' + comment.last_name + ' ' + comment.name + ' ' + comment.middle_name + '</td><td class="widget-comments-date">' + comment.date_created + '</td></tr></table>');

      var $delBtn = $comment.find('.widget-button-delete');
      // сохраняем DOM и обычный объект
      $delBtn.data('$comment', $comment);
      $delBtn.data('comment', comment);

      $delBtn.click(function(event){
        event.preventDefault();
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
        $wrapper = $('<div class="widget-modal"><div class="widget-modal-title">' + lang['widgets.comments.title'] + '<img class="widget-icons-close" title="' + lang['widgets.icons.close'] + '" src="' + common.baseUrl + 'assets/images/dashboard/delete.png" /></div></div>').appendTo($container);

        // настраиваем эффекты
        $wrapper.draggable({ handle: ".widget-modal-title"});

        // список комментариев
        $comments = $('<div class="widget-comments-area"></div>');

        // форма добавления комментария
        $form = $('<div class="widget-comments-textarea"><textarea placeholder="' + lang['widgets.comments.text-placeholder'] + '" cols="10" rows="5"></textarea><br/><a href="#" class="widget-button widget-button-white widget-button-medium"> ' + lang['widgets.buttons.add'] + '</a></div>');
        $formTextArea = $form.find('textarea');
        $formAddBtn   = $form.find('a');

        $formAddBtn.click(function(event){
          event.preventDefault();
          var text = $formTextArea.val();
          $formTextArea.val('');
          add_new_comment(text);
        });

        $wrapper.find('.widget-modal-title img').click(function(event){
          cancel_btn(event);
        });

        $wrapper.append($comments);
        $wrapper.append($form);
        
        // Кнопки онка
        var $right = $('<div class="widget-modal-buttons"><a class="widget-button widget-button-medium widget-button-green widget-button-save" href="#">' + lang['widgets.buttons.save'] + '</a><a href="#" class="widget-button widget-button-medium widget-button-orange widget-button-cancel">' + lang['widgets.buttons.cancel'] + '</a></div>');
        $right.find('.widget-button-save').click(function(event){
          event.preventDefault();
          save_btn(event);
        });
        $right.find('.widget-button-cancel').click(function(event){
          event.preventDefault();
          cancel_btn(event);
        });

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