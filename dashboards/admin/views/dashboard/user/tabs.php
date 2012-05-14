<div id="dashboard_tabs">
	<ul class="zakladki uzers">
  		<li class="item1 <?php echo $current=="staff"?"curent":""; ?>"><a href="<?php echo site_url("admin/user/staff/?act=view");?>">Сотрудники</a></li>
    	<li class="<?php echo $current=="admins"?"curent":""; ?>"><a href="<?php echo site_url("admin/user/admins/?act=view");?>">Руководители</a></li>
    	<li class="<?php echo $current=="invites"?"curent":""; ?>"><a href="<?php echo site_url("admin/user/invites/?act=view");?>">Приглашения</a></li>


    	<?php if($current == "invites"): ?>
    	   <span class="plus minus" id="del_invites" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Удалить</a></span> 
        <?php endif; ?>

        <?php if($current == "staff" or $current == "admins"): ?>
           <span class="plus minus" id="del_users" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Уволить</a></span>
        <?php endif; ?>

        <span class="plus" id="add_admin" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Админ</a></span>
    	<span class="plus" id="add_manager" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Менеджер</a></span>
        <span class="plus" id="add_agent" style="margin-top:5px; float:right; margin-right:16px"><a href="#" onclick="return false;">Агент</a></span>
    </ul>
</div>