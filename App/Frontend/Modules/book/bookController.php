<?php

	namespace App\Frontend\Modules\Book;
	
	use \Core\HTTPRequest;
	use \Core\BackController;
	use \Entity\Comment;
	use \Core\AjaxRequest;
	use \Core\PostRequest;
	
	class BookController extends BackController
	{
		
		/* 
			ModBook.php 
		*/
		
		public function executeModBook(HTTPRequest $HTTPRequest)
		{
			if($HTTPRequest->getExists('id'))
			{
				if((int) $HTTPRequest->getData('id'))
				{
					if($this->app->user()->isAuthentificated())
					{
						/* Récupération du book */
						$bManager = $this->managers->getManagerOf('Book');
						$book = $bManager->getBook('id', $HTTPRequest->getData('id'));
						
						if($_SESSION['membre']->getId() == $book->getIdUtilisateur())
						{
							$this->page->addVar('title', 'Blog - Book Modif');
							if($HTTPRequest->postExists('bTitle') AND $HTTPRequest->postExists('bDesc'))
							{
								$fData = [htmlspecialchars($HTTPRequest->postData('bTitle')), htmlspecialchars($HTTPRequest->getData('id'))];
								$sData = $HTTPRequest->postData('bDesc');
								$postForm = new PostRequest('modifBook', $fData, $sData);
								if($postForm->run())
								{
									$this->app->HTTPResponse()->redirect('./modBook-' . $HTTPRequest->getData('id') . '?success');
								}
								else
								{
									$_SESSION['error'] = $postForm->getPostError();
									$this->app->HTTPResponse()->redirect('./modBook-' . $HTTPRequest->getData('id') . '?error');
								}
							}
							else
							{
								$this->page->addVar('book', $book);
								$this->hasMsg($HTTPRequest);
							}
						}
						else
						{
							$_SESSION['error'] = 'Une erreur est survenue.';
							$this->app->HTTPResponse()->redirect('./?error');
						}
					}
					else
					{
						$_SESSION['error'] = 'Connectez-vous pour accèder à cette page.';
						$this->app->HTTPResponse()->redirect('./?error');
					}
				}
				else
				{
					$_SESSION['error'] = 'Une erreur est survenue.';
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
			else
			{
				$_SESSION['error'] = 'Une erreur est survenue.';
				$this->app->HTTPResponse()->redirect('./?error');
			}
		}
		
		/*
			showBook.php
			Affiche le book sélectionné
		*/
		
		public function executeShowBook(HTTPRequest $HTTPRequest)
		{
			if($HTTPRequest->getExists('id'))
			{
				if((int) $HTTPRequest->getData('id'))
				{
					$this->page->addVar('title', 'Blog');
					/* Récupération du book */
					$bManager = $this->managers->getManagerOf('Book');
					$book = $bManager->getBook('id', $HTTPRequest->getData('id'));
					/* Récupération des billets du book */
					$billetManager = $this->managers->getManagerOf('Billet');
					$listBillet = $billetManager->getBillet('idBook', $book->getId());
					/* Ajout d'une vue */
					$addView = $bManager->addView($HTTPRequest->getData('id'), $book->getNbVue());
					/* Si l'utilisateur est connecté on ajoute le book à l'historique */
					if($this->app->user()->isAuthentificated())
					{
						$userManager = $this->managers->getManagerOf('User');
						$addBookHistory = $userManager->updateHistory($_SESSION['membre']->getId(), htmlspecialchars($HTTPRequest->getData('id')));
					}
					if($book)
					{
						$this->page->addVar('book', $book);
					}
					else
					{
						$this->page->addVar('book', $bManager->getManagerError());
					}
					$this->page->addVar('listBillet', $listBillet);
					$this->hasMsg($HTTPRequest);
					$this->detectForm($HTTPRequest);
				}
				else
				{
					$_SESSION['error'] = 'Une erreur est survenue. Ce book est introuvable.';
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
			else
			{
				$_SESSION['error'] = 'Une erreur est survenue.';
				$this->app->HTTPResponse()->redirect('./?error');
			}
		}
		
		/*
			readUb.php
			Accès aux différents book de l'utilisateur 
		*/
		
		public function executeReadUb(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				if($this->app->user()->getCategoryUser() >= 2)
				{
					$this->page->addVar('title', 'Book');
					$bManager = $this->managers->getManagerOf('Book');
					$listBillet = $bManager->getBook('idUtilisateur', $_SESSION['membre']->getId());
					$this->page->addVar('list', $listBillet);
					$this->hasMsg($HTTPRequest);
				}
				else
				{
					$_SESSION['error'] = 'Modifier vos données d\'utilisateur si vous souhaitez publier du contenu.<br />Cela débloquera du contenu prévu à cet effet.';
					$this->app->HTTPResponse()->redirect('./profil?error');
				}
			}
			else
			{
				$_SESSION['error'] = 'Connectez-vous pour accèder à vos données.';
				$this->app->HTTPResponse()->redirect('./?error');
			}
		}
		
		/***********************************************
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