var authentication = (function(){
	
	function accountLogout(){
		FB.getLoginStatus(function(response) {
	        if (response && response.status === 'connected') {
	            FB.logout(function(response) {
	            	localStorage.setItem("token", null);
	                window.location="/";
	            });
	        }else {
	        	localStorage.setItem("token", null);
	            window.location="/";
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
