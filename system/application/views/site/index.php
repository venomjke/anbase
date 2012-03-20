<div class="contentWrap">

	<div id="blog">
		<h2>Новости ( Блог )</h2>
		<div class="blogPost">
			Пост какой-то
		</div> 
		<div class="blogPost">
			Пост какой-то
		</div>
    </div>
	<div id="loginBox">
		<h3> Вход в панель </h3>
		<div id="loginWrap">
			<?php echo form_open("login"); ?>
			<div class="loginField">
				<h4> Логин </h4>
				<?php echo form_input("login","",'placeholder="Логин"'); ?>
			</div>
			<div class="loginField">
				<h4> Пароль </h4>
				<?php echo form_password("password","",'placeholder="******"'); ?>
			</div>
			<div class="loginField">
				<?php echo form_checkbox("remember","1"); ?> Запомнить | <?php echo anchor("forget_password","Забыли пароль?"); ?>
			</div>
			<div class="loginField">
				<?php echo form_submit("Submit","Войти"); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>