$(document).ready(function(){
    
    clearErrorDivs();
    
   // make ajax call to populate fields in account.html
    $.ajax(
    {
        type: 'GET',
        url:"/controllers/accountSettings",
        data: {"token":localStorage.getItem("token")},
        dataType: "json",
        success: function(data){
           console.log(data);
           
           if(data != null){
               document.getElementById("email_account").value = data.email;
               document.getElementById("password_account").value = data.password;
               document.getElementById("firstName_account").value = data.firstName;
               document.getElementById("lastName_account").value = data.lastName;
               document.getElementById("city_account").value = data.city;
               document.getElementById("country_account").value = getCountryName(data.countryCode);
           }else{
               document.getElementById('save_message').innerHTML = "Error: Unable to retrieve account information!";
           }
            
        },
        error: function(data){
            document.getElementById('save_message').innerHTML = "Error: Unable to retrieve account information!";
        }
        
    }
    );
    
    $("#account_setting_form").submit(function(event){
    
    document.getElementById('save_message').innerHTML = "";    
        
    event.preventDefault();
	var $form = $(this),
	url = $form.attr('action');

	/**
	 * Send data using AJAX call
	 */
	var data = {
	'firstName':$('#firstName_account').val(),
	'lastName':$('#lastName_account').val(),
	'city':$('#city_account').val(),
	'country': getKeyByValue($('#country_account').val()), //store as country code in db
    'token':localStorage.getItem("token")
	};
    
    clearErrorDivs();
    
    if(isFormValid() === true){
        
        $.ajax({
		type: 'POST',
		url: url, 
		data: data,
        dataType: "json", 
		timeout: 3000,
		success: function(data) {				
	    console.log(data);
	   
       if(data.success === true){
       document.getElementById('save_message').innerHTML = "Changes saved successfully!";    
       }else{
       document.getElementById('save_message').innerHTML = "Error! Changes not saved!";    
       }
       
		},
		error: function(data) {
			console.log(data);
            document.getElementById('save_message').innerHTML = "Error! Changes not saved!"; 
		}
	});
        
    }else{
        document.getElementById('save_message').innerHTML = "Error! Changes not saved!";
    }
});

    $('#password_setting_form').submit(function(event){
        
     event.preventDefault();
	var $form = $(this),
	url = $form.attr('action');
    
    clearPasswordDivs();
    document.getElementById('error_save_password').innerHTML = "";
    
    if(isValidPasswordForm()===true){
        
        var data = {
            "old_password": $("#old_password").val(),
            "new_password": $("#new_password").val()
        }
        
        $.ajax({
            type:'POST',
            url: url,
            data: data,
            //dataType:"json",
            success:function(data){
              console.log(data);
              document.getElementById('error_save_password').innerHTML = "Password successfully changed!";  
            },
            error:function(data){
                console.log(data);
                document.getElementById('error_save_password').innerHTML = "Password not successfully changed!";   
            }
        });
        
        
    }else{
        document.getElementById('error_save_password').innerHTML = "Password not successfully changed!";
    } 
        
    });
    
});

function isFormValid(){
    var isValid = true;
    
        if($("#firstName_account").val() === "" || hasWhiteSpace( $("#firstName_account").val())){
			document.getElementById('firstName_account_error').innerHTML = "<p>Please provide a valid first name!</p>";
			isValid= false;
		}
		
		if($("#lastName_account").val() === "" || hasWhiteSpace( $("#lastName_account").val())){
			document.getElementById('lastName_account_error').innerHTML = "<p>Please provide a valid last name!</p>";
			isValid= false;
		}
		
		if($("#city_account").val() === "" || hasWhiteSpace( $("#city_account").val())){
			document.getElementById('city_account_error').innerHTML = "<p>Please provide a valid city!</p>";
			isValid= false;
		}
		
		if($("#country_account").val() === "" || hasWhiteSpace( $("#country_account").val())|| getKeyByValue($("#country_account").val() ) === undefined ){
			document.getElementById('country_account_error').innerHTML = "<p>Please provide a valid country!</p>";
			isValid= false;
		}
    
    
    return isValid;
}

function clearErrorDivs(){
    document.getElementById('firstName_account_error').innerHTML = "";
    document.getElementById('lastName_account_error').innerHTML="";
    document.getElementById('city_account_error').innerHTML="";
    document.getElementById('country_account_error').innerHTML="";
    document.getElementById('save_message').innerHTML ="";
}

function isValidPasswordForm(){
    var isValid = true;
    
        if($("#old_password").val() === "" || hasWhiteSpace( $("#old_password").val())){
			document.getElementById('error_old_password').innerHTML = "<p>Please provide a valid old password!</p>";
			isValid= false;
		}
		
		if($("#new_password").val() === "" || hasWhiteSpace( $("#new_password").val())){
			document.getElementById('error_new_password').innerHTML = "<p>Please provide a valid new password!</p>";
			isValid= false;
		}
		
		if($("#confirm_new_password").val() != $("#new_password").val() ){
			document.getElementById('error_new_password').innerHTML = "<p>New password do not match!</p>";
			isValid= false;
		}
    
    return isValid;
}

function clearPasswordDivs(){
   document.getElementById('error_old_password').innerHTML = "";
    document.getElementById('error_new_password').innerHTML="";
    document.getElementById('error_confirm_password').innerHTML="";
    document.getElementById('error_save_password').innerHTML=""; 
}

$("#change_password").leanModal({
	top: 100,
	overlay: 0.6,
	closeButton: ".modal_close"
});