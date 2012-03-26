<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("manager/profile/view/?s=personal","Личная информация"),
			anchor("manager/profile/view/?s=org","Организация"),
			anchor("manager/profile/view/?s=system","Аккаунт")
		);
		echo ul($tabs);
	?>
</div>