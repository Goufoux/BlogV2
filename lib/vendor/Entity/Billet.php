<?php

	namespace Entity;
	
	use \Core\Entity;
	
	class Billet extends Entity
	{
		protected $titre;
		protected $contenu;
		protected $idUtilisateur;
		protected $datePub;
		protected $dateMod;
		protected $pseudo;
		protected $idBook;
		protected $nbLike = [];
		protected $nbVue;
		
		/* Getters */
		
		public function getTitre()
		{
			return $this->titre;
		}
		
		public function getContenu()
		{
			return $this->contenu;
		}
		
		public function getIdUtilisateur()
		{
			return $this->idUtilisateur;
		}
		
		public function getDatePub()
		{
			return $this->datePub;
		}
		
		public function getDateMod()
		{
			return $this->dateMod;
		}
		
		public function getPseudo()
		{
			return $this->pseudo;
		}
		
		public function getidBook()
		{
			return $this->idBook;
		}
		
		public function getNbLike()
		{
			return count(unserialize($this->nbLike));
		}
		
		public function getListLike()
		{
			return unserialize($this->nbLike);
		}
		
		public function getNbVue($module = false)
		{
			if($module)
			{
				if($this->nbVue > 1)
					return $this->nbVue . ' Vues';
				else
					return $this->nbVue . ' Vue';
			}
			else
			{
				return $this->nbVue;
			}
		}
		/* Setters */
		
		public function setTitre($titre)
		{
			$this->titre = $titre;
		}
		
		public function setContenu($contenu)
		{
			$this->contenu = $contenu;
		}
		
		public function setIdUtilisateur($idUtilisateur)
		{
			$this->idUtilisateur = $idUtilisateur;
		}
		
		public function setDatePub($datePub)
		{
			return $this->datePub = $datePub;
		}
		
		public function setDateMod($dateMod)
		{
			return $this->dateMod = $dateMod;
		}
		
		public function setPseudo($pseudo)
		{
			$this->pseudo = $pseudo;
		}
		
		public function setIdBook($idBook)
		{
			$this->idBook = $idBook;
		}
		
		public function setNbLike($like)
		{
			$this->nbLike[] = $like;
		}
		
		public function setNbVue($vue)
		{
			$this->nbVue = $vue;
		}
	}