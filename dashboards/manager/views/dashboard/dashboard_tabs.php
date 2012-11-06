<div id="dashboard_tabs">
	<ul class="zakladki zayavki">
  		<li class="item1 <?php echo $current=="my"?"curent":""; ?>"><a href="<?php echo site_url("manager/orders/?act=view&s=my"); ?>">Мои заявки</a></li>
    	<li class="<?php echo $current=="off"?"curent":""; ?>"><a href="<?php echo site_url("manager/orders/?act=view&s=off"); ?>">Завершенные заявки</a></li>
    	<li class="<?php echo $current=="delegate_on"?"curent":""; ?>"><a href="<?php echo site_url("manager/orders/?act=view&s=delegate_on"); ?>">Заявки агентов</a></li>
    	<li class="<?php echo $current=="free"?"curent":""; ?>"><a href="<?php echo site_url("manager/orders/?act=view&s=free"); ?>">Свободные заявки</a></li>
    	<li class="<?php echo $current=="delegate_off"?"curent":"";?>"><a href="<?php echo site_url("manager/orders/?act=view&s=delegate_off"); ?>">Завершенные заявки агентов</a></li>
		<?php if($current == "my"): ?>
            <span class="plus" id="print_order" style="margin-top:5px; float:right; margin-right:16px"> <a href="#" onclick="return false;">Распечатать</a></span>
    	<?php endif; ?>
    </ul>
</div>