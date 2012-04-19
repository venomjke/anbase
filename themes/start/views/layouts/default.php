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
			echo link_tag('themes/start/css/style.css');
			echo $template['metadata'];
		?>
	</head>
	<body>
		<div class="bg">
			<div class="shapka">
		  		<a href="<?php echo site_url("");?>">
		  			<img src="<?php echo site_url("themes/start/images/logo.png");?>" width="232" height="84" /></a>
		    	<div class="slogan">персональная база заявок для агентств недвижимости</div>
			</div>
			<div class="menu">
				<?php load_partial_template($template,'menu'); ?>
			</div>
	    	<div class="content">
	    		<div class="left">
	    			<?php echo $template['body']; ?>
	    		</div>
	    		<div class="right">
				<?php if(!empty($loginBox))echo $loginBox; ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="podval">
				<div><img src="<?php echo site_url("themes/start"); ?>/images/logo_flyweb.png" width="117" height="35" />	<br />
				© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
			</div>
		</div>
	</body>
</html>