<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap2">
	<div id="profileWrap">
		<?php echo form_open("agent/profile/view/?s=system"); ?>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/profile.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Профиль
				</span>
				<span class="profile_col profile_col_text">
					Агент
				</span>
			</div>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/password.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Пароль
				</span>
				<span class="profile_col profile_col_text">
					******
				</span>
			</div>
			<div class="profile_row">
				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/login.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Логин
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->agent_users->get_user_login(); ?>
				</span>
			</div>
			<div class="profile_row">

				<span class="profile_col profile_col_img">
					<?php echo img("themes/dashboard/images/email.png"); ?>
				</span>
				<span class="profile_col profile_col_label">
					Email
				</span>
				<span class="profile_col profile_col_text">
					<?php echo $this->agent_users->get_user_email(); ?>
				</span>
			</div>
		 <?php echo form_close(); ?>
	</div>
</div>