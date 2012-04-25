<?php echo doctype('html5');  ?>
<html>
	<head>
		<?php echo meta('Content-type','text/html; charset=utf-8','equiv'); ?>
		<?php echo link_tag('themes/dashboard/images/an.ico', 'shortcut icon', 'image/ico'); ?>
		<?php echo link_tag("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css"); ?>
		<?php echo link_tag("themes/dashboard/css/style.css"); ?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript">
			google.load("jquery","1.7.1");
			google.load("jqueryui","1.8");
		</script>
		<script language="javascript" src="<?php echo base_url(); ?>themes/dashboard/js/common.js"> </script>
		<script language="javascript" src="<?php echo base_url(); ?>themes/dashboard/js/jquery.noty.js"> </script>
		<script type="text/javascript" src="<?php echo base_url(); ?>themes/dashboard/js/jquery.center.js"> </script>
		<?php echo link_tag("themes/dashboard/css/noty/jquery.noty.css"); ?>
		<?php echo link_tag("themes/dashboard/css/noty/noty_theme_default.css"); ?>
		<?php echo link_tag("themes/dashboard/css/noty/noty_theme_mitgux.css"); ?>

		<?php echo link_tag("themes/dashboard/css/slick.grid.css"); ?>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/jquery.event.drag-2.0.min.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.core.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.grid.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.remotemodel.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.formatters.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.fw_formatters.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.editors.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.fw_editors.js"> </script>
		<script language="javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.ui.datepicker-ru.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.center.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.maphilight.js"></script>

		<script type="text/javascript">
			common.baseUrl = '<?php echo base_url(); ?>';
		</script>
		<?php echo $template['metadata']; ?>
	</head>
	<body>
		<?php echo $template['body']; ?>
	</body>
</html>