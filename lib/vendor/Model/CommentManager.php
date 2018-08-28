<?php

	namespace Model;
	
	use \Core\Manager;
	use \Entity\Comment;
	
	abstract class CommentManager extends Manager
	{
		/* Insère l'object Comment dans la base de donnée */
		
		abstract public function addComment($data);
		
		/* Selon $module :
			$module = 'list' -> $id = id du billet 
				retourne l'ensemble des commentaires attribués au billet 
			
			$module = 'once' -> $id = id du commentaire
				Retourne le nombre de signalement du commentaire avec l'id spécifié
		*/
		
		abstract public function getComment($module, $id = false);
		
		/*
			$id = id du commentaire
			$nb = nombre de signalement
		*/
		
		abstract public function signalerComment($id, $nb);
		
		/*
		
			si $report = true alors $id -> ['idComment', 'email']
			sinon $id = idComment;
		*/
		
		abstract public function delComment($id, $report = false);
		
		abstract public function getCommentReport();
		
		
	}