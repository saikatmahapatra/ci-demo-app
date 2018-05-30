console.log("##Timesheet##");
var selectedDate = [];
$(function(){
	/*$.each('.day',function(){
		if($(this).text().trim().length <= 0){
			$(this).css('cursor','default');
		}
	});*/
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