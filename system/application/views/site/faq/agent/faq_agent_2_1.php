<?php load_partial_template($template,'faq_navigation'); ?>

<span align="center" style="color:#06C;"><h3>2. Работа с заявками</h3></span>
<span align="center" style="color:#06C;"><h3>2.1 Описание таблиц.</h3></span>

<p align="center"><img src="<?php echo base_url(); ?>themes/start/images/faq/agent/agent2.jpg" width="100%"><p> 
<p align="center">рис.2. Таблица с заявками</p>
<p style="text-align:justify">После входа в систему Вы попадаете на страницу с таблицей заявок.</p>
<p><b><u>Таблица «Мои заявки».</u></b></p>
<p style="text-align:justify">По умолчанию открыта вкладка <b>«Мои заявки»</b> с пока еще пустой таблицей, здесь будут размещены Ваши рабочие заявки. </p>
<p><b><u>Таблица «Свободные заявки».</u></b></p>
<p style="text-align:justify">В таблице <b>«Свободные заявки»</b> можно увидеть все заявки Вашего агентства, которые еще не закреплены за агентами, соответственно вы можете запросить у диспетчера закрепление этих заявок за Вами. После этого выбранные вами заявки появятся в таблице <b>«Мои заявки»</b>. В таблице <b>«Свободные заявки»</b> не доступно для просмотра поле <b>«Телефон клиента»</b>, это поле открыто только для Ваших заявок.</p>
<p><b><u>Таблица «Завершенные заявки».</u></b></p>
<p style="text-align:justify">В данной таблице размещены ваши заявки, работа с которыми завершена.</p>
<p><span><?php echo anchor("faq/agent/?page=faq_agent_1","<- Шаг назад"); ?></span> <span style="float:right"><?php echo anchor("faq/agent/?page=faq_agent_2_2","Шаг вперед ->"); ?></span></p>