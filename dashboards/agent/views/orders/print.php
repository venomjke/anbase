<?php
	$orders_table = array();
	$orders_table_head = array('№','Тип объекта','Метро','Район','Описание');
	$orders_table_tmpl = array('table_open'=>'<table border="0" class="print_table" cellspacing="0" cellpadding="2" style="width:95%">');
	$this->table->set_heading($orders_table_head);
	$this->table->set_template($orders_table_tmpl);

	foreach($orders as $order){
		$o = array();

		$building = '';
		$building .= '<b>Категория:</b>'.$order->category.'<br/>';
		$building .= '<b>Тип сделки:</b>'.$order->deal_type.'<br/>';

		$description = '';
		$description .= $order->description.'<br/>';

		$description .= '<b>Цена:</b>'.$order->price.'<br/>';
		$description .= $order->phone;

		$regions = '';
		foreach($order->regions as $region){
			$regions .= $region->name.'<br/>';
		}
		/*
		$metros = '';
		foreach($order->metros as $k => $v){
			$metros .= '<b> Линия '.$k.'</b><br/>';
			foreach($v as $metro){
				$metros .= $metro.'<br/>';
			}
		};
		*/
		$metros = '';
		foreach($order->metros as $v){
			foreach($v as $metro){
				$metros .= $metro.' ';
			}
		}

		$o[] = $order->number;
		$o[] = $building;
		$o[] = $metros;
		$o[] = $regions;
		$o[] = $description;
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