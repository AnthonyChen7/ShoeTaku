
var renderTemplate = (function(){

	var template = '<li><li>'

})

$('document').ready(function() {
	$("#pagination a").trigger('click'); // When page is loaded we trigger a click
});

$('#sellPage').on('click', function(e) { // When click on a 'a' element of the pagination div
	var page = 1; // Page number is the id of the 'a' element
	var pagination = ''; // Init pagination
	var url = "/controllers/shoe";
	
	var data = {page: page, per_page: 9}; // Create JSON which will be sent via Ajax
	// We set up the per_page var at 4. You may change to any number you need.

	$.ajax({ // jQuery Ajax
		type: 'POST',
		url: url, // URL to the PHP file which will insert new value in the database
		data: JSON.stringify(data), // We send the data string
		dataType: 'json', // Json format
		timeout: 3000,
		success: function(data) {
			// TODO: figure out to implement this using loop
			$('#first_firstPost').html(data[0]['brand']);
			$('#first_secondPost').html(data[1]['brand']);
			$('#first_thirdPost').html(data[2]['brand']);
			$('#second_firstPost').html(data[3]['brand']);
			$('#second_secondPost').html(data[4]['brand']);
			$('#second_thirdPost').html(data[5]['brand']);
			$('#third_firstPost').html(data[6]['brand']);
			$('#third_secondPost').html(data[7]['brand']);
			$('#third_thirdPost').html(data[8]['brand']);

		},
		error: function(data) {
			// for debugging purpose
			alert("error");
		}
	});
	return false;
});

