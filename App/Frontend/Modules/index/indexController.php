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
			showBillet.php
			Affiche le billet d'un Book
			
		*/
		
		public function executeShowBillet(HTTPRequest $HTTPRequest)
		{
			$this->page->addVar('title', 'Genarkys - Billet');
			if($HTTPRequest->getExists('id'))
			{
				if($this->managers->getManagerOf('Billet')->existBillet($HTTPRequest->getData('id')))
				{
					$billet = $this->managers->getManagerOf('Billet')->getBillet('id', $HTTPRequest->getData('id'));
					$listComment = $this->managers->getManagerOf('Comment')->getComment($billet->getId(), 'list');
					$this->page->addVar('billet', $billet);
					/* AJout d'une vue */
					$nb = $billet->getNbVue() + 1;
					$this->managers->getManagerOf('Billet')->addVue($billet->getId(), $nb);
					$this->page->addVar('listComment', $listComment);
				}
				else
				{
					$this->page->addVar('error', $this->managers->getManagerOf('Billet')->getManagerError());
				}
			}
			$this->detectForm($HTTPRequest);
		}
		
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
					$this->hasMsg($HTTPRequest);
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
					$this->detectForm($HTTPRequest);
				}
				else
				{
					$_SESSION['error'] = 'Connectez-vous pour accèder à votre profil.';
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
		}
		
		/*
			write.php
		*/
		
		public function executeWrite(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				$this->page->addVar('title', 'Publier');
				$this->hasMsg($HTTPRequest);
			}
			else
			{
				$_SESSION['error'] = 'Connectez-vous pour pouvoir publier.';
				$this->app->HTTPResponse()->redirect('./?error');
			}
		}
		
		/* writeBillet.php */
		
		public function executeWriteBillet(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				if($HTTPRequest->getExists('id'))
				{
					$bookManager = $this->managers->getManagerOf('Book');
					if($bookManager->existBook($HTTPRequest->getData('id')))
					{
						if($HTTPRequest->postData('bTitle') AND $HTTPRequest->postData('bDesc'))							
						{
							$fData = [htmlspecialchars($HTTPRequest->postData('bTitle')), htmlspecialchars($HTTPRequest->getData('id'))];
							$sData = $HTTPRequest->postData('bDesc');
							$ajaxForm = new AjaxRequest('publish', $fData, $sData);
							if($ajaxForm->run())
							{
								$this->app->HTTPResponse()->redirect('./writeBillet-' . htmlspecialchars($HTTPRequest->getData('id')) . '?success');
							}
							else
							{
								$_SESSION['error'] = $ajaxForm->getAjaxError();
								$this->app->HTTPResponse()->redirect('./writeBillet-' . htmlspecialchars($HTTPRequest->getData('id')) . '?error');
							}
						}
						else
						{
							$this->page->addVar('title', 'Nouveau Billet');
							$this->hasMsg($HTTPRequest);
						}
					}
					else
					{
						$_SESSION['error'] = $bookManager->getManagerError();
						$this->app->HTTPResponse()->redirect('./?error');
					}
					
				}
				else
				{
					$_SESSION['error'] = 'Book inexistant.';
					$this->app->HTTPResponse()->redirect('./?error');
				}
			}
			else
			{
				$_SESSION['error'] = 'Connectez-vous pour pouvoir publier.';
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
		
		/*
			showBook.php
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
					$this->page->addVar('book', $book);
					$this->page->addVar('listBillet', $listBillet);
					$this->hasMsg($HTTPRequest);
					$this->detectForm($HTTPRequest);
				}
				else
				{
					
				}
			}
			else
			{
				
			}
		}
		
		/* ModBook.php */
		
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
							
						}
					}
					else
					{
						
					}
					
				}
				else
				{
					
				}
			}
			else
			{
				
			}
		}
		
		/*
			readBillet.php
		*/
		
		public function executeReadBillet(HTTPRequest $HTTPRequest)
		{
			if($HTTPRequest->getExists('id'))
			{
				if((int) $HTTPRequest->getData('id'))
				{
					$this->page->addVar('title', 'Billet');
					/* Récupération des billets du book */
					$billetManager = $this->managers->getManagerOf('Billet');
					if($billetManager->existBillet($HTTPRequest->getData('id')))
					{
						$billet = $billetManager->getBillet('id', $HTTPRequest->getData('id'));
						$this->page->addVar('billet', $billet);
					}
					else
					{
						$_SESSION['error'] = $billetManager->getManagerError();
						$this->app->HTTPResponse()->redirect('./?error');
					}
				}
				else
				{
					
				}
			}
			else
			{
				
			}
		}
		
		/*
			modUb.php
			
			Modification d'un Billet
		*/
		
		public function executeModUb(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				if($this->app->user()->getCategoryUser() >= 2)
				{
					if($HTTPRequest->getExists('id'))
					{
						$bManager = $this->managers->getManagerOf('Billet');
						if($bManager->existBillet(htmlspecialchars($HTTPRequest->getData('id'))))
						{
							if($HTTPRequest->postExists('bTitle') AND $HTTPRequest->postExists('bDesc'))
							{
								$fData = [htmlspecialchars($HTTPRequest->postData('bTitle')), htmlspecialchars($HTTPRequest->getData('id'))];
								$sData = $HTTPRequest->postData('bDesc');
								$ajaxForm = new AjaxRequest('modifBillet', $fData, $sData);
								if($ajaxForm->run())
								{
									$this->app->HTTPResponse()->redirect('./modUb-' . $HTTPRequest->getData('id') . '?success');
								}
								else
								{
									$_SESSION['error'] = $ajaxForm->getAjaxError();
									$this->app->HTTPResponse()->redirect('./modUb-' . $HTTPRequest->getData('id') . '?error');
								}
							}
							else
							{
								$this->page->addVar('title', 'Blog - Modifier');
								$billet = $bManager->getBillet('id', htmlspecialchars($HTTPRequest->getData('id')));
								$this->page->addVar('billet', $billet);
								$this->hasMsg($HTTPRequest);
							}
						}
						else
						{
							$_SESSION['error'] = $bManager->getManagerError();
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