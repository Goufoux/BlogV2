<?php

	namespace Entity;
	
	use \Core\Entity;
	
	class View extends Entity
	{
		protected $idView;
		protected $tabView;
		
		public function getIdView()
		{
			return $this->idView;
		}
		
		public function getTabView()
		{
			return $this->tabView;
		}
		
		public function setIdView($idView)
		{
			$this->idView = $idView;
		}
		
		public function setTabView($tabView)
		{
			$this->tabView = $tabView;
		}
	}