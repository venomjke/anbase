<?php echo doctype('html5'); ?>
<html>
	<head>
		<?php echo meta('Content-type','text/html; charset=utf-8','equiv'); ?>

	  	<meta http-equiv="keywords" name="keywords" content="система для учета заявок агентств недвижимости, персональная база для агентств недвижимости,база заявок для агентств недвижимости,crm для агентства недвижимости, crm для риэлторов база недвижимости программа, программа для агентства недвижимости, программа для реелторов, программа для риелторов, программа недвижимость,программа по продаже недвижимости,программа учета недвижимости, управление недвижимостью программа,софт для агентства недвижимости, "/>

		<meta http-equiv="description" name="description" content="Сервис и программа для автоматизации бизнеса агентства недвижимости, строительных компании, жилищно строительных кооперативов"/>

		<?php echo link_tag('themes/start/images/an.ico', 'shortcut icon', 'image/ico'); ?>
		
		<?php if(is_production_mode()): /* в production версии подключаем css и js через google cdn */ ?>
		
			<?php echo link_tag("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/cupertino/jquery-ui.css"); ?>

			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script language="javascript">
				google.load("jquery","1.7.1");
				google.load("jqueryui","1.8");
			</script>

		<?php else: ?>

			<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>themes/dashboard/js/ui/themes/base/jquery.ui.theme.css" />
			<script type="text/javascript" src="<?php echo base_url(); ?>themes/dashboard/js/jquery-1.6.1.min.js" > </script>
			<script type="text/javascript" src="<?php echo base_url(); ?>themes/dashboard/js/ui/jquery-ui-1.8.16.custom.min.js"> </script>
		
		<?php endif; ?>

		<script type="text/javascript" src="<?php echo base_url();?>themes/dashboard/js/jquery.keyfilter.js"></script>
		<?php
			echo link_tag('themes/start/css/style.css');
			echo $template['metadata'];
		?>
	</head>
	<body>
		<div class="bg">
			<div class="shapka">
		  		<a href="<?php echo site_url();?>">
		  			<img src="<?php echo site_url("themes/start/images/logo.png");?>" width="232" height="84" /></a>
		    	<div class="slogan">система учета заявок для агентств недвижимости</div>
			</div>
			<div class="menu">
				<?php load_partial_template($template,'menu'); ?>
			</div>
	    	<div class="content">
	    		<div class="left">
	    			<?php echo $template['body']; ?>
	    		</div>
	    		<div class="right">
					<?php if( ! empty($loginBox)):
							echo $loginBox; 
						  else :
					?>
						Вы уже авторизованы. <br/>
						Войти в <a href="<?php echo site_url($this->users->resolve_user_redirect_uri()); ?>">панель управления</a>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="podval">
				<div><img src="<?php echo site_url("themes/start"); ?>/images/logo_flyweb.png" width="117" height="35" style="cursor:pointer;" onclick="document.location='http://flywebstudio.ru/'" />	<br />
				© <a href="http://flywebstudio.ru">copyright 2012 Flyweb inc.</a>	</div>
			</div>
		</div>
		<?php  if($this->session->userdata('browser') == 'bad'): ?>

			<div id="main_overlay" style="position:absolute;top:0px;left:0px;z-index:9999;width:100%;background-color:#0099cc;opacity:0.1"> 
			</div>
			<div style="position:absolute;top:45%;left:32%;background-color:#fff;color:#000;z-index:99999;margin:auto;padding:10px;border: 3px #09C solid;opacity:0.95">
				<p style="text-align:justify;">
					<img src="<?php echo base_url(); ?>themes/dashboard/images/browsers/alert.png" align="left"/>
					Ваш браузер не входит в число рекомендуемых к использованию. <br/> Настоятельно рекомендуем установить себе следующие браузеры.
				</p>
				<div style="overflow:auto;">
					<div style="float:left;text-align:center;margin-left:30px">
						<img src="<?php echo base_url();?>themes/dashboard/images/browsers/browser_chrome.png" style="height:128px;width:128px;"/> <br/>
						<?php echo anchor("https://www.google.com/chrome/?installdataindex=nosearch&hl=ru&brand=CHMA&utm_campaign=ru&utm_source=ru-ha-emea-ru-bk&utm_medium=ha","Скачать",'target="blank"'); ?>
					</div>
					<div style="float:left;text-align:center;margin-left:45px">
						<img src="<?php echo base_url();?>themes/dashboard/images/browsers/browser_opera.png" style="height:128px;width:128px;" /> <br/>
						<?php echo anchor("http://ru.opera.com/download/","Скачать",'target="blank"'); ?>
					</div>
					<div style="float:left;text-align:center;margin-left:40px">
						<img src="<?php echo base_url();?>themes/dashboard/images/browsers/browser_firefox.png" style="height:128px;width:128px;" /> <br/>
						<?php echo anchor("http://www.mozilla.org/ru/firefox/new/","Скачать",'target="blank"'); ?>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$(function(){
					var overlay = document.getElementById('main_overlay');
					overlay.style.height = document.body.scrollHeight+'px';
				})
			</script>
		<?php endif; ?>
		
		<?php /* В Production версии добавляем код отслеживания*/
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

		<?php endif; ?>
	</body>
</html>