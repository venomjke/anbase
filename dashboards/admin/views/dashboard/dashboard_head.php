<div id="org_box">
	<span id="org_name"> <?php echo $this->admin_users->get_org_name(); ?> </span>
	<div style="margin-left: 300px;width: 300px;">
		<div id="callmanager_box">
			Диспетчер: <span> <?php echo $this->admin_users->get_callmanager_phone(); ?> </span>
		</div>
	</div>
</div>
<ul id="menu">
	<li> <?php echo anchor("admin/orders","Заявки"); ?> </li>
	<li> <?php echo anchor("admin/user","Пользователи"); ?> </li>
</ul>
<div id="user_box">
	<span id="greetings"> Здравствуйте <?php echo $this->admin_users->get_official_name(); ?> </span>
	<span id="profile"> Вы вошли как <?php echo anchor("admin/profile","Админ") ?> </span>
	<?php echo anchor("admin/profile","Профиль",'class="user_box_btn"'); ?>
	<?php echo anchor("logout","Выход",'class="user_box_btn"'); ?>
</div>