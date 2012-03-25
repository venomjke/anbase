<ul id="menu">
	<li> <?php echo anchor("agent/orders","Заявки"); ?> </li>
	<li> <?php echo anchor("agent/users","Пользователи"); ?> </li>
</ul>
<div id="user_box">
	<span id="greetings"> Здравствуйте <?php echo $this->agent_users->get_official_name(); ?> </span>
	<span id="profile"> Вы вошли как <?php echo anchor("agent/profile","Агент") ?> </span>
	<?php echo anchor("admin/profile","Профиль",'class="user_box_btn"'); ?>
	<?php echo anchor("logout","Выход",'class="user_box_btn"'); ?>
</div>