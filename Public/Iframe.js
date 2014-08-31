function FormProcess(iframe)
{
	$(iframe).contents().find("input[type=submit]").css("display","none");
	
	$(iframe).contents().find("form").on("DOMSubtreeModified",function(){
			IFrameError(iframe);
	});

	$(iframe).contents().find("input[name='"+$(iframe).attr("title-extract")+"']").on("input",function(){
		$(iframe).parent().parent().find(".panel-heading .title").html($(this).val());
	});
	$(iframe).contents().find("input[name='"+$(iframe).attr("title-extract")+"']").trigger("input");		
}

function IFrameFill(iframe)
{

	$(iframe).height($(iframe).contents().find("#Page").height()+40);
}

function IFrameError(iframe)
{
	if($(iframe).contents().find(".error_label").length !== 0 )//|| IFrameError($(iframe).contents().find("iframe")) === false)
	{
		$(iframe).parent().parent().addClass("panel-danger");
		
		return false;
	}
	else
	{
		$(iframe).parent().parent().removeClass("panel-danger");
		return true;
	}
	
}

$(document).ready(function(){
	
	$("iframe").each(function()
	{
		$(this).load(function(){
			IFrameFill(this);
			FormProcess(this);
		});
	});

	$(window).on("resize",function(){
		$("iframe").each(function()
		{
			IFrameFill(this);
		});

	});


	$(".iframe-list").delegate("input[type=text]","input",function(){
		var lsearchBar = this;
		$.ajax({
			type: 'POST',
			url : $(this).attr("action"),
			data : {"search": $(this).val(),"type" : $(this).attr("search-id")},
			dataType : 'json',
            success : function(data){
            	$(lsearchBar).parent().find("ul li").remove();
            	$.each(data.search,function(index,value){
            	
            		$(lsearchBar).parent().find("ul").append("<li><a href='#' action='" + value.action+ "'>"+ value.name+"</a></li>");
            	});
            }
		});
		return false;
	});

	$(document).on("mouseup",function(e){
		if (!$(".iframe-list").is(e.target) && $(".iframe-list").has(e.target).length === 0) { 
			$(this).find(".dropdown").removeClass("open");
		}
		return false;
	});

	$(".iframe-list").delegate("input[type=text]","focus",function(){
		$(this).parent().addClass("open");
		return false;
	});


	$(".iframe-list").delegate(".dropdown-menu li a","click",function(){
		$(this).parent().parent().parent().parent().parent().find(".dropdown").removeClass("open");
		
		var lpanel = $(this).parent().parent().parent().parent().parent().find(".refrence div").first().clone();
		lpanel.attr("src",$(this).attr("action"));
		lpanel.find("iframe").attr("src",$(this).attr("action"))
		lpanel.find("iframe").load(function(){
			FormProcess(lpanel.find("iframe"));
		});
		$(this).parent().parent().parent().parent().parent().find(".iframe-container").append(lpanel);
		return false;
	});

	$(".iframe-list").delegate(".delete-button","click",function(){
		$(this).parent().parent().remove();
		return false;
	});

	$(".iframe-panel").delegate(".expand-button","click",function(){
		if($(this).parent().parent().find(".panel-body").css("display") ==="block")
		{
			$(this).parent().parent().find(".panel-body").css("display","none");
			$(this).find("span").addClass("glyphicon-plus");
			$(this).find("span").removeClass("glyphicon-minus");
		}
		else
		{
			$(this).parent().parent().find(".panel-body").css("display","block");
			$(this).find("span").addClass("glyphicon-minus");
			$(this).find("span").removeClass("glyphicon-plus");
		}
		IFrameFill($(this).parent().parent().find("iframe"));
		return false;
	});

	$(".iframe-list").delegate(".add-another","click",function(){
		var lpanel = $(this).parent().find(".refrence div").first().clone();
		lpanel.find("iframe").attr("src",$(this).attr("action"))
		lpanel.find("iframe").load(function(){
			FormProcess(lpanel.find("iframe"));
		});
		$(this).parent().find(".iframe-container").append(lpanel);
		return false;
	});


});