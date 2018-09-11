<?php
	
	/*
		Genarkys
		
		Ver 1.0
		
		Gestion des requêtes sur la table 'bookCategory'
	*/
	
	namespace Model;
	
	use Core\MyError;
	use Entity\CategoryBook;
	
	class BookCategoryManagerPDO extends BookCategoryManager
	{
		protected $managerError;
		
		/*
			Récupération de toutes les catégories d'un book
		*/
		
		public function getBookCategory($idBook)
		{
			if(!empty($idBook))
			{
				if((int) $idBook)
				{
					try
					{
						$req = $this->dao->prepare('SELECT bookcategory.*, bookcategorylist.* FROM bookcategory INNER JOIN bookcategorylist ON bookcategorylist.id = bookcategory.idCategory WHERE idBook = :idBook');	
						$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\CategoryBook');
						$req->bindValue(':idBook', $idBook, \PDO::PARAM_INT);
						if(!$req->execute())
							throw new MyError('Impossible de lire les données.');
						if($rs = $req->fetchAll())
							return $rs;
						else
							return false;
					}
					catch(MyError $e)
					{
						$this->setManagerError($e->getMessage());
						return false;
					}
				}
				else
				{
					$this->setManagerError('$idBook doit être entier.');
					return false;
				}
			}
			else
			{
				$this->setManagerError('Argument invalide.');
				return false;
			}
		}
		
		/* Insertion d'une ligne */
		
		public function addBookCategory(CategoryBook $categoryBook)
		{
			if(!empty($categoryBook))
			{
				try
				{
					$req = $this->dao->prepare('INSERT INTO bookcategory(idBook, idCategory) VALUES(:idBook, :idCategory)');
					$req->bindValue(':idBook', $categoryBook->getIdBook(), \PDO::PARAM_INT);
					$req->bindValue(':idCategory', $categoryBook->getIdCategory(), \PDO::PARAM_INT);
					if(!$req->execute())
						throw new MyError('Impossible d\'insérer la catégorie.');
					return true;
				}
				catch(MyError $e)
				{
					$this->setManagerError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setManagerError('CategoryBook est vide.');
				return false;
			}
		}
		
		/* Getters */
		
		public function getManagerError()
		{
			return $this->managerError;
		}
		
		/* Setters */
		
		protected function setManagerError($managerError)
		{
			$this->managerError = $managerError;
		}
	}