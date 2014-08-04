

$(document).ready(function(){
	var IgnoreClick = false;
	var IsActive = false;
	var IgnoreClick = false;
	
	$("#signup_form").css({"display":"none"});
	$("#sign_in").on("click",function(){
		if(!IsActive)
		{
			IgnoreClick = true;
			IsActive = true;
			$("#login_form_container").show();
			$("#login_form_container").css({top:"-100%"});
			$("#login_form_container").stop().animate({top:"10%"},500);
		
			$("#overlay").show();
			$("#overlay").css({opacity:0});
			$("#overlay").animate({opacity:1},500);
		}

	});
	
	$("html").on("click",function(e){
		if (!(e.target.id == "#login_form_container" || $(e.target).parents("#login_form_container").size())) { 
			if(IgnoreClick)
			{
				IgnoreClick = false;
			}
			else
			{
				if(IsActive)
				{
					IsActive = false;
					$("#login_form_container").stop().animate({top:"-100%"},500,function(){
						$("#login_form_container").hide();
					});
					
					$("#overlay").animate({opacity:0},500,function(){
						$("#overlay").hide();
					});
				}
			}
		}
	});	
	
	$("#login_form_select").on("click",function(){
		if($("#login_form_select").hasClass("unselected"))
		{
			$("#signup_form_select").addClass("unselected");
			$("#login_form_select").removeClass("unselected");
			
			$("#signup_form").hide();
			$("#login_form").show();
		}
		
	});
	
	$("#signup_form_select").on("click",function(){
		if($("#signup_form_select").hasClass("unselected"))
		{
			$("#signup_form").show();
			$("#login_form").hide();
			
			$("#login_form_select").addClass("unselected");
			$("#signup_form_select").removeClass("unselected");
		}
	});


	$("#user_logout").on("click",function(){
		$.ajax({
			type : 'POST',
			url : SITE_URL + "JsonHandle.php?json-id=AjaxDestroySession",
			dataType : 'json',
		}).done(function(data){
			 location.reload();
		});

	});

	
});


