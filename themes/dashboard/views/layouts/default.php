<?php echo doctype('html5');  ?>
<html>
	<head>
		<?php echo link_tag("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/cupertino/jquery-ui.css"); ?>
		<?php echo link_tag("themes/dashboard/css/dashboard.css"); ?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript">
			google.load("jquery","1.7.1");
			google.load("jqueryui","1.8");
		</script>
		<script language="javascript" src="<?php echo site_url("themes/dashboard/js/common.js"); ?>"> </script>
		<?php echo $template['metadata']; ?>
	</head>
	<body>
		<?php
			/*
			*
			*
			*	Можно сказать, что вид панели управления состоит из соствных частей:
			*	
			*	Шапка
			*
			*	Тело
			*
			*	Футер
			*
			*/
		?>
		<div id="dashboard_head">
			<?php load_partial_template($template,"dashboard_head"); ?>
		</div>
		<div id="dashboard_content">
			<?php echo $template['body']; ?>
		</div>
		<div id="dashboard_foot">
			<span id="stats"> Время выполнения <?php echo $this->benchmark->elapsed_time(); ?> </span>
			<span id="copyright"> (c) copyright 2012 Flyweb inc. </span>
		</div>
	</body>
</html>