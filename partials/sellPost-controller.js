function clearForm(){
	$('#createShoePost').each(function(){
    	this.reset();
	});
}

function createShoePost(){
	var title = $('#sellPostTitle').val();
	var brand = $('#sellShoeBrand').val();
	var model = $('#sellShoeModel').val();
	var size = $('#sellShoeSize').val();
	var itemCondition = $('#sellShoeCond').val();
	var description = $('#sellShoeDescription').val();
	var price = $('#sellShoePrice').val();
	price = parseInt(price);

	if(title =='' || title.length > 30){
		alert("title cannot be left empty or exceed 30 chars");
		return false;
	}
	if(brand =='' || brand =='-- Select a Brand --'){
		alert("you must choose brand");
		return false;
	}
	if(model =='' || model.length > 25){
		alert("model cannot be left empty or exceed 25 chars");
		return false;
	}
	if(itemCondition == 7 || itemCondition == ''){
		alert("you must choose item condition");
		return false;
	}
	if(description == ''){
		alert("description cannot be left empty");
		return false;
	}
	if(price < 0){
		alert("price cannot be a negative value");
		return false;
	}
	
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
			console.log(data);
		},
		error: function(data){
			clearForm();
			var data = data;
			console.log(data);
		}
	});

	clearForm();

	return true;
}

function closeModal(){
    $('#createSellPost').modal('hide');
}

$(document).ready(function() {
	$('#createShoePost').submit(function(event){
		event.preventDefault();
		if(!createShoePost()){
			alert("Post Registration not successful, try again.");
			return false;
		}
		alert("Your post has been created");
	});

	// this should not happen if false is returned from submitForm
	$('#createSellShoeButton').click(function(){
		closeModal();
	});
});




