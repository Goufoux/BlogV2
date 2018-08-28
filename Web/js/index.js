window.onload = function()
{
	var el = document.getElementById('bd');
	Form.init(el, false, false);
	
	$(function()
	{
		/* Form inscription */
		$('nav').on('click', '#clSuscribe', function()
		{
			Form.launchForm('suscribe');
			Form.showForm();
		});
		/* Form connexion */
		$('nav').on('click', '#clConnect', function()
		{
			Form.launchForm('connect');
			Form.showForm();
		});
		/* Form catégorie utilisateur */
		$('article').on('click', '#ucVd', function()
		{
			var e = $('#ucForm input:checked').length;
			if(e > 0)
			{
				$('.ucError').html("");
				var nData = [$('#ucForm #ucRead:checked').val(), $('#ucForm #ucWrite:checked').val(), $('#ucForm #ucNothing:checked').val()];
				$.ajax({
					type: "GET",
					url: "ajax/form.php",
					data: "role=ucForm&fData="+nData,
					success: function(data)
					{
						if(data == true)
						{
							window.location = window.location.href;
						}
						else
						{
							$('.ucError').html(data);
						}
					}
				});
			}
		});
		/* Add Comment */
		$('body').on('click', '#cComment', function()
		{
			Form.launchForm('comment');
			Form.showForm();
		});
		/* Add Like */
		$('body').on('click', '.addLike', function()
		{
			var id = $(this).attr('id');
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=addLike&fData="+id,
				success: function(data)
				{
					if(data == true)
					{
						var nbLike = $(this).text();
						nbLike++;
						$('.nbLike').html(nbLike + ' j\'aime');
					}
				}
			});
		});
		/* Form Commentaire */
		$('body').on('click', '#uComment', function()
		{
			var bId = window.location.href;
			var billet = bId.split("-");
			var fData = [billet[1], $('#cDesc').val()];
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=comment&fData="+fData,
				success: function(data)
				{
					if(data == true)
					{
						Form.deleteForm();
					}
					else
					{
						$('.errorForm').html(data);
					}
				}
			});
		});
		/* Supp Billet */
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
						window.location = './';
					}
					else
						$('.error').html(data);
				}
			});
		});
		
		/* Cookie */
		$('.cookieInfo').on('click', '.cookie', function()
		{
			var id = $(this).attr('id');
			if(id == 'clAcceptCookie')
			{
				var d = new Date();
				d.setTime(d.getTime() + (30*24*60*60*1000));
				var expires = d.toUTCString();
				document.cookie = "cookieAccept=true;" + expires + ";path=/";
				$(this).parent().slideUp();
			}
			else
				document.location = "https://google.com/";
		});
	});
}