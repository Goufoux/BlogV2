<?php
	/*
		Genarkys
		
		09/09/2018
		
		Gestion des requêtes Ajax du Backend
		
		
	*/
	namespace Core;
	
	use Core\PDOFactory;
	use Core\Managers;
	use Core\FormBillet;
	use Core\MyError;
	use Core\Form;
	
	use Entity\Category;
	
	class AjaxAdminRequest
	{
		protected $role;
		protected $data = [];
		protected $ajaxError;
		protected $sData; // Données spéciales
		
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
				return $this->$role();
			}
			else
			{
				$this->setAjaxError('Une erreur est survenue. {Code : 103}');
				return false;
			}
		}
		
		/*
			Crée ou mets à jour le fichier category.json 
		*/
		
		public function executeCreateJSONFileCategory()
		{
			$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
			$jsonData = $managers->getManagerOf('BookCategoryList')->getBookCategoryList();
			$dataCat = [];
			if($jsonData)
			{
				for($i = 0; $i < count($jsonData); $i++)
				{
					$cat = ['id' => $jsonData[$i]->getId(), 'name' => $jsonData[$i]->getName(), 'comment' => $jsonData[$i]->getComment()];
					$dataCat[] = $cat;
				}
			}
			else
				$jsonData = ['empty'];
			$dataCat = json_encode($dataCat);
			$fileName = '../json/category.json';
			$file = fopen($fileName, 'w+');
			fwrite($file, $dataCat);
			fclose($file);
			$_SESSION['success'] = 'Fichier ' . $fileName . ' mis à jour avec succès.';
			return true;
		}
		
		/* 
			Ajouter une nouvelle catégorie
				fData = nameCat;
				sData = commentCat;
		*/
		
		public function executeAddCat()
		{
			if(!empty($this->data))
			{
				$form = new Form();
				if($form->verifNameCategory(htmlspecialchars($this->data[0])))
				{
					if(!empty($this->sData))
					{
						if(!$form->verifCommentCategory(htmlspecialchars($this->sData)))
						{
							$this->setAjaxError($form->getFormError());
							return false;
						}
					}
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
					$category = new Category(['name' => $this->data[0], 'comment' => $this->sData]);
					if($managers->getManagerOf('BookCategoryList')->addCategory($category))
					{
						return true;
					}
					else
					{
						$this->setAjaxError($managers->getManagerOf('BookCategoryList')->getManagerError());
						return false;
					}
				}
				else
				{
					$this->setAjaxError($form->getFormError());
					return false;
				}
			}
			else
			{
				$this->setAjaxError('Veuillez donner un nom à la catégorie.');
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