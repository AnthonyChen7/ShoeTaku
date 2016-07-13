$(document).ready(function(){
    clearErrorDivs.accountLabels();
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
    
    clearErrorDivs.accountLabels();
    
        if(validate.account() === true){
        
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

    clearErrorDivs.passwordLabels();
    document.getElementById('error_save_password').innerHTML = "";

        if(validate.password()===true){
        
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


