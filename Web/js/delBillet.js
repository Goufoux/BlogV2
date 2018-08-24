$(function()
{
	$('article').on('click', '.delBillet', function()
	{
		var id = $(this).attr('id');
		$.ajax({
			type: "GET",
			url: "ajax/form.php",
			data: "role=del&fData="+id,
			success: function(data)
			{
				if(data == true)
				{
					$('.error').html();
					$('#'+id).parent().fadeOut('slow');
				}
				else
					$('.error').html(data);
			}
		});
	});
});