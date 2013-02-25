$(function(){
	$('.clickable').click(function(event)
	{
		if($(this).data('url'))
		{
			url = $(this).data('url');
			
			if(event.which == 1)
			{
				if($(this).hasClass('external'))
				{
					window.open(url);
				}
				else
				{
					window.location = url;
				}
				
				event.stopPropagation();
				return false;
			}
			else if(event.which == 2)
			{
				window.open(url);
				event.stopPropagation();
				return false;
			}
		}
	});
	
	$('.conditional').each(function(element){
		var affected = $(this);
		var target = $("#" + affected.data("conditional-element"));
		var desired = $(this).data("conditional-value");
		
		target.change(function(){
			if($(this).val() == desired)
			{
				affected.show();
			}
			else
			{
				affected.hide();
			}
		});
		
		if(target.val() == desired)
		{
			affected.show();
		}
		else
		{
			affected.hide();
		}
	});
});
