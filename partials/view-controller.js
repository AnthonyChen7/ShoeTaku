var debug = (function(){
	var production = false;
	return {
		alert: function(object) {
			if (!production)
				alert(object);
		},
		log: function(object){
			if (!production)
				console.log(object);
		}
	}
})();

var constants = (function(){
	return {
		DEFAULT: "DEFAULT",
		NULL: "NULL"
	}
})();

var controller= (function(){
	var controllerPhp = "/partials/view-controller.php";
	var currentPage = "";
	var pages= ["error","sell","wanted","account","main-page",""];
	var $partialView = $("#partial_view");

	function determineCurrentPage(){
		if (currentPage == "" || currentPage == null)
			return constants.DEFAULT;
		else
			return currentPage;
	}

	function initiateRequestProcess(page){
		var index = pages.indexOf(page);
		requestPage(pages[index]);
	}

	function requestPage(page){
		var param = JSON.stringify({page:page});
		$.ajax({
			url: controllerPhp,
			data: param,
			success: function(data, textStatus, jqXHR){
				var $html = $.parseHTML(data, keepScripts = true);
				$partialView.empty();
				$partialView.append($html);
			},
			error: function(jqXHR, textStatus, error){
				debug.log(textStatus + ":" + error);
			},
			complete: function(jqXHR, textStatus){
				debug.log("ajax call complete");
			}
		})
	}

	return {
		getPages: function(){
			return pages;
		},
		sendRequest: function(page){
			initiateRequestProcess(page);
		},
		setupAjax: function(){
			$.ajaxSetup({
				contentType:"application/json",
				async: true,
				cache: false,
				crossDomain: false,
				dataType: "html",
				method: "POST",
				timeout: 10000
			});
			debug.log("setupAjax complete");
		}
	}
})();

$(document).ready(function(){
	controller.setupAjax();
	controller.sendRequest("sell");
	$("li .page").click(function(){
		controller.sendRequest($(this).data('page'));
		$(".nav").find(".active").removeClass("active");
		$(this).parent().addClass("active");
	});	
});