<div class="filter">
  <table width="100%" border="0" cellspacing="4" cellpadding="0">
    <tr>
      <td>Фильтр&nbsp;по&nbsp;номеру:</td>
      <td><input style="width:100px" type="text" /></td>
      <td><input type="radio" name="radio" id="radio" value="radio" /></td>
      <td>Все</td>
      <td><input type="radio" name="radio" id="radio2" value="radio" /></td>
      <td>Актиные</td>
      <td style="width:80%">&nbsp;</td>
      <td colspan="2"><a href="#" id="filter_toggle" style="width:150px" onclick="img=$(this).find('img');if(img.attr('class')=='down'){img.attr('src','<?php echo site_url("themes/dashboard/images/strelkavverh.png"); ?>'); img.attr('class','up');}else{img.attr('class','down');img.attr('src','<?php echo site_url("themes/dashboard/images/strelkavniz.png"); ?>')};return false;" class="svernut"><img src="<?php echo site_url("themes/dashboard/images/strelkavniz.png"); ?>" class="down"/></a></td>
    </tr>
  </table>

  <div id="dashboard_filter" style="display:none;margin-top:6px; margin-bottom:6px">
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><strong>Категория</strong></td>
        <td colspan="2" class="l"><strong>Цена</strong></td>
        <td colspan="2" class="l"><strong>Дата</strong></td>
        <td class="l"><strong>Описание</strong></td>
        <td class="l"><strong>Район</strong></td>
        <td class="l"><strong>Метро</strong></td>
      </tr>
      <tr>
        <td>Объект:</td>
        <td><select style="width:120px"></select></td>
        <td class="l">От:</td>
        <td><input style="width:100px" type="text" /></td>
        <td class="l">С:</td>
        <td><input style="width:130px" type="text" /></td>
        <td width="50%" rowspan="3" valign="top" class="l"><textarea style="width:90%" rows="5"></textarea></td>
        <td width="20%" rowspan="3" valign="top" class="l"><select size="4" multiple="" style="width:98%">
        </select><span class="plus" style="margin-top:5px"><a href="111">Добавить</a></span></td>
        <td width="20%" rowspan="3" valign="top" class="l"><select size="4" multiple="" style="width:98%">
        </select><span class="plus" style="margin-top:5px"><a href="111">Добавить</a></span></td>
      </tr>
      <tr>
        <td>Вид&nbsp;сделки:</td>
        <td><select style="width:120px"></select></td>
        <td class="l">До:</td>
        <td><input type="text" style="width:100px" /></td>
        <td class="l">До:</td>
        <td><input style="width:130px" type="text" /></td>
      </tr>
      <tr>
        <td colspan="2"><input type="button"  style="cursor:pointer; padding:5px" value="подобрать" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
  <script type="text/javascript">
    $(function(){
      $('#filter_toggle').click(function(e){
        $('#dashboard_filter').slideToggle("fast");
      });
    })
  </script>
</div>