function renderPagination(page){
	var page = page;
	var pagination = '';
	var articleList = '';
	var per_page = 10;
	var url = "/controllers/shoe";

	var isWanted = 0;

	var data = {'page':page, 'per_page':per_page,'token': localStorage.getItem('token'), 'isWanted' : isWanted};

	$.ajax({

		type: 'POST',
		url:url,
		data:data,
		dataType:'json',
		timeout:3000,
		success: function(data){
			var totalNumPage = data['totalNumPage'];
			var rawArticleList = data['articleList']['shoePostArray'];

			$(".postTitle a").each(function(i){
					if(i < per_page && i >= rawArticleList.length){
						$(this).empty();
					}else{
						shoeId = rawArticleList[i]["shoeId"];
						postTitle = rawArticleList[i]["title"];
						$(this).html(postTitle);
						newUrl = "/partials/wantedPost.php?id=" + shoeId;
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

			var pagination = '';

		    if (page == 1){
		        pagination += '<li class="disabled"><a href="#" aria-label="First"<span aria-hidden="true">First</span></a></li>';
		        pagination += '<li class="disabled><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		    }
		    else {
		        pagination += '<li><a href="#" aria-label="First" id="1"><span aria-hidden="true">First</span></a></li><li><a href="#" aria-label="Previous" id="' + (page - 1) + '">&laquo</span></a></li>';
		    }

		    for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++) {
		        if (i >= 1 && i <= totalNumPage) {
		            pagination += '<li';
		            if (i == page) pagination += ' class="active"><span>' + i + '</span>';
		            else pagination += '><a href="#" id="' + i + '">' + i + '</a>';
		            pagination += '</li>';
		        }
		    }

		    if (page == totalNumPage) pagination += '<li class="disabled><a href="#" aria-label="Next"><span aria-hidden="true">&raquo</span></a></li><li class="disabled"><a href="#" aria-label="Last"<span aria-hidden="true">Last</span></a></li>';
		    else pagination += '<li><a href="#" aria-label="Next" id="' + (parseInt(page) + 1) + '">&raquo</a></li><li><a href="#" aria-label="Last" id="' + totalNumPage + '">Last</span></a></li>';

		    $('#listofPages').html(pagination); // We update the pagination DIV

		},
		error: function(data){
			console.log(data.responseText);
			nonFBController.sessionExpired(data.responseJSON,'save_message');
		}
	});
}

function getSellPost(){
	console.log("individual post is clicked");

}

function bringFirstWantedPage(){
	var page = 1;
	var pagination = '';
	var per_page = 10;
	var articleList = '';
	var url = "/controllers/shoe";

	var isWanted = 0;

	var data = {'page':page, 'per_page':per_page,'token': localStorage.getItem('token'), 'isWanted' : isWanted};

	$.ajax({
		type: 'POST',
		url:url,
		data:data,
		dataType:'json',
		timeout:3000,
		success: function(data){
			var totalNumPage = data['totalNumPage'];
			var rawArticleList = data['articleList']['shoePostArray'];

			$(".postTitle a").each(function(i){
					if(i < per_page && i >= rawArticleList.length){
						$(this).empty();
					}else{
						shoeId = rawArticleList[i]["shoeId"];
						postTitle = rawArticleList[i]["title"];
						$(this).html(postTitle);
						newUrl = "/partials/wantedPost.php?id=" + shoeId;
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

			var pagination = '';

		    if (page == 1){
		        pagination += '<li class="disabled"><a href="#" aria-label="First"<span aria-hidden="true">First</span></a></li>';
		        pagination += '<li class="disabled><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		    }
		    else {
		        pagination += '<li><a href="#" aria-label="First" id="1"><span aria-hidden="true">First</span></a></li><li><a href="#" aria-label="Previous" id="' + (page - 1) + '">&laquo</span></a></li>';
		    }

		    for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++) {
		        if (i >= 1 && i <= totalNumPage) {
		            pagination += '<li';
		            if (i == page) pagination += ' class="active"><span>' + i + '</span>';
		            else pagination += '><a href="#" id="' + i + '">' + i + '</a>';
		            pagination += '</li>';
		        }
		    }

		    if (page == totalNumPage) pagination += '<li class="disabled><a href="#" aria-label="Next"><span aria-hidden="true">&raquo</span></a></li><li class="disabled"><a href="#" aria-label="Last"<span aria-hidden="true">Last</span></a></li>';
		    else pagination += '<li><a href="#" aria-label="Next" id="' + (parseInt(page) + 1) + '">&raquo</a></li><li><a href="#" aria-label="Last" id="' + totalNumPage + '">Last</span></a></li>';

		    $('#listofPages').html(pagination); // We update the pagination DIV

		},
		error: function(data){
			console.log(data.responseText);
			nonFBController.sessionExpired(data.responseJSON,'save_message');
		}
	});
}

$('document').ready(function(){
	bringFirstWantedPage();
	$('#listofPages').on('click','a', function(e){
		var page = this.id;
		renderPagination(page);
	});
	$(".postTitle a").on('click',function(){
		console.log("anchor clicked");
		getSellPost();
	});
});