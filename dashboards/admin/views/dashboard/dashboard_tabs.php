<div id="dashboard_tabs">
	<ul class="zakladki zayavki">
  		<li class="item1 <?php echo $current=="all"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=all"); ?>">Все заявки</a></li>
    	<li class="<?php echo $current=="free"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=free"); ?>">Свободные заявки</a></li>
    	<li class="<?php echo $current=="delegate"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=delegate"); ?>">Заявки агентов</a></li>
    	<li class="<?php echo $current=="off"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=off"); ?>">Завершенные заявки</a></li>
    </ul>
</div>