<?php

	namespace App\Backend\Modules\Index;
	
	use \Core\HTTPRequest;
	use \Core\BackController;
	use \Entity\Billet;
	
	/*
	 * author: Genarkys
	 *
	 * La class IndexController pour le Backend
	*/
	
	class IndexController extends BackController
	{
		public function executeIndex(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				if($this->app->user()->getUserLevel() >= 2)
				{
					$this->page->addVar('title', 'Blog - Admin');
					/* On regarde si des commentaires sont signalés, si c'est le cas on les affiche */
					$cManager = $this->managers->getManagerOf('Comment')->getCommentReport();
					$this->page->addVar('report', $cManager);
					
					/* On regarde si des données sont présentes dans l'url */
					if($HTTPRequest->getExists('delComment') AND !empty($HTTPRequest->getData('delComment'))) // Demande de suppression d'un commentaire //
					{
						$e = $this->managers->getManagerOf('Comment')->delComment($HTTPRequest->getData('delComment'));
						if($e)
						{
							$this->app->HTTPResponse()->redirect('/openclassroom/Blog/Web/admin/');
						}
						else // L'id est invalid
						{
							$this->page->addVar('error', 'Id invalid.');
						}
					}
					if($HTTPRequest->getExists('delCommentAndReport') AND !empty($HTTPRequest->getData('delCommentAndReport')))
					{
						$tab = [$HTTPRequest->getData('delCommentAndReport'), $HTTPRequest->getData('email')];
						$e = $this->managers->getManagerOf('Comment')->delComment($tab, true);
						if($e)
						{
							$this->app->HTTPResponse()->redirect('/openclassroom/Blog/Web/admin/');
						}
						else
							$this->page->addVar('error', $e);
					}
				}
				else
				{
					$this->app->HTTPResponse()->redirect('../');
				}
			}
			else
			{
				$this->app->HTTPResponse()->redirect('../');
			}
		}
		
		/* commentReport */
		
		public function executeCommentReport(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				if($this->app->user()->getUserLevel() >= 2)
				{
					$this->page->addVar('title', 'Admin - Commentaires');
					$commentManager = $this->managers->getManagerOf('Comment');
					$listComment = $commentManager->getCommentReport();
					$this->page->addVar('report', $listComment);
				}
				else
				{
					$this->app->HTTPResponse()->redirect('../');
				}
			}
			else
			{
				$this->app->HTTPResponse()->redirect('../');
			}
		}
	
		/* listUser */
		
		public function executeListUser(HTTPRequest $HTTPRequest)
		{
			if($this->app->user()->isAuthentificated())
			{
				if($this->app->user()->getUserLevel() >= 3)
				{
					$userManager = $this->managers->getManagerOf('User');
					$listUser = $userManager->getUser('list');
					$this->page->addVar('title', 'Liste des Utilisateurs');
					$this->page->addVar('listUser', $listUser);
				}
				else
				{
					$this->app->HTTPResponse()->redirect('../');
				}
			}
			else
			{
				$this->app->HTTPResponse()->redirect('../');
			}
		}
	}