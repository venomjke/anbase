<div class="zagolovok"> 
<div class="date">
<div>
<img src="<?php echo base_url();?>themes/start/images/6.png">
</div>
</div> 
<h1>
<a href="<?php echo site_url("demo"); ?>"> Демо </a>
</h1>
Категория: Демо
</div>

<div>
	<h3> Администратор </h3>
	<span> Логин: </span> demo_admin <span> Пароль: </span> demo_admin <a href="#" id="demo_admin"> Войти </a> 
</div>
<div>
	<h3> Менеджер </h3>
	<span> Логин: </span> demo_manager <span> Пароль: </span> demo_manager <a href="#" id="demo_manager"> Войти </a>
</div>
<div>
	<h3> Агент </h3>
	<span> Логин: </span> demo_agent <span> Пароль: </span> demo_agent <a href="#" id="demo_agent"> Войти </a>
</div>
<script type="text/javascript">
	$(function(){

		$('#demo_admin').click(function(){
			login('demo_admin','demo_admin');
		});

		$('#demo_manager').click(function(){
			login('demo_manager','demo_manager');
		});

		$('#demo_agent').click(function(){
			login('demo_agent','demo_agent');
		});

		/*
		* Функция вхожа
		*/
		function login(login,password){

			$('#login').val(login);
			$('#password').val(password);
			$('#login_form').submit();
		}
	});
</script>