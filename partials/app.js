console.log("in here");

$(document).ready(function() {
console.log("in document ready");

			// $("#createShoePost").submit(function(event){

			// 	event.preventDefault();
			
			// 	var $form = $(this),
			// 	url = $form.attr('action');
			
			// 	var data = {
			// 				'email': $("#email").val(), 
			// 				'password': $("#password").val(), 
			// 				'action':'login'
			// 				};
			
			// 		if(validate.login() === true){
				
			// 			$.ajax({
			// 				type: 'POST',
			// 				url: url, 
			// 				data: data, 
			// 				timeout: 3000,
			// 				success: function(data) {
			// 				console.log(data); 		
			// 					if(data != ""){
			// 						clearErrorDivs.login();
			// 						localStorage.setItem("token",data);
			// 						window.location="/partials/main-page.html";
			// 					}else{
			// 						document.getElementById('error_email').innerHTML = "Incorrect email/password!";
			// 						localStorage.setItem("token",null);
			// 					}
			// 				},
			// 				error: function(data) {
			// 				console.log(data);
			// 				document.getElementById('error_email').innerHTML = data.responseJSON;
			// 				localStorage.setItem("token",null);
			// 				}
			// 			});
			// 	}
			// });

$('#createShoePost').submit(function(event){
console.log("in submit");
	event.preventDefault();
	var title = $('#sellPostTitle').val();
	console.log(title);
	// var brand = $('#shoeBrand').val();
	// var size = $('#shoeSize').val();
	// var itemCondition = $('#itemConditionImg').val();
	// var model = $('#sellShoeModel').val();
	// var price = $('#sellPrice').val();
	// var description = $('#shoeDescription').val();
	// console.log(brand);
	// console.log(size);
	// console.log(itemCondition);
	// console.log(model);
	// console.log(description);

	var url = '/controllers/shoe';

	var data ={
		title: title
		// shoeBrand : brand,
		// shoeModel : model,
		// shoeSize : size,
		// itemCondition : itemCondition,
		// description : description
	};

	$.ajax({
		type:'POST',
		url : url,
		data : data,//JSON.stringify(data),
		//dataType : 'json',
		timeout : 3000,
		success: function(data){
			var data = data;
			console.log("ajax call complete2");
			console.log(data);
		},
		error: function(data){
			var data = data;
			console.log(data);
		}

	});

});
});

