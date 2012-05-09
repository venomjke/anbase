<?php
	if(!function_exists("selected_checkbox")){
		function checked_checkbox($val,$check){
			if($val == $check) return "checked";
			return "";
		}
	}
?>
<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu'); ?>
    <div class="content">
    	<div class="zagolovok profil">Настройки</div> 
			<div class="tablica2" id="settings">
    			<table width="100%" border="0" cellpadding="" cellspacing="0">
					<tr>	
					    <td width="33%">КОЛОНКИ</td>
					    <td class="l left" width="33%">ПО УМОЛЧАНИЮ</td>
					</tr>
					<tr>
						<td valign="top">
							<form method="post" id="cols" onsubmit="admin.settings.save_form('cols');return false;">
								<table width="100%" border="0" cellpadding="4px" cellspacing="0px">
									<tr>
										<td> Цена </td>
										<td> <input type="checkbox" name="price_col" value="<?php echo $settings->price_col;?>" <?php echo checked_checkbox($settings->price_col,1);?> onclick="admin.settings.switch($(this));"/> </td>
									</tr>
									<tr>
										<td> Районы </td>
										<td> <input type="checkbox" name="regions_col" onclick="admin.settings.switch($(this));" value="<?php echo $settings->regions_col;?>" <?php echo checked_checkbox($settings->regions_col,1); ?> /></td>
									</tr>
									<tr>
										<td> Метро </td>
										<td> <input type="checkbox" name="metros_col" onclick="admin.settings.switch($(this));" value="<?php echo $settings->metros_col; ?>" <?php echo checked_checkbox($settings->metros_col,1); ?> /> </td>
									</tr>
									<tr>
										<td> Телефон </td>
										<td> <input type="checkbox" name="phone_col"  onclick="admin.settings.switch($(this));" value="<?php echo $settings->phone_col; ?>" <?php echo checked_checkbox($settings->phone_col,1); ?>/> </td>
									</tr>
								</table>
							</form>
						</td>
						<td valign="top" class="left">
							<form method="post" id="default" onsubmit="admin.settings.save_form('default');return false;">
								<table width="100%" border="0" cellpadding="4px" cellspacing="0px">
									<tr>
										<td> Категория </td>
										<td> <select name="default_category" style="width:150px"><?php 
											foreach($this->admin_users->get_category_list() as $category):
												?>
												<option value="<?php echo $category; ?>" <?php if( $category == $settings->default_category ):  echo "selected"; endif; ?>> <?php echo $category; ?> </option>
											<?php
											endforeach;
										 ?></select> </td>
									</tr>
									<tr>
										<td> Тип сделки </td>
										<td> <select name="default_dealtype" style="width:150px"><?php
											foreach($this->admin_users->get_dealtype_list() as $dealtype):
												?>
												<option value="<?php echo $dealtype; ?>" <?php if( $dealtype == $settings->default_dealtype ): echo "selected"; endif; ?>> <?php echo $dealtype; ?></option>
											<?php
												endforeach;
											?></select>
										</td>
									</tr>
								</table>
							</form>
						</td>
						<td valign="top" class="left">
						</td>
					</tr>
					<tr>
						<td>
							<span class="l">
			     				<input class="button" name="button" type="submit" value="Сохранить изменения" onclick="$('#cols').submit();"/>
		    				</span>
		    			</td>
						<td>
							<span class="l">
			     				<input class="button" name="button" type="submit" value="Сохранить изменения" onclick="$('#default').submit();"/>
		    				</span>
		    			</td>						
		    			<td>
		    			</td>
					</tr>	  
				</table>
		      </div>
		    </div>
			<div class="podval">©<a href="111">copyright 2012 Flyweb inc.</a></div>
</div>