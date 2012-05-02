<div class="menu"><span style="float:left"><span><?php echo russian_week_day(date("w"));?>, <?php echo date("d.m.y"); ?></span></span>
    <ul>
	    <li class="item1"><a href="<?php echo site_url("logout");?>">Выход</a></li>
	    <li class="item2"><a href="<?php echo site_url("manager/profile");?>">Профиль</a></li>
	    <li class="item3"><a href="<?php echo site_url("manager/user");?>">Пользователи</a></li>
	    <li class="item4"><a href="<?php echo site_url("manager/orders");?>">Заявки</a></li>
    </ul>
</div>