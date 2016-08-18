$(document).ready(function(){
	$('#account_info').click(function(){

		//re-direct to account-settings page
		controller.setupAjax();
		controller.sendRequest("account-settings");

	});
	
	$('#message').click(function(){
		alert('To be implemented!');
	});
});