<?php echo doctype('html5');  ?>
<html>
	<head>
		<?php echo meta('Content-type','text/html; charset=utf-8','equiv'); ?>
		<?php echo link_tag('themes/dashboard/images/an.ico', 'shortcut icon', 'image/ico'); ?>


		<?php Assets::css(array(
					'dashboard/style.css', 
					'dashboard/noty/jquery.noty.css', 
					'dashboard/noty/noty_theme_default.css', 
					'dashboard/slick.grid.css'
			)); ?>

		<?php if(is_production_mode()): ?>

			<?php echo link_tag("http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/smoothness/jquery-ui.css"); ?>

		<?php else: ?>
			<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>themes/dashboard/js/ui/themes/smoothness/jquery-ui-1.9.1.custom.min.css" />
			<script type="text/javascript" src="<?php echo base_url(); ?>themes/dashboard/js/jquery-1.6.1.min.js" > </script>
			<script type="text/javascript" src="<?php echo base_url(); ?>themes/dashboard/js/ui/jquery-ui-1.8.16.custom.min.js"> </script>
		<?php endif; ?>


		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">google.load("jquery","1.7.1");google.load("jqueryui","1.8");</script>
		
		<!--
		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/common.js"> </script>-->

		<?php Assets::js_group('common', array(
					'dashboard/common.js', 
					'dashboard/lang.js'
			)); ?>

		<?php Assets::js_group('slick.grid.widgets', array(
						'dashboard/widgets/order_comments.js', 
						'dashboard/widgets/metro_map.js',
						'dashboard/widgets/metro_list.js',
						'dashboard/widgets/region_map.js',
				
			)); ?>

			<?php // echo Assets::js_group('region', array('dashboard/widgets/region_map.js')) ?>

		<?php Assets::js_group('slickgrid', array(
						'dashboard/slick_grid/jquery.event.drag-2.0.min.js',
						'dashboard/slick_grid/slick.core.js',
						'dashboard/slick_grid/slick.grid.js',
						'dashboard/slick_grid/slick.checkboxselectcolumn.js',
						'dashboard/slick_grid/slick.rowselectionmodel.js',
						'dashboard/slick_grid/slick.cellrangeselector.js',
						'dashboard/slick_grid/slick.cellrangedecorator.js',
						'dashboard/slick_grid/slick.formatters.js',
						'dashboard/slick_grid/slick.fw_formatters.js',
						'dashboard/slick_grid/slick.editors.js',
						'dashboard/slick_grid/slick.fw_editors.js'
						));
				?>


		<?php Assets::js_group('plugins', array(
						'dashboard/jquery.cookie.js',
						'dashboard/jquery.ui.datepicker-ru.js',
						'dashboard/jquery.center.js',
						'dashboard/jquery.maphilight.min.js',
						'dashboard/jquery.keyfilter.js'
					)); ?>
					
		<script type="text/javascript">
			common.baseUrl = '<?php echo base_url(); ?>';
			common.userName= '<?php $app = get_instance(); echo $app->users->get_official_name(); ?>';

			/*
			* Для разрешения 1024*(x)
			*/
			if(window.screen.width <= 1024){
				document.write ('<link href="<?php echo base_url();?>themes/dashboard/css/slick1024.grid.css" rel="stylesheet" type="text/css">'); 
			}
		</script>

		<?php echo $template['metadata']; ?>
	</head>
	<body>
		<?php echo $template['body']; ?>
		
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
		<?php 
			/*
			* В debug режиме показываем время вывода страницы
			*/
			if(is_production_mode()):
			?>
			
				<script type="text/javascript">
				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', 'UA-31646688-1']);
				  _gaq.push(['_trackPageview']);

				  (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();
				</script>
			
			<?php
			else:
			   echo $this->benchmark->elapsed_time();
			endif;
			?>
	</body>
</html>