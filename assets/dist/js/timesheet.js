console.log("Timesheet Loaded...");
var selectedDate = [];

$(function(){	
	var selected_date = $('input[name="selected_date"]').val();
	if(selected_date){
		var selected_date_array = selected_date.split(',');
		selectedDate = selected_date_array;
		//console.log(selected_date_array,selected_date_array.length);
		if(selected_date_array.length>0){
			$.each(selected_date_array,function(index,clickedSelectedDay){
				$(".day").each(function(){
					var calDay= $(this).text();
					//console.log(calDay);
					if(calDay == clickedSelectedDay){
						$(this).addClass("selected");
					}
				});
			});
		}
	}
	
	//Load Timesheet Data On Page Load
	get_timesheet_stat();
	
	//Render Data Table
	renderDataTable();
});

$(".day").on("click",function(e){
	console.log(e);
	var day = $(this).text();
	if(day.trim().length>0){
		if($(this).hasClass("selected")){
			$(this).removeClass("selected");		
			selectedDate.splice( $.inArray(day, selectedDate), 1 );
			
		}else{
			$(this).addClass("selected");
			selectedDate.push(day);
		}
		//$(this).toggleClass("selected");
	}
	
	//$("#timesheetModal").modal("show");
	console.log(selectedDate);
	$('input[name="selected_date"]').val(selectedDate.join());
});


function get_timesheet_stat(){
	var XHR = new Ajax();
	XHR.type ='POST';
	XHR.url = SITE_URL+ROUTER_DIRECTORY+ROUTER_CLASS+'/timesheet_stats';
	XHR.data = {via: 'ajax'};
	var promise = XHR.init();		
	promise.done(function(response){
		//console.log(response.data.data_rows);
		$.each(response.data.data_rows,function(i,obj){
			$(".day").each(function(){
				var calDay= $(this).text();
				//console.log(calDay);
				obj.timesheet_day = Number(obj.timesheet_day).toString();				
				if(calDay == obj.timesheet_day){
					$(this).addClass("filled");
				}
			});
		});
	});
	promise.fail(function(){
		alert("Failed");
	});
	promise.always(function(){		
	});
}

function renderDataTable(){
	this.table = $('#timesheet-datatable').DataTable({
		/*dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
		iDisplayLength: 10,*/
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		order: [], //Initial no order.
		// Load data for the table's content from an Ajax source
		ajax: {
			url: SITE_URL+ROUTER_DIRECTORY+ROUTER_CLASS+'/render_datatable',
		},
		//Set column definition initialisation properties.
		columnDefs: [
			{
				targets: [-1], //last column
				orderable: false, //set not orderable
			},
		],
	});
}