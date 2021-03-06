window.onload = function()
{
	
	var el = document.getElementById('bd');
	Form.init(el, false, false);
	
	$(function()
	{
		/* Form inscription */
		$('nav').on('click', '#clSuscribe', function()
		{
			Form.launchForm('Suscribe');
			Form.showForm();
		});
		/* Form connexion */
		$('nav').on('click', '#clConnect', function()
		{
			Form.launchForm('Connect');
			Form.showForm();
		});
		/* Form catégorie utilisateur */
		$('article').on('click', '#ucVd', function()
		{
			var e = $('#ucForm input:checked').length;
			if(e > 0)
			{
				$('.ucError').html("");
				var nData = [$('#ucForm #ucRead:checked').val(), $('#ucForm #ucWrite:checked').val()];
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
			Form.launchForm('Comment');
			Form.showForm();
		});
		/* Follow Book */
		$('.possAct').on('click', '.folBook', function()
		{
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=followBook&fData="+$(this).val(),
				success: function(data)
				{
					if(data == true)
						$('.error').html("Abonnement ajouté").fadeIn().delay(2000).slideUp();
					else
						$('.error').html(data);
				}
			});
		});
		$('.possAct').on('click', '.unfolBook', function()
		{
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=unfollowBook&fData="+$(this).val(),
				success: function(data)
				{
					if(data == true)
						$('.error').html("Abonnement supprimé").fadeIn().delay(2000).slideUp();
					else
						$('.error').html(data);
				}
			});
		});
		/* Follow User */
		$('.possAct').on('click', '.folUser', function()
		{
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=followUser&fData="+$(this).val(),
				success: function(data)
				{
					if(data == true)
						$('.error').html("Abonnement ajoutée").fadeIn().delay(2000).slideUp();
					else
						$('.error').html(data);
				}
			});
		});
		$('.possAct').on('click', '.unfolUser', function()
		{
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=unfollowUser&fData="+$(this).val(),
				success: function(data)
				{
					if(data == true)
						$('.error').html("Abonnement supprimé").fadeIn().delay(2000).slideUp();
					else
						$('.error').html(data);
				}
			});
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
						$('.nbLike').html('<i class="far fa-thumbs-up" title="Nombre de J\'aime"></i> ' + nbLike + ' j\'aime');
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
		/* Comment report */
		$('body').on('click', '.btSignaler', function()
		{
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=reportComment&fData="+$(this).val(),
				success: function(data)
				{
					if(data == true)
					{
						$('.error').html('Commentaire signalé. Merci');
						$('.error').show().delay(500).fadeOut();
					}
					else
					{
						$('.error').html(data);
					}
				}
			});
		});
		
		/* New Pass */
		$('body').on('click', '#unPass', function()
		{
			$.ajax({
				type: "GET",
				url: "ajax/form.php",
				data: "role=newPass&fData="+$('#uLogin').val(),
				success: function(data)
				{
					if(data == true)
					{
						var url = window.location.href;
						url = url.split("?");
						window.location = url[0]+'?success';
					}
					else
					{
						$('.error').html(data);
					}
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
				$('.cookieInfo').slideUp();
			}
			else
				document.location = "https://google.com/";
		});
	});
}