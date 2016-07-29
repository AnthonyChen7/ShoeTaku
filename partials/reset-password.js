$(document).ready(function(){
	clearErrorDivs.resetPassword();
	
	$('#reset_password_form').submit(function(event){
		event.preventDefault();
		clearErrorDivs.resetPassword();
		console.log(validate.resetPassword());
		if(validate.resetPassword() === true){
			console.log("form is valid");
		}
		
	});
});