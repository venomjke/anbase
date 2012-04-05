<div class="contentWrap">
	<div id="registerWrap">
		<?php echo form_open("agent/register/?key={$invite->key_id}&email={$invite->email}",'onsubmit="register.submit({jObjForm:$(this)});"'); ?>
		<div class="registerRow">
			<div style="overflow: auto; border-bottom: 1px #B4B4B4 solid;">
				<div class="registerLeftColumn">
					<h3> Агент </h3>
					<?php
						if(!empty($runtime_error)) echo $runtime_error;
					?>
					<fieldset>
						<legend> Учетная запись </legend>
						
						<table>
							<tr>
								<td>  <label for=""> Логин </label> </td>
								<td> 
									<?php echo form_error("login"); ?>
									<?php echo form_input("login",set_value("login"),'placeholder="Введите имя"'); ?> </td>
							</tr>
							<tr>
								<td> <label for="">Email </label> </td>
								<td> <?php echo $invite->email; ?> </td>
							</tr>
							<tr>
								<td> <label for="password"> Пароль </label></td>
								<td><?php echo form_error("password"); ?> 
									<?php echo form_password("password",set_value("password")); ?>
						 		</td>
							</tr>
							<tr>
								<td>
									<label for="re_password"> Повторите пароль </label>
								</td>
								<td>
									<?php echo form_error("re_password"); ?>
									<?php echo form_password("re_password"); ?>
								</td>
							</tr>
						</table>
					</fieldset>		
					<fieldset>
						<legend> Личные данные </legend>
						<table>
							<tr>
								<td> <label for=""> Имя </label>
								 </td>
								<td><?php echo form_error("name"); ?>
									<?php echo form_input("name",set_value("name"),'placeholder="Введите свое имя"'); ?>
							 	</td>
							</tr>
							<tr>
								<td><label for=""> Отчество </label>
								</td>
								<td><?php echo form_error("middle_name"); ?>
									<?php echo form_input("middle_name",set_value("middle_name"),'placeholder="Введите свое отчество"'); ?>
								</td>
							</tr>
							<tr>
								<td><label for=""> Фамилия </label>
								 </td>
								<td><?php echo form_error("last_name"); ?>
									<?php echo form_input("last_name",set_value("last_name"),'placeholder="Введите свою фамилию"'); ?>
								 </td>
							</tr>
							<tr>
								<td><label for=""> Телефон </label>
								</td>
								<td><?php echo form_error("phone"); ?>
									<?php echo form_input("phone",set_value("phone"),'placeholder="Введите свой телефон"'); ?>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
				<div class="registerRightColumn">
					<h3> Организация </h3>
					<table>
							<tr>
								<td><label for=""> Название организации </label>
								</td>
								<td><?php echo $invite->org_name; ?></td>
							</tr>
							<tr>
								<td><label for=""> Тел. организации ( диспетчера ) </label></td>
								<td>
									<?php echo $invite->org_phone; ?>
								</td>
							</tr>
							<tr>
								<td> <label for=""> Имя директора </label> </td>
								<td> <?php echo make_official_name($invite->ceo_name,$invite->ceo_middle_name,$invite->ceo_last_name); ?> </td>
							</tr>
						</table>
				</div>
			</div>
			<?php echo form_submit("submit","Зарегистрироваться"); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>