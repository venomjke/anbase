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
	
							<?php if(!empty($admins)): 
										foreach($admins as $admin):
							?>
								<tr>
									<td class="checkbox">
										<?php echo form_checkbox("all"); ?>
										<input type="hidden" id="user_id" value="<?php echo $admin->id; ?>"/>
									</td>
									<td>
										<?php echo	$admin->name; ?>
									</td>
									<td>
										<?php echo $admin->middle_name; ?>
									</td>
									<td>
										<?php echo $admin->last_name; ?>
									</td>
									<td>
										<?php echo $admin->phone; ?>
									</td>
									<td>
										<?php echo $admin->email; ?>
									</td>
									<td ondblclick="admin.user.dblclick_show_col({ jObjAct:$(this), name:'role', uri:'admin/user/admins/?act=change_position', role:['Админ','Менеджер','Агент'],text:'<?php echo lang("confirm_change_position"); ?>'});">
										<?php if($this->admin_users->is_ceo($admin->id)): ?>
												Директор
										<?php else: ?>
											<?php echo $admin->role; ?>
										<?php endif; ?>
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