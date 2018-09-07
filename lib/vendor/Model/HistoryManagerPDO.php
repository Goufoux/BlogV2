<?php

	/*
		Genarkys
		
		06/09/2018
	
		HistoryManagerPDO()
		
		S'occupe de gérer les données de la table UserHistory.
		
		Liste des Méthodes: cf HistoryManager
		
	*/

	namespace Model;
	
	use \Core\MyError;
	use \Entity\History;
	
	class HistoryManagerPDO extends HistoryManager
	{
		protected $idUser = 0;
		protected $idData = 0;
		protected $module = 'null';
		protected $type = 'null';
		protected $error;
		
		
		public function run()
		{
			if(is_array($this->parameter) OR $this->parameter == false)
			{
				try
				{
					if($this->parameter)
					{
						if(!(int) $this->parameter[0] OR $this->parameter[0] < 0)
							throw new MyError('Le paramètre $idUser est invalide.');
						if(!(int) $this->parameter[1] OR $this->parameter[1] < 0)
							throw new MyError('Le paramètre $idData est invalide.');
						if(!is_string($this->parameter[2]))
							throw new MyError('Le paramètre $module est invalide.');
						if(!is_string($this->parameter[3]))
							throw new MyError('Le paramètre $type est invalide.');
						
						
						$this->idUser = $this->parameter[0];
						$this->idData = $this->parameter[1];
						$this->module = $this->parameter[2];
						$this->type = $this->parameter[3];
						
						if($this->module == 'null')
							throw new MyError('Aucun module défini');
						$role = 'execute'.ucfirst($this->module);
						if(is_callable([$this, $role]))
						{
							return $this->$role();
						}
						else
						{
							throw new MyError('Le module ' . $this->module . ' n\'existe pas.');
						}
					}
				}
				catch(MyError $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setError('Paramètres invalide.');
				return false;
			}
		}
		
		public function executePrint()
		{
			if(!empty($this->type) AND $this->type != 'null')
			{
				if(!empty($_SESSION['membre']))
				{
					try 
					{
						$req = $this->dao->prepare('SELECT * FROM userHistory WHERE idUtilisateur = :idUtilisateur AND typeHistory = :typeHistory ORDER BY id DESC');
						$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\History');
						$req->bindValue(':idUtilisateur', $_SESSION['membre']->getId(), \PDO::PARAM_INT);
						$req->bindValue(':typeHistory', $this->getType(), \PDO::PARAM_STR);
						if(!$req->execute())
							throw new \PDOException('Une erreur est survenue');
						if(!$rs = $req->fetchAll())
							return false;
						return $rs;
					}
					catch(\PDOException $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
				}
				else
				{
					$this->setError('Une erreur est survenue.');
					return false;
				}
			}	
			else
			{
				$this->setError('Type invalide.');
				return false;
			}
		}
		
		public function executeUnfollow()
		{
			if($this->idUser != 0 AND $this->idData != 0 AND $this->type != 'null')
			{
				try
				{
					$req = $this->dao->prepare('DELETE FROM userHistory WHERE idUtilisateur = :idUtilisateur AND idHistory = :idHistory AND typeHistory = :typeHistory');
					$req->bindValue(':idUtilisateur', $this->idUser, \PDO::PARAM_INT);
					$req->bindValue(':idHistory', $this->idData, \PDO::PARAM_INT);
					$req->bindValue(':typeHistory', $this->type, \PDO::PARAM_STR);
					if(!$req->execute())
						throw new \PDOException('Impossible de supprimer l\'abonnement.');
					return $this->idData;
				}
				catch(\PDOException $e)
				{
					$this->setError($e->getMessage());
					return "e";
				}
			}
			else
			{
				$this->setError('Les arguments sont invalide.');
				return false;
			}
		}
		
		/* Recherche les correspondances avec l'utilisateur */
		
		public function executeSearch()
		{
			if($this->idUser != 0 AND $this->idData != 0 AND $this->type != 'null')
			{
				try
				{
					$req = $this->dao->prepare('SELECT * FROM userHistory WHERE typeHistory = :typeHistory AND idUtilisateur = :idUtilisateur AND idHistory = :idHistory');
					$req->bindValue(':idUtilisateur', $this->idUser, \PDO::PARAM_INT);
					$req->bindValue(':idHistory', $this->idData, \PDO::PARAM_INT);
					$req->bindValue(':typeHistory', $this->type, \PDO::PARAM_STR);
					if(!$req->execute())
						throw new \PDOException('Une erreur est survenue.');
					if(!$rs = $req->fetch())
					{
						$this->setError('Aucune correspondances');
						return false;
					}
					return true;
				}
				catch(\PDOException $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setError('Les arguments sont invalide.');
				return false;
			}
		}
		
		/* Ajout un abonnement à un Book */
		
		public function executeFollow()
		{
			if($this->idUser != 0 AND $this->idData != 0 AND $this->type != 'null')
			{
				try
				{
					$req = $this->dao->prepare('INSERT INTO userHistory(idUtilisateur, typeHistory, idHistory) VALUES(:idUtilisateur, :typeHistory, :idHistory)');
					$req->bindValue(':idUtilisateur', $this->idUser, \PDO::PARAM_INT);
					$req->bindValue(':typeHistory', $this->type, \PDO::PARAM_STR);
					$req->bindValue(':idHistory', $this->idData, \PDO::PARAM_INT);
					if(!$req->execute())
						throw new \PDOException('Impossible d\'ajouter l\'abonnement.');
					return true;
				}
				catch(\PDOException $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setError('Les arguments sont invalide.');
				return false;
			}
		}
		
		/* Ajout un book ou un billet dans l'historique */
		
		public function executeAddHistory()
		{
			if($this->idUser != 0 AND $this->idData != 0 AND $this->type != 'null')
			{
				try
				{
					if(!$this->executeSearch())
					{
						$req = $this->dao->prepare('INSERT INTO userHistory(idUtilisateur, typeHistory, idHistory) VALUES(:idUtilisateur, :typeHistory, :idHistory)');
						$req->bindValue(':idUtilisateur', $this->idUser, \PDO::PARAM_INT);
						$req->bindValue(':typeHistory', $this->type, \PDO::PARAM_STR);
						$req->bindValue(':idHistory', $this->idData, \PDO::PARAM_INT);
						if(!$req->execute())
							throw new \PDOException('Une erreur est survenue');
						return true;
					}
					else
					{
						$this->executeUnfollow();
						$this->executeAddHistory();
					}
				}
				catch(\PDOException $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setError('Arguments invalide.');
				return false;
			}
		}
		
		/* Getters */
		
		public function getError()
		{
			return $this->error;
		}
		
		public function getModule()
		{
			return $this->module;
		}
		
		public function getType()
		{
			return $this->type;
		}
		
		public function getIdData()
		{
			return $this->idData;
		}
		
		public function getIdUser()
		{
			return $this->idUser;
		}
		
		/* Setters */
		
		public function setError($error)
		{
			$this->error = $error;
		}
		
		public function setModule($module)
		{
			if(is_string($module))
				$this->module = $module;
			else
				$this->module = 'null';
		}
		
		public function setType($type)
		{
			if(is_string($type))
				$this->type = $type;
			else
				$this->type = 'null';
		}
		
		public function setIdData($idData)
		{
			if((int) $idData)
				$this->idData = $idData;
			else
				$this->idData = 0;
		}
		
		public function setIdUser($idUser)
		{
			if((int) $idUser)
				$this->idUser = $idUser;
			else
				$this->idUser = 0;
		}
	}