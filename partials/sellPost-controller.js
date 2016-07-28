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

	if(title =='' || title.length > 30){
		alert("title cannot be left empty or exceed 30 chars");
		return false;
	}
	if(brand ==''){
		alert("brand cannot be left empty");
		return false;
	}
	if(model =='' || model.length > 25){
		alert("model cannot be left empty or exceed 25 chars");
		return false;
	}
	if(description == ''){
		alert("description cannot be left empty");
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

		return function(e){
			alert(e);
			// if return successful, close modal
		}
	});

	// this should not happen if false is returned from submitForm
	$('#createSellShoeButton').click(function(){
		closeModal();
	});
});




