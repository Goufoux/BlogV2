<?php

	namespace Core;
	
	use Core\PDOFactory;
	use Core\Managers;
	use Core\FormBillet;
	use Core\Form;
	
	use Entity\Billet;
	use Entity\Book;
	
	class AjaxRequest
	{
		protected $role;
		protected $data = [];
		protected $ajaxError;
		protected $sData = []; // Données spéciales
		
		public function __construct($role, $data = false, $sData = false)
		{
			$this->setRole($role);
			if($data)
				$this->setData($data);
			if($sData)
				$this->setSdata($sData);
		}
		
		public function run()
		{
			$role = 'execute'.ucfirst($this->role);
			if(is_callable([$this, $role]))
			{
				return $this->$role($this->data);
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue. {Code : 103}');
				return false;
			}
		}
		
		/*
			Création d'un Book 
		*/
		
		public function executeCreateBook($data)
		{
			$formBook = new FormBillet();
			$val = explode(',', $data[0]);
			if($formBook->verifTitle($val[0]))
			{
				if($formBook->verifContenu($val[1]))
				{
					if(!empty($this->sData))
					{
						if(!$formBook->verifCategorie($this->sData))
						{
							$this->setAjaxError($formBook->getFormError());
							return false;
						}
					}
					else
					{
						$this->sData = 'Aucune';
					}
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$bManager = $managers->getManagerOf('Book');
					$book = new Book([
						'name' => $val[0],
						'content' => $val[1],
						'idUtilisateur' => $_SESSION['membre']->getId(),
						'categorie' => $this->sData
					]);
					return $bManager->addBook($book);
				}
				else
				{
					$this->setAjaxError($formBook->getFormError());
					return false;
				}
			}
			else
			{
				$this->setAjaxError($formBook->getFormError());
				return false;
			}
		}
		
		/*
			Modification d'un billet 
		*/
		
		public function executeModifBillet($data)
		{
			$val = [$data[0][0], $data[0][1]];
			$formBillet = new FormBillet();
			if($formBillet->verifTitle($val[0]))
			{
				if($formBillet->verifContenu(htmlspecialchars($this->getSdata())))
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$bManager = $managers->getManagerOf('Billet');
					$billet = new Billet([
						'id' => $val[1],
						'titre' => $val[0],
						'contenu' => $this->getSdata(),
						'idUtilisateur' => $_SESSION['membre']->getId()
					]);
					return $bManager->updBillet($billet);
					// $this->setAjaxError($billet);
					// return false;
				}
				else
				{
					$this->setAjaxError($formBillet->getFormError());
					return false;
				}
			}
			else
			{
				$this->setAjaxError($formBillet->getFormError());
				return false;
			}
		}
		
		/*
			Rafraîchit les commentaires
		*/
		
		public function executeRefreshComment($data)
		{
			$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
			$cManager = $managers->getManagerOf('Comment');
			return $cManager->getComment('list', $data[0]);
		}
		
		/*
		
			Ajout d'un commentaire 
			$data = [idBillet, commentContent];
		*/
		
		public function executeComment($data)
		{
			$data = explode(',', $data[0]);
			if(!empty($data[0]) AND (int)$data[0])
			{
				$form = new Form();
				if($form->verifComment(htmlspecialchars($data[1])))
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$cManager = $managers->getManagerOf('Comment');
					return $cManager->addComment($data);
				}
				else
				{
					$this->setAjaxError($form->getFormError());
					return false;
				}
			}
			else
			{
				$this->setAjaxError($data);
				return false;
			}
		}
		
		
		/*
			Ajoute un like au billet
		*/
		
		public function executeAddLike($data)
		{
			if(!empty($data))
			{
				$id = explode("_", $data[0]);
				$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
				$billetManager = $managers->getManagerOf('Billet');
				if($billetManager->existBillet($id[1]))
				{
					return $billetManager->addLike($id[1]);
				}
				else
				{
					$this->setAjaxError($billetManager->getManagerError());
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue');
				return false;
			}
		}
		
		/*
			Suppression d'un Billet
			$data = id
		*/
		
		public function executeDel($data)
		{
			if(!empty($data))
			{
				$id = explode('_', $data[0]);
				if(!empty($id[1]))
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$bManager = $managers->getManagerOf('Billet');
					if($bManager->existBillet($id[1]))
					{
						return $bManager->delBillet($id[1]);
					}
					else
					{
						$this->setAjaxError($bManager->getManagerError());
						return false;
					}
				}
				else
				{
					$this->setAjaxError('Une erreur est survenue. {Code : 105}');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue. {Code : 104}');
				return false;
			}
			
		}
		
		/*
			Formulaire d'ajout de Billet
			$data = [title, idBook];
			$this->sData = [contenu]:
		*/
		
		public function executePublish($data)
		{
			$val = [$data[0][0], $data[0][1]];
			$formBillet = new FormBillet();
			if($formBillet->verifTitle($val[0]))
			{
				if($formBillet->verifContenu($this->getSdata()))
				{
					if(!empty($val[1]) AND (int) $val[1])
					{
						$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
						$bookManager = $managers->getManagerOf('Book');
						if($bookManager->existBook($val[1]))
						{
							$billetManager = $managers->getManagerOf('Billet');
							$billet = new Billet([
								'titre' => $val[0],
								'contenu' => $this->sData,
								'idUtilisateur' => $_SESSION['membre']->getId(),
								'idBook' => $val[1]
							]);
							return $billetManager->addBillet($billet);
						}
						else
						{
							$this->setAjaxError($bookManager->getManagerError());
							return false;
						}
					}
					else
					{
						$this->setAjaxError('Une erreur est survenue. {Code : 109}');
						return false;
					}
				}
				else
				{
					$this->setAjaxError($formBillet->getFormError());
					return false;
				}
			}
			else
			{
				$this->setAjaxError($formBillet->getFormError());
				return false;
			}
		}
		
		/*
			Suppression Book 
		*/
		
		public function executeDelBook($data)
		{
			if(!empty($data))
			{
				$id = explode('_', $data[0]);
				if(!empty($id[1]))
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$bookManager = $managers->getManagerOf('Book');
					if($bookManager->existBook($id[1]))
					{
						return $bookManager->delBook($id[1]);
					}
					else
					{
						$this->setAjaxError($bookManager->getManagerError());
						return false;
					}
				}
				else
				{
					$this->setAjaxError('Une erreur est survenue. {Code : 105}');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue. {Code : 104}');
				return false;
			}
			
		}
		
		/* 
			Formulaire modification Info partie profil 
			$data = [nData, vData];
		*/
		
		public function executeProfil($data)
		{
			if(!empty($data))
			{
				$nData = $data[0][0];
				$vData = $data[0][1];
				$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
				$uManager = $managers->getManagerOf('User');
				/* Regarde si le pseudo existe pas */
				if(!$uManager->existLogin($vData[0]))
				{
					return $uManager->majUser($nData, $vData);
				}
				else
				{
					$this->setAjaxError('Le pseudo est déjà utilisé');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue. {Code : 100}');
				return false;
			}
		}
		
		/* Formulaire de catégorie */
		
		public function executeUcForm($data)
		{
			if(!empty($data))
			{
				$val = explode(',', $data[0]);
				$x = 0;
				for($i = 0; $i < count($val); $i++)
				{
					if(!empty($val[$i]))
						$x += $val[$i];
				}
				if($x >= 1 OR $x <= 3)
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$uManager = $managers->getManagerOf('User');
					$nData = ['categoryUser'];
					$vData = [$x];
					return $uManager->majUser($nData, $vData);
				}
				else
				{
					$this->setAjaxError('Une erreur est survenue. {Code : 101}');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Veuillez choisir une option...');
				return false;
			}
		}
		
		/*
			Suppression d'un commentaire
			CommentReport
		*/
		
		public function executeDelComment($data)
		{
			if(!empty($data))
			{
				$data = explode("_", $data[0]);
				if((int) $data[1])
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$commentManager = $managers->getManagerOf('Comment');
					return $commentManager->delComment($data[1]);
				}
				else
				{
					$this->setAjaxError('Une erreur est survenue.');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue.');
				return false;
			}
		}
		
		/* Suppression d'un Utilisateur ADMIN */
		
		public function executeDelUser($data)
		{
			if(!empty($data))
			{
				$data = explode("_", $data[0]);
				if((int) $data[1])
				{
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$userManager = $managers->getManagerOf('User');
					return $userManager->delUser($data[1]);
				}
				else
				{
					$this->setAjaxError('Une erreur est survenue');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue');
				return false;
			}
		}
		
		/* Modification du niveau d'accès de l'utilisateur ADMIN */
		
		public function executeModLevelUser($data)
		{
			if(!empty($data))
			{
				$val = explode(",", $data[0]);
				$id = explode("_", $val[0]);
				if((int) $id[1])
				{
					if(!empty($val[1]))
					{
						if($val[1] >= 0 AND $val[1] <= 3 OR $val[1] == 'aucun')
						{
							$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
							$userManager = $managers->getManagerOf('User');
							return $userManager->updAccessLevel($id[1], $val[1]);
						}
						else
						{
							$this->setAjaxError('Le niveau d\'accès est invalide. Il doit être compris entre 0 et 3');
							return false;
						}
					}
					else
					{
						$this->setAjaxError('Veuillez renseigner le nouveau niveau d\'accès.');
						return false;
					}
				}
				else
				{
					$this->setAjaxError('Une erreur est survenue.');
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue.');
				return false;
			}
		}
		
		/* Getters */
		
		public function getRole()
		{
			return $this->role;
		}
		
		public function getData()
		{
			return $this->data;
		}
		
		public function getAjaxError()
		{
			return $this->ajaxError;
		}
		
		public function getSdata()
		{
			return $this->sData;
		}
		
		/* Setters */
		
		public function setRole($role)
		{
			if(is_string($role))
			{
				$this->role = $role;
			}
			else
			{
				return false;
			}
		}
		
		public function setData($data)
		{
			$this->data []= $data;
		}
		
		public function setAjaxError($error)
		{
			$this->ajaxError = $error;
		}
		
		public function setSdata($sData)
		{
			$this->sData = $sData;
		}
	}