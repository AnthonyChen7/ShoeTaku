// $('document').ready(function() {
// 	$("#pagination a").trigger('click'); // When page is loaded we trigger a click
// });



// =============== WILL NEED TO CHANGE THIS INTO MODULAR PATTERN ======================================== //

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

			var shoeInfo = jQuery.parseJSON(data['convertJSON']);

			var shoePostList;

			for(var i = 0; i < 4; i++){
				var shoeId = shoeInfo["shoePostArray"][i]["shoeId"];
				var shoeBrand = shoeInfo["shoePostArray"][i]["brand"];
				var shoeModel = shoeInfo["shoePostArray"][i]["model"];
				shoePostList += '<div class="well well-sm">' + shoeId + '. <b>' + shoeBrand + '</b><p>' + shoeModel + '</p></div>';
			}

			$('#articleArea').html(shoePostList);

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
		},
		error: function(data) {
			// for debugging purpose
			alert("error");
		}
	});
	return false;
});

var pagination = (function(){

	function callAjax(sendUrl, pageNum){
		
		var url = sendUrl;
		var page = pageNum;

		if (!url || !page)
			return false;

		var pagination = ''; // Init pagination
		var data = {page: page, per_page: 4}; // Create JSON which will be sent via Ajax
		// We set up the per_page var at 4. You may change to any number you need.

		$.ajax({ // jQuery Ajax
			type: 'POST',
			url: url, // URL to the PHP file which will insert new value in the database
			data: JSON.stringify(data), // We send the data string
			dataType: 'json', // Json format
			timeout: 3000,
			success: function(data) {

				console.log(jQuery.parseJSON(data['convertJSON']));
				var shoeInfo = jQuery.parseJSON(data['convertJSON']);

				var shoeId1 = shoeInfo["shoePostArray"][0]["shoeId"];
				var shoeBrand1 = shoeInfo["shoePostArray"][0]["brand"];
				var shoeModel1 = shoeInfo["shoePostArray"][0]["model"];

				var shoeId2 = shoeInfo["shoePostArray"][1]["shoeId"];
				var shoeBrand2 = shoeInfo["shoePostArray"][1]["brand"];
				var shoeModel2 = shoeInfo["shoePostArray"][1]["model"];

				var shoeId3 = shoeInfo["shoePostArray"][2]["shoeId"];
				var shoeBrand3 = shoeInfo["shoePostArray"][2]["brand"];
				var shoeModel3 = shoeInfo["shoePostArray"][2]["model"];

				var shoeId4 = shoeInfo["shoePostArray"][3]["shoeId"];
				var shoeBrand4 = shoeInfo["shoePostArray"][3]["brand"];
				var shoeModel4 = shoeInfo["shoePostArray"][3]["model"];
				$('#articleArea').html('<div class="well well-sm">' + shoeId1 + '. <b>' + shoeBrand1 + '</b><p>' + shoeModel1 + '</p></div>' + '<div class="well well-sm">' + shoeId2 + '. <b>' + shoeBrand2 + '</b><p>' + shoeModel2 + '</p></div>' + '<div class="well well-sm">' + shoeId3 + '. <b>' + shoeBrand3 + '</b><p>' + shoeModel3 + '</p></div>' + '<div class="well well-sm">' + shoeId4 + '. <b>' + shoeBrand4 + '</b><p>' + shoeModel4 + '</p></div>');

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
			},
			error: function(jqXHR, textStatus, error) {
				// for debugging purpose
				console.log(jqXHR.status + " , " + textStatus + " : "+ jqXHR.responseText);
			}
		});
	}

	return {
		pagination: function(url, page){
			return callAjax(url, page);
		}
	}
})();

// 	var page = this.id;
// 	var pagination = ''; // Init pagination

// 	var url = "/controllers/shoe";
	
// 	var data = {page: page, per_page: 4}; // Create JSON which will be sent via Ajax
// 	// We set up the per_page var at 4. You may change to any number you need.
// 	$.ajax({ // jQuery Ajax
// 		type: 'POST',
// 		url: url, // URL to the PHP file which will insert new value in the database
// 		data: JSON.stringify(data), // We send the data string
// 		dataType: 'json', // Json format
// 		timeout: 3000,
// 		success: function(data) {
// 			$('#articleArea').html(data.articleList);


// 			if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
// 			else pagination += '<div class="cell"><a href="#" id="1">First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '">Previous</span></a></div>';

// 			for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++) {
// 				if (i >= 1 && i <= data.numPage) {
// 					pagination += '<div';
// 					if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
// 					else pagination += ' class="cell"><a href="#" id="' + i + '">' + i + '</a>';
// 					pagination += '</div>';
// 				}
// 			}

// 			if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
// 			else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '">Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '">Last</span></a></div>';
			
// 			$('#pagination').html(pagination); // We update the pagination DIV

// 		},
// 		error: function(data) {
// 			// for debugging purpose
// 			alert("error");
// 		}
// 	});
// 	return false;

// });

$('document').ready(function() {

	// $('#sellPage').on('click', function(e){
	// 	var id = 1;
	// 	var url = "/controllers/shoe";
	// 	if (id){
	// 		pagination.pagination(url, id);
	// 	}
	// });

	$('#pagination').on('click', 'a', function(e){
		var id = e.target.id;
		var url = "/controllers/shoe";
		if (id){
			pagination.pagination(url, id);
		}

	});
});

