
<div class="zagolovok"><h3>Вход в панель</h3></div>
	<?php if(!empty($errors)): ?>
	<?php foreach($errors as $error): ?>
	<p class="error">
		<?php echo $error; ?>
	</p>
	<?php endforeach; ?>
<?php endif; ?>
<?php echo form_open("login"); ?>
	 Логин  <br/>
	<?php echo form_error('login'); ?>
	<?php echo form_input("login","",'placeholder="Логин" class="text"'); ?>

	Пароль <br/>
	<?php echo form_error('password'); ?>
	<?php echo form_password("password","",'placeholder="******" class="text"'); ?>

	<?php if(!empty($recaptcha_html)): ?>
	<div class="loginField">
			<?php echo $recaptcha_html; ?>
	</div>
	<?php endif; ?>
	<br/>
	  <input name="" type="checkbox" value="" />Запомнить меня<br />
	  <input class="button" type="submit" value="ВОЙТИ" /> <br/>
<?php echo form_close(); ?>
&nbsp;&nbsp;<a href="<?php echo site_url("forget_password"); ?>">Забыли пароль</a> / <a href="<?php echo site_url("register");?>">Регистрация</a>
<!--
&nbsp;&nbsp;Логин:<br />
    <input class="text" type="text" value="логин" />
    <br />
    &nbsp;&nbsp;Пароль:<br />
    <input class="text" type="text" value="логин" />
    <br />
    <input name="" type="checkbox" value="" />Запомнить меня<br />
    <input class="button" type="button" value="ВОЙТИ" /><br />

-->

