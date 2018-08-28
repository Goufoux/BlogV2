<?php

	/*
		Genarkys
		ver 1.0
		Gestion des données renvoyées au client
	*/

	namespace Core;
	
	class HTTPResponse extends ApplicationComponent
	{
		/* Ajoute un header */
		
		public function addHeader($header)
		{
			header($header);
		}
		
		/* Redirection */
		
		public function redirect($loc)
		{
			header('Location: '.$loc);
			exit;
		}
		
		/* Redirection vers la page courante en supprimant les variables de l'url */
		
		public function redirectClean($url, $module = false)
		{
			$lUrl = explode('/', $url);
			$url = end($lUrl);
			$lUrl = explode('?', $url);
			if($module)
				$this->redirect('./'.$lUrl[0].'?'.$module);
			else
				$this->redirect('./'.$lUrl[0]);
		}
		
		/* Redirection si la page demandée n'existe pas */
		
		public function redirect404()
		{
			$this->page = new Page($this->app);
			$this->page->setContentFile(__DIR__.'/../../Errors/404.html');
			
			$this->addHeader('HTTP/1.0 404 Not Found');
			
			$this->send();
		}
		
		/* Génére la page */
		
		public function send()
		{
			exit($this->page->getGeneratedPage());
		}
		
		/* Assigne la page */
		
		public function setPage(Page $page)
		{
			$this->page = $page;
		}
	}