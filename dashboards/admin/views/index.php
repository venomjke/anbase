			<div id="dashboard_tabs">
				<ul>
					<li> <a href="#"> Все заявки </a> </li>
					<li> <a href="#"> Свободные </a> </li>
					<li> <a href="#"> Заявки агентов </a> </li>
				</ul>
			</div>
			<div id="wrap1">
				<ul id="toolbar">
					<li> <a href="#"> Добавить </a> </li>
					<li> <a href="#"> Удалить </a> <li>
				</ul>
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
							<th>Ф.И.О агента</th>
							<th>Дата делегирования</th>
							<th>Контакты<th>
							<th>Операции</th>
						</tr>
					</thead>
					<tbody>
						<td>
							<?php echo form_checkbox("check_all"); ?>
						</td>
						<td> 1 </td>
						<td> 2012-11-3 12:42</td>
						<td> Жилая недвижимость </td>
						<td> Аренда</td>
						<td> р. Фрунзенский</td>
						<td> м. Парк-Победы </td>
						<td> 20 000 </td>
						<td> бла бла бла бла бла бла бла <br/>
								бла бла бла <br/>
								бла бла
						</td>
						<td>Стригин А.П</td>
						<td>2012-11-3 15:43</td>
						<td>+79219844040</td>
						<td> пусто </td>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>