/*
	Genarkys
	Ver 1.3
	
	L'objet Form est un objet qui construit un formulaire
	
	1.2
		-> Utilisation de l'objet alert de bootstrap dans le css pour gérer les options d'ouverture et fermeture
		-> Ajout de createFormConnect()
		-> Ajout de deleteForm()
		
	1.3
		-> Ajout d'un formulaire pour l'insertion de commentaire
*/

var Form = {
	
	el: null,
	form: null,
	cat: null,
	input: [],
	title: null,
	imgClose: null,
	launch: false,
	formError: null,
	formCatList: null,
	formCatInput: [],
	launcher: 'createForm',
	
	/** 
		Init()
			el -> Élément parent de Form
			form -> Array
				p1 -> method de l'élément form, default GET
				p2 -> url de l'élément form , default #
	**/
	
	init: function(el, form = false, autoLaunch = true)
	{
		Form.el = el;
		/* Création de la base du formulaire */
		if(form)
			Form.createBaseForm(form[0], form[1]);
		else
			Form.createBaseForm();
		if(autoLaunch)
			Form.launchForm();
	},
	
	/**
		CreateBaseForm()
			Construit les balises <form> </form>
			
			
	**/
	
	createBaseForm: function(method = 'GET', url = '#')
	{
		Form.form = document.createElement('form');
		Form.form.className = 'formGenerator alert alert-dark';
		Form.id = 'formGenerator';
		Form.form.method = method;
		Form.form.action = url;
		
		/* Span qui contiendra les erreurs */
		Form.formError = document.createElement('span');
		Form.formError.className = 'error errorForm';
		Form.formError.id = 'errorForm';
		
		/* Croix pour fermer */
		Form.imgClose = document.createElement('button');
		Form.imgClose.type = 'button';
		Form.imgClose.className = 'close';
		Form.imgClose.setAttribute('data-dismiss', 'alert');
		Form.imgClose.setAttribute('aria-label', 'Close');
		
		var span = document.createElement('span');
		span.setAttribute('aria-hidden', 'true');
		span.innerHTML = '&times;';
		
		Form.imgClose.appendChild(span);
		
		/*
			Catégorie 
		*/
		Form.cat = document.createElement('article');
		Form.cat.id = 'formCat';
		
		
		
		Form.imgClose.addEventListener('click', Form.deleteForm, false);
	},
	
	deleteForm: function()
	{
		for(var i =0; i < Form.input.length; i++)
		{
			Form.form.removeChild(Form.input[i]);
		}
		if(Form.formCatInput.length > 0)
		{
			for(var j = 0; j < Form.formCatInput.length; j++)
			{
				Form.cat.removeChild(Form.formCatInput[j]);
			}
		}
		Form.el.removeChild(Form.form);
		Form.form.removeChild(Form.imgClose);
		Form.form.removeChild(Form.title);
		Form.input = [];
		Form.title = null;
		Form.launch = false;
		Form.formCatInput = [];
	},
	
	/**
		createInput()
			Construction des input contenu dans le formulaire
	**/
	
	createInput: function(type, id, name = false, value = false, className = false, placeholder = false, required = false)
	{
		if(type == 'textarea')
			var input = document.createElement('textarea');
		else
			var input = document.createElement('input');
		/* Input Submit */
		if(type == 'submit')
		{
			input.type = type;
			input.id = id;
			if(value)
				input.value = value;
			if(className)
				input.className = className;
			
		}
		else
		{
			input.type = type;
			input.id = id;
			if(name)
				input.name = name;
			if(value)
				input.value = value;
			if(className)
				input.className = className;
			if(placeholder)
				input.placeholder = placeholder;
			if(required)
				input.required = required;
		}
		
		if(input.id == 'uMdp')
		{
			input.addEventListener('click', function()
			{
				Form.newMdp();
			});
		}
		Form.input.push(input);
	},
	
	/**
		createFormSuscribe()
			Construit un formulaire d'inscription avec les champs email et pass
	**/
	
	createFormSuscribe: function()
	{
		var email = Form.createInput('email', 'clEmail', 'clEmail', false, 'col-lg-5 col-md-5 col-sm-5 col-12 form-control', 'Adresse E-Mail', true);
		var pass = Form.createInput('password', 'clPass', 'clPass', false, 'col-lg-5 col-md-5 col-sm-5 col-12 form-control', 'Password', true);
		var send = Form.createInput('submit', false, false, 'Inscription', 'btn btn-primary');
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Inscription';
	},
	
	/*
		Launcher formulaire de connexion
	*/
	
	createFormConnect: function()
	{
		var login = Form.createInput('text', 'uLogin', 'uLogin', false, 'col-lg-5 col-md-5 col-sm-5 col-12 form-control', 'Pseudo ou Email', true);
		var pass = Form.createInput('password', 'uPass', 'uPass', false, 'col-lg-5 col-md-5 col-sm-5 col-12 form-control', 'Pass', true);
		var send = Form.createInput('submit', false, false, 'Connexion', 'btn btn-primary');
		var mdp = Form.createInput('button', 'uMdp', 'uMdp', 'Pass oublié ?', 'sButton btn btn-info');
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Connexion';
	},
	
	/*
		Launcher formulaire commentaire
	*/
	
	createFormComment: function()
	{
		var contenu = Form.createInput('textarea', 'cDesc', 'cDesc', false, 'col-lg-10 col-md-10 col-sm-12 col-12 form-control', 'Commentaire', true);
		var send = Form.createInput('button', 'uComment', 'uComment', 'Envoyer', 'btn col-lg-5 col-md-5 col-sm-5 col-12 sButton btn-primary', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Commenter !';
	},
	
	/*
		Launcher formulaire nouveau pass 
	*/
	
	createFormNewPass: function()
	{
		var pass = Form.createInput('text', 'uLogin', 'uLogin', false, 'col-lg-10 col-md-10 col-sm-12 col-12 form-control', 'Pseudo/E-Mail', true);
		var send = Form.createInput('button', 'unPass', 'unPass', 'Nouveau Pass', 'btn col-lg-5 col-md-8 col-sm-12 col-12 sButton btn-primary', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Nouveau Pass';
	},
	
	/*
		Launcher formulaire création de catégorie
	*/
	
	createFormAddCategory: function()
	{
		var name = Form.createInput('text', 'catName', 'catName', false, 'col-12 form-control', 'Nom', true);
		var comment = Form.createInput('textarea', 'catDesc', 'catDesc', false, 'col-12 form-control', 'Commentaire', true);
		var send = Form.createInput('button', 'addCat', 'addCat', 'Créer', 'btn col-12 btn-primary', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Nouvelle Catégorie';
	},
	
	/*
		Launcher du formulaire de création de book
	*/
	
	createFormCBook: function()
	{
		var name = Form.createInput('text', 'bName', 'bName', false, 'col-lg-10 col-md-10 col-sm-12 col-12 form-control', 'Nom du Book', true);
		var content = Form.createInput('textarea', 'bContenu', 'bContenu', false, 'col-lg-10 col-md-10 col-sm-12 col-12 form-control', 'Description du Book', true);
		var send = Form.createInput('button', 'bCreateBook', 'bCreateBook', 'Créer', 'btn col-lg-10 col-md-10 col-sm-12 col-12 sButton btn btn-primary', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Création de Book';
	},
	
	/*
		Récupération des catégories à la création d'un Book
	*/
		
	createCategorie: function()
	{
		ajaxGet.init('ajax/json/category.json', function(response)
		{
			var category = JSON.parse(response);
			for(var i = 0; i < category.length; i++)
			{
				var cat = document.createElement('input');
				cat.type = 'button';
				cat.value = category[i].name;
				cat.title = category[i].comment;
				cat.id = 'cat_'+category[i].id;
				cat.className = 'formCatEl btn';
				cat.setAttribute('data-sel', '0');
				cat.addEventListener('click', function()
				{
					Form.addCategorie(this);
				});
				Form.formCatInput.push(cat);
				Form.cat.appendChild(cat);
			}
		});
	},
	
	/*
		Launcher du formulaire de nouveau mot de passe 
	*/
	
	newMdp: function()
	{
		Form.launchForm('NewPass');
		Form.showForm();
	},
	
	/*
		Observateur declenchée au clique sur une catégorie, l'active si elle est désactivée et inversement
	*/
	
	addCategorie: function(cat)
	{
		console.log("Element ajouté:");
		var e = cat.getAttribute('data-sel');
		if(e == '1')
			cat.setAttribute('data-sel', '0');
		else
			cat.setAttribute('data-sel', '1');
	},
	
	/*
		Identifie les catégories cochées au moment de la création d'un Book
	*/
	
	recupCategorie: function()
	{
		var list = [];
		for(var i = 0; i < Form.formCatInput.length; i++)
		{
			var e = Form.formCatInput[i].getAttribute('data-sel');
			if(e == '1')
			{
				var nameC = Form.formCatInput[i].id.split("_");
				list.push(nameC[1]);
			}
		}
		return list;
	},
	
	/**
		launchForm()
			Ajoute l'élément à la page
	**/
	
	launchForm: function(type)
	{
		if(Form.launch)
			Form.deleteForm();
		
		var code = "Form."+Form.launcher+type+"()";
		var e = new Function(code);
		e();
		
		Form.form.appendChild(Form.imgClose);
		Form.form.appendChild(Form.title);
		Form.form.appendChild(Form.cat);
		
		for(var i = 0; i < Form.input.length; i++)
		{
			Form.form.appendChild(Form.input[i]);
		}
		Form.form.appendChild(Form.formError);
		Form.launch = true;
	},
	
	/*
		Fait apparaître le formulaire
	*/
	
	showForm: function()
	{
		Form.el.appendChild(Form.form);
		$('.'+Form.form.className).fadeIn('slow');
	}
}