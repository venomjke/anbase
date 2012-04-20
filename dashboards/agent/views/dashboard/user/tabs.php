<div id="dashboard_tabs">
	<ul class="zakladki uzers">
  		<li class="item1 <?php echo $current=="staff"?"curent":""; ?>"><a href="<?php echo site_url("agent/user/staff/?act=view"); ?>">Сотрудники</a></li>
    	<li class="<?php echo $current=="admins"?"curent":""; ?>"><a href="<?php echo site_url("agent/user/admins/?act=view"); ?>">Руководители</a></li>
    </ul>
</div>