/**
 * Attach submit handler to Login button
 */

$("#form").submit(function(event){
	
	/**
	 * Stop from submitting normally
	 */
	event.preventDefault();
	
	/**
	 * Retrieve action attribute;URL to send it to
	 */
	var $form = $(this),
	url = $form.attr('action');
	console.log("url is " + url);
	
	/**
	 * Send data using post
	 * http://api.jquery.com/jQuery.post/
	 */
	// var posting = $.post(
	// 	url,
	// 	{
	// 		email: $("#uid").val(),
	// 		password: $("#password").val()
	// 	}
	// );
	
	/**
	 * Send data using AJAX call
	 */
	var data = {email: $("#uid").val(), password: $("#password").val()};
	
	$.ajax({ // jQuery Ajax
		type: 'POST',
		url: url, // URL to the PHP file which will insert new value in the database
		data: JSON.stringify(data), // We send the data string
		dataType: 'json', // Json format
		timeout: 3000,
		success: function(data) {
			alert("success");
			console.log(data);
		},
		error: function(data) {
			alert("error");
			console.log(data);
		}
	});
	
	
	/**
	 * Alerts the results
	 */
	// posting.done(function(data){
	// 	alert(data);
	// });
	
});