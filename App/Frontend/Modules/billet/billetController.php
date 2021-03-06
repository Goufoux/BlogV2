<?php

	namespace App\Frontend\Modules\Billet;
	
	use \Core\HTTPRequest;
	use \Core\BackController;
	use \Entity\Comment;
	use \Core\AjaxRequest;
	use \Core\PostRequest;
	
	/*
		Genarkys
		
		Ver 1.0
	*/
	
	class BilletController extends BackController
	{
		
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
					if($this->app->user()->isAuthentificated())
					{
						$historyManager = $this->managers->getManagerOf('History');
						$historyManager->setType('historyBillet');
						$historyManager->setIdData($HTTPRequest->getData('id'));
						$historyManager->setIdUser($_SESSION['membre']->getId());
						$historyManager->executeAddHistory();
					}
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
			addBillet.php 
		*/
		
		public function executeAddBillet(HTTPRequest $HTTPRequest)
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
								$this->app->HTTPResponse()->redirect('./addBillet-' . htmlspecialchars($HTTPRequest->getData('id')) . '?success');
							}
							else
							{
								$_SESSION['error'] = $ajaxForm->getAjaxError();
								$this->app->HTTPResponse()->redirect('./addBillet-' . htmlspecialchars($HTTPRequest->getData('id')) . '?error');
								$this->hasMsg($HTTPRequest);
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