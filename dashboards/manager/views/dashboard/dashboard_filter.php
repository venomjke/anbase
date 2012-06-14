<div class="filter">
  <table width="100%" border="0" cellspacing="4" cellpadding="0">
    <tr>
      <td><b>Фильтр</b>&nbsp;по&nbsp;номеру:</td>
      <td><input style="width:100px" type="text" id="f_number" /></td>

     <?php /*в свободных заявках отключаем фильтр по телефонам */if($current != 'free' and $settings_org->phone_col): ?>
        <td> по&nbsp;телефону:</td>
        <td><input type="text" name="phone" id="f_phone" /></td>
      <?php endif; ?>
      <?php /*в заявках агентов выводим фильтр по агентам*/ if($current == 'delegate'): ?>
      <td> по&nbsp;агенту: </td>
      <td>  <select  name="user_id" id="f_user_id">
                <option value=""></option> 
        <?php foreach($manager_agents as $manager_agent): ?>
          <option value="<?php echo $manager_agent->id; ?>"><?php echo make_official_name($manager_agent->name,$manager_agent->middle_name,$manager_agent->last_name); ?></option>
        <?php endforeach;?>
      </select></td>
      <?php endif; ?> 
      <td class="filter_header" style="width:80%">&nbsp;</td>
      <td class="filter_header" colspan="2"><a href="#" style="width:150px" class="svernut" onclick="return false;"><img id="filter_toggle" src="<?php echo site_url("themes/dashboard/images/strelkavniz.png"); ?>" class="down"/></a></td>
    </tr> 
  </table>

  <div id="dashboard_filter" style="display:none;margin-top:6px; margin-bottom:6px">
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><strong>Категория</strong></td>
        <?php if($settings_org->price_col):?>
          <td colspan="2" class="l"><strong>Цена</strong></td>
        <?php endif; ?>
        <td colspan="2" class="l"><strong>Дата</strong></td>
        <td class="l"><strong>Описание</strong></td>
        <?php if($settings_org->metros_col or $settings_org->regions_col): ?>
        <td valign="top" rowspan="4" style="width:10%">
            <?php if($settings_org->metros_col): ?>
            <strong>Метро</strong><br/>
            <span id="metro_btn" class="plus" style="margin-top:5px"><a href="#" onclick="return false;">Выбрать</a></span><br/><br/>
            <?php endif; ?>

            <?php if($settings_org->regions_col): ?>
              <strong>Район</strong><br/>
              <span id="region_btn" class="plus" style="margin-top:5px"><a href="#" onclick="return false;">Выбрать</a></span><br/> 
            <?php endif; ?>
        </td>
        <?php endif;?>
        <td class="l" colspan="2"><strong>Номер</strong></td>
      </tr>
      <tr>
        <td>Объект:</td>
        <td><select tabindex="5" class="filter_select" id="f_category">
            <option value=""></option>
        <?php 
          foreach($this->m_order->get_category_list() as $category){
            ?>
              <option value="<?php echo $category ?>"> <?php echo $this->m_order->get_category_name($category);?> </option>
            <?php
          }  
        ?>
        </select></td>
       <?php if($settings_org->price_col): ?>
          <td class="l">От:</td>
          <td><input tabindex="7" class="filter_input" type="text" id="f_price_from"/></td>
        <?php endif;?>
        <td class="l">С:</td>
        <td><input tabindex="9" class="filter_input date" type="text" id="f_createdate_from"/></td>
        <td tabindex="11" width="50%" rowspan="2" valign="top" class="l"><textarea id="f_description" style="width:90%" rows="3"></textarea></td>
        <td>От:</td>
        <td><input tabindex="12" type="text" id="f_number_from" class="filter_input"/></td>
      </tr>
      <tr>
        <td>Вид&nbsp;сделки:</td>
        <td><select tabindex="6" class="filter_select" id="f_dealtype">
            <option value=""></option>
        <?php 
          foreach($this->m_order->get_dealtype_list() as $deal_type){
            ?>
              <option value="<?php echo $deal_type?>"> <?php echo $deal_type;?></option>
            <?php
          }
        ?>
        </select></td>
        <?php if($settings_org->price_col): ?>
          <td class="l">До:</td>
          <td><input tabindex="8" type="text" class="filter_input" id="f_price_to"/></td>
        <?php endif; ?>
        <td class="l">До:</td>
        <td><input tabindex="10" class="filter_input date" type="text" id="f_createdate_to"/></td>
        <td>До:</td>
        <td><input tabindex="13" type="text" id="f_number_to" class="filter_input"/></td>
      </tr>
      <tr>
        <td><input id="search_btn" type="button"  style="cursor:pointer; padding:5px" value="подобрать" /></td>
        <td><input id="reset_filter_btn" type="button" style="cursor:pointer; padding:5px" value="очистить"/> </td>
         <?php if($settings_org->price_col): ?>
          <td colspan="2">&nbsp;</td>
        <?php endif;?>
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
      $('.filter_header').click(function(e){
        $('#dashboard_filter').slideToggle("fast");
        img=$('#filter_toggle');
        if(img.attr('class')=='down'){
            img.attr('src','<?php echo site_url("themes/dashboard/images/strelkavverh.png"); ?>'); 
            img.attr('class','up');
        }else{
            img.attr('class','down');
            img.attr('src','<?php echo site_url("themes/dashboard/images/strelkavniz.png"); ?>');
        };
      });

      $.datepicker.setDefaults($.datepicker.regional["ru"]);
      $('.date').datepicker();

    })
  </script>
</div>