$(document).ready(function(){
	$("#show_login").click(function(){
		showPopUp();
	});
	
	$("#closeButton").click(function(){
		closePopUp();
	});
});

function showPopUp(){
	$("#loginForm").fadeIn();
	$("#loginForm").css({"visibility":"visible","display":"block"});
}

function closePopUp(){
	$("#loginForm").fadeOut();
	$("#loginForm").css({"visibility":"hidden","display":"none"});
}
