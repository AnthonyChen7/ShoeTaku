$(document).ready(function(){
    
    //make ajax call to populate fields in account.html
    
    $.ajax(
    {
        type: 'GET',
        url:"/controllers/accountSettings",
        data: localStorage.getItem("token"),
        success: function(data){
           console.log(data); 
        },
        error: function(data){
            
        }
        
    }
    );
    
});