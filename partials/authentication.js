var authentication = (function(){
	function isFBloggedIn(){
		FB.getLoginStatus(function(response){
			if (response.status == "connected")
				return true;
			else
				return false;		
		});
	}
	function logout(){
		if(isFBloggedIn)
			fbLogout();
		else
			console.log("here log out non fb");
		
	}
	return{
		logout: function(){
			logout();
		}
	}
})();