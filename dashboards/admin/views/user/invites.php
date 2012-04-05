<div id="wrap3">
	<?php load_partial_template($template,'sidebar'); ?>
	<div id="wrap4">
		<div class="toolbar">
			<button> Удалить </button>
		</div>
		<div class="user_table">
			<table>
				<thead>
					<tr>
						<th>
							<?php echo form_checkbox("all"); ?>
						</th>
						<th>
							Ключ
						</th>
						<th>
							email
						</th>
						<th>
							Должность
						</th>
						<th>
							Дата
						</th>
						<th>
							Операции
						</th>
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<?php if(!empty($invites)): 
							foreach($invites as $invite):
					?>
							<tr id="invite_<?php echo $invite->id; ?>">
								<td> <?php echo form_checkbox("all"); ?> </td>
								<td> <?php echo $invite->key_id; ?> </td>
								<td> <?php echo $invite->email; ?> </td>
								<td> <?php echo $invite->role; ?> </td>
								<td> <?php echo $invite->created; ?> </td>
								<td>
									<a href="#" onclick="admin.user.del_invite({jObjAct:$(this),id:'<?php echo $invite->id;?>',uri:'admin/user/invites/?act=del',text:'<?php echo lang('confirm_delete_invite'); ?>'});return false"> Удалить </a>
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