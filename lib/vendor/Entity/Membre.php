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
		protected $followBook;
		protected $followUser;
		
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
		
		public function getFollowBook($module = false, $search = false)
		{
			if($module)
			{
				if($module == 'unserialize')
				{
					return unserialize($this->followBook);
				}
				if($module == 'already')
				{
					$list = unserialize($this->followBook);
					if(in_array($search, $list))
						return true;
					else
						return false;
				}
			}
			else
				return $this->followBook;
		}
		
		public function getFollowUser($module = false, $search = false)
		{
			if($module)
			{
				if($module == 'unserialize')
				{
					return unserialize($this->followUser);
				}
				if($module == 'already')
				{
					$list = unserialize($this->followUser);
					if(in_array($search, $list))
						return true;
					else
						return false;
				}
			}
			else
				return $this->followBook;
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
		
		public function setFollowBook($followBook)
		{
			$this->followBook = $followBook;
		}
		
		public function setFollowUser($followUser)
		{
			$this->followUser = $followUser;
		}
	}