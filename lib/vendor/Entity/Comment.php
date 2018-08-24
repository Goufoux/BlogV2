<?php

	namespace Entity;
	
	use \Core\Entity;
	
	class Comment extends Entity
	{
		protected $idUtilisateur;
		protected $pseudo;
		protected $contenu;
		protected $datePub;
		protected $idBillet;
		protected $report;
		
		public function getIdUtilisateur()
		{
			return $this->idUtilisateur;
		}
		
		public function getPseudo()
		{
			return $this->pseudo;
		}
		
		public function getContenu()
		{
			return $this->contenu;
		}
		
		public function getDatePub()
		{
			return $this->datePub;
		}
		
		public function getIdBillet()
		{
			return $this->idBillet;
		}
		
		public function getReport()
		{
			return $this->report;
		}
		
		/* Setters */
		
		public function setIdUtilisateur($idUtilisateur)
		{
			$this->idUtilisateur = $idUtilisateur;
		}
		
		public function setName($pseudo)
		{
			$this->pseudo = $pseudo;
		}
		
		public function setContenu($contenu)
		{
			$this->contenu = $contenu;
		}
		
		public function setIdBillet($id)
		{
			$this->idBillet = $id;
		}
	}