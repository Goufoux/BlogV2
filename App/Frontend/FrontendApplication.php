<?php
	/*
		Genarkys
		ver 1.0
		Obtient le controlleur grâce à la méthode parente et renvoie la réponse
	*/
	
	namespace App\Frontend;

	use \Core\Application;

	class FrontendApplication extends Application
	{
		public function __construct()
		{
			parent::__construct();

			$this->name = 'Frontend';
		}

		public function run()
		{
			$controller = $this->getController();
			$controller->execute();

			$this->HTTPResponse->setPage($controller->page());

			$this->HTTPResponse->send();
		}
	}