$(document).ready(function(){
    //make ajax call to populate fields in account.html
    $.ajax(
    {
        type: 'GET',
        url:"/controllers/accountSettings",
        data: {"token":localStorage.getItem("token")},
        dataType: "json",
        success: function(data){
           console.log(data);
           
           if(data != null){
               document.getElementById("emailRegister").value = data.email;
               document.getElementById("firstName").value = data.firstName;
               document.getElementById("lastName").value = data.lastName;
               document.getElementById("city").value = data.city;
               document.getElementById("country").value = getCountryName(data.countryCode);
           }else{
               alert("Error: Unable to retrieve account information!");
           }
            
        },
        error: function(data){
            alert("Error: Unable to retrieve account information!");
        }
        
    }
    );
    
//button handler for "Save Changes"
// $("#account_setting_form").submit(function(event){
//     event.preventDefault();
    
//     var $form = $(this);
//     var url = $form.attr('action');
//     var method = $form.attr('method').toUpperCase();
    
//     var data = {
//     'email': $("#emailRegister").val(), 
// 	// 'password': $("#passwordRegister").val(),
// 	'firstName':$('#firstName').val(),
// 	'lastName':$('#lastName').val(),
// 	'city':$('#city').val(),
// 	'country': getKeyByValue($('#country').val()), //store as country code in db   
//     "token":localStorage.getItem("token")
//     };
            
//     $.ajax({
// 		type: 'POST',
// 		url: url, 
// 		data: data, 
// 		timeout: 3000,
// 		success: function(data) {			
// 			console.log(data);
// 		},
// 		error: function(data) {
// 			alert("error");
	
// 		}
// 	});	
    
       
// });
    
});

