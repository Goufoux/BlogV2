window.onload = function()
{
	$(function()
	{
		var el = document.getElementById('bd');
		Form.init(el, false, false);
		
		$('nav').on('click', '.ajouterCategory', function()
		{	
			Form.launchForm('AddCategory');
			Form.showForm();
		});
		
		/* Ajout catégorie */
		
		$('body').on('click', '#addCat', function()
		{
			var fData = $('#catName').val();
			var sData = $('#catDesc').val();
			$.ajax({
				type: "GET",
				url: "../ajax/admin/formAdmin.php",
				data: "role=addCat&fData="+fData+"&sData="+sData,
				success: function(data)
				{
					if(data == true)
						window.location = window.location.href+'?success';
					else
						$('.errorForm').html(data);
				}
			});
		});
		
		/* Mise à jour fichier JSON */
		
		$('body').on('click', '.createJSONCat', function()
		{
			$.ajax({
				type: "GET",
				url: "../ajax/admin/formAdmin.php",
				data: "role=createJSONFileCategory",
				success: function(data)
				{
					if(data == true)
					{
						window.location = window.location.href+'?success';
					}
					else
						$('.error').html(data);
				}
			});
		});
		
	});
}