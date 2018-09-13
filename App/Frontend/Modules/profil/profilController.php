<?php

	namespace App\Frontend\Modules\Profil;
	
	use \Core\HTTPRequest;
	use \Core\BackController;
	use \Entity\Comment;
	use \Core\AjaxRequest;
	use \Core\PostRequest;
	
	/*
		Genarkys
		
		Ver 1.1
		
		@Ajout des l'affichage de l'historique des billets et des books
			-> Mise à jour de executeProfil
		
		
	*/
	
	class ProfilController extends BackController
	{
		/*
			profil.php
			Affiche le profil de l'utilisateur ou un profil d'un membre si un id est donnée 
		*/
		
		public function executeProfil(HTTPRequest $HTTPRequest)
		{
			if($HTTPRequest->getExists('id'))
			{
				$userManager = $this->managers->getManagerOf('User');
				if($userManager->existId(htmlspecialchars($HTTPRequest->getData('id'))))
				{
					$userView = $userManager->printUser(htmlspecialchars($HTTPRequest->getData('id')));
					if($userView)
					{
						$this->page->addVar('userView', $userView); // Info de l'utilisateur
						if($userView->getCategoryUser() >= 2)
						{
							$bookManager = $this->managers->getManagerOf('Book');
							if($HTTPRequest->getExists('pageUserBook') AND (int) $HTTPRequest->getData('pageUserBook'))
								$listBook = $bookManager->getBook('idUtilisateur', $HTTPRequest->getData('id'), 6, $HTTPRequest->getData('pageUserBook'));
							else
								$listBook = $bookManager->getBook('idUtilisateur', $HTTPRequest->getData('id'), 6);
							$this->page->addVar('title', 'profil de ' . $userView->getPseudo());
							$this->page->addVar('listBook', $listBook);
							$this->page->addVar('pagUserBook', $bookManager->getNumberPage());
							$this->detectForm($HTTPRequest);
							$this->hasMsg($HTTPRequest);
						}
					}
					else
					{
						$_SESSION['error'] = $userManager->getManagerError();
						$this->app->HTTPResponse()->redirect('./?error');
					}
				}
				else
				{
					$_SESSION['error'] = 'Cet utilisateur n\'existe pas. (Ou plus)';
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
			else
			{
				if($this->app->user()->isAuthentificated())
				{
					$this->page->addVar('title', 'Profil - ' . $_SESSION['membre']->getPseudo());
					$this->page->addVar('userMod', true);
					$historyManager = $this->managers->getManagerOf('History');
					/* Récupération des abonnements au book */
					$historyManager->setType('book');
					if($HTTPRequest->getExists('pageFolBook') AND (int) $HTTPRequest->getData('pageFolBook'))
						$bookFollow = $historyManager->executePrint($HTTPRequest->getData('pageFolBook'));
					else
						$bookFollow = $historyManager->executePrint();
					if($bookFollow)
					{
						for($i = 0; $i < count($bookFollow); $i++)
						{
							if($x = $this->managers->getManagerOf('Book')->getName($bookFollow[$i]->getIdHistory())['name'])
							{
								$bookFollow[$i]->setName($x);
							}
							else
							{
								$historyManager->setIdData($bookFollow[$i]->getIdHistory());
								$historyManager->setidUser($_SESSION['membre']->getId());
								$historyManager->executeUnfollow();
								unset($bookFollow[$i]);
							}
						}
					}
					$this->page->addVar('bookFollow', $bookFollow);
					$this->page->addVar('pagFolBook', $historyManager->getNb());
					
					/* Récupération abonnements aux utilisateurs */
					
					$historyManager->setType('user');
					if($HTTPRequest->getExists('pageFolUser') AND (int) $HTTPRequest->getData('pageFolUser'))
						$userFollow = $historyManager->executePrint($HTTPRequest->getData('pageFolUser'));
					else
						$userFollow = $historyManager->executePrint();
					if($userFollow)
					{
						for($i = 0; $i < count($userFollow); $i++)
						{
							if($x = $this->managers->getManagerOf('User')->getPseudo($userFollow[$i]->getIdHistory())['pseudo'])
							{
								$userFollow[$i]->setName($x);
							}
							else
							{
								$historyManager->setIdData($userFollow[$i]->getIdHistory());
								$historyManager->setidUser($_SESSION['membre']->getId());
								$historyManager->executeUnfollow();
								unset($userFollow[$i]);
							}
						}
					}
					$this->page->addVar('userFollow', $userFollow);
					$this->page->addVar('pagFolUser', $historyManager->getNb());
					/* Récupération historique book */
					
					$historyManager->setType('historyBook');
					if($HTTPRequest->getExists('pageHisBook') AND (int) $HTTPRequest->getData('pageHisBook'))
						$historyBook = $historyManager->executePrint($HTTPRequest->getData('pageHisBook'));
					else
						$historyBook = $historyManager->executePrint();
					if($historyBook)
					{
						for($i = 0; $i < count($historyBook); $i++)
						{
							if($x = $this->managers->getManagerOf('Book')->getName($historyBook[$i]->getIdHistory())['name'])
							{
								$historyBook[$i]->setName($x);
							}
							else
							{
								$historyManager->setIdData($historyBook[$i]->getIdHistory());
								$historyManager->setidUser($_SESSION['membre']->getId());
								$historyManager->executeUnfollow();
								unset($historyBook[$i]);
							}
						}
					}
					$this->page->addVar('historyBook', $historyBook);
					$this->page->addVar('pagHisBook', $historyManager->getNb());
					
					/* Récupération historique billets */
					
					$historyManager->setType('historyBillet');
					if($HTTPRequest->getExists('pageHisBillet') AND (int) $HTTPRequest->getData('pageHisBillet'))
						$historyBillet = $historyManager->executePrint($HTTPRequest->getData('pageHisBillet'));
					else
						$historyBillet = $historyManager->executePrint();
					if($historyBillet)
					{
						for($i = 0; $i < count($historyBillet); $i++)
						{
							if($x = $this->managers->getManagerOf('Billet')->getTitre($historyBillet[$i]->getIdHistory()))
							{
								$historyBillet[$i]->setName($x);
							}
							else
							{
								$historyManager->setIdData($historyBillet[$i]->getIdHistory());
								$historyManager->setidUser($_SESSION['membre']->getId());
								$historyManager->executeUnfollow();
								unset($historyBillet[$i]);
							}
						}
					}
					$this->page->addVar('historyBillet', $historyBillet);
					$this->page->addVar('pagHisBillet', $historyManager->getNb());
					$this->detectForm($HTTPRequest);
					$this->hasMsg($HTTPRequest);
				}
				else
				{
					$_SESSION['error'] = 'Connectez-vous pour accèder à votre profil.';
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
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