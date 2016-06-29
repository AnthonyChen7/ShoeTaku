var authentication = (function(){
	
	function accountLogout(){
		
		FB.getLoginStatus(function(response){
			console.log(response);
			console.log(response.status === "connected");
			if (response.status === "connected"){
				fbLogout();
			}	
			else{
				logout();
			}
						
		});
	}

	return{
		logout: function(){
			return accountLogout();
		}
	}
})();

$(document).ready(function(){
	$("#logout").on("click",function(event){
		authentication.logout();
	});
});
