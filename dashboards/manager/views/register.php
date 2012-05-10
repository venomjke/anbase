<?php echo form_open("manager/register/?key={$invite->key_id}&email={$invite->email}",'onsubmit="register.submit({jObjForm:$(this)});"'); ?>
<div class="zagolovok">
   	<div class="date"><div><img src="<?php echo site_url("themes/start")?>/images/6.png" /></div></div>
        <h1><a href="#"> Регистрация</a></h1>
            Регистрация необходима для работы в системе. <br />
        Все поля обязательны для заполнения
</div>
	<h3 class="reg">Организация</h3>
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="200px">Название организации:</td>
    <td width="300px"><?php echo $invite->org_name; ?></td>
  </tr>
  <tr>
    <td>Имя директора:</td>
    <td> <?php echo make_official_name($invite->ceo_name,$invite->ceo_middle_name,$invite->ceo_last_name); ?></td>
  </tr>
  <tr>
    <td>Телефон организации (диспетчера)</td>
    <td><?php echo $invite->org_phone; ?></td>
  </tr>
</table>
	<h3 class="reg">Личные данные</h3>
	<?php
		if(!empty($runtime_error)) echo $runtime_error;
	?>
    <table width="500px" border="0" cellspacing="4" cellpadding="0">
	  <tr>
	    <td width="200px">Фамилия:</td>
	    <td width="300px"><?php echo form_error("last_name",'<div class="error">',"</div>"); ?>
			<?php echo form_input("last_name",set_value("last_name"),'placeholder="Введите свою фамилию" class="text"'); ?></td>
	  </tr>
	  <tr>
	    <td>Имя:</td>
	    <td><?php echo form_error("name",'<div class="error">',"</div>"); ?>
			<?php echo form_input("name",set_value("name"),'placeholder="Введите свое имя" class="text"'); ?></td>
	  </tr>
	  <tr>
	    <td>Отчество:</td>
	    <td><?php echo form_error("middle_name",'<div class="error">',"</div>"); ?>
			<?php echo form_input("middle_name",set_value("middle_name"),'placeholder="Введите свое отчество" class="text"'); ?></td>
	  </tr>
	  <tr>
	    <td>Телефон:</td>
	    <td>
		<?php echo form_error("phone",'<div class="error">',"</div>"); ?>
		<?php echo form_input("phone",set_value("phone"),'placeholder="Введите свой телефон" class="text"'); ?><br/>
        <span> Пример: +79219995544 </span>
      </td>
	  </tr>
	</table>

		<h3 class="reg">Учетная запись</h3>
        <table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="200px">Логин:</td>
    <td width="300px"><?php echo form_error("r_login",'<div class="error">',"</div>"); ?>
					  <?php echo form_input("r_login",set_value("r_login"),'placeholder="Введите логин" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Email :</td>
    <td><?php echo $invite->email; ?></td>
  </tr>
  <tr>
    <td>Пароль:</td>
    <td><?php echo form_error("r_password",'<div class="error">',"</div>"); ?> 
		<?php echo form_password("r_password",set_value("r_password"),'placeholder="Введите свой пароль" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Повторите пароль:</td>
    <td><?php echo form_error("r_re_password",'<div class="error">',"</div>"); ?>
		<?php echo form_password("r_re_password","",'placeholder="Повторите ввод пароля" class="text"'); ?></td>
  </tr>
</table>
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="235" align="center" valign="bottom"><input class="button" type="submit" value="ЗАРЕГИСТРИРОВАТЬСЯ" /></td>
  </tr>
</table>
<?php echo form_close(); ?>