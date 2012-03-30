<div id="wrap3">
	<?php load_partial_template($template,'sidebar'); ?>
	<div id="wrap4">
		<div class="toolbar">
			<button> Удалить </button>
			Поиск
			<span> 
				<?php echo form_input("search"); ?>
			</span>	
		</div>
		<div class="user_table">
			<table>
				<thead>
					<tr>
						<th>
							<?php echo form_checkbox("all"); ?>
						</th>
						<th>
							Имя
						</th>
						<th>
							Фамилия
						</th>
						<th>
							Отчество
						</th>
						<th>
							Телефон
						</th>
						<th>
							Email
						</th>
						<th>
							Должность
						</th>
						<th>
							Операции
						</th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
	
							<?php if(!empty($staff)): 
										foreach($staff as $employee):
							?>
								<tr>
									<td class="checkbox">
										<?php echo form_checkbox("all"); ?>
									</td>
									<td>
										<?php echo	$employee->name; ?>
									</td>
									<td>
										<?php echo $employee->middle_name; ?>
									</td>
									<td>
										<?php echo $employee->last_name; ?>
									</td>
									<td>
										<?php echo $employee->phone; ?>
									</td>
									<td>
										<?php echo $employee->email; ?>
									</td>
									<td>
										<?php echo $employee->role; ?>
									</td>
								</tr>
							<?php
								  		endforeach;
								  endif;
							?>
				</tbody>
			</table>
		</div>
	</div>
</div>