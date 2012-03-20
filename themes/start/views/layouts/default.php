<?php echo doctype('html5'); ?>
<html>
	<head>
		<?php
			echo meta('Content-type','text/html; charset=utf-8','equiv');
			echo link_tag('themes/start/css/site.css');
		?>
	</head>
	<body>
		<div id="head">
			<?php load_partial_template($template,'menu'); ?>
		</div>
		<div id="content">
			<?php echo $template['body']; ?>
		</div>
		<div id="footer">
			<span id="stats"> Время выполнения <?php echo $this->benchmark->elapsed_time(); ?> </span>
			<span id="copyright"> (c) copyright 2012 Flyweb inc. </span>
		</div>
	</body>
</html>