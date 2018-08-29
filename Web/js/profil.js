$(function()
{
	$('body').on('click', '.uModInfo', function()
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
	/* modPass */
	$('body').on('click', '.uModPass', function()
	{
		var fData = [$('#mOldPass').val(), $('#mNewPass').val(), $('#mConfirmPass').val()];
		$.ajax({
			type: "GET",
			url: "ajax/form.php",
			data: "role=modPass&fData="+fData,
			success: function(data)
			{
				if(data == true)
				{
					$('#mOldPass, #mNewPass, #mConfirmPass').val('');
					$('.error').html("Votre Pass a été modifié !").fadeIn().delay(2000).slideUp();
				}
				else
				{
					$('.error').html(data);
				}
			}
		});
	});
});