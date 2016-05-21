
var renderTemplate = (function(){

	var template = '<li><li>'

})

$('document').ready(function() {
	$("#pagination a").trigger('click'); // When page is loaded we trigger a click
});

$('#pagination').on('click', function(e) { // When click on a 'a' element of the pagination div
	var page = 1; // Page number is the id of the 'a' element
	var pagination = ''; // Init pagination
	var url = "/controllers/shoe";
	
	var data = {page: page, per_page: 3}; // Create JSON which will be sent via Ajax
	// We set up the per_page var at 4. You may change to any number you need.
	
	$.ajax({ // jQuery Ajax
		type: 'POST',
		url: url, // URL to the PHP file which will insert new value in the database
		data: data, // We send the data string
		dataType: 'json', // Json format
		timeout: 3000,
		success: function(data) {
			alert(data);
		},
		error: function(data) {
			alert("123");
		}
	});
	return false;
});