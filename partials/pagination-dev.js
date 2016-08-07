function renderPagination(page){
	var page = page;
	var pagination = '';
	var articleList = '';
	var per_page = 6;
	var url = "/controllers/shoe";

	var data = {page:page, per_page: per_page};

	$.ajax({

		type: 'POST',
		url:url,
		data:JSON.stringify(data),
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

			pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
			for(i=1; i < (totalNumPage+1); i++){
				pagination += '<div';
				if(i==page){
					pagination += ' class="cell_active"><span>' + i + '</span>';
				}else{
					pagination += ' class="cell"><a href="#" id="' + i + '">' + i + '</a>';
					pagination += '</div>';
				}
			}
			pagination += '<div><span>Next</span></div><div><span>Last</span></div>';
			$('#listofPages').html(pagination); // We update the pagination DIV

		},
		error: function(data){
			console.log("error is " + data);
		}
	});
}

function getSellPost(){
	alert("individual post is clicked");

}

function bringFirstSellPage(){
	var page = 1;
	var pagination = '';
	var per_page = 6;
	var articleList = '';
	var url = "/controllers/shoe";

	var data = {page:page, per_page:per_page};

	$.ajax({
		type: 'POST',
		url:url,
		data:JSON.stringify(data),
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

			pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
			for(i=1; i < (totalNumPage+1); i++){
				pagination += '<div';
				if(i==page){
					pagination += ' class="cell_active"><span>' + i + '</span>';
				}else{
					pagination += ' class="cell"><a href="#" id="' + i + '">' + i + '</a>';
					pagination += '</div>';
				}
			}
			pagination += '<div><span>Next</span></div><div><span>Last</span></div>';
			$('#listofPages').html(pagination); // We update the pagination DIV

		},
		error: function(data){
			console.log("error is " + data);
		}
	});
}

$('document').ready(function(){
	$('#sellPage').on('click',function(e){
		bringFirstSellPage();
	});
	$('#listofPages').on('click','a', function(e){
		var page = this.id;
		renderPagination(page);
	});
	$('a .page').on('click', function(){
			alert("user clicked the title");
			console.log('bug');
			getSellPost();
	});
});