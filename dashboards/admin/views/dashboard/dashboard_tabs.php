<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("admin/orders/?act=view","Все заявки"),
			anchor("admin/orders/?act=view&s=free","Свободные"),
			anchor("admin/orders/?act=view&s=delegate","Заявки агентов")
		);
		echo ul($tabs);
	?>
</div>