function transformFieldName(field){
  return field.replace(/[\[\]]/g,'_');
}

function doValidQuery(data,url){
  $.ajax({
    url:url,
    type:'POST',
    dataType:'json',
    data:data,
    success:function(response){
      if(response.code && response.fields){
        switch(response.code){
          case 'success':
            for(var i in response.fields){
              $('.'+transformFieldName(i)).remove(); // удаляем сообщения 
              $('input[name="'+i+'"]').after('<img class="success_icon '+transformFieldName(i)+'" src="/themes/start/images/success.png" />');
            }
            break;
          case 'error':
            for(var i in response.fields){
              var tr = $('input[name="'+i+'"]').parent().parent(); // строка таблицы, в которой содержится input
              $('.'+transformFieldName(i)).remove(); // удаляем сообшения об ошибках
              $('input[name="'+i+'"]').after('<img class="error_icon '+transformFieldName(i)+'" src="/themes/start/images/fail.png"/>');
              tr.after('<tr class="error_row '+transformFieldName(i)+'"><td colspan="2">'+response.fields[i]+'</td></tr>');
            }
            break;
        }
      }
    }
  });
}