$(document).ready(function(){
    clearErrors.accountLabels();
    console.log(localStorage.getItem("token"));
   // make ajax call to populate fields in account.html
    $.ajax(
    {
        type: 'POST',
        url:"/controllers/accountSettings",
        data: {"token":localStorage.getItem("token"),"action":"retrieve"},  
        dataType: "json",
        success: function(data){
          var data = data;
          console.log(data); 
           if(data.error!=null){
              document.getElementById('save_message').innerHTML = data.error; 
           }else if(data != null){
                document.getElementById("firstName_account").value = data.firstName;
               document.getElementById("lastName_account").value = data.lastName;
               document.getElementById("city_account").value = data.city;
               document.getElementById("country_account").value = getCountryName(data.countryCode);
           }else{
               document.getElementById('save_message').innerHTML = "Error: Unable to retrieve account information!";
           }
            
        },
        error: function(data){
            var data=data;
            console.log(data);
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
    "action":"update",
	'firstName':$('#firstName_account').val(),
	'lastName':$('#lastName_account').val(),
	'city':$('#city_account').val(),
	'country': getKeyByValue($('#country_account').val()), //store as country code in db
    'token':localStorage.getItem("token")
	};
    
    clearErrors.accountLabels();
        if(isFormValid() === true){
        
        $.ajax({
		type: 'POST',
		url: url, 
		data: data,
        dataType: "json", 
		timeout: 3000,
		success: function(data) {				
	    
        
        var data = data;
        console.log(data);
	   
       if(data.error != null){
         document.getElementById('save_message').innerHTML = data.error;   
       }
       else if(data.success === true){
       document.getElementById('save_message').innerHTML = "Changes saved successfully!";    
       }else{
       document.getElementById('save_message').innerHTML = "Error! Changes not saved!";    
       }
       
		},
		error: function(data) {
            var data = data;
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

    clearErrors.passwordLabels();
    document.getElementById('error_save_password').innerHTML = "";
    
    if(isValidPasswordForm()===true){
        
        var data = {
            "old_password": $("#old_password").val(),
            "new_password": $("#new_password").val(),
            'token':localStorage.getItem("token")
        }
        
        $.ajax({
            type:'POST',
            url: url,
            data: data,
            dataType:"json",
            success:function(data){
              
              var data = data;  
                
              console.log(data);
              
              if(data.error != null){
              document.getElementById('error_save_password').innerHTML = data.error;        
              }
              
              else if(data.password_match === true){
              $('#password_account').val(data.new_password);    
              document.getElementById('error_save_password').innerHTML = "Password successfully changed!";    
              }else{
              document.getElementById('error_old_password').innerHTML = "Password is incorrect!";    
              document.getElementById('error_save_password').innerHTML = "Password not successfully changed!";    
              }
                
            },
            error:function(data){
                var data = data;
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
}

var clearErrors = (function(){
    
    return{
        accountLabels : function(){
                document.getElementById('firstName_account_error').innerHTML = "";
                document.getElementById('lastName_account_error').innerHTML="";
                document.getElementById('city_account_error').innerHTML="";
                document.getElementById('country_account_error').innerHTML="";
                document.getElementById('save_message').innerHTML ="";
        },
        
        passwordLabels : function(){
                document.getElementById('error_old_password').innerHTML = "";
                document.getElementById('error_new_password').innerHTML="";
                document.getElementById('error_confirm_password').innerHTML="";
                document.getElementById('error_save_password').innerHTML=""; 
        }
    }
    
})();

function isValidPasswordForm(){
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
}

