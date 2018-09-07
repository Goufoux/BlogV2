<?php
	/*
		Genarkys
		ver 1.0
		Gère les différents managers et permettra aux classes filles d'y accéder 
		
		@07/09/18
			-> Ajout de l'attribut parameter, qui contiendra les éventuelles paramètres d'un manager
			-> Modification du constructeur
			-> 
	*/
	namespace Core;
	
	class Managers
	{
		protected $api = null;
		protected $dao = null;
		protected $parameter = false;
		protected $managers = [];
		
		public function __construct($api, $dao, $parameter = false)
		{
			$this->api = $api;
			$this->dao = $dao;
			$this->parameter = $parameter;
		}
		
		public function getApi()
		{
			return $this->api;
		}
		
		public function getDAO()
		{
			return $this->dao;
		}
		
		public function getManagerOf($module)
		{
			if(!is_string($module) || empty($module))
			{
				throw new \InvalidArgumentException('Le module spécifié est invalide.');
			}
			if(!isset($this->managers[$module]))
			{
				$manager = '\\Model\\'.$module.'Manager'.$this->api;
				
				$this->managers[$module] = new $manager($this->dao, $this->parameter);
			}
			
			return $this->managers[$module];
		}
	}