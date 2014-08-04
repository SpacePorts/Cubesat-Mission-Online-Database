
{

	function ItemSelectSearch(itemselection,url,data)
	{
		$.ajax({
			type : 'POST',
			url : url,
			data :  data,
			dataType : 'json',
			}).done(function(data){
				$(itemselection).find(".unselected ul").empty();
				$.each(data.parts,function(index,value){
					var lisValueFound = false;
					var count = $(itemselection).find(".selected ul li").length;
					if($(itemselection).find(".selected ul li").length === 0)
						$(itemselection).find(".unselected ul").append("<li><a href='#'' item-id='"+value.id+"'>"+value.value+"</a></li>")
					else
					$(itemselection).find(".selected ul li").each(function(index){

						if(parseInt($(this).find("a").attr("item-id")) === parseInt(value.id))
						{
							lisValueFound = true;
						}
						if(($(itemselection).find(".selected ul li").length -1) === index && lisValueFound === false)
							$(itemselection).find(".unselected ul").append("<li><a href='#'' item-id='"+value.id+"'>"+value.value+"</a></li>")
					});
			});
		});
	}


	$(document).ready(function(){ 
		$(".item_selection").each(function(){
			//$(this).find(".search")
			var litemSelection = this;
			ItemSelectSearch(litemSelection,$(this).find(".item_selection_search").attr("ajax-search"), {"search":$(this).find(".item_selection_search").val()});
			$(this).find(".item_selection_search").on("input",function(){
				ItemSelectSearch(litemSelection,$(this).attr("ajax-search"), {"search":$(this).val()});
			});


			$(this).find(".unselected ul").delegate("li","click",function(){
				$(litemSelection).find(".selected ul").append(this);
				$(this).append("<input type='hidden' name='"+$(litemSelection).find(".item_selection_search").attr("identifier")+"[]' value='"+$(this).find("a").attr("item-id")+"'/>")
				return false;
			});

			$(this).find(".selected ul").delegate("li","click",function(){
				$(this).remove();
				ItemSelectSearch(litemSelection,$(litemSelection).find(".item_selection_search").attr("ajax-search"),{"search":$(litemSelection).find(".item_selection_search").val()});
				return false;
			});
		});
	});
}