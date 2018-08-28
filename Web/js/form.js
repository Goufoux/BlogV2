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
				Form.form.removeChild(Form.formCatInput[j]);
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
		var email = Form.createInput('email', 'clEmail', 'clEmail', false, 'col-lg-5 col-md-5 col-sm-5 col-12', 'Adresse E-Mail', true);
		var pass = Form.createInput('password', 'clPass', 'clPass', false, 'col-lg-5 col-md-5 col-sm-5 col-12', 'Password', true);
		var send = Form.createInput('submit', false, false, 'Inscription');
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Inscription';
	},
	
	createFormConnect: function()
	{
		var login = Form.createInput('text', 'uLogin', 'uLogin', false, 'col-lg-5 col-md-5 col-sm-5 col-12', 'Pseudo ou Email', true);
		var pass = Form.createInput('password', 'uPass', 'uPass', false, 'col-lg-5 col-md-5 col-sm-5 col-12', 'Pass', true);
		var send = Form.createInput('submit', false, false, 'Connexion');
		var mdp = Form.createInput('button', 'uMdp', 'uMdp', 'Pass oublié ?', 'sButton');
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Connexion';
	},
	
	createFormComment: function()
	{
		var contenu = Form.createInput('textarea', 'cDesc', 'cDesc', false, 'col-lg-10 col-md-10 col-sm-12 col-12', 'Commentaire', true);
		var send = Form.createInput('button', 'uComment', 'uComment', 'Envoyer', 'col-lg-5 col-md-5 col-sm-5 col-12 sButton', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Commenter !';
	},
	
	createFormNewPass: function()
	{
		var pass = Form.createInput('text', 'uLogin', 'uLogin', false, 'col-lg-10 col-md-10 col-sm-12 col-12', 'Pseudo/E-Mail', true);
		var send = Form.createInput('button', 'unPass', 'unPass', 'Nouveau Pass', 'col-lg-5 col-md-8 col-sm-12 col-12 sButton', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Nouveau Pass';
	},
	
	createFormCBook: function()
	{
		var name = Form.createInput('text', 'bName', 'bName', false, 'col-lg-10 col-md-10 col-sm-12 col-12', 'Nom du Book', true);
		var cat = Form.createCategorie();
		var content = Form.createInput('textarea', 'bContenu', 'bContenu', false, 'col-lg-10 col-md-10 col-sm-12 col-12', 'Description du Book', true);
		var send = Form.createInput('button', 'bCreateBook', 'bCreateBook', 'Créer', 'col-lg-10 col-md-10 col-sm-12 col-12 sButton', false, false);
		Form.title = document.createElement('h3');
		Form.title.id = 'formTitle';
		Form.title.innerHTML = 'Création de Book';
	},
	
	createCategorie: function()
	{
		Form.formCatList = ['Aventure', 'Action', 'Classique', 'Espionnage', 'Fantastique', 'Frisson', 'Terreur', 'Guerre', 'Historique', 'Policier', 'Roman', 'Science-Fiction', 'Thriller', 'Western'];
		for(var i = 0; i < Form.formCatList.length; i++)
		{
			var cat = document.createElement('input');
			cat.type = 'button';
			cat.value = Form.formCatList[i];
			cat.id = 'cat_'+Form.formCatList[i];
			cat.className = 'formCatEl';
			cat.setAttribute('data-sel', '0');
			cat.addEventListener('click', function()
			{
				Form.addCategorie(this);
			});
			Form.formCatInput.push(cat);
		}
	},
	
	newMdp: function()
	{
		Form.launchForm('NewPass');
		Form.showForm();
	},
	
	addCategorie: function(cat)
	{
		var e = cat.getAttribute('data-sel');
		if(e == '1')
			cat.setAttribute('data-sel', '0');
		else
			cat.setAttribute('data-sel', '1');
	},
	
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
		if(type == 'CBook')
		{
			for(var j = 0; j < Form.formCatInput.length; j++)
			{
				Form.form.appendChild(Form.formCatInput[j]);
			}
		}
		for(var i = 0; i < Form.input.length; i++)
		{
			Form.form.appendChild(Form.input[i]);
		}
		Form.form.appendChild(Form.formError);
		Form.launch = true;
	},
	
	showForm: function()
	{
		Form.el.appendChild(Form.form);
		$('.'+Form.form.className).fadeIn('slow');
	}
}