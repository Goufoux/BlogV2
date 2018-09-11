<?php

	namespace Model;
	
	use \Entity\Book;
	use \Entity\BookCategory;
	use \Core\MyError;
	
	class BookManagerPDO extends BookManager
	{
		protected $managerError;
		
		public function getName($id)
		{
			if((int) $id)
			{
				try
				{
					if(!$this->existBook($id))
						throw new MyError('Book introuvable');
					
					$req = $this->dao->prepare('SELECT id, name FROM book WHERE id = :id');
					$req->bindValue(':id', $id, \PDO::PARAM_INT);
					$req->execute();
					$rs = $req->fetch();
					return $rs;
				}
				catch(MyError $e)
				{
					$this->setManagerError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setManagerError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			Ajoute une vue au book correspondant
		*/
		
		public function addView($id, $nb)
		{
			try
			{
				if((int) $id)
				{
					if($this->existBook($id))
					{
						if($nb >= 0)
						{
							$nb++;
							$req = $this->dao->prepare('UPDATE book SET nbVue = :nbVue WHERE id = :id');
							$req->bindValue(':nbVue', $nb, \PDO::PARAM_INT);
							$req->bindValue(':id', $id, \PDO::PARAM_INT);
							$req->execute();
							return true;
						}
						else
						{
							$this->setManagerError('Une erreur est survenue.');
							return false;
						}
					}
					else
					{
						$this->setManagerError('Book Introuvable');
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
			Ajout un book
		*/
		
		public function addBook(Book $book)
		{
			if(!empty($book))
			{
				try
				{
					/* On prépare l'insertion en bdd du Book */
					$req = $this->dao->prepare('INSERT INTO book(name, datePub, dateMod, content, idUtilisateur, nbVue) VALUES(:name, :datePub, :dateMod, :content, :idUtilisateur, :nbVue)');
					$req->bindValue(':name', $book->getName(), \PDO::PARAM_STR);
					$req->bindValue(':datePub', time(), \PDO::PARAM_INT);
					$req->bindValue(':dateMod', 0, \PDO::PARAM_INT);
					$req->bindValue(':content', $book->getContent(), \PDO::PARAM_STR);
					$req->bindValue(':idUtilisateur', $book->getIdUtilisateur(), \PDO::PARAM_INT);
					$req->bindValue(':nbVue', 0, \PDO::PARAM_INT);
					if(!$req->execute())
						throw new MyError('Impossible d\'ajouter le Book.');
					$idBook = $this->dao->lastInsertId();
					if(empty($idBook))
						throw new MyError('Une erreur est survenue après l\'insertion. IdBook->' . $idBook);
					return $idBook;
				}
				catch(MyError $e)
				{
					$this->setManagerError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setManagerError('Object Book vide.');
				return false;
			}
		}
		
		/*
			si
				$cat = all -> Renvoi une liste de tous les books [$data = false]
				$cat = id -> Renvoi le book correspondant à l'id [$data = idBook]
				$cat = idUtilisateur ->Renvoi une liste de book correspondant à l'id d'un utilisateur [$data = $idUtilisateur]
		*/
		
		public function getBook($cat, $data)
		{
			if(is_string($cat))
			{
				$sql = '';
				switch($cat)
				{
					case 'all':	
						$sql = 'SELECT b.*, u.pseudo FROM utilisateur u INNER JOIN book b ON b.idUtilisateur = u.id ORDER BY datePub DESC';
							break;
					case 'idUtilisateur':
						if(!empty($data))
						{
							$sql = 'SELECT b.*, u.pseudo AS pseudo FROM utilisateur u INNER JOIN book b ON b.idUtilisateur = u.id WHERE b.idUtilisateur = :idUtilisateur ORDER BY datePub DESC';
						}
						else
						{
							$this->setManagerError('Une erreur est survenue. {Code 124}');
							return false;
						}
						break;
					case 'id':
						if(!empty($data))
						{
							$sql = 'SELECT b.*, u.pseudo AS pseudo FROM utilisateur u INNER JOIN book b ON b.idUtilisateur = u.id WHERE b.id = :id';
						}
						else
						{
							$this->setManagerError('Une erreur est survenue. {Code 135}');
							return false;
						}
						break;
					default:
						$sql = false;
						break;
				}
				if($cat == 'all' AND $sql != false)
				{
					$req = $this->dao->query($sql);
					$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Book');
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
				else if($sql != false)
				{
					try
					{
						$req = $this->dao->prepare($sql);
						$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Book');
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
					catch(MyError $e)
					{
						$this->setManagerError('Une erreur est survenue. Code -> 166');
						return false;
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
			Recherche une liste de book correspondant au $name 
		*/
		
		public function searchBook($name)
		{
			if(is_string($name))
			{
				$req = $this->dao->prepare('SELECT b.*, u.pseudo AS pseudo FROM utilisateur u INNER JOIN book b ON b.idUtilisateur = u.id WHERE name LIKE :name');
				$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Book');
				$req->bindValue(':name', '%'.$name.'%', \PDO::PARAM_STR);
				$req->execute();
				if($rs = $req->fetchAll())
				{
					return $rs;
				}
				else
				{
					$this->setManagerError('Aucun Book correspondant.');
					return false;
				}
			}
			else
			{
				$this->setManagerError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			Vérifie l'existence d'un book en fonction de l'id transmis
		*/
		
		public function existBook($id)
		{
			if((int)$id)
			{
				$req = $this->dao->prepare('SELECT id FROM book WHERE id = :id');
				$req->bindValue(':id', $id, \PDO::PARAM_INT);
				$req->execute();
				if($rs = $req->fetch())
					return true;
				else
				{
					$this->setManagerError('Le Book est introuvable.<br />Il a peut-être était déplacé ou supprimé !');
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
			ModBook(Book $book)
			Mets à jour les champs d'un book
		*/
		
		public function modBook(Book $book)
		{
			if($this->existBook($book->getId()))
			{
				$req = $this->dao->prepare('UPDATE book SET name = :name, dateMod = :dateMod, content = :content WHERE id = :id');
				$req->bindValue(':name', $book->getName(), \PDO::PARAM_STR);
				$req->bindValue(':dateMod', time(), \PDO::PARAM_INT);
				$req->bindValue(':content', $book->getContent(), \PDO::PARAM_STR);
				$req->bindValue(':id', $book->getId(), \PDO::PARAM_INT);
				$req->execute();
				return true;
			}
			else
			{
				$this->setManagerError('Une erreur est survenue');
				return false;
			}
		}
		
		/*
			Supprime un Book et tout ses billets
		*/
		
		public function delBook($id)
		{
			if($this->existBook($id))
			{
				$req = $this->dao->prepare('DELETE FROM book WHERE id = :id');
				$req->bindValue(':id', $id, \PDO::PARAM_INT);
				$req->execute();
				
				$req = $this->dao->prepare('DELETE FROM billet WHERE idBook = :idBook');
				$req->bindValue(':idBook', $id, \PDO::PARAM_INT);
				$req->execute();
				
				return true;
			}
			else
			{
				$this->setManagerError('Le Book est introuvable.');
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