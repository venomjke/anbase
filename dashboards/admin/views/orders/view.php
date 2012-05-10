<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu') ?>
	<?php load_partial_template($template,'dashboard_filter'); ?>
	<div class="content">
		<?php load_partial_template($template,'dashboard_tabs'); ?>
	    <div class="tablica" id="orders_grid" style="height:550px; border:1px #8AA1BC solid;">
	    </div>
	    <div class="nastroiki"><?php echo anchor("admin/settings","Настройки таблицы"); ?></div>
  	</div>
  	<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
</div>

<script type="text/javascript">
	<?php echo $this->load->view('orders/'.$section.'.js',array(),true); ?>
</script>


<!-- Форма добавления заявки -->

<div id="add_order_dialog">
	<table cellspacing="0" cellpadding="6" style="float:left">
		<tbody>
			<tr>
				<td>
					<label for="add_order_category" style="font-weight:bold">Тип объекта</label>
					<br/>
					<select style="width:170px" id="add_order_category">
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="add_order_building" style="font-weight:bold">Тип помещения</label>
					<br/>
					<select style="width:170px" multiple id="add_order_building">
						<option value="комната">комната</option>
						<option value="1к.кв">1к.кв</option>
						<option value="2к.кв">2к.кв</option>
						<option value="3к.кв">3к.кв</option>
						<option value="4к.кв"> 4к.кв </option>
						<option value="5к.кв и более"> 5к.кв и более </option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="add_order_dealtype" style="font-weight:bold">Тип сделки</label>
					<br/>
					<select id="add_order_dealtype" style="width:170px"></select>
				</td>
			</tr>
			<tr>
				<td>
					<table cellspacing="5">
						<tbody>
							<tr>
								<td>
									<label style="font-weight:bold">Районы</label>
									<br>
									<a id="add_order_regions" href="#" onclick="return false;">Выбрать</a>
								</td>
								<td>
									<label style="font-weight:bold">Метро</label>
									<br>
									<a id="add_order_metros" href="#" onclick="return false;">Выбрать</a>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<label for="add_order_price" style="font-weight:bold">Цена</label>
					<br>
					<input type="text" id="add_order_price">
				</td>
			</tr>
			<tr>
				<td>
					<label for="add_order_phone" style="font-weight:bold">Телефон</label>
					<br>
					<input id="add_order_phone" type="text">
				</td>
			</tr>
		</tbody>
	</table>
	<table cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
					<label for="add_order_description" style="font-weight:bold">Описание</label>
					<br>
					<textarea id="add_order_description" style="width:350px; height:90px"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</div>