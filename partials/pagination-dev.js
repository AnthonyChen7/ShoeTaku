function renderPagination(page){
	var page = page;
	var pagination = '';
	var articleList = '';
	var per_page = 4;
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

			for(j=0; j < rawArticleList.length; j++){
				articleList += '<div id="postTitle" class="col-md-4"><a href="">';
				postTitle = rawArticleList[j]["title"];
				articleList += postTitle;
				articleList += '</a></div> <div id="postPrice" class="col-md-4">';
				postPrice = rawArticleList[j]["price"];
				articleList += postPrice;
				articleList += '</div> <div id="postCreated" class="col-md-4">';
				postCreated = rawArticleList[j]["created"];
				articleList += postCreated;
				articleList += '</div>';
			}
			if(rawArticleList.length != per_page){
				var k = rawArticleList.length;
				for(; k < per_page; k ++){
					articleList += '<div id="postTitle" class="col-md-4"><a href="">';
					postTitle = '&nbsp';
					articleList += postTitle;
					articleList += '</a></div> <div id="postPrice" class="col-md-4">';
					postPrice = '&nbsp';
					articleList += postPrice;
					articleList += '</div> <div id="postCreated" class="col-md-4">';
					postCreated = '&nbsp';
					articleList += postCreated;
					articleList += '</div>';
				}
			}
			$("#articleArea").html(articleList);

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
	var per_page = 4;
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

			for(j=0; j < rawArticleList.length; j++){
				articleList += '<div id="postTitle" class="col-md-4"><a href="">';
				postTitle = rawArticleList[j]["title"];
				articleList += postTitle;
				articleList += '</a></div> <div id="postPrice" class="col-md-4">';
				postPrice = rawArticleList[j]["price"];
				articleList += postPrice;
				articleList += '</div> <div id="postCreated" class="col-md-4">';
				postCreated = rawArticleList[j]["created"];
				articleList += postCreated;
				articleList += '</div>';
			}
			if(rawArticleList.length != per_page){
				var k = rawArticleList.length;
				for(; k < per_page; k ++){
					articleList += '<div id="postTitle" class="col-md-4"><a href="">';
					postTitle = '&nbsp';
					articleList += postTitle;
					articleList += '</a></div> <div id="postPrice" class="col-md-4">';
					postPrice = '&nbsp';
					articleList += postPrice;
					articleList += '</div> <div id="postCreated" class="col-md-4">';
					postCreated = '&nbsp';
					articleList += postCreated;
					articleList += '</div>';
				}
			}
			$("#articleArea").html(articleList);

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
		$('#postTitle').on('click', function(e){
			e.preventDefault();
			getSellPost();
		});
	});
	$('#listofPages').on('click','a', function(e){
		var page = this.id;
		renderPagination(page);
		$('#postTitle').on('click', function(e){
			e.preventDefault();
			getSellPost();
		});
	});
});