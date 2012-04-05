<?php echo doctype('html5'); ?>
<html>
	<head>
		<?php echo meta('Content-type','text/html; charset=utf-8','equiv'); ?>
		<?php echo link_tag("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/cupertino/jquery-ui.css"); ?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript">
			google.load("jquery","1.7.1");
			google.load("jqueryui","1.8");
		</script>
		<?php
			echo link_tag('themes/start/css/site.css');
			echo $template['metadata'];
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