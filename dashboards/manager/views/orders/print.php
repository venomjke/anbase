<?php
	$orders_table = array();
	$orders_table_head = array('Номер','Описание','Цена','Телефон');
	$orders_table_tmpl = array('table_open'=>'<table border="1" cellpadding="2" style="width:100%">');
	$this->table->set_heading($orders_table_head);
	$this->table->set_template($orders_table_tmpl);

	foreach($orders as $order){
		$o = array();
		$o[] = $order->number;
		$o[] = $order->description;
		$o[] = $order->price;
		$o[] = $order->phone;
		$orders_table[] = $o;
	}
	echo $this->table->generate($orders_table);
?>
<script>
	$(function(){
		$('body').css("background-image","none");
		window.print();
	});
</script>