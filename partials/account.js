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
        },
        error: function(data){
            
        }
        
    }
    );
    
});