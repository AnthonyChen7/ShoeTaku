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
	var pages= ["home","dashboard","sell","wanted","account","reset-password","sellPost",'account-settings','account'];
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
		// var param = JSON.stringify({page:page});
		var param = {page:page};
		$.ajax({
			url: controllerPhp,
			data: param,
			success: function(data, textStatus, jqXHR){
				var $html = $.parseHTML(data, keepScripts = true);

				currentPage = page;

				$partialView.empty();
				$partialView.append($html);
			},
			error: function(jqXHR, textStatus, error){
				debug.log(textStatus + ":" + error);
			},
			complete: function(jqXHR, textStatus){
				debug.log("ajax call complete");
				if(page == "sell"){
					$("div > div .postTitle").click(function(){
						controller.setupAjax();
						controller.sendRequest("sellPost");
					});
				}
			}
		})
	}

	return {
		
		getCurrentPage: function(){
			return determineCurrentPage();	
		},
		
		getPages: function(){
			return pages;
		},
		sendRequest: function(page){
			initiateRequestProcess(page);
		},
		setupAjax: function(){
			$.ajaxSetup({
				//contentType:"application/json",
				async: true,
				cache: false,
				crossDomain: false,
				//dataType: "html",
				method: "POST",
				timeout: 10000
			});
			debug.log("setupAjax complete");
		}
	}
})();

$(document).ready(function(){

	if($(this).data('page')==undefined){
		controller.setupAjax();
		controller.sendRequest("dashboard");
	}

	$("#mainDashBoard").click(function(){
		controller.setupAjax();
		controller.sendRequest("dashboard");
		$(".nav").find(".active").removeClass("active");
	});

	$("#sellPage").click(function(){
		controller.setupAjax();
		controller.sendRequest($(this).data('page'));
		$(".nav").find(".active").removeClass("active");
		$(this).parent().addClass("active");

		console.log("render");
	});

	$("#wantedPage").click(function(){
		controller.setupAjax();
		controller.sendRequest($(this).data('page'));
		$(".nav").find(".active").removeClass("active");
		$(this).parent().addClass("active");
	});

	$("#accountPage").click(function(){
		controller.setupAjax();
		controller.sendRequest($(this).data('page'));
		$(".nav").find(".active").removeClass("active");
		$(this).parent().addClass("active");
	});

});