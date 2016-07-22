function createShoePost(){
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
}

$(document).ready(function() {

	$('#createShoePost').submit(function(event){
		console.log("in submit");
		event.preventDefault();
		// createShoePost();
	});
});

