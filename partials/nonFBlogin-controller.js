/**
 * Attach submit handler to Login button
 */
$(document).ready(function() {
	
	
	
		/**
 * Handles the login dialog box popping p
 * when the login buttin is pressed
 */
	$("#show_login").click(function(){
		showPopUp();
	});
	
	$("#closeButton").click(function(){
		closePopUp();
	});
	
	$("#closeButtonRegister").click(function(){
		closePopUpRegister();
	});
		
$("#form").submit(function(event){
	
	/**
	 * Stop from submitting normally
	 */
	event.preventDefault();
	
	/**
	 * Handle form validation first.
	 * If it contains valid field, we make AJAX call
	 */
	
	/**
	 * Retrieve action attribute;URL to send it to
	 */
	var $form = $(this),
	url = $form.attr('action');
	
	/**
	 * Send data using AJAX call
	 */
	var data = {'email': $("#email").val(), 'password': $("#password").val()};
	
	 if(validateForm(true) === true){
	
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


$("#form2").submit(function(event){
event.preventDefault();
	var $form = $(this),
	url = $form.attr('action');

	if(validateForm(false) === true){
		
		console.log("form is valid");
		/**
	 * Send data using AJAX call
	 */
	var data = {
	'email': $("#emailRegister").val(), 
	'password': $("#passwordRegister").val(),
	'firstName':$('#firstName').val(),
	'lastName':$('#lastName').val(),
	'age':$('#age').val(),
	'city':$('#city').val(),
	'country': getKeyByValue($('#country').val()) //store as country code in db
	};
		
		$.ajax({
		type: 'POST',
		url: url, 
		data: data, 
		dataType: 'json',
		timeout: 3000,
		success: function(data) {
			alert("success");
			console.log(data);
			
			if(data.success === true){
				
				//clear any existing error messages first
		document.getElementById('error_emailRegister').innerHTML = "";
		document.getElementById('error_passwordRegister').innerHTML = "";
		document.getElementById('error_confirmPassword').innerHTML = "";
		document.getElementById('error_firstName').innerHTML = "";
		document.getElementById('error_lastName').innerHTML = "";
		document.getElementById('error_age').innerHTML = "";
		document.getElementById('error_city').innerHTML = "";
		document.getElementById('error_country').innerHTML = "";
				// window.location="/partials/main-page.html";
			}else{
				document.getElementById('error_emailRegister').innerHTML = data.errorMsg;
			}
		},
		error: function(data) {
			alert("error");
			//console.log(data);
		}
	});	
		
	}else{
		console.log("form is invalid");
	}
});

});

/**
 * Validates form
 * True if form is valid
 */
function validateForm(isLoginForm){
		
		var isValid = true;
		
		if(isLoginForm === false){
			
							//clear any existing error messages first
		document.getElementById('error_emailRegister').innerHTML = "";
		document.getElementById('error_passwordRegister').innerHTML = "";
		document.getElementById('error_confirmPassword').innerHTML = "";
		document.getElementById('error_firstName').innerHTML = "";
		document.getElementById('error_lastName').innerHTML = "";
		document.getElementById('error_age').innerHTML = "";
		document.getElementById('error_city').innerHTML = "";
		document.getElementById('error_country').innerHTML = "";
		
		
		if($("#emailRegister").val() === "" || !validateEmail($("#emailRegister").val()) || hasWhiteSpace($("#emailRegister").val()) ){
			document.getElementById('error_emailRegister').innerHTML = "<p>Please provide a valid email!</p>";
			isValid = false;
		}
		
		if($("#passwordRegister").val() === "" || hasWhiteSpace( $("#passwordRegister").val())){
			document.getElementById('error_passwordRegister').innerHTML += "<p>Please provide a valid password!</p>";
			isValid= false;
		}
		
		// if($("#passwordRegister").val().length <= 6){
		// 	document.getElementById('error_passwordRegister').innerHTML += "<p>Length of password must be > 6!</p>";
		// 	isValid= false;
		// }
			
		if($("#passwordRegister").val() !== $("#confirmPassword").val() ){
				document.getElementById('error_confirmPassword').innerHTML += "<p>Passwords don't match!</p>";
		isValid= false;
		}
			
			if($("#firstName").val() === "" || hasWhiteSpace( $("#firstName").val())){
			document.getElementById('error_firstName').innerHTML += "<p>Please provide a valid first name!</p>";
			isValid= false;
		}
		
		if($("#lastName").val() === "" || hasWhiteSpace( $("#lastName").val())){
			document.getElementById('error_lastName').innerHTML += "<p>Please provide a valid last name!</p>";
			isValid= false;
		}
		
		if($("#age").val() === "" || $("#age").val() <= 0 ){
			document.getElementById('error_age').innerHTML += "<p>Age must be greater than 0!</p>";
			isValid= false;
		}
		
		if($("#city").val() === "" || hasWhiteSpace( $("#city").val())){
			document.getElementById('error_city').innerHTML += "<p>Please provide a valid city!</p>";
			isValid= false;
		}
		
		if($("#country").val() === "" || hasWhiteSpace( $("#country").val())){
			document.getElementById('error_country').innerHTML += "<p>Please provide a valid country!</p>";
			isValid= false;
		}
	}else{
		
				//clear any existing error messages first
		document.getElementById('error_email').innerHTML = "";
		document.getElementById('error_password').innerHTML = "";
		
				if($("#email").val() === "" || !validateEmail($("#email").val()) || hasWhiteSpace($("#email").val()) ){
			document.getElementById('error_email').innerHTML = "<p>Please provide a valid email!</p>";
			isValid = false;
		}
		

		if($("#password").val() === "" || hasWhiteSpace( $("#password").val())){
			document.getElementById('error_password').innerHTML += "<p>Please provide a valid password!</p>";
			isValid= false;
		}
		
	}

	return isValid;
}

/**
 * Returns true if valid email entered. False otherwise.
 */
function validateEmail(input){
	
	atpos = input.indexOf("@");
	dotpos = input.lastIndexOf(".");
	
	if(atpos < 1 || (dotpos - atpos <2 )){
		return false;
	}
	
	return true;
}

/**
 * Returns true if there are white spaces
 */
function hasWhiteSpace(s) {
  
  if (!/\S/.test(s)) {
    // string is not empty and not just whitespace
	return true;
}

return false;

}

function someFunction(){
	//alert("yolo");
	closePopUp();
	showPopUpRegister();
}

function showPopUp(){
		//clear any existing error messages first
		document.getElementById('error_email').innerHTML = "";
		document.getElementById('error_password').innerHTML = "";
	$("#loginForm").fadeIn();
	$("#loginForm").css({"visibility":"visible","display":"block"});
}

function closePopUp(){
	$("#loginForm").fadeOut();
	$("#loginForm").css({"visibility":"hidden","display":"none"});
}

function showPopUpRegister(){
								//clear any existing error messages first
		document.getElementById('error_emailRegister').innerHTML = "";
		document.getElementById('error_passwordRegister').innerHTML = "";
		document.getElementById('error_confirmPassword').innerHTML = "";
		document.getElementById('error_firstName').innerHTML = "";
		document.getElementById('error_lastName').innerHTML = "";
		document.getElementById('error_age').innerHTML = "";
		document.getElementById('error_city').innerHTML = "";
		document.getElementById('error_country').innerHTML = "";
	$("#registerForm").fadeIn();
	$("#registerForm").css({"visibility":"visible","display":"block"});
}

function closePopUpRegister(){
	$("#registerForm").fadeOut();
	$("#registerForm").css({"visibility":"hidden","display":"none"});
}

function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }