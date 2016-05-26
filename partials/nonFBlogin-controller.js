/**
 * Attach submit handler to Login button
 */
$(document).ready(function() {

$("#form").submit(function(event){
	
	//clear any existing error messages first
	document.getElementById('error_email').innerHTML = "";
	document.getElementById('error_password').innerHTML = "";
	
	/**
	 * Stop from submitting normally
	 */
	event.preventDefault();
	
	/**
	 * Handle form validation first.
	 * If it contains valid field, we make AJAX call
	 */
	
	if($("#email").val() === "" || $("#password").val() === "" || !validateEmail() || hasWhiteSpace( $("#password").val()) || hasWhiteSpace($("#email").val())){
		/**
		 * Put inside here so we can display appropriate message
		 * without making AJAX call
		 */
		if($("#email").val() === "" || !validateEmail() || hasWhiteSpace($("#email").val()) )
		document.getElementById('error_email').innerHTML = "<p>Please provide a valid email!</p>";

		if($("#password").val() === "" || hasWhiteSpace( $("#password").val()))
		document.getElementById('error_password').innerHTML += "<p>Please provide a valid password!</p>";
	}
	else{
	/**
	 * Retrieve action attribute;URL to send it to
	 */
	var $form = $(this),
	url = $form.attr('action');
	
	/**
	 * Send data using AJAX call
	 */
	var data = {'email': $("#email").val(), 'password': $("#password").val()};
	
	$.ajax({
		type: 'POST',
		url: url, 
		data: data, 
		dataType: 'json',
		timeout: 3000,
		success: function(data) {
			//alert("success");
			console.log(data);
			
			if(data.success === true){
				document.getElementById('error_email').innerHTML = "";
				document.getElementById('error_password').innerHTML = "";
				window.location="/partials/main-page.html";
			}else{
				document.getElementById('error_email').innerHTML = "Incorrect email/password!";
			}
		},
		error: function(data) {
			alert("error");
			console.log(data);
		}
	});
	
	}
});

});

/**
 * Returns true if valid email entered. False otherwise.
 */
function validateEmail(){
	var email = $("#email").val();
	
	atpos = email.indexOf("@");
	dotpos = email.lastIndexOf(".");
	
	if(atpos < 1 || (dotpos - atpos <2 )){
		return false;
	}
	
	return true;
}

/**
 * Returns true if there are white spaces
 */
function hasWhiteSpace(s) {
  return s.indexOf(' ') >= 0;
}