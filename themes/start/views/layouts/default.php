<?php echo doctype('html5'); ?>
<html>
	<head>
		<?php
			echo meta('Content-type','text/html; charset=utf-8','equiv');
		?>
	</head>
	<body>
		<div id="head">
			<ul id="menu">
				<li>
					<a href="#"> Демо | </a>
				</li>
				<li>
					<a href="#"> Регистрация | </a>
				</li>
				<li>
					<a href="#"> О системе | </a>
				</li>
				<li>
					<a href="#"> Цены |</a>
				</li>
				<li>
					<a href="#"> О нас | </a>
				</li>
				<li>
					<a href="#"> FAQ </a>
				</li>
			</ul>
		</div>
		<div id="content">
			<?php echo $template['body']; ?>
		</div>
		<div id="footer">
			<div id="stats">
				Время выполнения: 	<?php echo $this->benchmark->elapsed_time(); ?>
			</div>
			<div id="copyright">
				(c) copyright 2012 Flyweb inc.
			</div>
		</div>
	</body>
</html>