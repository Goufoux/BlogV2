<?php

	/*
		Genarkys
		Ver 1.0
	*/
	
	namespace Entity;
	
	use \Core\Entity;
	
	class Membre extends Entity
	{
		protected $pseudo;
		protected $pass;
		protected $email;
		protected $dti;
		protected $accessLevel;
		protected $keyEmail;
		protected $categoryUser;
		
		public function getPseudo()
		{
			return $this->pseudo;
		}
		
		public function getPass()
		{
			return $this->pass;
		}
		
		public function getEmail()
		{
			return $this->email;
		}
		
		public function getDti()
		{
			return $this->dti;
		}
		
		public function getAccessLevel()
		{
			return $this->accessLevel;
		}
		
		public function getKeyEmail()
		{
			return $this->keyEmail;
		}
		
		public function getCategoryUser()
		{
			return $this->categoryUser;
		}
		
		/* Setters */
		
		public function setPseudo($pseudo)
		{
			$this->pseudo = $pseudo;
		}
		
		public function setPass($pass)
		{
			$this->pass = $pass;
		}
		
		public function setEmail($email)
		{
			$this->email = $email;
		}
		
		public function setDti($dti)
		{
			$this->dti = $dti;
		}
		
		public function setAccessLevel($accessLevel)
		{
			$this->accessLevel = $accessLevel;
		}
		
		public function setKeyEmail($keyEmail)
		{
			$this->keyEmail = $keyEmail;
		}
		
		public function setCategoryUser($categoryUser)
		{
			$this->categoryUser = $categoryUser;
		}
	}