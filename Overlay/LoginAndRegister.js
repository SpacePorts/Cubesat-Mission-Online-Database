var ActiveOverlay;
var IgnoreClick = false;
$(window).load(function()
{
Recaptcha.create("6LdCLOYSAAAAACCP0h1_xlvecf4zahV3xbnwAXZo", 'Capcha_Container', {
              theme: 'red',
            });
});
//"#SignUpContainer"
function ActivateOverlay(OverlayObject)
{
	$("#Overlay").show();
	$("#Overlay").css({opacity:0});
	$("#Overlay").stop().animate({opacity:.5},500);

	IgnoreClick = true;
	$(OverlayObject).show();
	$(OverlayObject).css({top:"-100%"});
	$(OverlayObject).stop().animate({top:"10%"},500);
	ActiveOverlay = OverlayObject;
}

$('html').click(function(e) {
	   if (e.target.id == ActiveOverlay || $(e.target).parents(ActiveOverlay).size()) { 
   
        } else { 
        	if(!IgnoreClick)
        	{
        		$(ActiveOverlay).animate({top:"-100%"},500,function(){
         		$(ActiveOverlay).hide();});

				$("#Overlay").animate({opacity:0},500,function(){	$("#Overlay").hide();});
         	}
           	else
           		IgnoreClick = false;
        }
});




function LoginActive()
{
	$("#SignUpForm").hide();
	$("#LoginForm").show();

	$("#Login").css({background:"#424242"});
	$("#Registration").css({background:"none"});
}
function RegisterActive()
{
	$("#SignUpForm").show();
	$("#LoginForm").hide();

	$("#Registration").css({background:"#424242"});
	$("#Login").css({background:"none"});
}

function LoginAjax()
{
	$.post(SITE_URL + "Overlay/AjaxLogin.php",{
		JUsername:getInput("#LoginUsername"),
		JPassword: getInput("#LoginPassword")},function(data)
	{

		if(data.Warning.length != 0)
		{
			for(x = 0; x < data.Warning.length;x++)
			{
				$(data.Warning[x].MainID + " .WarningContainer").html(""+data.Warning[x].Error);
				$(data.Warning[x].MainID + " .WarningContainer").css({left:-10,opacity:0});
				$(data.Warning[x].MainID + " .WarningContainer").animate({left:5,opacity:1},1000);
			}
		}
		else
		{
			window.location.reload();
		}

	},"json");
}

function RegisterAjax()
{
	$.post(SITE_URL + "Overlay/AjaxRegister.php",{
		JTermsAndCondtions:$("#SignUpAgreeTerms input").is(':checked'),
		JUsername:getInput("#SignUpUsername"),
		JPassword: getInput("#SignUpPassword"),
		JEmail: getInput("#SignUpEmail"),
		JRecapcha: $("#recaptcha_response_field").val(),
		JRecapcheChallange:  $("#recaptcha_challenge_field").val()},function(data)
	{
		if(data.Warning.length != 0)
		{
			for(x = 0; x < data.Warning.length;x++)
			{
				if(data.Warning[x].MainID == "#SignUpCapcha")
				{
					Recaptcha.reload();
				}
				$(data.Warning[x].MainID + " .WarningContainer").html(""+data.Warning[x].Error);
				$(data.Warning[x].MainID + " .WarningContainer").css({left:-10,opacity:0});
				$(data.Warning[x].MainID + " .WarningContainer").animate({left:5,opacity:1},1000);
			
			}
		}
		else
		{
			alert("Registered");
		}

	},"json");
}

function getInput(ID)
{
	return $(ID +" input").val();
}

function logOut()
{
	$.post(SITE_URL + "/Overlay/AjaxDestroySession.php",{},function(data)
	{
 		location.reload();
	});
}