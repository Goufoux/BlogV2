<?php
	
	/*
		Genarkys
		
		ver 1.0
		
		La classe FormBillet consacré au formulaire de Billet du blog (Ajout, Modif...)
		
		Liste des champs d'un billet
			- Titre
			- Catégorie
			- Contenu
		
	*/

	namespace Core;
	
	class FormBillet
	{
		const MIN_TITLE = 3;
		const MAX_TITLE = 25;
		const MIN_CONTENU = 10;
		const MAX_CONTENU = 10000;
		
		protected $formError;
		
		/*
			Vérification du champ TITRE 
		*/
		
		public function verifTitle($title)
		{
			if(!empty($title))
			{
				if(is_string($title))
				{
					if(strlen($title) >= FormBillet::MIN_TITLE AND strlen($title) <= FormBillet::MAX_TITLE)
					{
						return true;
					}
					else
					{
						$this->setFormError('Le titre doit être compris entre ' . FormBillet::MIN_TITLE . ' et ' . FormBillet::MAX_TITLE . ' caractères.');
					}
				}
				else
				{
					$this->setFormError('Le titre doit être une chaine de caractères.');
					return false;
				}
			}
			else
			{
				$this->setFormError('Le titre est vide.');
				return false;
			}
		}
		
		/*
			Vérification du champ CATEGORIE
		*/
		
		public function verifCategorie($cat)
		{
			if(!empty($cat))
			{
				$cat = explode(',', $cat);
				for($i = 0; $i < count($cat); $i++)
				{
					if($cat[$i] != 0 AND $cat[$i] != 1)
					{
						$this->setFormError('Une erreur est survenue' . $cat[$i]);
						return false;
					}
				}
				return true;
			}
			else
			{
				$this->setFormError('Catégorie vide.');
				return false;
			}
		}
		
		/*
			Vérification du champ CONTENU
		*/
		
		public function verifContenu($desc)
		{
			if(!empty($desc))
			{
				if(is_string($desc))
				{
					if(strlen($desc) >= FormBillet::MIN_CONTENU AND strlen($desc) <= FormBillet::MAX_CONTENU)
					{
						return true;
					}
					else
					{
						$this->setFormError('Le contenu doit être compris entre ' . FormBillet::MIN_CONTENU . ' et ' . FormBillet::MAX_CONTENU . ' caractères. Caractères envoyés : ' . strlen($desc));
					}
				}
				else
				{
					$this->setFormError('Le contenu doit être une chaine de caractères.');
					return false;
				}
			}
			else
			{
				$this->setFormError('Le contenu est vide.');
				return false;
			}
		}
		
		/* Getters */
		
		public function getFormError()
		{
			return $this->formError;
		}
		
		/* Setters */
		
		public function setFormError($error)
		{
			$this->formError = $error;
		}
		
	}