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
			
			//make ajax call to handle token invalidation
			// $.ajax({
			// 	type:'POST',
			// 	url:"/controllers/logout",
			// 	data: {"token":localStorage.getItem("token")},
			// 	 //dataType: "json",
			// 	 success:function(data){
					 
			// 		 console.log(data);
					 
			// 	 },
			// 	 error:function(data){
			// 		 console.log(data);
			// 	 }
			// });
			
			localStorage.setItem("token",null);
			window.location="/";
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
