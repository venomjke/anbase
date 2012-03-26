<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("admin/profile/view/?s=personal","Личная информация"),
			anchor("admin/profile/view/?s=org","Организация"),
			anchor("admin/profile/view/?s=system","Аккаунт")
		);
		echo ul($tabs);
	?>
</div>