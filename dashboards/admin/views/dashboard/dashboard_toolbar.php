<div id="dashboard_toolbar">

    <?php if($current != 'off'): ?>
    <span class="plusplus" id="add_order" style="margin-top:5px; margin-right:16px"><a href="#" onclick="return false;">Добавить</a></span>
    <span class="plusplus minus" id="finish_order" style="margin-top:5px;  margin-right:16px"><a href="#" onclick="return false;">Завершить</a></span>
    <?php else: ?>
    <span class="plusplus" id="restore_order" style="margin-top:5px;  margin-right:16px"><a href="#" onclick="return false;">Возобновить</a></span>
    <?php endif; ?>
    <span class="plusplus minus" id="del_order" style="margin-top:5px;  margin-right:16px"><a href="#" onclick="return false;">Удалить</a></span>
    <span class="plusplus" id="print_order" style="margin-top:5px;  margin-right:16px;float:right"> <a href="#" onclick="return false;">Распечатать</a></span>

</div>