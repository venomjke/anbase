<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap2">
	<div id="profileWrap">
		<?php echo form_open("manager/profile/?s=personal","onsubmit=\"return false;\""); ?>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/label.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Имя 
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->manager_users->get_user_name(); ?>
				</span>
				<span class="profile_col profile_col_action">
					<a href="#" onclick="manager.profile.click_show_field({ jObjAct:$(this), name:'name', uri:'<?php echo "manager/profile/view/?s=personal"; ?>' }); return false;">Изменить</a>
				</span>
			</div>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
						<?php echo img("themes/dashboard/images/label.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Отчество
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->manager_users->get_user_middle_name(); ?>
				</span>
				<span class="profile_col profile_col_action">
					<a href="#" onclick="manager.profile.click_show_field({ jObjAct:$(this), name:'middle_name', uri:'<?php echo "manager/profile/view/?s=personal"; ?>' }); return false;">Изменить</a>
				
				</span>
			</div>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/label.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Фамилия
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->manager_users->get_user_last_name(); ?>
				</span>
				<span class="profile_col profile_col_action">
					<a href="#" onclick="manager.profile.click_show_field({ jObjAct:$(this), name:'last_name', uri:'<?php echo "manager/profile/view/?s=personal"; ?>' }); return false;">Изменить</a>
				</span>
			</div>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/phone.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Телефон
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->manager_users->get_user_phone(); ?>
				</span>
				<span class="profile_col profile_col_action">
					<a href="#" onclick="manager.profile.click_show_field({ jObjAct:$(this), name:'phone', uri:'<?php echo "manager/profile/view/?s=personal"; ?>' }); return false;">Изменить</a>
				</span>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>