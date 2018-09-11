<?php
	
	/*
		Genarkys
		
		Ver 1.0
		
		Gestion des requêtes sur la table 'bookCategoryList'
	*/
	
	namespace Model;
	
	// use \Entity\BookCategory;
	use Core\MyError;
	use Entity\Category;
	
	class BookCategoryListManagerPDO extends BookCategoryListManager
	{
		protected $managerError;
		
		/* 
			Ajoute une catégorie dans la table
		*/
		
		public function addCategory(Category $category)
		{
			if(!empty($category))
			{
				if(!$this->getNameCategory($category->getName()))
				{
					try
					{
						$req = $this->dao->prepare('INSERT INTO bookcategorylist(name, comment) VALUES(:name, :comment)');
						$req->bindValue(':name', $category->getName(), \PDO::PARAM_STR);
						$req->bindValue(':comment', $category->getComment(), \PDO::PARAM_STR);
						if(!$req->execute())
							throw new MyError('Impossible d\'insérer la nouvelle catégorie.');
						$_SESSION['success'] = 'Catégorie \'' . $category->getName() . '\' ajoutée avec succès.';
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
					$this->setManagerError('Le nom existe déjà.');
					return false;
				}
			}
			else
			{
				$this->setManagerError('L\'argument $category est vide.');
				return false;
			}
		}
		
		/*
			Vérifie si le nom d'une category est existant
		*/
		
		public function getNameCategory($name)
		{
			if(!empty($name))
			{
				if(is_string($name))
				{
					try
					{
						$req = $this->dao->prepare('SELECT name FROM bookcategorylist WHERE name = :name');
						$req->bindValue(':name', $name, \PDO::PARAM_STR);
						if(!$req->execute())
							throw new MyError('Impossible de rechercher la correspondance.');
						if($rs = $req->fetch())
							return true;
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
					$this->setManagerError('$name est invalide.');
					return false;
				}
			}
			else
			{
				$this->setManagerError('L\'argument $name est vide.');
				return false;
			}
		}
		
		/* 
			Récupère la liste des catégories de la table 
		*/
		
		public function getBookCategoryList()
		{
			try
			{
				$req = $this->dao->query('SELECT * FROM bookcategorylist');
				$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Category');
				if(!$req)
					throw new MyError('Une erreur est survenue');
				if(!$req->execute())
					throw new MyError('Impossible d\'exécuter la requète.');
				if(!$rs = $req->fetchAll())
					throw new MyError('Aucun résultat.');
				return $rs;
			}
			catch(MyError $e)
			{
				$this->setManagerError($e->getMessage());
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