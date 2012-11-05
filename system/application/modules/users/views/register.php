<?php echo form_open("register"); ?>      
<div class="zagolovok">
   	<div class="date"><div><img src="<?php echo site_url("themes/start")?>/images/6.png" /></div></div>
        <h1> <?php echo anchor("register","Регистрация") ?> </h1>
            Регистрация необходима для работы в системе. <br />
       Обязательные поля отмечены звездочкой (*)
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
    <td width="200px">* Название организации:</td>
    <td width="300px"><?php echo form_input("org[name]",set_value("org[name]"),'placeholder="Введите название организации" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2">
      <?php echo form_error("org[name]",'<div class="error">',"</div>"); ?>
    </td>
  </tr>
  <tr>
    <td>* Email организации:</td>
    <td>
		<?php echo form_input("org[email]",set_value("org[email]"),'placeholder="Введите email организации" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2">
      <?php echo form_error("org[email]",'<div class="error">',"</div>"); ?>
    </td>
  </tr>
  <tr>
    <td>* Телефон организации (диспетчера)</td>
    <td>
		<?php echo form_input("org[phone]",set_value("org[phone]"),'placeholder="Введите телефон организации" class="text phone" required'); ?><br/>
        <span> Пример: +79219995544 </span></td>
  </tr>
  <tr>
    <td colspan="2">
      <?php echo form_error("org[phone]",'<div class="error">',"</div>"); ?>
    </td>
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
    <td width="200px">* Фамилия:</td>
    <td width="300px">
		<?php echo form_input("user[last_name]",set_value("user[last_name]"),'placeholder="Введите свою фамилию" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2">
      <?php echo form_error("user[last_name]",'<div class="error">',"</div>"); ?>
    </td>
  <tr>
    <td>* Имя:</td>
    <td><?php echo form_input("user[name]",set_value("user[name]"),'placeholder="Введите свое имя" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2"> <?php echo form_error("user[name]",'<div class="error">',"</div>"); ?> </td>
  <tr>
    <td>* Отчество:</td>
    <td><?php echo form_input("user[middle_name]",set_value("user[middle_name]"),'placeholder="Введите свое отчество" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2"> <?php echo form_error("user[middle_name]",'<div class="error">',"</div>"); ?> </td>
  </tr>
  <tr>
    <td>Телефон:</td>
    <td>	<?php echo form_input("user[phone]",set_value("user[phone]"),'placeholder="Введите свой телефон" class="text phone"'); ?><br/><span> Пример: +79219995544 </span></td>
  </tr>
  <tr>
    <td colspan="2">  <?php echo form_error("user[phone]",'<div class="error">',"</div>"); ?></td>
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
    <td width="200px">* Логин:</td>
    <td width="300px"><?php echo form_input("user[login]",set_value("user[login]"),'placeholder="Введите логин" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2"> <?php echo form_error("user[login]",'<div class="error">',"</div>"); ?> </td>
  </tr>
  <tr>
    <td>* Email :</td>
    <td> <input type="email" name="user[email]" value="<?php echo set_value("user[email]"); ?>" placeholder="Введите свой email" class="text" required /></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo form_error("user[email]",'<div class="error">',"</div>"); ?></td>
  <tr>
    <td>* Пароль:</td>
    <td><?php echo form_password("user[password]",set_value("user[password]"),'placeholder="Введите свой пароль" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2"> <?php echo form_error("user[password]",'<div class="error">',"</div>"); ?>  </td>
  </tr>
  <tr>
    <td>* Повторите пароль:</td>
    <td><?php echo form_password("user[re_password]","",'placeholder="Повторите ввод пароля" class="text" required'); ?></td>
  </tr>
  <tr>
    <td colspan="2"> <?php echo form_error("user[re_password]",'<div class="error">',"</div>"); ?> </td>
  </tr>
</table>
<?php echo form_error("recaptcha_response_field"); ?>
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="253"><?php echo $recaptcha_html; ?></td>-
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
<script type="text/javascript">
  $(function(){
      $('.phone').keyfilter(/[\+\d]/);
    

      function check_field($field){
        var data = {};
        var fields = [];

        fields.push($field.attr('name'));
        data['fields'] = fields;
        data[$field.attr('name')] = $field.val();

        doValidQuery(data,'<?php echo site_url("users/auth/register_ajax_validation"); ?>');
      }
      
      /*
      * Валидация полей
      */
      $('input[type="text"],input[type="email"]').change(function(){
        check_field($(this));
      });

      $('input[type="password"]').change(function(){
        var data   = {};
        var fields = [];

        $('input[type="password"]').each(function(){
          fields.push($(this).attr('name'));
          data[$(this).attr('name')] = $(this).val();
        });

        data['fields'] = fields;

        doValidQuery(data,'<?php echo site_url("users/auth/register_ajax_validation"); ?>');
      });
  });
</script>