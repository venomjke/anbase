<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap2">
	<div id="profileWrap">
		<div class="profile_row">
			<span class="profile_col profile_col_img">
				<?php echo img("themes/dashboard/images/label.png"); ?>
			</span>
			<span class="profile_col profile_col_label">
				Название
			</span>
			<span class="profile_col profile_col_text">
				<?php echo $this->manager_users->get_org_name(); ?>
			</span>
		</div>
	 	<div class="profile_row">
	 		<span class="profile_col profile_col_img"> 
	 			<?php echo img("themes/dashboard/images/phone.png"); ?>
	 		</span>
	 		<span class="profile_col profile_col_label">
	 			Диспетчер
	 		</span>
	 		<span class="profile_col profile_col_text">
	 			<?php echo $this->manager_users->get_callmanager_phone(); ?>
	 		</span>
	 	</div>	
	 	<div class="profile_row">
	 		<span class="profile_col profile_col_img">
	 			<?php echo img("themes/dashboard/images/email.png"); ?>
	 		</span>
	 		<span class="profile_col profile_col_label">Email</span>
	 		<span class="profile_col profile_col_text">
	 			<?php echo $this->manager_users->get_org_email(); ?>
	 		</span>
	 	</div>
	</div>
</div>