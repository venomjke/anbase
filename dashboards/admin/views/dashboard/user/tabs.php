<div id="dashboard_tabs">
	<ul class="zakladki uzers">
  		<li class="item1 <?php echo $current=="staff"?"curent":""; ?>"><a href="<?php echo site_url("admin/user/staff/?act=view"); ?>">Сотрудники</a></li>
    	<li class="<?php echo $current=="admins"?"curent":""; ?>"><a href="<?php echo site_url("admin/user/admins/?act=view"); ?>">Руководители</a></li>
    	<span class="plus" id="togglePanel" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Агент</a></span>
    	<span class="plus" id="togglePanel" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Менеджер</a></span>
    	<span class="plus" id="togglePanel" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Админ</a></span>
    </ul>
</div>