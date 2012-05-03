<div id="dashboard_tabs">
	<ul class="zakladki zayavki">
  		<li class="item1 <?php echo $current=="my"?"curent":""; ?>"><a href="<?php echo site_url("agent/orders/?act=view&s=my"); ?>">Мои заявки</a></li>
    	<li class="<?php echo $current=="off"?"curent":""; ?>"><a href="<?php echo site_url("agent/orders/?act=view&s=off"); ?>">Завершенные заявки</a></li>
    	<li class="<?php echo $current=="free"?"curent":""; ?>"><a href="<?php echo site_url("agent/orders/?act=view&s=free"); ?>">Свободные заявки</a></li>
    </ul>
</div>