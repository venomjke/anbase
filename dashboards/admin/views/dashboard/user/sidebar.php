<div class="sidebar">
		<div class="action_box">
			<div> <a href="<?php echo site_url("admin/user/staff"); ?>"> Сотрудники </a> </div>
			<div> <a href="<?php echo site_url("admin/user/admins"); ?>"> Админы </a> </div>
		</div>
		<hr/>
		<div class="action_box">
			<div> <a href="<?php echo site_url("admin/user/invites/?act=view") ?>"> Просмотреть инвайты </a> </div>
			<hr/>
			<div> <a href="#" onclick="admin.user.admin_invite();return false;" >Добавить админа</a> </div>
			<div> <a href="#" onclick="admin.user.manager_invite();return false;"> Добавить менеджера </a> </div>
			<div> <a href="#" onclick="admin.user.agent_invite();return false;"> Добавить агента </a> </div>
		</div>
</div>

<div id="admin_invite_dialog">
	<form action="<?php echo site_url("admin/user/invites/?act=add&for=admins");?>" method="post" onsubmit="return false;">
		<?php echo form_label("Email"); ?><br/>
		<?php echo form_input("email","",'style="width:100%"'); ?>
	</form>
</div>
<div id="manager_invite_dialog">
	<form action="<?php echo site_url("admin/user/invites/?act=add&for=managers");?>" method="post" onsubmit="return false;">
		<?php echo form_label("Email"); ?><br/>
		<?php echo form_input("email","",'style="width:100%"'); ?>
	</form>
</div>
<div id="agent_invite_dialog">
	<form action="<?php echo site_url("admin/user/invites/?act=add&for=agents");?>" method="post" onsubmit="return false;">
		<div>
			<?php echo form_label("Email"); ?><br/>
			<?php echo form_input("email","",'style="width:100%"'); ?> 
		</div>
		<div>
			<?php echo form_label("Менеджер"); ?> <br/>
			<select name="manager_id" style="width:100%">
				<option value=""> - Нету - </option>
				<?php
					foreach($this->admin_users->get_all_managers() as $manager):
				?>
					<option value="<?php echo $manager->id; ?>"> <?php echo make_official_name($manager->name,$manager->middle_name,$manager->last_name); ?></option>
				<?php endforeach; ?> 
			</select>
		</div>
	</form>
</div>