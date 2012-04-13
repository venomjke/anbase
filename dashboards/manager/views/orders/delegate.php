<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap1">
	<table cellspacing="0" cellpadding="0" id="dashboard_table">
		<thead>
			<tr>
				<th>
					<?php echo form_checkbox("check_all"); ?>
				</th>
				<th>
					№
				</th>
				<th>
					Дата создания.
				</th>
				<th>
					Объект
				</th>
				<th>
					Вид сделки
				</th>
				<th>
					Район
				</th>
				<th>Метро</th>
				<th>Цена</th>
				<th>Описание</th>
				<th>Ф.И.О агента </th>
				<th>Дата делегирования </th>
				<th>Контакты<th>
			</tr>
		</thead>
		<tbody>
			<?php 
					if(!empty($orders)):
						foreach($orders as $order):
			?>
				<tr>
						<td>
							<?php echo form_checkbox("check_all"); ?>
						</td>
						<td> <?php echo $order->number; ?> </td>
						<td> <?php echo $order->create_date; ?></td>
						<td> <?php echo $order->category; ?> </td>
						<td> <?php echo $order->deal_type; ?></td>
						<td> </td>
						<td> <?php echo $order->metro_name; ?></td>
						<td> <?php echo $order->price; ?></td>
						<td> <?php echo nl2br($order->description); ?>
						</td>
						<td> <?php echo make_official_name($order->user_name,$order->user_middle_name,$order->user_last_name); ?> </td>
						<td> <?php echo $order->delegate_date; ?> </td>
						<td> <?php echo $order->phone; ?> </td>
						<td> </td>
				</tr>
			<?php
						endforeach;
					endif;
			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</div>