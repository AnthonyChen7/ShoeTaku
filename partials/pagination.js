
var renderTemplate = (function(){

	var template = '<li><li>'

})


$(document).ready(function(){

	$("#test").click(function(){
		var url = "/controllers/testing/tests";
		
		var param = JSON.stringify({test:test});
		$.ajax({
			url: url,
			data: param,
			dataType: "json",
			success: function(data, textStatus, jqXHR){
				console.log(data);
			},
			error: function(jqXHR, textStatus, error){
				debug.log(textStatus + ":" + error);
			},
			complete: function(jqXHR, textStatus){
				debug.log("ajax call complete");
			}
		})
	});
});