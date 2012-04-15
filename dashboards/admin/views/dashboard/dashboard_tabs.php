<div id="dashboard_tabs">
	<?php
		$tabs = array(
			anchor("admin/orders/?act=view","Все заявки"),
			anchor("admin/orders/?act=view&s=free","Свободные"),
			anchor("admin/orders/?act=view&s=delegate","Заявки агентов")
		);
		echo ul($tabs);
	?>
	<div id="filter_header">
		<ul id="filter_controls">
			<li style="padding-right:15px">
					Фильтр:
			</li>
			<li style="border-right: 1px #B4B4B4 solid;" >
				По номеру: <input type="text" name="f_number" style="margin-right:15px"/>
			</li>
			<li>
				<span>
					<input type="radio" name="status" id="status_on" /> <label for="status_on"> Все </label>
				</span>
				<span>
					<input type="radio" name="status" id="status_off" /> <label for="status_off"> Активные </label>
				</span>
			</li>
			<li>
				<a id="filter_toggle" href="#" onclick="return false;"> Развернуть/Свернуть </a>
			</li>
		</ul>
	</div>
</div>
<div id="dashboard_filter" style="display:none">
	<div class="filter_section">
		<h4> Категория </h4>
		<table>
			<tr>
				<td>
					Объект
				</td>
				<td>
					<select name="category">
						<option> Жилая </option>
						<option> Коммерческая </option>
						<option> Загородная </option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Вид сделки
				</td>
				<td>
					<select name="deal_type">
						<option> Сниму </option>
						<option> Куплю </option>
						<option> Продам </option>
						<option> Сдам </option>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="filter_section">
		<h4> Цена </h4>
		<table>
			<tr>
				<td>
					От
				</td>
				<td>
					<input type="text" name="from_price" />
				</td>
			</tr>
			<tr>
				<td>
					До
				</td>
				<td>
					<input type="text" name="to_price" />
				</td>
			</tr>
		</table>
	</div>
	<div class="filter_section">
		<h4> Дата </h4>
		<table>
			<tr>
				<td>
					С
				</td>
				<td>
					<input type="text" name="create_date_from" />
				</td>
			</tr>
			<tr>
				<td>
					По
				</td>
				<td>
					<input type="text" name="create_date_to" />
				</td>
			</tr>
		</table>
	</div>
	<div class="filter_section">
		<h4> Описание </h4>
		<textarea name="description" style="width: 300px;height:70px"></textarea>
	</div>
	<div class="filter_section">
		<h4> Районы </h4>
		<select name="region" multiple>
			<option> - Любой - </option>
			<option> Фрунзенский </option>
			<option> Красногвардейский </option>
		</select> 
		<a href="#" onclick="return false;" style="display:block"> Добавить </a>
	</div>
	<div class="filter_section">
		<h4> Метро </h4>
		<select name="metro" multiple>
			<option> - Любой - </option>
			<option> Купчино </option>
			<option> Пл. Восстания </option>
		</select>
		<a href="#" onclick="return false;" style="display:block"> Добавить </a>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#filter_toggle').click(function(e){
			$('#dashboard_filter').slideToggle("fast");
		});
	})
</script>