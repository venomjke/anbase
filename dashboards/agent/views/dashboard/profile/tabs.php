<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("agent/profile/view/?s=personal","Личная информация"),
			anchor("agent/profile/view/?s=org","Организация"),
			anchor("agent/profile/view/?s=system","Аккаунт")
		);
		echo ul($tabs);
	?>
</div>