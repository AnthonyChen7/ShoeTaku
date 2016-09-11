function clearForm(){
	$('#wantedPostSubmit').each(function(){
    	this.reset();
	});
}

function createSellPost(){
	var title = $('#wantedPostTitle').val();
	var brand = $('#wantedShoeBrand').val();
	var model = $('#wantedShoeModel').val();
	var size = $('#wantedShoeSize').val();
	var itemCondition = $('#wantedShoeCond').val();
	var description = $('#wantedShoeDescription').val();
	var price = $('#wantedShoePrice').val();
	price = parseInt(price);

	// 1 is for sell
	// 0 for wanted
	var isWanted = 0;

	var isPostValid = true;

	if(title =='' || title.length > 30){
		document.getElementById("title_wanted_error").innerHTML = "Title cannot be left empty or exceed 30 chars!";
		isPostValid =  false;
	}
	if(brand =='' || brand =='-- Select a Brand --'){
		document.getElementById("brand_wanted_error").innerHTML = "You must choose a brand!";
		isPostValid =  false;
	}
	if(model =='' || model.length > 25){
		document.getElementById("model_wanted_error").innerHTML = "Model cannot be left empty or exceeh 25 chars!";
		isPostValid = false;
	}
	if(size == 15){
		document.getElementById("size_wanted_error").innerHTML = "You must choose the size of the shoes!";
		isPostValid = false;
	}	
	if(itemCondition == 6 || itemCondition == ''){
		document.getElementById("condition_wanted_error").innerHTML = "You must choose item condition!";
		isPostValid = false;
	}
	if(description == ''){
		document.getElementById("description_wanted_error").innerHTML = "Description must be filled!";
		isPostValid = false;
	}
	if(price < 0 || isNaN(price)){
		document.getElementById("price_wanted_error").innerHTML = "Price cannot be a negative value or word(s)!"
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
		description : description,
		price: price,
		token : localStorage.getItem("token")
	};
	console.log("url is " +url);

	$.ajax({
		type:'POST',
		url : url,
		data : data,
		dataType : 'json',
		timeout : 3000,
		success: function(data){
			console.log("is wanted" + data['isWanted']);
			clearForm();
			if(data){
				closeModal();
				$('.wanted_error').html('');
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
    $('#createWantedPost').modal('hide');
}

$(document).ready(function() {
	$('#wantedPostSubmit').submit(function(event){
		event.preventDefault();
		if(!createSellPost()){
			// create another css modal for this
			alert("Post Registration not successful, try again.");
			return false;
		}else{
			location.reload();
		}
		
	});
});




