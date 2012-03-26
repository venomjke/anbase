<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("manager/orders/view","Мои заявки"),
			anchor("manager/orders/view/?s=free","Свободные заявки"),
			anchor("manager/orders/view/?s=delegate","Заявки агентов")
		);
		echo ul($tabs);
	?>
</div>