<div id="org_box">
	<span id="org_name"> <?php echo $this->agent_users->get_org_name(); ?> </span>
	<div style="margin-left: 300px;width: 300px;">
		<div id="callmanager_box">
			Диспетчер: <span> <?php echo $this->agent_users->get_callmanager_phone(); ?> </span>
		</div>
		<?php if($this->agent_users->has_manager()): ?>
		<div id="manager_box">
				Менеджер: <span> <?php echo $this->agent_users->get_manager_name(); ?> </span> <br/>
				Телефон: <span> <?php echo $this->agent_users->get_manager_phone(); ?> </span>
		</div>
		<?php endif; ?>
	</div>
</div>
<ul id="menu">
	<li> <?php echo anchor("agent/orders","Заявки"); ?> </li>
	<li> <?php echo anchor("agent/users","Пользователи"); ?> </li>
</ul>
<div id="user_box">
	<span id="greetings"> Здравствуйте <?php echo $this->agent_users->get_official_name(); ?> </span>
	<span id="profile"> Вы вошли как <?php echo anchor("agent/profile","Агент") ?> </span>
	<?php echo anchor("agent/profile","Профиль",'class="user_box_btn"'); ?>
	<?php echo anchor("logout","Выход",'class="user_box_btn"'); ?>
</div>