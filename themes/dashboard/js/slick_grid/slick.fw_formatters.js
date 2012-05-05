/***
*/

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Formatters": {
        "Rubbles":RubblesFormatter,
        "RegionsList":RegionsListFormatter,
        "MetrosList":MetrosListFormatter,
        "Phone":PhoneFormatter,
        "Agent":AgentFormatter,
        "Description":DescriptionFormatter
      }
    }
  });

  /*
  * Форматтер цена
  */
  function RubblesFormatter(row,cell,price,columnDef,dataContext){
    if( price && typeof(price) == "number" ) price = price.toString();

    if( price == null || typeof(price) != "string" || !price.length)
        return "0.0 р";

    //Копейки. Если они встретятся - мы их переопределим
    var price_penny= 0; //Пишем "var", тогда переменные "умрут" с выходом из функции и не будут засорять память
    
    //Заменяем на всякий случай запятую на точку
    price= price.replace( /\,/, "." );
    price= price.replace( /\s/, "" );

    //в javascript неудобно резать: нельзя сразу по переменным это распределитиь
    var price_parts= price.split( "." ); //Из "12548.80" получаем массив: [0]= "12548", [1]= "80"
    if ( price_parts[1] ) { //Если есть второй элемент массива, то есть - копейки
      price= price_parts[0]; //Переопределяем цену
      price_penny= price_parts[1]; //Запоминаем копейки
    }
    
    var characters= price.split(""); //...а вот резать на части удобнее - в php explode так не может работать
    
    var getted= 0; 
    var resultPrice= new Array(); // Массив. Потому что строку в javascript тяжело реверсировать
    for ( var r= characters.length; r--; r>= 0 ) {
      resultPrice[ resultPrice.length ]= characters[ r ];
      getted++;
      if ( getted%3 == 0 ) resultPrice[ resultPrice.length ]= " ";
    } //end for
    
    resultPrice.reverse(); //Отображаем зеркально массив
    var almoustReady= resultPrice.join( "" ); //Склеиваем массив
    if ( price_penny )  almoustReady+= "."+price_penny; //Если есть копейки - прибавляем и их
    
    return almoustReady+" руб.";
  };

  /*
  * Форматирование списка значений районов
  */
  function RegionsListFormatter(row,cell,list,columnDef,dataContext){
    if( !list && dataContext.any_region == "0" || !(list instanceof Object))
        return "";


      var list_string = "";

      if(dataContext.any_region == "1"){
        list_string = "Любой/";
      }

      Slick.Formatters.RegionsList.show_full_list = function(event,row,cell){
        var regions = common.grid.getDataItem(row).regions;
        var any_region = common.grid.getDataItem(row).any_region;

        var tooltip_id = '.tooltip_list_'+row+'_'+cell;
        if($(tooltip_id).length <= 0){
          var tooltip = $('<div class="tooltip_list_'+row+'_'+cell+' cell_tooltip" style="display:none;width:450">');
          if(any_region == "1")
            tooltip.append("Любой <br/>");

          for(var i in regions){
            if(common.regions[regions[i]])
              tooltip.append(common.regions[regions[i]]);
            tooltip.append('<br/>');
          }
          $('body').append(tooltip);  
          tooltip.css('position','absolute');
          tooltip.css('top',event.pageY-tooltip.outerHeight(true)-20);
          tooltip.css('left',event.pageX-tooltip.outerWidth()-20);
          tooltip.css('display','block');
        }
      }

      Slick.Formatters.RegionsList.hide_full_list = function(event,row,cell){
        $('.tooltip_list_'+row+'_'+cell).remove();
      }

      for(var i in list){
        if(common.regions[list[i]])
          list_string += common.regions[list[i]]+"/";
      };

      if(common.grid && list_string != ""){
        var $wrapper = $('<div style="height:25px"/>');
        $wrapper.attr('onclick','Slick.Formatters.RegionsList.show_full_list(event,'+row+','+cell+')');
        $wrapper.attr('onmouseout',' Slick.Formatters.RegionsList.hide_full_list(event,'+row+','+cell+');');
        $wrapper.text(list_string.substr(0,list_string.length-1));
        var d=$('<div>');
        d.append($wrapper);
        return d.html();
      } 
      return list_string.substr(0,list_string.length-1);
  };

  function MetrosListFormatter(row,cell,list,columnDef,dataContext){
    if(!list && (dataContext.any_metro=="0") || !(list instanceof Object) )
      return "";

      var list_string = "";

      if(dataContext.any_metro == "1"){
        list_string = "Любое/";
      }

      for(var i in list){
         if(common.metros.hasOwnProperty(i)){
          for(var j in list[i]){
            if(common.metros[i].hasOwnProperty(list[i][j]) != -1){
                list_string += common.metros[i][list[i][j]]+"/";              
            }
          }
        }
      };

      return list_string.substr(0,list_string.length-1);
  };

    /*
    * Форматирование телефонного номера.
    */
  function PhoneFormatter(row,cell,phone,columnDef,dataContext){
    if( (typeof(phone) == "number") && phone > 0) phone = phone.toString();

    if(!phone || (typeof phone != "string") || phone.length < 7)
      return "";
  
    /*
    * Если длина номера == 7
    */  
    if(phone.length == 7){
      return phone.substr(0,3)+"-"+phone.substr(3,2)+"-"+phone.substr(5,2);
    }else if(phone.length <= 10){
      var len_code = phone.length-7;
      return "+7"+" ("+phone.substr(0,len_code)+") "+phone.substr(len_code,3)+"-"+phone.substr(len_code+3,2)+"-"+phone.substr(len_code+5,2);
    }else if(phone.length > 10){
      var len_code = phone.length-10;
      var code = phone.substr(0,len_code);
      if(code == "7") code = "+"+code;
      return code+" ("+phone.substr(len_code,3)+") "+phone.substr(len_code+3,3)+"-"+phone.substr(len_code+6,2)+"-"+phone.substr(len_code+8,2);
    }
  }

  function AgentFormatter(row,cell,agent_id,columnDef,dataContext){
    /*
    * -1 возможен в тех случаях, когда редактируя заявку мы убираем агента
    */
      if(!agent_id || agent_id == -1)
        return '<a href="#" onclick="return false;">Назначить</a>';
      var agent_name = dataContext.user_name.charAt(0).toUpperCase();
      var agent_middle_name = dataContext.user_middle_name.charAt(0).toUpperCase();
      var agent_last_name = dataContext.user_last_name.charAt(0).toUpperCase()+dataContext.user_last_name.substr(1,dataContext.user_last_name.length);
      return '<a href="#" onclick="return false;">'+agent_last_name+' '+agent_name+'.'+agent_middle_name+'</a>';
  }

    /*
    * Мои форматтеры
    */
    function DescriptionFormatter(row,cell,value,columnDef,dataContext){
      if(!value)
        return "";
      /*
      * Доступ к данным через глобальный объект common.grid!
      */
      var cell_content = $('<div id="cell_description" style="height:40px;overflow:hidden;">').html(value);
      cell_content.attr('onmousemove','common.show_full_text(event,'+row+','+cell+',common.grid.getDataItem('+row+').description);');
      cell_content.attr('onmouseout','common.hide_full_text(event,'+row+','+cell+');');
      var wrap = $('<div>').html(cell_content);
      return wrap.html();
    };

})(jQuery);