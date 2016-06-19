console.log(localStorage.getItem("token"));

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
	 * Handle form validation first.
	 * If it contains valid fields, we make AJAX call
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
		timeout: 3000,
		success: function(data) {
						
			if(data != ""){
				console.log(data);
				document.getElementById('error_email').innerHTML = "";
				document.getElementById('error_password').innerHTML = "";
				
				
				localStorage.setItem("token",data);
				
				window.location="/partials/main-page.html";
				
			}else{
				document.getElementById('error_email').innerHTML = "Incorrect email/password!";
				console.log(data);
				
				localStorage.setItem("token",null);
			}
		},
		error: function(data) {
			alert("error");
			console.log(data);
			localStorage.setItem("token",null);
			
		}
	});
	
	 }

}
);


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
	'city':$('#city').val(),
	'country': getKeyByValue($('#country').val()) //store as country code in db
	};
		
		$.ajax({
		type: 'POST',
		url: url, 
		data: data, 
		timeout: 3000,
		success: function(data) {			
			if(data != "error"){
			console.log(data);
			
			localStorage.setItem("token",data);
				
		//clear any existing error messages first
		document.getElementById('error_emailRegister').innerHTML = "";
		document.getElementById('error_passwordRegister').innerHTML = "";
		document.getElementById('error_confirmPassword').innerHTML = "";
		document.getElementById('error_firstName').innerHTML = "";
		document.getElementById('error_lastName').innerHTML = "";
		document.getElementById('error_city').innerHTML = "";
		document.getElementById('error_country').innerHTML = "";
		//window.location="/partials/main-page.html";
			}else{
				localStorage.setItem("token",null);
				document.getElementById('error_emailRegister').innerHTML = $("#emailRegister").val() + " already exists!";
			}
		},
		error: function(data) {
			alert("error");
			
			localStorage.setItem("token",null);
			
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
		
		if($("#city").val() === "" || hasWhiteSpace( $("#city").val())){
			document.getElementById('error_city').innerHTML += "<p>Please provide a valid city!</p>";
			isValid= false;
		}
		
		if($("#country").val() === "" || hasWhiteSpace( $("#country").val())|| getKeyByValue($("#country").val() ) === undefined ){
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

function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
	  
$("#modal_trigger").leanModal({
	top: 100,
	overlay: 0.6,
	closeButton: ".modal_close" ,
});


$(function(){
	//Call Login Form
	$('#login_form').click(function(){
		$(".social_login").hide();
		$(".user_login").show();
		$(".forgot_password_div").hide();
		return false;
	});
	
	//Call Register Form
	$('#register_form').click(function(){
		$(".social_login").hide();
		$(".user_register").show();
		$(".header_title").text('Register');
		$(".forgot_password_div").hide();
		return false;
	});
	
	//Go back to social forms
	$('.back_btn').click(function(){
		$(".social_login").show();
		$(".user_register").hide();
		$(".user_login").hide();
		$(".forgot_password_div").hide();
		$(".header_title").text('Login');
		return false;
	});
	
	$('.forgot_password').click(function(){
		$(".forgot_password_div").show();
		$(".social_login").hide();
		$(".user_register").hide();
		$(".user_login").hide();
		$(".header_title").text('Change Password');
		return false;
	});
});

/**
 * Temporary function to get logout working
 */
function logout(){
	window.location="/";
	localStorage.setItem("token",null);
}