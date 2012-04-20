<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu'); ?>
    <div class="content">
    	<div class="zagolovok profil">Профиль</div> 
			<div class="tablica2">
    			<table width="100%" border="0" cellpadding="4px" cellspacing="0">
					  <tr>
					    <td colspan="2" width="33%">АККАУНТ</td>
					    <td colspan="2" class="l" width="33%">ЛИЧНЫЕ ДАННЫЕ</td>
					    <td colspan="2" class="l" width="33%">ОРГАНИЗАЦИЯ</td>
					    <a href=""></a>
					  </tr>
					  <tr>
					    <td>Профиль:</td>
					    <td style="color:#666666">Агент</td>
					    <td class="l">Фамилия:</td>
					    <td><input class="text" name="textfield" type="text" value="<?php echo $this->agent_users->get_user_last_name(); ?>" /></td>
					    <td class="l">Название:</td>
					    <td><?php echo $this->agent_users->get_org_name(); ?></td>
					  </tr>
					  <tr>
					    <td>Логин:</td>
					    <td style="color:#666666"><?php echo $this->agent_users->get_user_login(); ?></td>
					    <td class="l">Имя:</td>
					    <td><input class="text" type="text" name="textfield2" value="<?php echo $this->agent_users->get_user_name();?>" /></td>
					    <td class="l">Диспетчер:</td>
					    <td><?php echo $this->agent_users->get_callmanager_phone(); ?></td>
					  </tr>
					  <tr>
					    <td>Email:</td>
					    <td style="color:#666666"><?php echo $this->agent_users->get_user_email(); ?></td>
					    <td class="l">Отчество:</td>
					    <td><input class="text" type="text" name="textfield3" value="<?php echo $this->agent_users->get_user_middle_name(); ?>" /></td>
					    <td class="l">Email:</td>
					    <td><?php echo $this->agent_users->get_org_email(); ?></td>
					  </tr>
					  <tr>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td class="l">Телефон:</td>
					    <td><input class="text" type="text" name="textfield7" value="<?php echo $this->agent_users->get_user_phone(); ?>" /></td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					  </tr>
					  <tr>
					    <td colspan="2">СМЕНИТЬ ПАРОЛЬ</td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					  </tr>
					  <tr>
					    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					        <tr>
					          <td width="150px">Текущий пароль:&nbsp;</td>
					          <td><input class="text" type="text" name="textfield8" /></td>
					        </tr>
					      </table>      </td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					  </tr>
					  <tr>
					    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					        <tr>
					          <td width="150px">Новый пароль:&nbsp;</td>
					          <td><input class="text" type="text" name="textfield8" /></td>
					        </tr>
					      </table> </td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					  </tr>
					  <tr>
					    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					        <tr>
					          <td width="150px">Повторите пароль:&nbsp;</td>
					          <td><input class="text" type="text" name="textfield8" /></td>
					        </tr>
					      </table> </td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					    <td class="l">&nbsp;</td>
					    <td>&nbsp;</td>
					  </tr>
					  <tr>
					    <td colspan="2"><span class="l">
					      <input class="button" name="button" type="submit" value="Сохранить изменения" />
					    </span></td>
					    <td colspan="2" class="l"><input class="button" name="button2" type="submit" value="Сохранить изменения" /></td>
					    <td colspan="2" class="l"></td>
					    </tr>
					</table>
		      </div>
		    </div>
			<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
</div>