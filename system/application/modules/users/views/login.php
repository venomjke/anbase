<?php echo validation_errors(); ?>
<?php print_r($errors); ?>
<?php echo form_open("users/auth/login"); ?>

<?php echo form_input("login","Логин"); ?>

<?php echo form_input("password","Пароль"); ?>

<?php echo form_submit("submit","Отправить"); ?>

<?php echo form_close(); ?>