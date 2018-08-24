<?php

	namespace Model;
	
	use \Entity\View;
	
	class ViewManagerPDO extends ViewManager
	{
		protected $managerError;
		
		public function addView($id)
		{
			if(!empty($id))
			{
				if((int) $id)
				{
					if($this->existId($id))
					{
						$view = $this->getView($id);
						$tabView = $view->getTabView() + 1;
						$req = $this->dao->prepare('UPDATE view SET tabView = :tabView WHERE idView = :idView');
						$req->bindValue(':tabView', $tabView, \PDO::PARAM_INT);
						$req->bindValue(':idView', $view->getIdView(), \PDO::PARAM_INT);
						$req->execute();
						return true;
					}
					else
					{
						$req = $this->dao->prepare('INSERT INTO view(idView, tabView) VALUES(:idView, :tabView)');
						$req->bindValue(':idView', $id, \PDO::PARAM_INT);
						$req->bindValue(':tabView', 1, \PDO::PARAM_INT);
						$req->execute();
						return true;
					}
				}
			}
		}
		
		public function getView($id)
		{
			if(!empty($id))
			{
				if((int) $id)
				{
					if($this->existId($id))
					{
						$req = $this->dao->prepare('SELECT * FROM view WHERE idView = :idView');
						$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\View');
						$req->bindValue(':idView', $id, \PDO::PARAM_INT);
						$req->execute();
						$rs = $req->fetch();
						return $rs;
					}
				}
			}
		}
		
		public function existId($id)
		{
			if(!empty($id))
			{
				if((int) $id)
				{
					$req = $this->dao->prepare('SELECT idView FROM view WHERE idView = :idView');
					$req->bindValue(':idView', $id, \PDO::PARAM_INT);
					$req->execute();
					if($rs = $req->fetch())
					{
						return true;
					}
					else
						return false;
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