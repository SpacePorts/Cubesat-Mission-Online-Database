(function()
{
	$(window).load(function(){
		ResizePage();

		Recaptcha.create("6LdCLOYSAAAAACCP0h1_xlvecf4zahV3xbnwAXZo", 'ContactFormCapcha', {
              theme: 'red',
            });
		
	});

})();


function sendEmailForm()
{
	$.post(AJAX_MAIL,{
		Name:     			$("#contactFormNameField").val(),
		Email:    			$("#contactFormEmailField").val(),
		Subject:  			$("#contactFormSubjectField").val(),
		RecapcheChallange:  $("#recaptcha_challenge_field").val(),
		Recapche: 			$("#recaptcha_response_field").val(),
		Message: 			$("#contactFormMessageField").val()},
		function(data){
			
			if(data.formSent)
			{
				for(x = 0; x < data.pass.length;x++)
				{
					$("#contactFormWarning"+data.pass[x]).animate({opacity:0},500);
					if(data.pass[x] == 4)
					{
						$("#capche").animate({height:0},500);
					}
				}
				$("#formSent").animate({height:80},500);
				$("#submitButton").animate({height:0},500);
				$("#contactForm input, #contactForm textarea").prop('disabled', true);
			}
			else
			{
				for(x = 0; x < data.warnings.length; x++)
				{
					if(data.warnings[x].ID == 4)
					{
						Recaptcha.reload();
					}
					if($("#contactFormWarning"+data.warnings[x].ID).html() != data.warnings[x].Warning)
					{
						$("#contactFormWarning"+data.warnings[x].ID).html(data.warnings[x].Warning);

						$("#contactFormWarning"+data.warnings[x].ID).css({opacity:0,left:-10});
						$("#contactFormWarning"+data.warnings[x].ID).animate({opacity:1,left:0},500);
					}
				}
				for(x = 0; x < data.pass.length;x++)
				{
					$("#contactFormWarning"+data.pass[x]).animate({opacity:0},500);
					if(data.pass[x] == 4)
					{
						$("#capche").animate({height:0},500);
					}
				}
			}
		},"json");
}