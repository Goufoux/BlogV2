<?php

	namespace Model;
	
	use \Entity\Comment;
	
	class CommentManagerPDO extends CommentManager
	{
		protected $error = '';
		
		/*
			Insertion d'un commentaire dans la bdd
			$data = [$idBillet, $contenu];
		*/
		
		public function addComment($data)
		{
			$req = $this->dao->prepare('INSERT INTO comment(idUtilisateur, contenu, datePub, idBillet, report) VALUES(:idUtilisateur, :contenu, :datePub, :idBillet, :report)');
			$req->bindValue(':idUtilisateur', $_SESSION['membre']->getId(), \PDO::PARAM_STR);
			$req->bindValue(':contenu', $data[1], \PDO::PARAM_STR);
			$req->bindValue(':datePub', time(), \PDO::PARAM_INT);
			$req->bindValue(':idBillet', $data[0], \PDO::PARAM_INT);
			$req->bindValue(':report', 0, \PDO::PARAM_INT);
			$req->execute();
			return true;
		}
		
		/*
			Récupération de commentaire en fonction du module
		*/
		
		public function getComment($module, $id = false)
		{
			if($module == 'list') // Liste des commentaires d'un billet
			{
				$req = $this->dao->prepare('SELECT c.*, u.pseudo AS pseudo FROM utilisateur u INNER JOIN comment c ON c.idUtilisateur = u.id WHERE idBillet = :idBillet ORDER BY datePub DESC');
				$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
				$req->bindValue(':idBillet', $id, \PDO::PARAM_INT);
				$req->execute();
				if($rs = $req->fetchAll())
				{
					return $rs;
				}
				else
					return false;
			}
			if($module == 'once') // Retourne le nombre de signalement du commentaire avec l'id spécifié
			{
				$req = $this->dao->prepare('SELECT report FROM comment WHERE id = :id');
				$req->bindValue(':id', $id, \PDO::PARAM_INT);
				$req->execute();
				if($rs = $req->fetch())
				{
					return $rs;
				}
				else
				{
					return false;
				}
			}
		}
		
		public function signalerComment($id, $nb)
		{
			$req = $this->dao->prepare('UPDATE comment SET report = :report WHERE id = :id');
			$req->bindValue(':report', $nb, \PDO::PARAM_INT);
			$req->bindValue(':id', $id, \PDO::PARAM_INT);
			$req->execute();
			return true;
		}
		
		public function getCommentReport()
		{
			$req = $this->dao->prepare('SELECT * FROM comment WHERE report > :report ORDER BY datePub DESC');
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
			$req->bindValue(':report', 0, \PDO::PARAM_INT);
			$req->execute();
			if($rs = $req->fetchAll())
			{
				return $rs;
			}
			else
			{
				return false;
			}
		}
		
		public function delComment($id, $report = false)
		{
			if(!$report)
			{
				if((int)$id)
				{
					$del = $this->dao->prepare('DELETE FROM comment WHERE id = :id');
					$del->bindValue(':id', $id, \PDO::PARAM_INT);
					$del->execute();
					return true;
				}
				else
					return false;
			}
			else
			{
				if((int)$id[0])
				{
					$req = $this->dao->prepare('INSERT INTO report(email) VALUES(:email)');
					$req->bindValue(':email', $id[1], \PDO::PARAM_STR);
					$req->execute();
					
					$del = $this->dao->prepare('DELETE FROM comment WHERE id = :id');
					$del->bindValue(':id', $id[0], \PDO::PARAM_INT);
					$del->execute();
					return $id;
				}
				else
					return false;
			}
		}
		
		/* Getters */
		
		public function getError()
		{
			return $this->error;
		}
		
		/* Setters */
		
		public function setError($string)
		{
			$this->error = $string;
		}
	}