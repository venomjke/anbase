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
		</div>
	</body>
</html>