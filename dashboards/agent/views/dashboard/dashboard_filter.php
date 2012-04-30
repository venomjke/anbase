<div class="filter">
  <table id="filter_header" width="100%" border="0" cellspacing="4" cellpadding="0">
    <tr>
      <td><b>Фильтр</b>&nbsp;по&nbsp;номеру:</td>
      <td><input style="width:100px" type="text" id="f_number" /></td>

      <?php /*в свободных заявках отключаем фильтр по телефонам */if($current != 'free'): ?>
        <td> по&nbsp;телефону:</td>
      <td><input type="text" name="phone" id="f_phone" /></td>
      <?php endif; ?>
      <td style="width:80%">&nbsp;</td>
      <td colspan="2"><a href="#" style="width:150px" class="svernut" onclick="return false;"><img id="filter_toggle" src="<?php echo site_url("themes/dashboard/images/strelkavniz.png"); ?>" class="down"/></a></td>
    </tr> 
  </table>

  <div id="dashboard_filter" style="display:none;margin-top:6px; margin-bottom:6px">
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><strong>Категория</strong></td>
        <td colspan="2" class="l"><strong>Цена</strong></td>
        <td colspan="2" class="l"><strong>Дата</strong></td>
        <td class="l"><strong>Описание</strong></td>
        <td valign="top" rowspan="4" style="width:10%">
            <strong>Метро</strong><br/>
            <span id="metro_btn" class="plus" style="margin-top:5px"><a href="#" onclick="return false;">Выбрать</a></span><br/><br/>
            <strong>Район</strong><br/>
            <span id="region_btn" class="plus" style="margin-top:5px"><a href="#" onclick="return false;">Выбрать</a></span><br/> 
        </td>
        <td class="l" colspan="2"><strong>Номер</strong></td>
      </tr>
      <tr>
        <td>Объект:</td>
        <td><select style="width:120px" id="f_category">
            <option value=""></option>
        <?php 
          foreach($this->m_order->get_category_list() as $category){
            ?>
              <option value="<?php echo $category ?>"> <?php echo $category;?> </option>
            <?php
          }  
        ?>
        </select></td>
        <td class="l">От:</td>
        <td><input style="width:100px" type="text" id="f_price_from"/></td>
        <td class="l">С:</td>
        <td><input style="width:130px" type="text" class="date" id="f_createdate_from"/></td>
        <td width="50%" rowspan="2" valign="top" class="l"><textarea id="f_description" style="width:90%" rows="3"></textarea></td>
        <td>От:</td>
        <td><input type="text" id="f_number_from" style="width:100px"/></td>
      </tr>
      <tr>
        <td>Вид&nbsp;сделки:</td>
        <td><select style="width:120px" id="f_dealtype">
            <option value=""></option>
        <?php 
          foreach($this->m_order->get_dealtype_list() as $deal_type){
            ?>
              <option value="<?php echo $deal_type?>"> <?php echo $deal_type;?></option>
            <?php
          }
        ?>
        </select></td>
        <td class="l">До:</td>
        <td><input type="text" style="width:100px" id="f_price_to"/></td>
        <td class="l">До:</td>
        <td><input style="width:130px" type="text" class="date" id="f_createdate_to"/></td>
        <td>До:</td>
        <td><input type="text" id="f_number_to" style="width:100px"/></td>
      </tr>
      <tr>
        <td colspan="2"><input id="search_btn" type="button"  style="cursor:pointer; padding:5px" value="подобрать" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="l" valign="top"><label for="f_description_full">Точное совпадение</label><input checked="checked" type="radio" id="f_description_full" name="f_description_type" value="full"/><label for="f_description_each">По каждому слову</label><input type="radio" id="f_description_each" name="f_description_type" value="each"/></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
  <script type="text/javascript">
    $(function(){
      $('#filter_header').click(function(e){
        if(!e.isImmediatePropagationStopped()){
          $('#dashboard_filter').slideToggle("fast");
          img=$('#filter_toggle');
          if(img.attr('class')=='down'){
              img.attr('src','<?php echo site_url("themes/dashboard/images/strelkavverh.png"); ?>'); 
              img.attr('class','up');
          }else{
              img.attr('class','down');
              img.attr('src','<?php echo site_url("themes/dashboard/images/strelkavniz.png"); ?>');
          };
        }
      });

      $.datepicker.setDefaults($.datepicker.regional["ru"]);
      $('.date').datepicker();

    })
  </script>
</div>