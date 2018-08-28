$(function()
{
	var el = document.getElementById('bd');
	Form.init(el, false, false);
	
	
	$('#bd').on('click', '#bCreate', function()
	{
		Form.launchForm('CBook');
		Form.showForm();
	});
	
	$('#bd').on('click', '#bCreateBook', function()
	{
		var fData = [$('#bName').val(), $('#bContenu').val()];
		var sData = Form.recupCategorie();
		$.ajax({
			type: "GET",
			url: "ajax/form.php",
			data: "role=createBook&fData="+fData+"&sData="+sData,
			success: function(data)
			{
				if(data == true)
				{
					var url = document.location.href;
					var nUrl = url.split('?');
					window.location = nUrl[0]+'?success';
				}
				else
				{
					$('.error').html(data);
				}
			}
		});
	});
	
	/* Suppresion Book */
	$('article').on('click', '.delBook', function()
	{
		var id = $(this).attr('id');
		$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=delBook&fData="+id,
				success: function(data)
				{
					if(data == true)
					{
						$('.error').html();
						$(this).parent().parent().hide();
					}
					else
						$('.error').html(data);
				}
			});
	});
	
});