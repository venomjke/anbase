<?php
	load_partial_template($template,'dashboard_head');
?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu') ?>
	<?php load_partial_template($template,'dashboard_filter'); ?>
	<div class="content">
		<?php load_partial_template($template,'dashboard_tabs'); ?>
	    <div class="tablica" id="orders_grid" style="height:550px; border:1px #8AA1BC solid;">
	    </div>
  	</div>
  	<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
</div>

<script type="text/javascript">
	<?php echo $this->load->view('orders/free.js',array(),true); ?>
</script>