<?php

	namespace App\Frontend\Modules\Profil;
	
	use \Core\HTTPRequest;
	use \Core\BackController;
	use \Entity\Comment;
	use \Core\AjaxRequest;
	use \Core\PostRequest;
	
	/*
		Genarkys
		
		Ver 1.0
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
							$listBook = $bookManager->getBook('idUtilisateur', $HTTPRequest->getData('id'));
							$this->page->addVar('title', 'profil de ' . $userView->getPseudo());
							$this->page->addVar('listBook', $listBook);
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
					$followUser = $_SESSION['membre']->getFollowUser();
					$followUser = [];
					for($i = 0; $i < count($_SESSION['membre']->getFollowUser('unserialize')); $i++)
					{
						$followUser []= $this->managers->getManagerOf('User')->getPseudo($_SESSION['membre']->getFollowUser('unserialize')[$i]);
					}
					$this->page->addVar('followUser', $followUser);
					$followBook = [];
					for($i = 0; $i < count($_SESSION['membre']->getFollowBook('unserialize')); $i++)
					{
						$followBook []= $this->managers->getManagerOf('Book')->getName($_SESSION['membre']->getFollowBook('unserialize')[$i]);
					}
					$this->page->addVar('followBook', $followBook);
					/* Récupération de l'historique */
					$userHistory = $this->managers->getManagerOf('User')->getHistory($_SESSION['membre']->getId());
					if($userHistory)
					{
						$history = unserialize($userHistory['history']);
						$titleHistory = [];
						for($i = 0; $i < count($history); $i++)
						{
							$titleHistory[] = $this->managers->getManagerOf('Book')->getName($history[$i]);
						}
						$this->page->addVar('history', $titleHistory);
					}
					else
					{
						$this->page->addVar('history', $this->managers->getManagerOf('User')->getManagerError());
					}
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