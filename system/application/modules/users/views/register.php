<?php echo form_open("register"); ?>      
<div class="zagolovok">
   	<div class="date"><div><img src="<?php echo site_url("themes/start")?>/images/6.png" /></div></div>
        <h1><a href="111"> Регистрация</a></h1>
            Регистрация необходима для работы в системе. <br />
       Обязательные поля отмечены звездочкой
</div>
	<h3 class="reg">Организация</h3>
<?php
	if(!empty($errors['register_org_error'])){
		echo $errors['register_org_error'];
	};
?>
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="200px" colspan="2">
      <div style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;color: #333;">Данные для связи агента с диспетчером</div>
    </td>
  </tr>
  <tr>
    <td width="200px">Название организации:</td>
    <td width="300px"><?php echo form_error("org_name"); ?>
		<?php echo form_input("org_name",set_value("org_name"),'placeholder="Введите название организации" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Email организации:</td>
    <td><?php echo form_error("org_email"); ?>
		<?php echo form_input("org_email",set_value("org_email"),'placeholder="Введите email организации" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Телефон организации (диспетчера)</td>
    <td><?php echo form_error("org_phone"); ?>
		<?php echo form_input("org_phone",set_value("org_phone"),'placeholder="Введите телефон организации" class="text"'); ?></td>
  </tr>
</table>
			<h3 class="reg">Личные данные</h3>
	<?php
		if(!empty($errors['register_user_error'])){
			echo $errors['register_user_error'];
		};
	?>
  <table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
   <td width="200px" colspan="2"><div style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;color: #333;">Данные для связи с представителем вашего агентства</div>
   </td>
  </tr>
  <tr>
    <td width="200px">Фамилия:</td>
    <td width="300px"><?php echo form_error("last_name"); ?>
		<?php echo form_input("last_name",set_value("last_name"),'placeholder="Введите свою фамилию" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Имя:</td>
    <td><?php echo form_error("name"); ?>
		<?php echo form_input("name",set_value("name"),'placeholder="Введите свое имя" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Отчество:</td>
    <td><?php echo form_error("middle_name"); ?>
		<?php echo form_input("middle_name",set_value("middle_name"),'placeholder="Введите свое отчество" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Телефон:</td>
    <td>
	<?php echo form_error("phone"); ?>
	<?php echo form_input("phone",set_value("phone"),'placeholder="Введите свой телефон" class="text"'); ?></td>
  </tr>
</table>
			<h3 class="reg">Учетная запись</h3>
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="200px" colspan="2">
      <div style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;color: #333;">Данные для для последующего входа в систему</div>
    </td>
  </tr>
  <tr>
    <td width="200px">Логин:</td>
    <td width="300px"><?php echo form_error("r_login"); ?>
					<?php echo form_input("r_login",set_value("r_login"),'placeholder="Введите имя" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Email :</td>
    <td><?php echo form_error("r_email"); ?>  
		<?php echo form_input("r_email",set_value("r_email"),'placeholder="Введите свой email" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Пароль:</td>
    <td><?php echo form_error("r_password"); ?> 
		<?php echo form_password("r_password",set_value("r_password"),'placeholder="Введите свой пароль" class="text"'); ?></td>
  </tr>
  <tr>
    <td>Повторите пароль:</td>
    <td><?php echo form_error("r_re_password"); ?>
		<?php echo form_password("r_re_password","",'placeholder="Повторите ввод пароля" class="text"'); ?></td>
  </tr>
</table>
<?php echo form_error("recaptcha_response_field"); ?>
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="253"><?php echo $recaptcha_html; ?></td>
    <td width="235" align="center" valign="bottom"><input class="button" type="submit" value="ЗАРЕГИСТРИРОВАТЬСЯ" /></td>
  </tr>
</table>

<!--
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="253"><img src="cap4a.png" width="251" height="100" /></td>
    <td width="235" align="center" valign="bottom"><input class="button" type="button" value="ЗАРЕГИСТРИРОВАТЬСЯ" /></td>
  </tr>
</table>
-->
<?php echo form_close(); ?>