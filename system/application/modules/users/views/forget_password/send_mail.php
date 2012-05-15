<?php if(!empty($success_send)&&$success_send): ?>
	На Ваш электронный адрес отправлено письмо со ссылкой на страницу восстановления пароля.
<?php else: ?>
	<span style="border-bottom: 1px #06D solid;display: inline-block;">
		Для того, чтобы сбросить пароль, требуется указать адрес электронной почты или имя пользователя.
	  </span>
	 <div class="forget_password" style="width: 400px;margin: 18px auto;">
	 <form action="<?php site_url("forget_password"); ?>" method="post" accept-charset="utf-8">	
	 Адрес электронной почты  <br/>
	 <?php echo form_error('email','<div class="error">','</div>');?>
			<input type="email" name="email"  style="width:270px" placeholder="example@example.ru" class="text" required >
		  <input class="button" type="submit" value="Отправить" style="margin: 0 auto;"> <br>
	</form>
	</div>
<?php endif; ?>