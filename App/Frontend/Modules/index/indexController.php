<?php

	namespace App\Frontend\Modules\Index;
	
	use \Core\HTTPRequest;
	use \Core\BackController;
	use \Entity\Comment;
	use \Core\AjaxRequest;
	use \Core\PostRequest;
	
	/*
		Genarkys
		
		Ver 1.0
		
		Gère l'affichage des vues:
			-> index.php
			-> list.php
			-> connect.php
			-> deconnect.php
			-> showBillet.php
			
		17/08/18
			Ajout de méthodes pour gérer l'inscription sur le Blog
	*/
	
	class IndexController extends BackController
	{
		/*
			index.php
			Affiche la liste des books dans l'ordre de dernière publication par défaut
			
		*/
		
		public function executeIndex(HTTPRequest $HTTPRequest)
		{
			$_SESSION['success'] = 'coucou';
			if($HTTPRequest->getExists('bSearch'))
			{
				$bookManager = $this->managers->getManagerOf('Book');
				$listBook = $bookManager->searchBook($HTTPRequest->getData('bSearch'));
				if($listBook)
				{
					$nb = count($listBook);
					$this->page->addVar('title', 'Blog - ' . $nb . ' Résultat');
					$this->page->addVar('listBook', $listBook);
				}
				else
				{
					$_SESSION['error'] = $bookManager->getManagerError();
					$this->app->HTTPResponse()->redirect('?error');
				}
			}
			else
			{
				$nbBillet = $this->app->config()->get('nbBillet');
				$this->page->addVar('title', 'Genarkys - ' . $nbBillet . ' derniers billets');
				$listBook = $this->managers->getManagerOf('Book')->getBook('all', false);
				$this->page->addVar('listBook', $listBook);
				$this->detectForm($HTTPRequest);
				$this->hasMsg($HTTPRequest);
			}
		}
		
		/* 
			deconnect.php 
		*/
		
		public function executeDeconnect(HTTPRequest $HTTPRequest)
		{
			session_destroy();
			$this->app->HTTPResponse()->redirect('.');
		}
		
		/*
		*/
		
		public function hasMsg($request)
		{
			$req = explode('?', $request->requestURI());
			if(count($req) >= 2)
			{
				switch($req[1])
				{
					case 'success':
						if(!empty($_SESSION['success']))
						{
							$this->page->addVar('success', $_SESSION['success']);
							unset($_SESSION['success']);
						}
						else
							$alreadyPrint = true;
							break;
					case 'error':
						if(!empty($_SESSION['error']))
						{
							$this->page->addVar('error', $_SESSION['error']);
							unset($_SESSION['error']);
						}
						else
							$alreadyPrint = true;
							break;
					case 'modifiedCategory':
						if(!empty($_SESSION['modifiedChanged']))
						{
							unset($_SESSION['modifiedChanged']);
							$this->app->HTTPResponse()->redirectClean($request->requestURI());
						}
						else
						{
							$this->page->addVar('emptyCategory', 'true');
							$_SESSION['modifiedChanged'] = true;
						}
						break;
					default:
						break;
				}
			}
			/* Si aucun message, on regarde si l'application a des message à afficher */
			else
			{
				if(!empty($_SESSION['auth']))
				{
					$this->userHasCategory();
				}
			}
		}
		
		public function userHasCategory()
		{
			if(!$this->app->user()->getCategoryUser())
			{
				$this->page->addVar('emptyCategory', 'true');
			}
		}
		
		public function detectForm(HTTPRequest $HTTPRequest)
		{
			/* On regarde si des identifiants sont présents pour l'inscription */
			if($HTTPRequest->getExists('clEmail'))
			{
				if($HTTPRequest->getExists('clPass'))
				{
					// $this->page->addVar('title', $HTTPRequest->requestURI());
					$userManager = $this->managers->getManagerOf('User');
					if($userManager->addUser(htmlspecialchars($HTTPRequest->getData('clEmail')), htmlspecialchars($HTTPRequest->getData('clPass'))))
					{
						$this->app->HTTPResponse()->redirect('./?success');
					}
					else
					{
						$_SESSION['error'] = $userManager->getManagerError();
						$this->app->HTTPResponse()->redirect('./?error');
					}
				}
				else /* Si on ne trouve pas le pass on redirige vers l'index */
				{
					$this->app->HTTPResponse()->redirect('.');
				}
			}
			
			/* On regarde si des identifiants sont transmis pour une connexion */
			if($HTTPRequest->getExists('uLogin') AND $HTTPRequest->getData('uLogin'))
			{
				if($HTTPRequest->getExists('uPass') AND !empty($HTTPRequest->getData('uPass')))
				{
					$uManager = $this->managers->getManagerOf('User');
					if($uManager->userConnect(htmlspecialchars($HTTPRequest->getData('uLogin')), htmlspecialchars($HTTPRequest->getData('uPass'))))
					{
						$this->app->HTTPResponse()->redirectClean($this->app->HTTPRequest()->requestURI(), 'success');
					}
					else
						$this->page->addVar('error', $uManager->getManagerError());
				}
				else
					$this->page->addVar('error', 'Veuillez renseigner votre Pass.');
			}
			
			/* On regarde si des identifiants sont transmis pour la confirmation d'inscription */
			if($HTTPRequest->getExists('email') AND $HTTPRequest->getExists('confirmkey'))
			{
				$uManager = $this->managers->getManagerOf('User');
				if($uManager->userVerifiedEmail(htmlspecialchars($HTTPRequest->getData('email')), htmlspecialchars($HTTPRequest->getData('confirmkey'))))
				{
					$this->app->HTTPResponse()->redirect('./?success');
				}
				else
				{
					$_SESSION['error'] = $uManager->getManagerError();
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
		}
	}