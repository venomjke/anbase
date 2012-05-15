<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu'); ?>
    <div class="content">
    	<div class="zagolovok profil">Профиль</div> 
			<div class="tablica2" id="profile">
    			<table width="100%" border="0" cellpadding="" cellspacing="0">
					<tr>	
					    <td  style="padding-left:6px" width="33%">АККАУНТ</td>
					    <td class="l left" width="33%">ЛИЧНЫЕ ДАННЫЕ</td>
					    <td class="l left" width="33%">ОРГАНИЗАЦИЯ</td>
					</tr>
					<tr>
						<td valign="top">
							<form method="post" id="system" onsubmit="manager.profile.save_form('system');return false;">
								<table width="100%" border="0" cellpadding="4px" cellspacing="0px">
									<tr>
										<td>Профиль:</td>
										<td style="color:#666666">Агент</td>
									</tr>
									<tr>
										<td>Логин:</td>
						   				 <td style="color:#666666"><?php echo $this->manager_users->get_user_login(); ?></td>
									</tr>
									<tr>
										 <td>Email:</td>
					    				<td style="color:#666666"><?php echo $this->manager_users->get_user_email(); ?></td>
									</tr>
									<tr>
										 <td>&nbsp;</td>
						   				 <td>&nbsp;</td>
									</tr> 	
									<tr>
										 <td colspan="2">СМЕНИТЬ ПАРОЛЬ</td>
									</tr>
									<tr>
									    <td colspan="2">
									    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
									       	 <tr>
									          <td width="150px">Текущий пароль:&nbsp;</td>
									          <td><input class="text" type="password" name="password" required/></td>
									       	 </tr>
									        </table>      
								  		</td>
									</tr>
									<tr>
									    <td colspan="2">
									    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
									       	 <tr>
									          <td width="150px">Новый пароль:&nbsp;</td>
									          <td><input class="text" type="password" name="new_password" required/></td>
									       	 </tr>
									        </table>      
								  		</td>
									</tr>
									<tr>
									    <td colspan="2">
									    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
									       	 <tr> 
									          <td width="150px">Повторите пароль:&nbsp;</td>
									          <td><input class="text" type="password" name="re_new_password" required/></td>
									       	 </tr>
									        </table>
									        <input type="submit" style="position:absolute; top:-9999999px;left:-999999999px"/>      
								  		</td>
									</tr>
								</table>
							</form>
						</td>
						<td valign="top" class="left">
							<form method="post" id="personal" onsubmit="manager.profile.save_form('personal');return false;">
								<table width="100%" border="0" cellpadding="4px" cellspacing="0px">
									<tr>
	 								<td class="l">Фамилия:</td>
						   			 <td><input class="text" name="last_name" type="text" value="<?php echo htmlspecialchars($this->manager_users->get_user_last_name()); ?>" required /></td>
									</tr>
									<tr>
										<td class="l">Имя:</td>
					    				<td><input class="text" type="text" name="name" value="<?php echo htmlspecialchars($this->manager_users->get_user_name());?>" required/></td>
									</tr>
									<tr>
										 <td class="l">Отчество:</td>
						    			 <td><input class="text" type="text" name="middle_name" value="<?php echo htmlspecialchars($this->manager_users->get_user_middle_name()); ?>" required/></td>
									</tr>
									<tr>
										 <td class="l">Телефон:</td>
						    			 <td><input class="text phone" type="text" name="phone" value="<?php echo htmlspecialchars($this->manager_users->get_user_phone()); ?>" required/>
									        <input type="submit" style="position:absolute; top:-9999999px;left:-999999999px"/>      
						    			 </td>
									</tr>
								</table>
							</form>
						</td>
						<td valign="top" class="left">
							<form method="post" onsubmit="return false;">
								<table width="100%" border="0" cellpadding="4px" cellspacing="0px">
									<tr>
										<td class="l">Название:</td>
						   				 <td><?php echo $this->manager_users->get_org_name(); ?></td>
									</tr>
									<tr>
										 <td class="l">Диспетчер:</td>
					    				<td><?php echo $this->manager_users->get_callmanager_phone(); ?></td>
									</tr>
									<tr>
										<td class="l">Email:</td>
						    			<td><?php echo $this->manager_users->get_org_email(); ?></td>
									</tr>
								</table>
							</form>
						</td>
					</tr>
					<tr>
						<td>
							<span class="l">
			     				 <input class="button" name="button" type="submit" value="Сохранить изменения" onclick="$('#system').submit();"/>
		    				</span>
		    			</td>
						<td>
							<span class="l">
				     				 <input class="button" name="button" type="submit" value="Сохранить изменения" onclick="$('#personal').submit();"/>
		    				</span>
		    			</td>						
		    			<td>&nbsp;</td>
					</tr>	  
				</table>
		      </div>
		    </div>
			<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
</div>
<script type="text/javascript">
  $(function(){
      $('.phone').keyfilter(/[\+\d]/);
  });
</script>