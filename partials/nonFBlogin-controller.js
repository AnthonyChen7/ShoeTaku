console.log(localStorage.getItem("token"));
		
		if(localStorage.getItem("token") != null  && location.pathname==='/' ){
			$.ajax({
				type:'POST',
				url:"/controllers/authentication",
				dataType: "json",
				data: {"token":localStorage.getItem("token"), "action":"redirect"},
				success : function(data){
					window.location.href="/partials/dashboard.html";
				},
				error: function(data){
				}
			});
		}

/**
 * Init Handler for login and register button
 * Redirect user if token hasn't expired
 */
$(document).ready(function() {
		
		nonFBController.loginButtonHandler();
		nonFBController.registerButtonHandler();
		nonFBController.forgotPassword();

});

var validate = (function(){
	
	return {
		
		hasWhiteSpace : function(str){
			 if (!/\S/.test(str)) {
				return true;
			}
			return false;
		},
		
		login: function(){
			var isValid = true;
			
			clearErrorDivs.login();
			
			if($("#email").val() === "" || validate.hasWhiteSpace($("#email").val()) ){
				document.getElementById('error_email').innerHTML = "<p>Please provide a valid email!</p>";
				isValid = false;
			}
			
	
			if($("#password").val() === "" || validate.hasWhiteSpace( $("#password").val())){
				document.getElementById('error_password').innerHTML += "<p>Please provide a valid password!</p>";
				isValid= false;
			}
			
			return isValid;
		},
		
		register: function(){
			var isValid = true;
			
			//clear any existing error messages first
			clearErrorDivs.register();

			if($("#emailRegister").val() === "" || validate.hasWhiteSpace($("#emailRegister").val()) ){
				document.getElementById('error_emailRegister').innerHTML = "<p>Please provide a valid email!</p>";
				isValid = false;
			}
			
			if($("#passwordRegister").val() === "" || validate.hasWhiteSpace( $("#passwordRegister").val())){
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
				
			if($("#firstName").val() === "" || validate.hasWhiteSpace( $("#firstName").val())){
				document.getElementById('error_firstName').innerHTML += "<p>Please provide a valid first name!</p>";
				isValid= false;
			}
			
			if($("#lastName").val() === "" || validate.hasWhiteSpace( $("#lastName").val())){
				document.getElementById('error_lastName').innerHTML += "<p>Please provide a valid last name!</p>";
				isValid= false;
			}
			
			if($("#city").val() === "" || validate.hasWhiteSpace( $("#city").val())){
				document.getElementById('error_city').innerHTML += "<p>Please provide a valid city!</p>";
				isValid= false;
			}
			
			if($("#country").val() === "" || validate.hasWhiteSpace( $("#country").val())|| getKeyByValue($("#country").val() ) === undefined ){
				document.getElementById('error_country').innerHTML += "<p>Please provide a valid country!</p>";
				isValid= false;
			}
			
			return isValid;
		},
		
		password : function(){
			    var isValid = true;
    
        if($("#old_password").val() === "" || validate.hasWhiteSpace( $("#old_password").val())){
			document.getElementById('error_old_password').innerHTML = "<p>Please provide a valid old password!</p>";
			isValid= false;
		}
		
		if($("#new_password").val() === "" || validate.hasWhiteSpace( $("#new_password").val())){
			document.getElementById('error_new_password').innerHTML = "<p>Please provide a valid new password!</p>";
			isValid= false;
		}
		
		if($("#confirm_new_password").val() != $("#new_password").val() ){
			document.getElementById('error_new_password').innerHTML = "<p>New password do not match!</p>";
			isValid= false;
		}
    
    	return isValid;
		},
		
		account : function(){
		var isValid = true;
    
        if($("#firstName_account").val() === "" || validate.hasWhiteSpace( $("#firstName_account").val())){
			document.getElementById('firstName_account_error').innerHTML = "<p>Please provide a valid first name!</p>";
			isValid= false;
		}
		
		if($("#lastName_account").val() === "" || validate.hasWhiteSpace( $("#lastName_account").val())){
			document.getElementById('lastName_account_error').innerHTML = "<p>Please provide a valid last name!</p>";
			isValid= false;
		}
		
		if($("#city_account").val() === "" || validate.hasWhiteSpace( $("#city_account").val())){
			document.getElementById('city_account_error').innerHTML = "<p>Please provide a valid city!</p>";
			isValid= false;
		}
		
		if($("#country_account").val() === "" || validate.hasWhiteSpace( $("#country_account").val())|| getKeyByValue($("#country_account").val() ) === undefined ){
			document.getElementById('country_account_error').innerHTML = "<p>Please provide a valid country!</p>";
			isValid= false;
		}
    
    
    	return isValid;
		},
		
		resetPassword : function(){
			var isValid = true;
			
			if($("#reset_new_password").val() === "" || validate.hasWhiteSpace( $("#reset_new_password").val())){
			document.getElementById('error_reset_new_password').innerHTML = "<p>Please provide a valid new password!</p>";
			isValid= false;
			}
			
			if($("#reset_confirm_new_password").val() === "" || validate.hasWhiteSpace( $("#reset_confirm_new_password").val())){
			document.getElementById('error_reset_confirm_new_password').innerHTML = "<p>Please confirm a valid new password!</p>";
			isValid= false;
			}
			
			if( $("#reset_new_password").val() != $('#reset_confirm_new_password').val() ){
			document.getElementById('error_reset_new_password').innerHTML = "<p>Passwords don't match!</p>";
			isValid= false;	
			}
			
			return isValid;
		}
	}
	
})();

var clearErrorDivs = (function(){
	
	return{
		register : function(){
			document.getElementById('error_emailRegister').innerHTML = "";
			document.getElementById('error_passwordRegister').innerHTML = "";
			document.getElementById('error_confirmPassword').innerHTML = "";
			document.getElementById('error_firstName').innerHTML = "";
			document.getElementById('error_lastName').innerHTML = "";
			document.getElementById('error_city').innerHTML = "";
			document.getElementById('error_country').innerHTML = "";	
		},
		
		login : function(){
			document.getElementById('error_email').innerHTML = "";
			document.getElementById('error_password').innerHTML = "";
		},

		resetPassword : function(){
			 document.getElementById('error_reset_new_password').innerHTML="";
             document.getElementById('error_reset_confirm_new_password').innerHTML="";
		}
	}
	
})();

var nonFBController = (function(){
	return{
	
		logout:	function(){
			
			var data = {token: localStorage.getItem("token"),'action':'logout'};
			//make ajax call to handle token invalidation
					$.ajax({
					type: 'POST',
					data: data,
					url: "/controllers/authentication",
					dataType: 'json',
					success: function(data) {
						var data = data;
						console.log(data);
						localStorage.setItem("token",null);
						window.location="/"; 		
			
					},
					error: function(data) {
						var data = data;
						console.log(data);
						
					}
				});
		},
		
		loginButtonHandler : function(){
			$("#form").submit(function(event){

				event.preventDefault();
			
				var $form = $(this),
				url = $form.attr('action');
			
				var data = {
							'email': $("#email").val(), 
							'password': $("#password").val(), 
							'action':'login'
							};
			
					if(validate.login() === true){
				
						$.ajax({
							type: 'POST',
							url: url, 
							data: data, 
							timeout: 3000,
							success: function(data) {
							console.log(data); 		
								if(data != ""){
									clearErrorDivs.login();
									localStorage.setItem("token",data);
									window.location="/partials/dashboard.html";
								}else{
									document.getElementById('error_email').innerHTML = "Incorrect email/password!";
									localStorage.setItem("token",null);
								}
							},
							error: function(data) {
							console.log(data);
							document.getElementById('error_email').innerHTML = data.responseJSON;
							localStorage.setItem("token",null);
							}
						});
				}
			});
		},
		
		registerButtonHandler : function(){
			/**
				* Handler for register button
				*/
				$("#form2").submit(function(event){
					event.preventDefault();
					var $form = $(this),
					url = $form.attr('action');
			
					if(validate.register() === true){
					
						console.log("form is valid");
			
						var data = {
						'email': $("#emailRegister").val(), 
						'password': $("#passwordRegister").val(),
						'confirmPassword': $("#confirmPassword").val(),
						'firstName':$('#firstName').val(),
						'lastName':$('#lastName').val(),
						'city':$('#city').val(),
						'country': getKeyByValue($('#country').val()), //store as country code in db
						'action':'register'
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
								clearErrorDivs.register();
								
								window.location="/partials/dashboard.html";
							}else{
								localStorage.setItem("token",null);
								document.getElementById('error_emailRegister').innerHTML = $("#emailRegister").val() + " already exists!";
							}
						},
						error: function(data) {
							console.log(data);
							document.getElementById('error_emailRegister').innerHTML = data.responseJSON;
							localStorage.setItem("token",null);	
						}
						});	
					
					}else{
						console.log("form is invalid");
					}
				});
		},
		
		forgotPassword : function(){
			$("#password_form").submit(function(event){
				document.getElementById('error_email_password').innerHTML = "";
				event.preventDefault();
				
				var data = {'email':$('#email_password').val()};
				
				$.ajax({
					type:"POST",
					url: "/controllers/forgotPassword",
					data:data,
					success : function(data){
						console.log(data);
						document.getElementById('error_email_password').innerHTML = data;
					},
					error: function(data){

						console.log(data);
						document.getElementById('error_email_password').innerHTML = data.responseJSON;
					}
				});
			});
		},

		sessionExpired : function(errorMsg, errorDivID){
			
			if(errorMsg === "Session timed out!"){

				// document.getElementById(errorDivID).innerHTML = "Session has expired! Redirecting...";
				alert("Session has expired! Redirecting...");

				setTimeout(function () {
       				window.location.href = "http://localhost:8080";
    			}, 2000); //will call the function after 2 secs.
			}
			
		}
	}
})();