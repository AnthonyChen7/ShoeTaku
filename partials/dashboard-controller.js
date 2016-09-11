function renderRecentSellPost(){
	var page = 1;
	var pagination = '';
	var per_page = 5;
	var articleList = '';
	var url = "/controllers/shoe";

	var wanted = 0;

	var data = {'page':page, 'per_page':per_page,'token': localStorage.getItem('token'), 'isWanted' : wanted};

	$.ajax({
		type: 'POST',
		url:url,
		 // data:JSON.stringify(data),
		data:data,
		dataType:'json',
		timeout:3000,
		success: function(data){
			var totalNumPage = data['totalNumPage'];
			var rawArticleList = data['articleList']['shoePostArray'];

			$("#wantedPosts ul li a").each(function(i){
					if(i < per_page && i >= rawArticleList.length){
						$(this).empty();
					}else{
						shoeId = rawArticleList[i]["shoeId"];
						postTitle = rawArticleList[i]["title"];
						$(this).html(postTitle);
						newUrl = "#/posts?id=" + shoeId;
						$(this).attr("href", newUrl);
					} 
			});	

			$(".postPrice").each(function(i){
				if(i < per_page && i >= rawArticleList.length){
					$(this).empty();
				}else{
					postPrice = rawArticleList[i]["price"];
					$(this).html(postPrice);
				}
			});

			$(".postCreated").each(function(i){
				if(i < per_page && i >= rawArticleList.length){
					$(this).empty();
				}else{
					postCreated = rawArticleList[i]["created"]
					$(this).html(postCreated);
				}					
			});


		},
		error: function(data){
			console.log(data.responseText);
			nonFBController.sessionExpired(data.responseJSON,'save_message');
		}
	});

	sell = 1;
	var sellData = {'page':page, 'per_page':per_page,'token': localStorage.getItem('token'), 'isWanted' : sell};

	$.ajax({
		type: 'POST',
		url:url,
		 // data:JSON.stringify(data),
		data:sellData,
		dataType:'json',
		timeout:3000,
		success: function(data){
			var totalNumPage = data['totalNumPage'];
			var rawArticleList = data['articleList']['shoePostArray'];

			$("#sellPosts ul li a").each(function(i){
					if(i < per_page && i >= rawArticleList.length){
						$(this).empty();
					}else{
						shoeId = rawArticleList[i]["shoeId"];
						postTitle = rawArticleList[i]["title"];
						$(this).html(postTitle);
						newUrl = "#/posts?id=" + shoeId;
						$(this).attr("href", newUrl);
					} 
			});	

			$(".postPrice").each(function(i){
				if(i < per_page && i >= rawArticleList.length){
					$(this).empty();
				}else{
					postPrice = rawArticleList[i]["price"];
					$(this).html(postPrice);
				}
			});

			$(".postCreated").each(function(i){
				if(i < per_page && i >= rawArticleList.length){
					$(this).empty();
				}else{
					postCreated = rawArticleList[i]["created"]
					$(this).html(postCreated);
				}					
			});


		},
		error: function(data){
			console.log(data.responseText);
			nonFBController.sessionExpired(data.responseJSON,'save_message');
		}
	});

}

function renderRecentWantedPost(){

}

$('document').ready(function(){
	renderRecentSellPost();
	renderRecentWantedPost();
});