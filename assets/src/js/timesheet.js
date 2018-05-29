console.log("##Timesheet##");
$(".day").on("click",function(e){
	console.log(e);
	$(this).toggleClass("selected");
});