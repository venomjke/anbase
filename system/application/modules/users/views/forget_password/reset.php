<?php if(!empty($success_reset)&&$success_reset): ?>
	Пароль успешно изменен.
<?php else: ?>
<span style="border-bottom: 1px #A9C4D1 solid;display: block;">
	Введите, пожалуйста, свой новый пароль, он станет основным.
 </span>
 <div class="forget_password" style="width: 400px;margin: 18px auto;">
 <form action="<?php echo site_url("forget_password/reset/?key=$key&email=$email") ?>" method="post" accept-charset="utf-8">	
 Новый пароль <br/>
 <?php echo form_error('f_password','<div class="error">','</div>');?>
		<input type="password" name="f_password"  placeholder="*******" class="text" required> <br/>
Повторите новый пароль <br/>
<?php echo form_error('f_re_password','<div class="error">','</div>'); ?>
		<input type="password" name="f_re_password" placeholder="*******" class="text" required> <br/>
	  <input class="button" type="submit" value="Отправить" style="margin: 0 auto;"> <br>
</form>
</div>
<?php endif; ?>