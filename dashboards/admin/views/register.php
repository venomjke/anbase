<?php echo form_open("admin/register/?key={$invite->key_id}&email={$invite->email}",'onsubmit="register.submit({jObjForm:$(this)});"'); ?>
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
      <td>  <?php echo form_input("user[phone]",set_value("user[phone]"),'placeholder="Введите свой телефон" class="text phone"'); ?><br/><span> Пример: +79219995544 </span></td>
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
        <td> Email :</td>
        <td><?php echo $invite->email; ?> <input type="hidden" name="user[email]" value="<?php echo $invite->email; ?>" /> </td>
      </tr>
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
<table width="500px" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td width="235" align="center" valign="bottom"><input class="button" type="submit" value="ЗАРЕГИСТРИРОВАТЬСЯ" /></td>
  </tr>
</table>
<?php echo form_close(); ?>
<script type="text/javascript">
  $(function(){
      $('.phone').keyfilter(/[\+\d]/);
  });

  
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
</script>