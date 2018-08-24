<?php

	namespace Entity;
	
	use \Core\Entity;
	
	class Book extends Entity
	{
		protected $name;
		protected $datePub;
		protected $dateMod;
		protected $content;
		protected $idUtilisateur;
		protected $categorie = [];
		
		protected $pseudo;
		
		/* Getters */
		
		public function getName()
		{
			return $this->name;
		}
		
		public function getDatePub()
		{
			return $this->datePub;
		}
		
		public function getDateMod()
		{
			return $this->dateMod;
		}
		
		public function getContent()
		{
			return $this->content;
		}
		
		public function getIdUtilisateur()
		{
			return $this->idUtilisateur;
		}
		
		public function getCategorie()
		{
			return $this->categorie;
		}
		
		public function getListCat()
		{
			return unserialize($this->categorie);
		}
		
		public function getPseudo()
		{
			return $this->pseudo;
		}
		
		/* Setters */
		
		public function setName($name)
		{
			$this->name = $name;
		}
		
		public function setDatePub($datePub)
		{
			return $this->datePub = $datePub;
		}
		
		public function setDateMod($dateMod)
		{
			return $this->dateMod = $dateMod;
		}
		
		public function setContent($content)
		{
			$this->content = $content;
		}
		
		public function setIdUtilisateur($idUtilisateur)
		{
			$this->idUtilisateur = $idUtilisateur;
		}
		
		public function setCategorie($categorie)
		{
			$this->categorie = $categorie;
		}
		
		public function setPseudo($pseudo)
		{
			$this->pseudo = $pseudo;
		}
	}