function clearForm(){
	$('#createShoePost').each(function(){
    	this.reset();
	});
}

function createShoePost(){
	var title = $('#sellPostTitle').val();
	console.log(title);
	var brand = $('#sellShoeBrand').val();
	var model = $('#sellShoeModel').val();
	var size = $('#sellShoeSize').val();
	var itemCondition = $('#sellShoeCond').val();
	var description = $('#sellShoeDescription').val();

	var price = $('#sellShoePrice').val();
	
	var url = '/controllers/shoe';
	var data ={
		title: title,
		shoeBrand : brand,
		shoeModel : model,
		shoeSize : size,
		itemCondition : itemCondition,
		description : description
	};

	$.ajax({
		type:'POST',
		url : url,
		data : data,//JSON.stringify(data),
		dataType : 'json',
		timeout : 3000,
		success: function(data){
			clearForm();
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

function closeModal(){
    $('#createSellPost').modal('hide');
}

$(document).ready(function() {
	$('#createShoePost').submit(function(event){
		console.log("in submit");
		event.preventDefault();
		createShoePost();
	});
	$('#createSellShoeButton').click(function(){
		closeModal();
	});
});

