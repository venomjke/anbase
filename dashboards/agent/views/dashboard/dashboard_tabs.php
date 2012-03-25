<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("agent/orders/view","Мои заявки"),
			anchor("agent/orders/view/?s=free","Свободные заявки")
		);
		echo ul($tabs);
	?>
</div>