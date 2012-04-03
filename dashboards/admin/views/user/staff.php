<!-- Всякие нужные данные-->
<div id="assign_manager_dialog"> 
	<select name="manager_id" style="width:100%">
		<?php foreach($this->admin_users->get_all_managers() as $manager): ?>
		<option value="<?php echo $manager->id;?>"> <?php echo make_official_name($manager->name,$manager->middle_name,$manager->last_name); ?> </option>
		<?php endforeach; ?>
	</select>
</div>
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
							Менеджер
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
										<input type="hidden" id="user_id" value="<?php echo $employee->id; ?>" />
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
									<td ondblclick="admin.user.dblclick_show_col({ jObjAct:$(this), name:'role', uri:'admin/user/staff/?act=change_position', role:['Админ','Менеджер','Агент'], user_id:'<?php echo $employee->id; ?>', text:'<?php echo lang("confirm_change_position"); ?>'});">
										<?php echo $employee->role; ?>
									</td>
									<td>
										<?php if( ($employee->role == M_User::USER_ROLE_AGENT) and ($employee->manager_id)): ?>
										<?php 	echo make_official_name($employee->manager_name,$employee->manager_middle_name,$employee->manager_last_name); ?>
									    <?php elseif($employee->role == M_User::USER_ROLE_AGENT): ?>
									    	<a href="#" onclick="admin.user.assign_manager({ user_id:'<?php echo $employee->id; ?>',uri:'admin/user/staff/?act=assign_manager' });return false;" > Назначить </a>
										<?php endif; ?>
									</td>
									<td>
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