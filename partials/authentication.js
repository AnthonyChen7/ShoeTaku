var authentication = (function(){
	
	function isFBloggedIn(){
		FB.getLoginStatus(function(response){
			if (response.status == "connected")
				return true;
			else
				return false;		
		});
	}

	function accountLogout(){
		if(isFBloggedIn()){
			fbLogout();
		}else{
			logout();
		}
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
