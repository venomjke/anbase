<?php
	$orders_table = array();
	$orders_table_head = array('№','Тип объекта','Метро','Район','Описание');
	$orders_table_tmpl = array('table_open'=>'<table border="0" class="print_table" cellspacing="0" cellpadding="2" style="width:95%">');
	$this->table->set_heading($orders_table_head);
	$this->table->set_template($orders_table_tmpl);

	foreach($orders as $order){
		$o = array();

		$building = '';
		$building .= '<b>Категория:</b>'.$this->m_order->get_category_name($order->category).'<br/>';
		$building .= '<b>Тип сделки:</b>'.$this->m_order->get_dealtype_name($order->deal_type).'<br/>';

		$description = '';
		$description .= $order->description.'<br/>';

		$description .= '<b>Цена:</b>'.$order->price.'<br/>';
		$description .= $order->phone;

		$regions = '';
		$i = 0;
		foreach($order->regions as $region){
			if( $i == 0 ){
				$regions .= $region->name;
				++$i;
				continue;
			}
			$regions .= ','.$region->name.'<br/>';
		}

		$metros = '';
		$i=0;
		foreach($order->metros as $v){
			foreach($v as $metro){
				if( $i == 0 ){
					$metros .= $metro;
					++$i;
					continue;
				} 
				$metros .= ', '.$metro;
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