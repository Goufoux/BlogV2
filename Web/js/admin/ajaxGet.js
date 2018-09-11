/*
	AjaxGet
*/
var ajaxGet = {
	
	req: null, // Objet XMLHttpRequest
	method: null, // Méthode utilisée GET, POST
	callback: null, // Valeur de retour 
	
	init: function(url, callback, method = "GET")
	{
		ajaxGet.method = method;
		ajaxGet.callback = callback;
		ajaxGet.req = new XMLHttpRequest();
		ajaxGet.req.open(ajaxGet.method, url);
		
		/* Ajout des observateurs de chargement et d'erreur */
		ajaxGet.req.addEventListener('load', ajaxGet.load);
		ajaxGet.req.addEventListener('error', ajaxGet.error, false);
		
		ajaxGet.req.send(null);
	},
	
	/* Appelé quand la connexion est établie */
	
	load: function()
	{
		if(ajaxGet.req.readyState == 4 && (ajaxGet.req.status == 200 || ajaxGet.req.status == 0))
		{
			ajaxGet.callback(ajaxGet.req.responseText);
		}
		else
		{
			ajaxGet.error();
		}
	},
	
	/* Appelé si une erreur est detecté */
	
	error: function()
	{
		switch(ajaxGet.req.status)
		{
			case 0:
				console.log("Une erreur est survenue. Status : " + ajaxGet.req.status);
					break;
			default:
				alert("Une erreur est survenue. Status : " + ajaxGet.req.status);
		}
	}
}
