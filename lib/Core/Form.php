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