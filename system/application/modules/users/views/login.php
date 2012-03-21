<div id="loginBox">
	<h3> Вход в панель </h3>
	<?php if(!empty($errors)): ?>
		<?php foreach($errors as $error): ?>
		<p class="error">
			<?php echo $error; ?>
		</p>
		<?php endforeach; ?>
	<?php endif; ?>

	<div id="loginWrap">
		<?php echo form_open(""); ?>
		<div class="loginField">
			<h4> Логин </h4>
			<?php echo form_error('login'); ?>
			<?php echo form_input("login","",'placeholder="Логин"'); ?>
		</div>
		<div class="loginField">
			<h4> Пароль </h4>
			<?php echo form_error('password'); ?>
			<?php echo form_password("password","",'placeholder="******"'); ?>
		</div>
		<div class="loginField">
		<?php echo form_checkbox("remember","1"); ?> Запомнить | <?php echo anchor("forget_password","Забыли пароль?"); ?>
		</div>
		<?php if(!empty($recaptcha_html)): ?>
		<div class="loginField">
				<?php echo $recaptcha_html; ?>
		</div>
		<?php endif; ?>
		<div class="loginField">
			<?php echo form_submit("Submit","Войти"); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

