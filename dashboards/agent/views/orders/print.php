<?php
	$orders_table = array();
	$orders_table_head = array('№','Сделка','Район','Метро','Описание','Цена');
	$orders_table_tmpl = array('table_open'=>'<table border="0" class="print_table" cellspacing="0" cellpadding="2" style="width:95%">');
	$this->table->set_heading($orders_table_head);
	$this->table->set_template($orders_table_tmpl);

	foreach($orders as $order){
		$o = array();

		/*
		* Сделка
		*/
		$building = '';
		$building .= '<b>Категория:</b><br/>'.$order->category.'<br/>';
		$building .= '<b>Тип сделки:</b><br/>'.$order->deal_type.'<br/>';

		/*
		* Район
		*/
		$regions = '';
		foreach($order->regions as $region){
			$regions .= $region->name.'<br/>';
		}

		/*
		* Метро
		*/
		$metros = '';
		if($order->any_metro) $metros.='Любое<br/>';
		foreach($order->metros as $v){
			foreach($v as $metro){
				$metros = $metro.'<br/>';
			}
		}
		/*
		* Описание
		*/
		$description = '';
		$description .= $order->description.'<br/>';

		$description .= '<b>Телефон:</b>'.$order->phone;

		$o[] = $order->number;
		$o[] = $building;
		$o[] = $regions;
		$o[] = $metros;
		$o[] = $description;
		$o[] = $order->price;
		$orders_table[] = $o;
	}

	echo $this->table->generate($orders_table);
?>
<script>
	$(function(){
		$('body').css("background-image","none");
		//window.print();
	});
</script>