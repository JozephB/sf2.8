$(document).ready(function(){

	// add * to required field labels
	//$('span.required').append('&nbsp;<strong>*</strong>&nbsp;');

	// validate signup form on keyup and submit
	var validator = $("#signupform").validate({
			rules: {
				firstname: {
					required: true,				
					minlength: 5,
					maxlength: 10,
					lettersonly:true
				},
				lastname: {
					required: true,					
					minlength: 5
				},
				email: {
					required: true,
					email: true,
				},
			},

			messages: {
				firstname: {
					required: "Enter your firstname",
					lettersonly: "Enter caractere only",
					minlength: $.validator.format( "Please enter {0} words or more." ),
					maxlength: $.validator.format( "maximum {0} words or less." )
				},
				lastname: "Enter your lastname",
				email: {
					required: "Please enter a valid email address",
					minlength: "Please enter a valid email address"
				}
			},

			// the errorPlacement has to take the table layout into account
			errorPlacement: function(error, element) {
				if (element.is(":radio"))
					error.appendTo(element.parent().next().next());
				else if (element.is(":checkbox"))
					error.appendTo(element.next());
				else
					error.appendTo(element.next());
					//error.appendTo(element.next());
			},
			// specifying a submitHandler prevents the default submit, good for the demo
			submitHandler: function() {
				
			   $("#signupform").submit(function(e){
				   e.preventDefault();
				   $.ajax({
					    type: "POST",
					    url: "server/server.php",
					    data: $(this).serialize(),
					    dataType: 'json', 
					    success: function(data) {
					    	if(data.success){
					    	 	$('#signupform').html("<div id='message'></div>");
						        $('#message').html("<h2>Your request is on the way!</h2>")
						        .append("<p>someone</p>")
						        .hide()
						        .fadeIn(1500, function() {
						            $('#message').text("ok");
						        });  
					    	}else{
					    		$('input[name="email"]').css({'border-color':'red'}).next().text(data.error.email).show();
					    	}
					    }
				    });
				    return false;
			 	});
			},
			// set this class to error-labels to indicate valid fields
			success: function(label,element) {
				// set &nbsp; as text for IE
				$(element).css({'border-color':'black'}).next().hide();
			},

			highlight: function(element, errorClass) {
				//$(element).parent().next().find("." + errorClass).removeClass("checked");
				$(element).css({'border-color':'red'}).next().show();
			}
	});
});


