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
    
});