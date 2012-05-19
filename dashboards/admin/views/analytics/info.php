<?php load_partial_template($template,'dashboard_head'); ?>
<div class="conteiner">
	<?php load_partial_template($template,'dashboard_user'); ?>
	<?php load_partial_template($template,'dashboard_menu') ?>
	<div class="content">
    	<div class="title_analytics">Аналитика</div> 
			<div class="tablica2" id="profile">
				<div style="width:1024px;height:250px;margin:0px auto;">
					<div id="chart_div" style="width:400px;height:250px;float:left;"></div>
					<div id="chart_div2" style="margin-left:400px;width:400;height:400"></div>
				</div>
			</div>
		</div>
  		<div class="podval">© <a href="111">copyright 2012 Flyweb inc.</a>	</div>
  	</div>
</div>
<script type="text/javascript">
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Заявки');
        data.addColumn('number', 'Число');
        data.addRows([
          ['Свободные заявки', <?php echo $this->org_statistic->count_all_free_orders();?>],
          ['Звершенные заявки',<?php echo $this->org_statistic->count_all_finish_orders(); ?>],
          ['Занятые', <?php echo $this->org_statistic->count_all_delegate_orders(); ?>]
        ]);

        // Set chart options
        var options = {'title':'Расположение заявок',
                       'width':400,
                       'height':250,
                       'chartArea':{left:0,top:25,width:400,height:200},
                       'backgroundColor':'#e6eff6'
                      };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);

        var date_parse = <?php echo json_encode($this->org_statistic->count_orders_org_group_by_date());?>;
        for(var i in date_parse){
          var arr = date_parse[i];
          date_parse[i][0] = new Date(arr[0]);
          date_parse[i][1] = parseInt(arr[1]);
        }

        var data_line_chart = new google.visualization.DataTable();
        data_line_chart.addColumn('date','Дата');
        data_line_chart.addColumn('number','Кол-во');
        data_line_chart.addRows(date_parse);
        
        var options = {
          title: 'Поступление заявок',
          width:600,
          backgroundColor:'#e6eff6',
          height:250,
          hAxis:{
            format:'d MMM y'
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
        chart.draw(data_line_chart, options);
      }
</script>