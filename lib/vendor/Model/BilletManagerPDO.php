<?php
	
	/*
		Genarkys
		
		Ver 1.1
		
			-> Mise à jour des méthodes pour la V2
	*/
	
	namespace Model;
	
	use \Entity\Billet;
	use \Core\MyError;
	
	class BilletManagerPDO extends BilletManager
	{
		protected $managerError = '';
		
		/*
			getBillet()
			
			$cat = all | auteur | id | book;
			$data = false | auteurName | idBillet | idBook;
		*/
		
		public function addLike($id)
		{
			if(!empty($id))
			{
				$billet = $this->getBillet('id', $id);
				$listLike = $billet->getListLike();
				if(!empty($listLike))
				{
					if(!in_array($_SESSION['membre']->getId(), $listLike))
					{
						$listLike[] = $_SESSION['membre']->getId();
						$req = $this->dao->prepare('UPDATE billet SET nbLike = :nbLike WHERE id = :id');
						$req->bindValue(':nbLike', serialize($listLike), \PDO::PARAM_STR);
						$req->bindValue(':id', $id, \PDO::PARAM_INT);
						$req->execute();
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					$listLike[] = $_SESSION['membre']->getId();
					$req = $this->dao->prepare('UPDATE billet SET nbLike = :nbLike WHERE id = :id');
					$req->bindValue(':nbLike', serialize($listLike), \PDO::PARAM_STR);
					$req->bindValue(':id', $id, \PDO::PARAM_INT);
					$req->execute();
					return true;
				}
			}
			else
			{
				$this->setManagerError('Une erreur est survenue');
				return false;
			}
		}
		
		/*
			Ajoute une vue sur un billet
			$id = id du billet
			$nb = nouveau compteur
		*/
		
		public function addVue($id, $nb)
		{
			try
			{
				if($this->existBillet($id))
				{
					if((int) $nb)
					{
						$req = $this->dao->prepare('UPDATE billet SET nbVue = :nbVue WHERE id = :id');
						$req->bindValue(':nbVue', $nb, \PDO::PARAM_INT);
						$req->bindValue(':id', $id, \PDO::PARAM_INT);
						$req->execute();
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			catch(MyError $e)
			{
				$this->setManagerError($e->getMessage());
				return false;
			}
		}
		
		/*
			Récupère un billet ou plusieurs billet en fonction du paramètre choisi
			$cat = 
					all: récupère tous les billets 
					idUtilisateur: Récupère les billets en fonction de l'idUtilisateur
					id: Récupère le billet correspondant à l'id
					idBook: Récupère tous les billets correspondant à l'idBook
			$data = $id en fonction de $cat
		*/
		
		public function getBillet($cat, $data = false)
		{
			if(is_string($cat))
			{
				$sql = '';
				switch($cat)
				{
					case 'all':	
						$sql = 'SELECT * FROM billet ORDER BY datePub DESC';
							break;
					case 'idUtilisateur':
						if(!empty($data))
						{
							$sql = 'SELECT b.*, u.pseudo AS pseudo FROM utilisateur u INNER JOIN billet b ON b.idUtilisateur = u.id WHERE b.idUtilisateur = :idUtilisateur ORDER BY datePub DESC';
						}
						else
						{
							$this->setManagerError('Une erreur est survenue.');
							return false;
						}
						break;
					case 'id':
						if(!empty($data))
						{
							$sql = 'SELECT b.*, u.pseudo AS pseudo FROM utilisateur u INNER JOIN billet b ON b.idUtilisateur = u.id WHERE b.id = :id';
						}
						else
						{
							$this->setManagerError('Une erreur est survenue.');
							return false;
						}
						break;
					case 'idBook':
						if(!empty($data))
						{
							$sql = 'SELECT bI.*, bO.id AS idBook FROM book bO INNER JOIN billet bI ON bI.idBook = bO.id WHERE bO.id = :idBook';
						}
						else
						{
							$this->setManagerError('Une erreur est survenue.');
							return false;
						}
						break;
					default:
						break;
				}
				if($cat == 'all')
				{
					$req = $this->dao->query($sql);
					$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Billet');
					$req->execute();
					if($rs = $req->fetchAll())
					{
						return $rs;
					}
					else
					{
						$this->setManagerError('Une erreur est survenue.');
						return false;
					}
				}
				else
				{
					$req = $this->dao->prepare($sql);
					$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Billet');
					$req->bindValue(':'.$cat, $data);
					$req->execute();
					if($cat == 'id')
					{
						if($rs = $req->fetch())
						{
							return $rs;
						}
						else
						{
							$this->setManagerError('Aucune publication.');
							return false;
						}
					}
					else
					{
						if($rs = $req->fetchAll())
						{
							return $rs;
						}
						else
						{
							$this->setManagerError('Aucune publication.');
							return false;
						}
					}
				}
			}
			else
			{
				$this->setManagerError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			existBillet()
			
			id = id du billet 
		*/
		
		public function existBillet($id)
		{
			if((int)$id)
			{
				$req = $this->dao->prepare('SELECT id FROM billet WHERE id = :id');
				$req->bindValue(':id', $id, \PDO::PARAM_INT);
				$req->execute();
				if($rs = $req->fetch())
					return true;
				else
				{
					$this->setManagerError('Le billet est introuvable.<br />Il a peut-être était déplacé ou supprimé !');
					return false;
				}
			}
			else
			{
				$this->setManagerError('L\'id est invalide.');
				return false;
			}
		}
		
		/*
			Supprime un billet
			$id = id du billet à supprimé
				-> Supprime les commentaires associés
		*/
		
		public function delBillet($id)
		{
			if($this->existBillet($id))
			{
				$req = $this->dao->prepare('DELETE FROM billet WHERE id = :id');
				$req->bindValue(':id', $id, \PDO::PARAM_INT);
				$req->execute();
				return true;
			}
			else
			{
				$this->setManagerError('Une erreur est survenue. {Code : 200}');
				return false;
			}
			
			$cmt = $this->dao->prepare('DELETE FROM comment WHERE idAttach = :idAttach');
			$cmt->bindValue(':idAttach', $id, \PDO::PARAM_INT);
			$cmt->execute();
		}
		
		/*
			Mise à jour des champs d'un billet
			$billet = Billet()
		*/
		
		public function updBillet(Billet $billet)
		{
			$req = $this->dao->prepare('UPDATE billet SET titre = :titre, contenu = :contenu, dateMod = :dateMod WHERE id = :id');
			$req->bindValue(':titre', $billet->getTitre(), \PDO::PARAM_STR);
			$req->bindValue(':contenu', $billet->getContenu(), \PDO::PARAM_STR);
			$req->bindValue(':dateMod', time(), \PDO::PARAM_STR);
			$req->bindValue(':id', $billet->getId(), \PDO::PARAM_INT);
			$req->execute();
			$_SESSION['success'] = 'Le billet a bien été modifié !';
			return true;
		}
		
		/*
			Ajoute un nouveau billet dans le bdd 
		*/
		
		public function addBillet(Billet $billet)
		{
			/* On prépare l'insertion en bdd */
			try
			{
				$req = $this->dao->prepare('INSERT INTO billet(titre, contenu, idUtilisateur, datePub, dateMod, idBook, nbLike, nbVue) VALUES(:titre, :contenu, :idUtilisateur, :datePub, :dateMod, :idBook, :nbLike, :nbVue)');
				$req->bindValue(':titre', $billet->getTitre(), \PDO::PARAM_STR);
				$req->bindValue(':contenu', $billet->getContenu(), \PDO::PARAM_STR);
				$req->bindValue(':idUtilisateur', $billet->getIdUtilisateur(), \PDO::PARAM_STR);
				$req->bindValue(':datePub', time(), \PDO::PARAM_INT);
				$req->bindValue(':dateMod', 0, \PDO::PARAM_INT);
				$req->bindValue(':idBook', $billet->getIdBook(), \PDO::PARAM_INT);
				$req->bindValue(':nbLike', serialize(array()), \PDO::PARAM_STR);
				$req->bindValue(':nbVue', 0, \PDO::PARAM_INT);
				$req->execute();
				$_SESSION['success'] = 'Billet Ajoutée !';
				return true;
			}
			catch(MyError $e)
			{
				$this->setManagerError($e->getMessage());
				return false;
			}
		}
		
		
		/*
			Récupère le titre d'un billet en fonction de l'id donné
		*/
		
		public function getTitre($idBillet)
		{
			$req = $this->dao->prepare('SELECT titre FROM billet WHERE id = :id');
			$req->bindValue(':id', $idBillet, \PDO::PARAM_INT);
			$req->execute();
			if($rs = $req->fetch())
				return $rs['titre'];
			else
				return false;
		}
		
		/*
			Compte le nombre de billet appartenant à l'idBook
		*/
		
		public function getNbBilletOfBook($idBook)
		{
			if((int) $idBook)
			{
				$req = $this->dao->prepare('SELECT COUNT(idBook) AS nbBillet FROM billet WHERE idBook = :idBook');
				$req->bindValue(':idBook', $idBook, \PDO::PARAM_INT);
				$req->execute();
				if($rs = $req->fetchAll())
				{
					return $rs;
				}
				else
				{
					return 'Aucun Billet';
				}
			}
			else
			{
				$this->setManagerError('Une erreur est survenue.');
				return false;
			}
		}
		
		/* Getters */
		
		public function getManagerError()
		{
			return $this->managerError;
		}
		
		/* Setters */
		
		public function setManagerError($error)
		{
			$this->managerError = $error;
		}
		
	}