/***
*/

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Formatters": {
        "Rubbles":RubblesFormatter,
        "RegionsList":RegionsListFormatter,
        "MetrosList":MetrosListFormatter
      }
    }
  });

  /*
  * Форматтер цена
  */
  function RubblesFormatter(row,cell,price,columnDef,dataContext){
    if( price == null || price === "")
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
    if( !list || !(list instanceof Object))
        return "";


      var list_string = "";
      for(var i in list){
        list_string += common.regions[list[i]]+"/";
      };
      return list_string.substr(0,list_string.length-1);
  };

  function MetrosListFormatter(row,cell,list,columnDef,dataContext){
    if(!list || !(list instanceof Object))
      return "";

      var list_string = "";
      for(var i in list){
        for(var j in list[i])
          list_string += common.metros[i][list[i][j]]+"/";
      };
      return list_string.substr(0,list_string.length-1);
  };

})(jQuery);