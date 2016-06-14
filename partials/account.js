// $(document).ready(function(){
    
    document.getElementById('save_message').innerHTML = "";
    
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
	
		
	
});
    
// });
