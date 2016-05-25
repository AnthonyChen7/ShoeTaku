/**
 * Attach submit handler to Login button
 */
$(document).ready(function() {

$("#form").submit(function(event){
	
	/**
	 * Stop from submitting normally
	 */
	event.preventDefault();
	
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
				document.getElementById('error').innerHTML = "";
				window.location="/partials/main-page.html";
			}else{
				document.getElementById('error').innerHTML = "Incorrect email/password!";
			}
		},
		error: function(data) {
			alert("error");
			console.log(data);
		}
	});

});

});