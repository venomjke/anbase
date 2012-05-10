Вы зарегистрированы. Через <span id="seconds" style="font-weight:bold">5</span> секунд вы будете перенаправлены на главную <?php echo anchor("","страницу"); ?>.
<script type="text/javascript">

function startTimer(timer){
	setInterval(function(){
		var time = $(timer).text();
		if(--time == 0){
			window.location.replace('/');
		}else{
			$(timer).text(time);
		}
	},1000);
}

$(function(){
	startTimer('#seconds');
});
</script>