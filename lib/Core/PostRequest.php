<?php

	/*
		Genarkys
		ver 1.0
		
		PostRequest()
	*/
	
	namespace Core;
	
	use Core\PDOFactory;
	use Core\Managers;
	use Core\FormBillet;
	use Core\Form;
	
	use \Entity\Book;
	
	class PostRequest
	{
		protected $role;
		protected $data = [];
		protected $postError;
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
				$this->setPostError('Une erreur est survenue. {Code : 103}');
				return false;
			}
		}
		
		public function executeModifBook($data)
		{
			$formBook = new FormBillet();
			if($formBook->verifTitle($data[0][0]))
			{
				if($formBook->verifContenu($this->sData))
				{
					if((int) $data[0][1])
					{
						$book = new Book([
							'id' => $data[0][1],
							'name' => $data[0][0],
							'content' => $this->sData
						]);
						$managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
						$bookManager = $managers->getManagerOf('Book');
						if($bookManager->modBook($book))
						{
							$_SESSION['success'] = 'Book mise à jour.';
							return true;
						}
						else
						{
							$this->setPostError($bookManager->getManagerError());
							return false;
						}
					}
					else
					{
						$this->setPostError('Une erreur est survenue.');
						return false;
					}
				}
				else
				{
					$this->setPostError($formBook->getFormError());
					return false;
				}
			}
			else
			{
				$this->setPostError($formBook->getFormError());
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
		
		public function getPostError()
		{
			return $this->postError;
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
		
		public function setPostError($error)
		{
			$this->postError = $error;
		}
		
		public function setSdata($sData)
		{
			$this->sData = $sData;
		}
	}