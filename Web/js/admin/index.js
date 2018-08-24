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
	$('article').on('click', '.delUser', function()
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
	
	$('article').on('click', '.modLevelUser', function()
	{
		if(!alreadyMod)
		{
			var e = document.getElementById("dtlU");
			var newLevel = document.createElement('input');
			newLevel.type = 'number';
			newLevel.placeholder = 'Nouveau niveau d\'accès';
			newLevel.min = 0;
			newLevel.max = 3;
			newLevel.required = true;
			newLevel.id = $(this).attr('id');
			newLevel.className = 'iLvl';
			var vd = document.createElement('button');
			vd.className = "vdNewLevel";
			vd.innerHTML = "Valider";
			e.appendChild(newLevel);
			e.appendChild(vd);
			alreadyMod = true;
		}
	});
	
	$('article').on('click', '.vdNewLevel', function()
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