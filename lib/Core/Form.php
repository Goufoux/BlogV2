<?php

	/*
		Genarkys
		
		ver 1.0
		
		Classe Form, qui vérifie les champs de formulaire
	*/
	
	namespace Core;
	
	class Form
	{
		protected $formError;
		
		const PASS_LENGTH_MIN = 8;
		const PSEUDO_LENGTH_MIN = 3;
		const PSEUDO_LENGTH_MAX = 25;
		const COMMENT_MIN = 5;
		const COMMENT_MAX = 100;
		const CATEGORY_NAME_MIN = 3;
		const CATEGORY_NAME_MAX = 30;
		const COMMENT_CAT_MIN = 5;
		const COMMENT_CAT_MAX = 150;
		
		/*
			verifPseudo()
			
				Effectue une vérification d'un champ de type TEXT qui attend un pseudo
		
		*/
		
		public function verifPseudo($pseudo)
		{
			if(is_string($pseudo))
			{
				if(strlen($pseudo) >= Form::PSEUDO_LENGTH_MIN AND strlen($pseudo) <= Form::PSEUDO_LENGTH_MAX)
				{
					if(preg_match("#^[a-zA-Z0-9_]{" . Form::PSEUDO_LENGTH_MIN . "," . Form::PSEUDO_LENGTH_MAX . "}$#", $pseudo))
					{
						return true;
					}
					else
					{
						$this->setFormError('Le pseudo peut contenir des lettres (A,b,c...), des chiffres (1,2,3...) et le caractère \'_\'.');
						return false;
					}
				}
				else
				{
					$this->setFormError('Le pseudo doit être une chaine comprise entre ' . Form::PSEUDO_LENGTH_MIN . ' et ' . Form::PSEUDO_LENGTH_MAX . ' caractères.');
					return false;
				}
			}
			else
			{
				$this->setFormError('Le pseudo doit être une chaine comprise entre ' . Form::PSEUDO_LENGTH_MIN . ' et ' . Form::PSEUDO_LENGTH_MAX . ' caractères.');
				return false;
			}
		}
		
		/*
			verifEmail()
			
			Effectue une vérification d'un champ de type EMAIL
			
		*/
		
		public function verifEmail($email)
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
				return true;
			else
			{
				$this->setFormError('L\'adresse email ' . $email . ' n\'est pas valide.');
				return false;
			}
		}
		
		/*
			isEmail()
		*/
		
		public function isEmail($string)
		{
			if(preg_match("#@#", $string))
				return true;
			else
				return false;
		}
		
		/*
			equalString()
		*/
		
		public function equalString($str1, $str2)
		{
			if($str1 === $str2)
				return true;
			else
				return false;
		}
		
		/*
			verifPass()
			
			Effectue une vérification d'un champ de type PASSWORD
				-> $pass doit être supérieur ou égal à PASS_LENGTH_MIN
		
		*/
		
		public function verifPass($pass)
		{
			if(strlen($pass) >= Form::PASS_LENGTH_MIN)
				return true;
			else
			{
				$this->setFormError('Le pass doit être composé d\'au moins ' . Form::PASS_LENGTH_MIN . ' caractères.');
				return false;
			}
		}
		
		/*
			verifComment()
			
			Effectue une vérification d'un champ de type COMMENTAIRE
				-> $comment doit être compris entre COMMENT_MIN et COMMENT_MAX
		*/
		
		public function verifComment($comment)
		{
			if(!empty($comment))
			{
				if(is_string($comment))
				{
					if(strlen($comment) >= Form::COMMENT_MIN AND strlen($comment) <= Form::COMMENT_MAX)
					{
						return true;
					}
					else
					{
						$this->setFormError('Le commentaire doit être compris entre ' . Form::COMMENT_MIN . ' et ' . Form::COMMENT_MAX . ' caractères.');
						return false;
					}
				}
				else
				{
					$this->setFormError('Le commentaire est invalide.');
					return false;
				}
			}
			else
			{
				$this->setFormError('Aucun commentaire.');
				return false;
			}
		}
		
		/*
			verifNameCategory()
			vérifie la validité d'un nom d'une catégorie
		*/
		
		public function verifNameCategory($nameCat)
		{
			if(!empty($nameCat))
			{
				if(is_string($nameCat))
				{
					if(preg_match("#^[a-zA-Z]{" . Form::CATEGORY_NAME_MIN . "," . Form::CATEGORY_NAME_MAX . "}$#", $nameCat))
					{
						return true;
					}
					else
					{
						if(strlen($nameCat) < Form::CATEGORY_NAME_MIN)
						{
							$this->setFormError('Le nom de la catégorie doit être supérieur ou égal à ' . Form::CATEGORY_NAME_MIN . ' caractères.');
							return false;
						}
						else if(strlen($nameCat) > Form::CATEGORY_NAME_MAX)
						{
							$this->setFormError('Le nom de la catégorie doit être inférieur ou égal à ' . Form::CATEGORY_NAME_MAX . ' caractères.');
							return false;
						}
						else
						{
							$this->setFormError('Le nom de la catégorie ne peut contenir que les lettres comprises entre A et Z. (majuscule et/ou minuscule).');
							return false;
						}
					}
				}
				else
				{
					$this->setFormError('Le nom de catégorie doit être une chaîne de caractère.');
					return false;
				}
			}
			else
			{
				$this->setFormError('Aucun nom.');
				return false;
			}
		}
		
		/* 
			verifCommentCategory()
			vérifie la validité du champ Commentaire du formulaire d'ajout de catégorie
			
			Retourne vrai lors de la première vérification car optionnel
				-> changer true à false pour définir à obligatoire
		*/
		
		public function verifCommentCategory($commentCat)
		{
			if(!empty($commentCat))
			{
				if(is_string($commentCat))
				{
					if(strlen($commentCat) >= Form::COMMENT_CAT_MIN)
					{
						if(strlen($commentCat) <= Form::COMMENT_CAT_MAX)
						{
							return true;
						}
						else
						{
							$this->setFormError('Le commentaire doit contenir ' . Form::COMMENT_CAT_MAX . ' caractères max.');
							return false;
						}
					}
					else
					{
						$this->setFormError('Le commentaire doit contenir au moins ' . Form::COMMENT_CAT_MIN . ' caractères.');
						return false;
					}
				}
				else
				{
					$this->setFormError('Le commentaire doit être une chaîne de commentaire.');
					return false;
				}
			}
			else
			{
				$this->setFormError('Aucun commentaire');
				return true;
			}
		}
		
		/* Getters */
		
		public function getFormError()
		{
			return $this->formError;
		}
		
		/* Setters */
		
		public function setFormError($formError)
		{
			$this->formError = $formError;
		}
		
	}