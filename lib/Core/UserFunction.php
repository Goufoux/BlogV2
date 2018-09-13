<?php

	namespace Core;
	
	use Core\PDOFactory;
	use Core\Managers;
	
	class UserFunction
	{
		protected $role;
		protected $data;
		protected $error;
		protected $html;
		protected $button = [];
		
		public function __construct($role, $data)
		{
			$this->role = $role;
			$this->data = $data;
		}
		
		public function launcher()
		{
			$role = 'execute'.ucfirst($this->role).'Function';
			if(is_callable([$this, $role]))
			{
				return $this->$role();
			}
			else
			{
				$this->setError('Une erreur est survenue. {Code : 103}');
				return false;
			}
		}
		
		public function executeUserFunction()
		{
			if(!empty($this->data))
			{
				if(!empty($_SESSION['auth']))
				{
					$arg = [$_SESSION['membre']->getId(), $this->data, 'search', 'user'];
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion(), $arg);
					$historyManager = $managers->getManagerOf('History');
					if(!$historyManager->run())
					{
						$this->setButton('<button value="user_'.$this->data.'" class="folUser btn btn-outline-link"> S\'abonner </button>');
					}
					else
					{
						$this->setButton('<button value="user_'.$this->data.'" class="unfolUser btn btn-outline-link"> Se désabonner </button>');
					}
					$this->constructHtml();
					return true;
				}
				else
				{
					$this->setError('Une erreur est survenue.');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		
		public function executeBookFunction()
		{
			if(!empty($this->data))
			{
				if(!empty($_SESSION['auth']))
				{
					$arg = [$_SESSION['membre']->getId(), $this->data, 'search', 'book'];
					$managers = new Managers('PDO', PDOFactory::getMysqlConnexion(), $arg);
					$historyManager = $managers->getManagerOf('History');
					if(!$historyManager->run())
					{
						$this->setButton('<button value="book_'.$this->data.'" class="folBook btn btn-outline-link"> S\'abonner </button>');
					}
					else
					{
						$this->setButton('<button value="book_'.$this->data.'" class="unfolBook btn btn-outline-link"> Se désabonner </button>');
					}
					$this->constructHtml();
					return true;
				}
				else
				{
					$this->setError('Authentifié vous.');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		public function constructHtml()
		{
			for($i = 0; $i < count($this->button); $i++)
			{
				$this->html .= $this->getButton($i);
			}
		}
		
		public function setButton($button)
		{
			$this->button []= $button;
		}
		
		public function getButton($key)
		{
			return $this->button[$key];
		}
		
		public function getHtml()
		{
			return $this->html;
		}
		
		public function setHtml($html)
		{
			$this->html = $html;
		}
		
		public function getError()
		{
			return $this->error;
		}
		
		public function setError($error)
		{
			$this->error = $error;
		}
	}