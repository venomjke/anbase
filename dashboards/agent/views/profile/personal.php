<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap2">
	<div id="profileWrap">
		<?php echo form_open("agent/profile/?s=personal"); ?>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/label.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Имя 
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->agent_users->get_user_name(); ?>
				</span>
				<span class="profile_col profile_col_action">
					Изменить
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
					<?php echo $this->agent_users->get_user_middle_name(); ?>
				</span>
				<span class="profile_col profile_col_action">
					Изменить
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
					<?php echo $this->agent_users->get_user_last_name(); ?>
				</span>
				<span class="profile_col profile_col_action">
					Изменить
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
					<?php echo $this->agent_users->get_user_phone(); ?>
				</span>
				<span class="profile_col profile_col_action">
					Изменить
				</span>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>