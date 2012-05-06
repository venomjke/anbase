<?php echo doctype('html5');  ?>
<html>
	<head>
		<?php echo meta('Content-type','text/html; charset=utf-8','equiv'); ?>
		<?php echo link_tag('themes/dashboard/images/an.ico', 'shortcut icon', 'image/ico'); ?>
		<?php echo link_tag("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css"); ?>
		<?php echo link_tag("themes/dashboard/css/style.css"); ?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("jquery","1.7.1");
			google.load("jqueryui","1.8");
		</script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/common.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/widgets/metro_map.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/widgets/metro_list.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/widgets/region_map.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.noty.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.center.js"> </script>

		<?php echo link_tag("themes/dashboard/css/noty/jquery.noty.css"); ?>
		<?php echo link_tag("themes/dashboard/css/noty/noty_theme_default.css"); ?>
		<?php echo link_tag("themes/dashboard/css/noty/noty_theme_mitgux.css"); ?>

		<?php echo link_tag("themes/dashboard/css/slick.grid.css"); ?>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/jquery.event.drag-2.0.min.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.core.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.grid.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.checkboxselectcolumn.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.rowselectionmodel.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.cellrangeselector.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.cellrangedecorator.js"></script>
		
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.formatters.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.fw_formatters.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.editors.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/slick_grid/slick.fw_editors.js"> </script>
		
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.cookie.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.ui.datepicker-ru.js"> </script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.center.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.maphilight.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.keyfilter.js?>"></script>

		<script type="text/javascript">
			common.baseUrl = '<?php echo base_url(); ?>';
		</script>
		<?php echo $template['metadata']; ?>
	</head>
	<body>
		<?php echo $template['body']; ?>
		<?php echo $this->benchmark->elapsed_time();?>
		<script type="text/javascript">
			$(function(){
				var $imgToggleUp = $('<img id="panel-collapse-up" src="'+common.baseUrl+'themes/dashboard/images/panel-collapse-up.png" style="cursor:pointer;display:block;width:16px;margin:0 auto;"/>');
				var $imgToggleDown = $('<img id="panel-collapse-down" src="'+common.baseUrl+'themes/dashboard/images/panel-collapse-down.png" style="cursor:pointer;display:none;width:16px;position: absolute;top: 0px;left: 49.4%;"/>');
				$('.menu').after($imgToggleUp);
				$('.menu').after($imgToggleDown);
				$imgToggleUp.click(function(){
					$('.shapka').slideToggle("fast");
					$('.user').slideToggle("fast");
					$('.podval').slideToggle("fast");
					$('.menu').css('margin-bottom','16px');
					$imgToggleDown.show();
					$(this).hide();
					if(!$.cookie('toggle'))
						$.cookie('toggle',1,{path:'/'});
				});
				$imgToggleDown.click(function(){
					$('.shapka').slideToggle("fast");
					$('.user').slideToggle("fast");
					$('.podval').slideToggle("fast");
					$('.menu').css('margin-bottom','0px');
					$imgToggleUp.show();
					$(this).hide();
					$.cookie('toggle',null,{path:'/'});
				});
				if($.cookie('toggle')){
					$('#panel-collapse-up').click();
				}
			});
		</script>
	</body>
</html>