<?php
	load_partial_template($template,'dashboard_tabs');
?>
<div id="wrap1">
	<?php load_partial_template($template,'orders_toolbar'); ?>
	<table cellspacing="0" cellpadding="0" id="dashboard_table">
		<thead>
			<tr>
				<th>
					<input type="checkbox" onclick="admin.orders.check_all({jObjAction:$(this)});" />
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
				<th>Ф.И.О агента</th>
				<th>Дата делегирования</th>
				<th>Контакты<th>
			</tr>
		</thead>
		<tbody>
			<?php 
					if(!empty($orders)):
						$i = 0;
						foreach($orders as $order):
			?>
				<tr <?php echo $i%2 == 0?'style="background-color:#c4c4c4;"':""; ?> id="order_<?php echo $order->id; ?>" >
						<td>
							<?php echo form_checkbox("orders_ids",$order->id); ?>
						</td>
						<td ondblclick="admin.orders.edit_text({jObjAction:$(this), name:'number', id:<?php echo $order->id; ?>, uri:'<?php  echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->number; ?> </td>
						<td ondblclick="admin.orders.edit_text({jObjAction:$(this), name:'create_date', id:<?php echo $order->id;?>, uri:'<?php echo "admin/orders/?act=edit";?>'});"> <?php echo $order->create_date; ?></td>
						<td ondblclick="admin.orders.edit_select({jObjAction:$(this),name:'category',id:<?php echo $order->id;?>, uri:'<?php echo "admin/orders/?act=edit";?>' });"> <?php echo $order->category; ?> </td>
						<td ondblclick="admin.orders.edit_select({jObjAction:$(this),name:'deal_type',id:<?php echo $order->id; ?>,uri:'<?php echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->deal_type; ?></td>
						<td ondblclick="admin.orders.edit_select({jObjAction:$(this),name:'region_id',id:<?php echo $order->id; ?>,uri:'<?php echo "admin/orders/?act=edit"; ?>'}); " > <?php echo $order->region_name; ?></td>
						<td ondblclick="admin.orders.edit_select({jObjAction:$(this),name:'metro_id',id:<?php echo $order->id; ?>,uri:'<?php echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->metro_name; ?></td>
						<td ondblclick="admin.orders.edit_text({jObjAction:$(this), name:'price', id:<?php echo $order->id; ?>, uri:'<?php  echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->price; ?></td>
						<td ondblclick="admin.orders.edit_bigtext({jObjAction:$(this), name:'description', id:<?php echo $order->id; ?>, uri:'<?php  echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->description; ?>
						</td>
						<td> <?php echo $order->user_name.' '.$order->user_middle_name.' '.$order->user_last_name; ?></td>
						<td ondblclick="admin.orders.edit_text({jObjAction:$(this), name:'delegate_date', id:<?php echo $order->id; ?>, uri:'<?php  echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->delegate_date; ?> </td>
						<td ondblclick="admin.orders.edit_text({jObjAction:$(this), name:'phone', id:<?php echo $order->id; ?>, uri:'<?php  echo "admin/orders/?act=edit"; ?>'});"> <?php echo $order->phone; ?> </td>
				</tr>
			<?php
						++$i;
						endforeach;
					endif;
			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</div>
<noscript> У вас отключен javascript, из-за этого могут не работать некоторые инструменты сайта </noscript>
<!-- диалоги для редактирования таблицы -->
<div id="dialog_add_order">
	<form onsubmit="return false;">
		<div>
			<label for="number"> № заявки </label> <br/>
			<input type="text" name="number" value=""/>
		</div>
		<div>
			<label for="create_date"> Дата создания </label> <br/>
			<input type="text" name="create_date" value="" />
		</div>
		<div>
			<label for="category"> Категория </label> <br/>
			<select name="category">
				<?php foreach($this->admin_users->get_category_list() as $category): ?>
				<option value="<?php echo $category; ?>"> <?php echo $category; ?> </option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label for="deal_type"> Вид сделки </label> <br/>
			<select name="deal_type">
				<?php foreach($this->admin_users->get_dealtype_list() as $deal_type): ?>
					<option value="<?php echo $deal_type; ?>"> <?php echo $deal_type; ?> </option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label for="region_id"> Регион </label> <br/>
			<select name="region_id">
			<?php foreach($this->admin_users->get_region_list() as $region ): ?>
				<option value="<?php echo $region->id; ?>"> <?php echo $region->name; ?> </option>
			<?php endforeach; ?>
			</select>	
		</div>
		<div>
			<label for="metro_id"> Метро </label>  <br/>
			<select name="metro_id">
				<?php foreach($this->admin_users->get_metro_list() as $metro): ?>
				<option value="<?php echo $metro->id;?>"> <?php echo $metro->name; ?> </option>
				<?php endforeach;?>
			</select>
		</div>
		<div>
			<label for="price"> Цена </label> <br/>
			<input type="text" name="price" value="" />
		</div>	
		<div>
			<label for="description"> Описание </label> <br/>
			<textarea name="description"></textarea>
		</div>
		<div>
			<label for="phone"> Телефон </label> <br/>
			<input type="text" name="phone" value="" />
		</div>
	</form>
</div>
<div id="dialog_edit_number">
	<form onsubmit="return false;">
		<input type="text" name="number" value="" />
	</form>
</div>
<div id="dialog_edit_create_date">
	<form onsubmit="return false;">
		<input type="text" name="create_date" value="" />
	</form>
</div>
<div id="dialog_edit_category">
	<form onsubmit="return false;">
		<select name="category">
		<?php foreach($this->admin_users->get_category_list() as $category): ?>
		<option value="<?php echo $category; ?>"> <?php echo $category; ?> </option>
	<?php endforeach; ?>
		</select>
	</form>
</div>
<div id="dialog_edit_deal_type">
	<form onsubmit="return false;">
		<select name="deal_type">
		<?php foreach($this->admin_users->get_dealtype_list() as $deal_type): ?>
		<option value="<?php echo $deal_type; ?>"> <?php echo $deal_type; ?> </option>
		<?php endforeach; ?>
		</select>
	</form>
</div>
<div id="dialog_edit_region_id">
	<form onsubmit="return false;">
		<select name="region_id">
		<?php foreach($this->admin_users->get_region_list() as $region ): ?>
			<option value="<?php echo $region->id; ?>"> <?php echo $region->name; ?> </option>
		<?php endforeach; ?>
		</select>
	</form>
</div>
<div id="dialog_edit_metro_id">
	<form onsubmi="return false;">
		<select name="metro_id">
			<?php foreach($this->admin_users->get_metro_list() as $metro): ?>
			<option value="<?php echo $metro->id;?>"> <?php echo $metro->name; ?> </option>
			<?php endforeach;?>
		</select>
	</form>
</div>
<div id="dialog_edit_price">
	<form onsubmit="return false;">
		<input type="text" name="price" value="" />
	</form>
</div>
<div id="dialog_edit_description">
	<form onsubmit="return false;">
		<textarea name="description"></textarea>
	</form>
</div>
<div id="dialog_edit_delegate_date">
	<form onsubmit="return false;">
		<input type="text" name="delegate_date" />
	</form>
</div>
<div id="dialog_edit_phone">
	<form onsubmit="return false;">
		<input type="text" name="phone" />
	</form>
</div>
<!-- -->

