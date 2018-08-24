<?php

	namespace Model;
	
	use \Entity\Book;
	
	class BookManagerPDO extends BookManager
	{
		protected $managerError;
		
		public function addBook(Book $book)
		{
			/* On prépare l'insertion en bdd */
			$req = $this->dao->prepare('INSERT INTO book(name, datePub, dateMod, content, idUtilisateur, categorie) VALUES(:name, :datePub, :dateMod, :content, :idUtilisateur, :categorie)');
			$req->bindValue(':name', $book->getName(), \PDO::PARAM_STR);
			$req->bindValue(':datePub', time(), \PDO::PARAM_INT);
			$req->bindValue(':dateMod', 0, \PDO::PARAM_INT);
			$req->bindValue(':content', $book->getContent(), \PDO::PARAM_STR);
			$req->bindValue(':idUtilisateur', $book->getIdUtilisateur(), \PDO::PARAM_INT);
			$req->bindValue(':categorie', serialize($book->getCategorie()), \PDO::PARAM_STR);
			$req->execute();
			$_SESSION['success'] = 'Book Créé !';
			return true;
		}
		
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
							$this->setManagerError('Une erreur est survenue.');
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
				else
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
			}
			else
			{
				$this->setManagerError('Une erreur est survenue.');
				return false;
			}
		}
		
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