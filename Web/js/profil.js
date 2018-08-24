$(function()
{
	var el = document.getElementById('formInfo');
	var submit = document.createElement('button');
	submit.type = 'button';
	submit.innerHTML = 'Mettre à jour';
	submit.id = 'majInfo';
	$('#formInfo').on('change', 'input', function()
	{
		el.appendChild(submit);
		$('.error').html('');
	});
	$('#formInfo').on('click', '#majInfo', function()
	{
		$.ajax({
			type: "GET",
			url: "ajax/profil.php",
			data: "role=profil&pseudo="+$('#mPseudo').val()+"&email="+$('#mEmail').val(),
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
					el.removeChild(submit);
					$('.error').html(data);
				}
			}
		});
	});
});