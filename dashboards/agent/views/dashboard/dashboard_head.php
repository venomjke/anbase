<div class="shapka">
  <a href="<?php echo site_url(""); ?>"><img src="<?php echo site_url("themes/dashboard/images/logo.png"); ?>" /></a>
  <div class="phone">  	
    <div class="dispetcher">Диспетчер:<br /><strong><?php echo $this->agent_users->get_callmanager_phone(); ?></strong>
    </div>
    <?php if($this->agent_users->has_manager()): ?>
	<div class="menedger">Ваш менеджер:<br /><strong><?php  echo $this->agent_users->get_manager_phone(); ?></strong><br /><?php echo $this->agent_users->get_manager_name(); ?>
    </div>
	<?php endif; ?>
    <div class="clier"></div>
  </div>
</div>