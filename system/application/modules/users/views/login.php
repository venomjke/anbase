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
			<table>
			<tr>
				<td colspan="2">
					<div id="recaptcha_image"></div>
				</td>
				<td>
					<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
					<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
					<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="recaptcha_only_if_image">Введите слово на картинке</div>
					<div class="recaptcha_only_if_audio">Введите цифры внизу</div>
				</td>
				<td>
								<?php echo form_error('recaptcha_response_field'); ?>
				<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
			    </td>
				<?php echo $recaptcha_html; ?>
			</tr>
		</table>
		<?php endif; ?>
		<div class="loginField">
			<?php echo form_submit("Submit","Войти"); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

