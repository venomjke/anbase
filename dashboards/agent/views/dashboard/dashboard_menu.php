<div class="menu">
	<span style="float:left"><span><?php echo russian_week_day(date("w"));?>, <?php echo date("d.m.y"); ?></span></span>
	<span style="margin-left:50px;float:left"><span>Ваш последний визит:<?php echo $this->agent_users->get_last_visit(); ?></span></span>
    <ul>
	    <li class="item1"><a href="<?php echo site_url("logout");?>">Выход</a></li>
	    <li class="item2"><a href="<?php echo site_url("agent/profile");?>">Профиль</a></li>
	    <li class="item3"><a href="<?php echo site_url("agent/user");?>">Пользователи</a></li>
	    <li class="item4"><a href="<?php echo site_url("agent/orders");?>">Заявки</a></li>
    </ul>
</div>