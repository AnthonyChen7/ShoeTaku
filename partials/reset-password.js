$(document).ready(function(){
	clearErrorDivs.resetPassword();
	
	$('#reset_password_form').submit(function(event){
		event.preventDefault();
		clearErrorDivs.resetPassword();

		if(validate.resetPassword() === true){
			var data = {
				'new_password': $('#reset_new_password').val(),
				'confirm_new_password': $('#reset_confirm_new_password').val()
			}
			
			$.ajax({
				type:'POST',
				url:"/controllers/resetPassword",
				data: data,
				dataType: "json",
				//dataType: "text",
				success: function(data){
					var data = data;
					console.log(data);
					document.getElementById('reset_confirm_new_password').innerHTML="Password successfully resetted!";
				},
				error: function(data){
					var data = data;
					console.log(data);
					document.getElementById('reset_confirm_new_password').innerHTML=data;
				}
			});
		}
		
	});
});