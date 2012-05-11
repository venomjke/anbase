<div id="dashboard_tabs">
	<ul class="zakladki zayavki">
  		<li class="item1 <?php echo $current=="all"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=all"); ?>">Все заявки</a></li>
    	<li class="<?php echo $current=="free"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=free"); ?>">Свободные заявки</a></li>
    	<li class="<?php echo $current=="delegate"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=delegate"); ?>">Заявки агентов</a></li>
    	<li class="<?php echo $current=="off"?"curent":""; ?>"><a href="<?php echo site_url("admin/orders/?act=view&s=off"); ?>">Завершенные заявки</a></li>

            <span class="plus minus" id="del_order" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Удалить</a></span>
            <span class="plus" id="print_order" style="margin-top:5px; float:right; margin-right:16px"> <a href="#" onclick="return false;">Распечатать</a></span>
    	<?php if($current != 'off'): ?>
    		<span class="plus minus" id="finish_order" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Завершить</a></span>
            <span class="plus" id="add_order" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Добавить</a></span>
        <?php else: ?>
            <span class="plus" id="restore_order" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Возобновить</a></span>
    	<?php endif; ?>

    </ul>
</div>