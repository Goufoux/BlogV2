<?php

	namespace Entity;
	
	use \Core\Entity;
	
	class Category extends Entity
	{
		protected $id;
		protected $name;
		protected $comment;
		
		/* Getters */
		
		public function getId()
		{
			return $this->id;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		public function getComment()
		{
			if($this->comment == null)
				return 'Aucun commentaire';
			else
				return $this->comment;
		}
		
		/* Setters */
		
		public function setId($id)
		{
			$this->id = $id;
		}
		
		public function setName($name)
		{
			$this->name = $name;
		}
		
		public function setComment($comment)
		{
			$this->comment = $comment;
		}
		
	}