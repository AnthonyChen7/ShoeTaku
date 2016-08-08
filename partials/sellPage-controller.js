function clearForm(){
	$('#sellPostSubmit').each(function(){
    	this.reset();
	});
}

function createSellPost(){
	var title = $('#sellPostTitle').val();
	var brand = $('#sellShoeBrand').val();
	var model = $('#sellShoeModel').val();
	var size = $('#sellShoeSize').val();
	var itemCondition = $('#sellShoeCond').val();
	var description = $('#sellShoeDescription').val();
	var price = $('#sellShoePrice').val();
	price = parseInt(price);
	// 1 is for sell
	var isWanted = 1;

	var isPostValid = true;

	if(title =='' || title.length > 30){
		document.getElementById("title_sell_error").innerHTML = "Title cannot be left empty or exceed 30 chars!";
		isPostValid =  false;
	}
	if(brand =='' || brand =='-- Select a Brand --'){
		document.getElementById("brand_sell_error").innerHTML = "You must choose a brand!";
		isPostValid =  false;
	}
	if(model =='' || model.length > 25){
		document.getElementById("model_sell_error").innerHTML = "Model cannot be left empty or exceeh 25 chars!";
		isPostValid = false;
	}
	if(size == 15){
		document.getElementById("size_sell_error").innerHTML = "You must choose the size of the shoes!";
		isPostValid = false;
	}	
	if(itemCondition == 6 || itemCondition == ''){
		document.getElementById("condition_sell_error").innerHTML = "You must choose item condition!";
		isPostValid = false;
	}
	if(description == ''){
		document.getElementById("description_sell_error").innerHTML = "Description must be filled!";
		isPostValid = false;
	}
	if(price < 0 || isNaN(price)){
		document.getElementById("price_sell_error").innerHTML = "Price cannot be a negative value or word(s)!"
		isPostValid = false;
	}

	if(!isPostValid){
		return false;
	}
	
	var url = '/controllers/shoe';
	var data ={
		title: title,
		shoeBrand : brand,
		shoeModel : model,
		shoeSize : size,
		itemCondition : itemCondition,
		isWanted : isWanted,
		description : description
	};
	console.log("url is " +url);

	$.ajax({
		type:'POST',
		url : url,
		data : JSON.stringify(data),
		dataType : 'json',
		timeout : 3000,
		success: function(data){
			clearForm();
			if(data){
				closeModal();
				$('.sell_error').html('');
			}
			var data = data;
			console.log(data);
		},
		error: function(data){
			clearForm();
			var data = data;
			console.log("error: " + data);
		}
	});

	clearForm();

	return true;
}

function closeModal(){
    $('#createSellPost').modal('hide');
}

$(document).ready(function() {
	$('#sellPostSubmit').submit(function(event){
		event.preventDefault();
		if(!createSellPost()){
			alert("Post Registration not successful, try again.");
			return false;
		}
		alert("Your post has been created");
	});
});




