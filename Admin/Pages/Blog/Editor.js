var MetaData;

function AddHiddenInput(Key,Value)
{
	$("#meta-option-container").append("<input type='hidden' name='MetaKey[]' value='"+Key+"' ></input>");
	$("#meta-option-container").append("<input id='"+Key+"' value='"+Value+"' type='hidden' name='"+Key+"' ></input>");
}
function AddTextInput(Key,Listed)
{
	$("#meta-option-container").append(Listed);
	$("#meta-option-container").append("<input type='hidden' name='MetaKey[]' value='"+Key+"' ></input>");
	$("#meta-option-container").append("<div><input id='"+Key+"' type='text' name='"+Key+"' ></input></div>");
}

function AddCheckBox(Key,Value,Listed)
{	
	$("#meta-option-container").append(Listed);
	$("#meta-option-container").append("<input type='hidden' name='MetaKey[]' value='"+Key+"' ></input>");
	$("#meta-option-container").append("<div><input id='"+Key+"' value='"+Value+"' type='checkbox' name='"+Key+"' ></input></div>");
}
function AddTextList(Key,Listed)
{
	$("#meta-option-container").append(Listed);
	$("#meta-option-container").append("<input type='hidden' name='MetaKey[]' value='"+Key+"' ></input>");
	$("#meta-option-container").append("<div id='list-container-"+Key+"'>");

	$("#meta-option-container").append("</div>");
	$("#meta-option-container").append("<a href='#' id='list-container-add-"+Key+"'>add</a>")

	$("#list-container-add-"+Key).bind("click",{ID:("#list-container-"+Key),Key:Key},function(event){
		$(event.data.ID).append("<input  name='"+event.data.Key+"[]'></input></br>");
	});

}
function MetaProcessing(Type)
{
	switch(Type)
	{
		case "Default":
		break;

		case "Theme":
			AddHiddenInput("Type","Theme");
			AddHiddenInput("RightBar","Remove");

			AddTextInput("Git","git");
			AddTextInput("Download","Download");

			AddTextList("SlideImage","Slide Image");
		break;
	}
}

$(window).load(function(){

	MetaData =jQuery.parseJSON( $("#category-data").text());

	for(var x = 0; x < MetaData.length; x++)
	{
		if(MetaData[x].key == "Type")
		{
			$("#meta-option-select").val(MetaData[x].value);
			MetaProcessing(MetaData[x].value);
		}
	}

	for(var x = 0; x < MetaData.length; x++)
	{
		if( typeof MetaData[x].value === 'string' ) {
			$("#" +MetaData[x].key).val(MetaData[x].value);
		}
		else
		{
			for(var y = 0; y < MetaData[x].value.length; y++)
			{
				$("#list-container-"+MetaData[x].key).append("<input value='"+MetaData[x].value[y]+"'  name='"+MetaData[x].key+"[]'></input></br>");
			}
		}
	}

	$("#meta-option-select").change(function(){
		$("#meta-option-container").empty();
		MetaProcessing($("#meta-option-select").val());
	});
});
