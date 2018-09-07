<?php

	/*
		Genarkys
		
		Ver 1.0
		
		@07/09/2018
		
		
	*/
	
	namespace Entity;
	
	use \Core\Entity;
	
	class History extends Entity
	{
		protected $idUtilisateur;
		protected $typeHistory;
		protected $idHistory;
		protected $name;
		
		public function getIdUtilisateur()
		{
			return $this->idUtilisateur;
		}
		
		public function getTypeHistory()
		{
			return $this->typeHistory;
		}
		
		public function getIdHistory()
		{
			return $this->idHistory;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		/* Setters */
		
		public function setIdUtilisateur($idUtilisateur)
		{
			$this->idUtilisateur = $idUtilisateur;
		}
		
		public function setTypeHistory($typeHistory)
		{
			$this->typeHistory = $typeHistory;
		}
		
		public function setIdHistory($idHistory)
		{
			$this->idHistory = $idHistory;
		}
		
		public function setName($name)
		{
			$this->name = $name;
		}
	}