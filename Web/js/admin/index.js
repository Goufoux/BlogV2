window.onload = function()
{
	var el = document.getElementById('bd');
	Form.init(el, false, false);
	
	$(function()
	{
		var alreadyMod = false;
		/* CommentReport */
		$('article').on('click', '.delComment', function()
		{
			var id = $(this).attr('id');
			$.ajax({
				type: "GET",
				url: "../ajax/form.php",
				data: "role=delComment&fData="+id,
				success: function(data)
				{
					if(data == true)
					{
						$('#'+id).parent().hide();
					}
					else
					{
						$('.formError').html(data);
					}
				}
			});
		});
		
		/* ListUser */
		$('div').on('click', '.delUser', function()
		{
			var id = $(this).attr('id');
			$.ajax({
				type: "GET",
				url: "../ajax/form.php",
				data: "role=delUser&fData="+id,
				success: function(data)
				{
					if(data == true)
					{
						window.location = window.location.href;
					}
					else
					{
						$('.formError').html(data);
					}
				}
			});
		});
		
		$('div').on('click', '.modLevelUser', function()
		{
			if(!alreadyMod)
			{
				var id = $(this).parent().attr('id');
				var e = document.getElementById(id);
				var newLevel = document.createElement('input');
				newLevel.type = 'number';
				newLevel.placeholder = 'Nouveau niveau d\'accès';
				newLevel.min = 0;
				newLevel.max = 3;
				newLevel.required = true;
				newLevel.id = $(this).attr('id');
				newLevel.className = 'iLvl form-control col-lg-10 col-12 my-2 mx-auto';
				var vd = document.createElement('input');
				vd.type = 'button';
				vd.className = "vdNewLevel btn btn-success col-lg-4 col-sm-6 my-1 mx-auto";
				vd.value = "Valider";
				vd.style = "display: block;";
				e.appendChild(newLevel);
				e.appendChild(vd);
				alreadyMod = true;
			}
		});
		
		$('div').on('click', '.vdNewLevel', function()
		{
			if($('.iLvl').val() == '0')
				$val = 'aucun';
			else
				$val = $('.iLvl').val();
			var fData = [$('.iLvl').attr('id'), $val];
			$.ajax({
				type: "GET",
				url: "../ajax/form.php",
				data: "role=modLevelUser&fData="+fData,
				success: function(data)
				{
					if(data == true)
					{
						window.location = window.location.href;
					}
					else
					{
						$('.formError').html(data);
					}
				}
			});
		});
	});
}