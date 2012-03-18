<h1> Index page flyweb anbase project! </h1>


<?php echo form_open("site/index"); ?>
<?php echo form_hidden("test_field","blablabla"); ?>
<?php echo form_submit("submit",'my submit!'); ?>
<?php echo form_close(); ?>

<?php echo "Execution time is - ".$this->benchmark->elapsed_time(); ?>