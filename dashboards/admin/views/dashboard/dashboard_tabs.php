<div id="dashboard_tabs">
	<ul class="zakladki zayavki">
  		<li class="item1 <?php echo $current=="all"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=all"); ?>">Все заявки</a></li>
    	<li class="<?php echo $current=="free"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=free"); ?>">Свободные заявки</a></li>
    	<li class="<?php echo $current=="delegate"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=delegate"); ?>">Делегированные заявки</a></li>
    	<span class="plus" id="togglePanel" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Свернуть рабочую область</a></span>
    </ul>
</div>
<script type="text/javascript">
	$(function(){
		$('#togglePanel').click(function(){
			$('.shapka').slideToggle("fast");
			$('.user').slideToggle("fast");
			$('.menu').slideToggle("fast");
			$('.podval').slideToggle("fast");
		});
	});
</script>