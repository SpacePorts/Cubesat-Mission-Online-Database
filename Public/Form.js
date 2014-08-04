
function MainFormProcess(form,formData,formUrl,fail)
{
		$.ajax({
			type : 'POST',
			url : formUrl,
			data : formData ,
			dataType : 'json',
			cache: false,
            contentType: false,
            processData: false,
            success : function(data){


			if(data.error.length === 0)
			{
				if(typeof data.redirect === 'undefined')
				{
						location.reload();
				}
				else
				{
						window.location = data.redirect;
				}
				
			}
			else
			{
				var SelectedLabels ="";
				$.each(data.error,function(index,value){
					if(index !== 0)
						SelectedLabels += ",";

					SelectedLabels += "label[label_id="+value.label_id+"] .error_label";
					
					if(form.find("label[label_id="+value.label_id+"] .error_label").length)
					{
						form.find("label[label_id="+value.label_id+"] .error_label").html(value.message);
					}
					else
					{
						if(form.find("label[label_id="+value.label_id+"] .error_label").html() !== value.message)
						{
							form.find("label[label_id="+value.label_id+"] .error_label").remove();
							form.find("label[label_id="+value.label_id+"]").append("<span class='error_label'>"+value.message+"</span>");
						}
					}
					
					//strip extra error messages and process post
					if((data.error.length-1) === index)
					{
						form.find("label .error_label:not("+SelectedLabels+")").each(function(){
							$(this).remove();
						});
						
						if(form.find("label[label_id=capcha] .error_label").length && form.find("#recaptcha_widget_div").length)
						{
							Recaptcha.reload();
						}
					}
				});
				fail();
			}

			return false;
		}});
}


function AjaxFormHandling(form)
{
	parent.$(form).on("submit",function(event){

		event.preventDefault();
		//prevents the form from processing action
		var forms_processed = 0;
		var lform = this;		
		var lformAction = $(this).attr("action");
		var lformData = new FormData(this);
		var lsubmitted= false;
		var lnumberOfIframes = $(lform).find("iframe").length;

		$(lform).find("iframe").each(function(){

				$(this).contents().find("form").submit();

				var lblockRemove = false;
				$(this).load(function(){
					lnumberOfIframes--;

					$(this).parent().find("input[name='" + $(this).attr("extract") + "[]']").remove();
					$(this).parent().append("<input type='hidden' name='"+$(this).attr("extract")+"[]' value='" + $(this).contents().find("input[name=" + $(this).attr("extract") + "]").val() + "'/>");
					
					$(this).attr("srcs",this.contentWindow.location);
					
					if(lblockRemove === false)
						$(this).contents().find("form").remove();

					if(lnumberOfIframes === 0 && lsubmitted === false)
					{
						lblockRemove = true;
						lsubmitted = true;
						lformData = new FormData(lform);
						MainFormProcess($(lform),lformData,lformAction,function(){
							$(lform).find("iframe").each(function(){
								$(this).attr("src",$(this).attr("srcs"));

							});
						});
					}
				});
		});
		if(lnumberOfIframes == 0)
			MainFormProcess($(lform),lformData,lformAction,function(){
							
			});
		
	});
}



$(document).ready(function(){
	$(".default_form_process").each(function(){
		AjaxFormHandling(this);
	});
});





