/*
*
* логина обработки админ части на стороне клиента
*
*/
var agent = {

	/*
	*
	*	Базовый URL для выполнения запросов
	*
	*/
	baseUrl:'',

	init:function(params){
	
		/*
		*	
		*	Установка базового URL
		*/
		agent.baseUrl = params.baseUrl || '';
	},

	profile:{
		init:function(){
			agent.profile.hash_form = {};
			agent.profile.load_form('personal');
			agent.profile.load_form('system');
		},
		/*
		* Загрузка формы
		*/
		load_form:function(form){
			if(!form || typeof form != "string")
				return false;			
			agent.profile.hash_form[form] = {};

			$('#'+form+' input').each(function(){
				if($(this).attr('type') != 'text' && $(this).attr('type') != 'password')
					return false;
				/*
				* Т.к все элементы формы у нас либо просто text,либо password, 
				* то считываем name и value и сохраняем их в hash_form для последующей обработки
				*/
				var name  = $(this).attr('name');
				var value = $(this).attr('value');
				agent.profile.hash_form[form][name] = value;
			})
		},
		/*
		* Сохранение формы
		*/
		save_form:function(form){
			if( !form || typeof form != "string")
				return false;
			var data = {};
			var cnt  = 0; // кол-во полей для изменения.
			$('#'+form+' input').each(function(){
				if($(this).attr('type') != 'text' && $(this).attr('type') != 'password')
					return false;

				var name = $(this).attr('name');
				var value = $(this).attr('value');

				if(agent.profile.hash_form[form][name] != value){
					data[name] = value;
					++cnt;

				}
			});

			/*
			* если сохранять нечего, то выходим
			*/
			if(cnt == 0){
				return false;
			}

			/*
			* Отправляем форму.
			*/
			$.ajax({
				url:agent.baseUrl+'/edit/?sct='+form,
				type:'POST',
				dataType:'json',
				data:data,
				beforeSend:function(){
					common.showAjaxIndicator();
				},
				complete:function(){
					common.hideAjaxIndicator();
				},
				success:function(response){
					if(response.code && response.data){
						switch(response.code){
							case 'success_edit_profile':
								common.showSuccessMsg(response.data);
								for(var i in data){
									agent.profile.hash_form[form][i] = data[i];
								}
							break;
							case 'error_edit_profile':
								if(response.data.errors && response.data.errorType){
									if(response.data.errorType == 'validation'){
										for(var i in response.data.errors){
											$('#profile').find('input[name="'+i+'"]').parent().prepend('<div class="error">'+response.data.errors[i]+'</div>');
										}
										setTimeout(function(){
											$('#profile .error').remove();
										},5000);
									}else{
										common.showErrorMsg(response.data.errors[0]);
									}
								}
							break;
						}
					}
				}
			});
		}

	},
	/*
	* JS модуль для реализации интерфейса заявок
	*/
	orders:{
		init:function(options){
		},
		print_orders:function(grid,model){
			var ids = [];
			var SelectedRows = grid.getSelectedRows();

			for(var i in SelectedRows){
				if(grid.getDataItem(SelectedRows[i]) && grid.getDataItem(SelectedRows[i]).id){
					ids.push(grid.getDataItem(SelectedRows[i]).id);
				}
			}
			if(ids.length){
				model.printOrders(ids);
			}
		}
	},
	user:{
		init:function(options){
			
		}
	}
}