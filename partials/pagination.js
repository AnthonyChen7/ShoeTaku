$('document').ready(function() {
	$("#pagination a").trigger('click'); // When page is loaded we trigger a click
});


$('#sellPage').on('click', function(e) { // When click on a 'a' element of the pagination div
	
	var page = 1;
	var pagination = ''; // Init pagination

	var url = "/controllers/shoe";
	
	var data = {page: page, per_page: 4}; // Create JSON which will be sent via Ajax
	// We set up the per_page var at 4. You may change to any number you need.

	$.ajax({ // jQuery Ajax
		type: 'POST',
		url: url, // URL to the PHP file which will insert new value in the database
		data: JSON.stringify(data), // We send the data string
		dataType: 'json', // Json format
		timeout: 3000,
		success: function(data) {
			$('#articleArea').html(data.articleList);

			pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
			
			for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++) {
				if (i >= 1 && i <= data.numPage) {
					pagination += '<div';
					if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
					else pagination += ' class="cell"><a href="#" id="' + i + '">' + i + '</a>';
					pagination += '</div>';
				}
			}

			pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '">Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '">Last</span></a></div>';
			
			$('#pagination').html(pagination); // We update the pagination DIV

			console.log("this is sellpost ajax");
			console.log(data);
			console.log("article list: " + data['articleList']);
			console.log(data['numPage']);
			console.log(data['numArticles']['0']);

			console.log("the first data is: " + data['first']);
			console.log(data['pageBefore']);
			console.log(data['perPageBefore']);

			console.log("start is: " + data['start']);
			console.log("after");
			console.log(data['pageAfter']);
			console.log(data['perPageAfter']);

		},
		error: function(data) {
			// for debugging purpose
			alert("error");
		}
	});

	return false;

});


$('#pagination').on('click', 'a', function(e){

	var page = this.id;
	var pagination = ''; // Init pagination

	var url = "/controllers/shoe";
	
	var data = {page: page, per_page: 4}; // Create JSON which will be sent via Ajax
	// We set up the per_page var at 4. You may change to any number you need.

	$.ajax({ // jQuery Ajax
		type: 'POST',
		url: url, // URL to the PHP file which will insert new value in the database
		data: JSON.stringify(data), // We send the data string
		dataType: 'json', // Json format
		timeout: 3000,
		success: function(data) {
			$('#articleArea').html(data.articleList);

			if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
			else pagination += '<div class="cell"><a href="#" id="1">First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '">Previous</span></a></div>';

			for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++) {
				if (i >= 1 && i <= data.numPage) {
					pagination += '<div';
					if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
					else pagination += ' class="cell"><a href="#" id="' + i + '">' + i + '</a>';
					pagination += '</div>';
				}
			}

			if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
			else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '">Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '">Last</span></a></div>';
			
			$('#pagination').html(pagination); // We update the pagination DIV
		
			console.log("this is pagination ajax");
			console.log("data is: "+data);
			console.log("article list: " + data['articleList']);
			console.log("num page is : " + data['numPage']);
			console.log("first element of numArticle: "+data['numArticles']['0']);

			console.log("the first data is: " + data['first']);
			console.log("page before is: " + data['pageBefore']);
			console.log("perpage after is: " + data['perPageBefore']);
			console.log("start is: " + data['start']);

			console.log("after");
			console.log("pageAfter is: "+ data['pageAfter']);
			console.log("perpage before is: "+ data['perPageAfter']);

		},
		error: function(data) {
			// for debugging purpose
			alert("error");
		}
	});

	return false;

});


