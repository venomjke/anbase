
<?php
		$actions = array(
			anchor("#","добавить",'onclick="admin.orders.add({uri:\'admin/orders/?act=add\'});return false;"'),
			anchor("#","удалить",'onclick="admin.orders.del({uri:\'admin/orders/?act=del\'});return false;"')
		);
		echo ul($actions,array('id'=>'toolbar'));
?>