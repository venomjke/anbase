<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("admin/orders/view","Все заявки"),
			anchor("admin/orders/view/?s=free","Свободные"),
			anchor("admin/orders/view/?s=delegate","Заявки агентов")
		);
		echo ul($tabs);
	?>
</div>