<div class="shapka">
  <a href="<?php echo site_url(""); ?>"><img src="<?php echo site_url("themes/dashboard/images/logo.png"); ?>" /></a>
  <div class="phone">  	
    <div class="dispetcher">Диспетчер:<br /><strong><?php echo $this->admin_users->get_callmanager_phone(); ?></strong>
    </div>
    <div class="clier"></div>
  </div>
</div>
<?php 
    $warningOrgs = [];
 ?>
<?php if (in_array($this->users->get_org_id(), $warningOrgs)): ?>
    <div class="notification warning-notification">
        <h2>
            Предупреждение    
        </h2>
        <div class="notification-content">
            <p>
                Уважаемый пользователь, пробный период пользования сервисом подошел к концу. <br/>
            </p>
            <p>
                Если вы являетесь администратором или владелецем зарегистрированной организации, <br> то         
                необходимо выбрать один из представленных <a href="<?php echo site_url('prices') ?>" target="_blank">тарифов</a>, и сообщить о нем по адресу <a href="mailto:sales@anbase.ru">sales@anbase.ru</a> <br> 

            </p>
            В случае, если у вас возникли дополнительные вопросы, можно позвонить по т. 8 (921) 984-40-40
        </div>
    </div>    
<?php endif ?>
