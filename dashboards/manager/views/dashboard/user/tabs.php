<div id="dashboard_tabs">
	<ul class="zakladki uzers">
  		<li class="item1 <?php echo $current=="staff"?"curent":""; ?>"><a href="<?php echo site_url("manager/user/staff/?act=view"); ?>">Сотрудники</a></li>
    	<li class="<?php echo $current=="admins"?"curent":""; ?>"><a href="<?php echo site_url("manager/user/admins/?act=view"); ?>">Руководители</a></li>
    </ul>
</div>